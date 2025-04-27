<x-app-layout>
    <body class="bg-gray-100 px-6 py-10 min-h-screen">
        <div class="flex flex-col lg:flex-row gap-8 max-w-screen-xl mx-auto">

            @if($avatar && $avatar->avatar_path)
            <!-- Avatar Preview Section -->
            <div class="flex-1 bg-gray-100 flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Your Avatar</h2>
                <div class="w-full max-w-md h-[420px] bg-white rounded-lg shadow-inner flex items-center justify-center">
                    <div id="avatar-canvas-container" class="w-full h-full"></div>
                </div>
            </div>
            @endif

            <!-- Form Section -->
            <div class="flex-1 bg-white px-4 sm:px-6 md:px-10 py-6">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Enter Your Measurements</h2>

                <form method="POST" action="{{ route('avatar.store') }}">
                    @csrf

                    <!-- Sex -->
                    <div class="mt-4">
                        <x-input-label for="sex" :value="__('Sex')" />
                        <select id="sex" name="sex" class="block mt-1 w-full p-2 border rounded-md shadow-inner">
                            <option value="Male" {{ old('sex', $avatar->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $avatar->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Only These Fields Are Used -->
                    @php
                        $fields = [
                            'height' => [140, 200],
                            'weight' => [40, 120],
                            'chest' => [70, 130],
                            'waist' => [50, 140],
                            'hips' => [70, 130],
                            'inseam' => [60, 100],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($fields as $field => [$min, $max])
                            <div class="mt-4">
                                <x-input-label for="{{ $field }}" :value="__(ucwords(str_replace('_', ' ', $field)) . ' (cm)')" />

                                <x-text-input
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    type="number"
                                    min="{{ $min }}"
                                    max="{{ $max }}"
                                    step="any"
                                    class="block mt-1 w-full placeholder-gray-600"
                                    value="{{ old($field, $avatar->$field ?? '') }}"
                                    required
                                />
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center mt-12">
                        <x-primary-button type="submit" class="w-full">
                            {{ $avatar ? 'Update Avatar' : 'Save Avatar' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</x-app-layout>

@if($avatar && $avatar->avatar_path)
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/examples/js/controls/OrbitControls.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('avatar-canvas-container');
            const scene = new THREE.Scene();
            scene.background = null;
        
            const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.set(0, 1.4, 3.5);
        
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);
        
            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.target.set(0, 1.2, 0);
            controls.update();
        
            const ambient = new THREE.AmbientLight(0xffffff, 0.4);
            scene.add(ambient);
        
            const directional = new THREE.DirectionalLight(0xffffff, 0.6);
            directional.position.set(3, 5, 2);
            scene.add(directional);
        
            const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
            backLight.position.set(-3, 3, -3);
            scene.add(backLight);
        
            let avatarObject = null;
        
            function captureAvatar(view) {
                if (!avatarObject) {
                    console.error('Avatar not loaded yet');
                    return;
                }
        
                controls.enabled = false;
        
                if (view === 'front') {
                    camera.position.set(0, 1.4, 3.5);
                } else if (view === 'back') {
                    camera.position.set(0, 1.4, -3.5);
                }
        
                controls.target.set(0, 1.2, 0);
                controls.update();
        
                renderer.render(scene, camera);
        
                const dataURL = renderer.domElement.toDataURL('image/png');
        
                fetch('{{ route('avatar.upload') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: dataURL,
                        view: view
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(`✅ ${view} uploaded:`, data);
                    controls.enabled = true;
        
                    // 👉 Reset camera back to front after back capture
                    if (view === 'back') {
                        setTimeout(() => {
                            camera.position.set(0, 1.4, 3.5); // Back to front view
                            controls.target.set(0, 1.2, 0);
                            controls.update();
                            renderer.render(scene, camera);
                            console.log('🔄 Camera reset to front after back capture.');
                        }, 500); // short delay so upload finishes cleanly
                    }
                })
                .catch(error => {
                    console.error(`❌ Upload error:`, error);
                    controls.enabled = true;
                });
            }
        
            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }
            animate();
        
            const loader = new THREE.OBJLoader();
            loader.load('{{ asset($avatar->avatar_path) }}', function (object) {
                object.traverse(function (child) {
                    if (child.isMesh) {
                        child.material = new THREE.MeshStandardMaterial({
                            color: 0xcccccc,
                            roughness: 0.5,
                            metalness: 0.1,
                        });
                    }
                });
        
                object.scale.set(1.3, 1.3, 1.3);
                object.position.y = 0;
                scene.add(object);
                avatarObject = object;
        
                console.log('✅ Avatar loaded.');
        
                setTimeout(() => {
                    document.getElementById('captureFront').click();
        
                    setTimeout(() => {
                        document.getElementById('captureBack').click();
                    }, 2000); 
                }, 1000);
            });
        
            document.getElementById('captureFront').addEventListener('click', function () {
                captureAvatar('front');
            });
        
            document.getElementById('captureBack').addEventListener('click', function () {
                captureAvatar('back');
            });
        });
        </script>
        
        <!-- hidden buttons -->
        <button id="captureFront" class="hidden">Capture Front</button>
        <button id="captureBack" class="hidden">Capture Back</button>
        
@endif

