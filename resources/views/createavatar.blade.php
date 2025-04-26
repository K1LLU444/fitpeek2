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
                            'waist' => [50, 100],
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

                                <input
                                    type="range"
                                    id="{{ $field }}_slider"
                                    min="{{ $min }}"
                                    max="{{ $max }}"
                                    step="any"
                                    value="{{ old($field, $avatar->$field ?? '') }}"
                                    class="w-full mt-2"
                                    oninput="syncSliderInput('{{ $field }}', this.value)"
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
            const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.set(0, 1.4, 3.5);

            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.target.set(0, 1.2, 0);
            controls.update();

            scene.add(new THREE.AmbientLight(0xffffff, 0.7));
            const light = new THREE.DirectionalLight(0xffffff, 0.8);
            light.position.set(5, 10, 7.5);
            scene.add(light);

            const axesHelper = new THREE.AxesHelper(1);
            scene.add(axesHelper);

            const gridHelper = new THREE.GridHelper(2, 10);
            scene.add(gridHelper);

            const loader = new THREE.OBJLoader();
            loader.load('{{ asset($avatar->avatar_path) }}', function (object) {
                console.log("✅ OBJ loaded:", object); // <-- Add this

                object.scale.set(1.3, 1.3, 1.3);
                object.position.y = 0;
                scene.add(object);
            });

            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }

            animate();
        });
    </script>

@endif
