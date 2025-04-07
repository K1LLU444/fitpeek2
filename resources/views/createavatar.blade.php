<x-app-layout>
    <body class="bg-gray-100 px-6 py-10 min-h-screen">

        <!-- Wider container without border or shadow -->
        <div class="flex flex-col lg:flex-row gap-8 max-w-screen-xl mx-auto">

            @if($avatar)
            <!-- Avatar Section -->
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

                    <form method="{{ $avatar ? 'POST' : 'POST' }}" 
                        action="{{ $avatar ? route('avatar.update', $avatar->id) : route('avatar.store') }}">
                        @csrf
                        @if($avatar)
                            @method('PUT')
                        @endif

                        <!-- Name -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name"
                                        class="block mt-1 w-full placeholder-gray-600"
                                        placeholder="Enter your name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $avatar->name ?? '') }}"
                                        required />
                        </div>

                        <!-- Sex -->
                        <div class="mt-4">
                            <x-input-label for="sex" :value="__('Sex')" />
                            <select id="sex" name="sex" class="block mt-1 w-full p-2 border rounded-md shadow-inner">
                                <option value="Male" {{ isset($avatar) && $avatar->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ isset($avatar) && $avatar->sex == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Measurements with Sliders -->
                        @php
                            $fields = [
                                'bust_size' => [70, 130],
                                'shoulder_width' => [30, 60],
                                'waist_size' => [50, 100],
                                'hip_size' => [70, 130],
                                'height' => [140, 200],
                                'weight' => [40, 120],
                                'arm_length' => [40, 70],
                                'leg_length' => [60, 120],
                                'thigh_circumference' => [30, 70],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($fields as $field => [$min, $max])
                                <div class="mt-4">
                                    <x-input-label for="{{ $field }}" :value="__(ucwords(str_replace('_', ' ', $field))) . ' (cm)'" />

                                    <!-- Text input -->
                                    <x-text-input
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        type="number"
                                        min="{{ $min }}"
                                        max="{{ $max }}"
                                        step="0.5"
                                        class="block mt-1 w-full placeholder-gray-600"
                                        value="{{ old($field, $avatar->$field ?? '') }}"
                                        oninput="syncInputSlider('{{ $field }}')"
                                        required
                                    />

                                    <!-- Range slider -->
                                    <input
                                        type="range"
                                        id="{{ $field }}_slider"
                                        min="{{ $min }}"
                                        max="{{ $max }}"
                                        step="0.5"
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
                                {{ $avatar ? 'Save' : 'Create Avatar' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>

@if($avatar)
    <script>
        window.avatarModelURL = "{{ asset('avatars/base_model_' . strtolower($avatar->sex) . '.glb') }}";
        window.avatarMorphData = {
            bust_size: {{ $avatar->bust_size }},
            waist_size: {{ $avatar->waist_size }},
            hip_size: {{ $avatar->hip_size }},
            shoulder_width: {{ $avatar->shoulder_width }},
            thigh_circumference: {{ $avatar->thigh_circumference }},
            arm_length: {{ $avatar->arm_length }},
            leg_length: {{ $avatar->leg_length }},
            height: {{ $avatar->height }},
            weight: {{ $avatar->weight }}
        };
    </script>

    <!-- Three.js Core + Addons -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/loaders/GLTFLoader.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(300, 400);
            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.outputEncoding = THREE.sRGBEncoding;
            renderer.toneMapping = THREE.ACESFilmicToneMapping;
            renderer.toneMappingExposure = 1.2;
            document.getElementById('avatar-canvas-container').appendChild(renderer.domElement);

            // Lighting
            scene.add(new THREE.AmbientLight(0xffffff, 0.6));
            scene.add(new THREE.HemisphereLight(0xffffff, 0x444444, 0.5));

            const keyLight = new THREE.DirectionalLight(0xffffff, 1);
            keyLight.position.set(1, 2, 2);
            scene.add(keyLight);

            const backLight = new THREE.DirectionalLight(0xffffff, 0.8);
            backLight.position.set(-1, 2, -2);
            scene.add(backLight);

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.enableZoom = false;
            controls.enablePan = false;
            controls.target.set(0, 1.0, 0);
            controls.update();

            const loader = new THREE.GLTFLoader();
            let mesh, morphDict, morphInfluences;

            const morphRanges = {
                bust_size: [70, 130],
                waist_size: [50, 100],
                hip_size: [70, 130],
                shoulder_width: [30, 60],
                thigh_circumference: [30, 70],
                arm_length: [40, 70],
                leg_length: [60, 120],
                height: [140, 200],
                weight: [40, 120]
            };

            const avatarMorphData = { ...window.avatarMorphData };

            loader.load(window.avatarModelURL, function (gltf) {
                const model = gltf.scene;

                const pivot = new THREE.Group();
                scene.add(pivot);

                model.position.set(0, -0.2, 0);
                model.scale.set(1.3, 1.3, 1.3);
                pivot.add(model);

                model.traverse((node) => {
                    if (node.isMesh && node.material) {
                        node.material.color.set(0xfce6d4);
                        node.material.needsUpdate = true;
                    }
                });

                mesh = model.getObjectByProperty('type', 'SkinnedMesh') || model.children[0];
                if (mesh && mesh.morphTargetInfluences) {
                    morphDict = mesh.morphTargetDictionary;
                    morphInfluences = mesh.morphTargetInfluences;

                    console.log('Morph Targets Available in Mesh:', mesh.morphTargetDictionary);

                    updateMorphTargets();
                }
            });

            function updateMorphTargets() {
                if (!mesh || !morphDict || !morphInfluences) return;

                for (let key in avatarMorphData) {
                    if (morphDict[key] !== undefined && morphRanges[key]) {
                        const norm = (avatarMorphData[key] - morphRanges[key][0]) / (morphRanges[key][1] - morphRanges[key][0]);
                        morphInfluences[morphDict[key]] = THREE.MathUtils.clamp(norm, 0, 1);
                    }
                }
            }

            // 🔁 Attach listeners to inputs + sliders
            document.querySelectorAll('input[type="range"], input[type="number"]').forEach((input) => {
                input.addEventListener('input', () => {
                    const key = input.name;
                    const val = parseFloat(input.value);
                    if (!isNaN(val)) {
                        avatarMorphData[key] = val;
                        const slider = document.getElementById(`${key}_slider`);
                        const textbox = document.getElementById(`${key}`);
                        if (input.type === 'range' && textbox) textbox.value = val;
                        if (input.type === 'number' && slider) slider.value = val;
                        updateMorphTargets();
                    }
                });
            });

            camera.position.set(0, 1.4, 3.75);

            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }
            animate();
        });
    </script>
@endif
