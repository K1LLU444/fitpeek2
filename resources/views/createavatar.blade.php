<script>
    const SHOULD_CAPTURE = {{ session('shouldCapture', false) ? 'true' : 'false' }};
    @php
        session()->forget('shouldCapture');
    @endphp

    const aPoseObjPath = "{{ $avatar && $avatar->avatar_apose_path ? asset($avatar->avatar_apose_path) : '' }}";
    const aPoseLowerPath = "{{ $avatar && $avatar->avatar_apose_lower_path ? asset($avatar->avatar_apose_lower_path) : '' }}";
    const tPoseObjPath = "{{ $avatar && $avatar->avatar_path ? asset($avatar->avatar_path) : '' }}";
</script>

<x-app-layout>
    <body class="bg-gray-100 px-6 py-10 min-h-screen">
        <div class="flex flex-col lg:flex-row gap-8 max-w-screen-xl mx-auto pt-28">

            <div id="avatar-loader" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50 hidden">
                <div class="text-center">
                    <div class="animate-spin h-10 w-10 border-4 border-gray-300 border-t-blue-500 rounded-full mx-auto mb-4"></div>
                    <p class="text-lg font-medium text-gray-800">Generating avatar poses...</p>
                </div>
            </div>

            <!-- Avatar Preview Section -->
            <div class="flex-1 bg-gray-100 flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Your Avatar</h2>
                <div class="w-full max-w-md h-[420px] bg-white rounded-lg shadow-inner flex items-center justify-center">
                    @if($avatar && $avatar->avatar_path)
                        <div id="avatar-canvas-container" class="w-full h-full"></div>
                    @else
                        <p class="text-gray-400">No avatar found. Fill in the form to generate your avatar.</p>
                    @endif

                </div>
            </div>

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

                    <!-- Unit Selection -->
                    <div class="mt-4">
                        <x-input-label for="unit" :value="__('Measurement Unit')" />
                        @php
                            $selectedUnit = old('unit') ?? ($avatar->unit ?? session('last_unit', 'cm'));
                        @endphp
                        <select id="unit" name="unit" class="block mt-1 w-full p-2 border rounded-md shadow-inner" required>
                            <option value="cm" {{ $selectedUnit === 'cm' ? 'selected' : '' }}>Centimeters (cm)</option>
                            <option value="in" {{ $selectedUnit === 'in' ? 'selected' : '' }}>Inches (in)</option>
                        </select>
                    </div>


                    @php
                        $allRanges = [
                            'height' => ['male' => [158.41, 200.65], 'female' => [144.68, 184.64]],
                            'weight' => ['male' => [11.52, 147.26],  'female' => [27.78, 141.15]],
                            'chest'  => ['male' => [75.56, 145.05],  'female' => [69.54, 147.15]],
                            'waist'  => ['male' => [45.32, 144.91],  'female' => [42.65, 131.50]],
                            'hips'   => ['male' => [75.24, 134.20],  'female' => [76.29, 141.91]],
                            'inseam' => ['male' => [62.71, 96.36],   'female' => [61.67, 89.28]],
                        ];
                        $currentSex = strtolower(old('sex', $avatar->sex ?? 'male'));
                        $unit = old('unit', session('last_unit', 'cm'));
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($allRanges as $field => $range)
                        @php
                            $value = old($field, $avatar->$field ?? '');
                            [$rawMin, $rawMax] = $range[$currentSex];
                            $unit = old('unit', session('last_unit', 'cm'));

                            if ($field === 'weight') {
                                $min = $rawMin;
                                $max = $rawMax;
                            } else {
                                $min = $unit === 'in' ? round($rawMin / 2.54, 2) : $rawMin;
                                $max = $unit === 'in' ? round($rawMax / 2.54, 2) : $rawMax;
                            }
                        @endphp

                            <div class="mt-4">
                            <x-input-label 
    for="{{ $field }}" 
    :value="__(ucwords(str_replace('_', ' ', $field)) . ($field === 'weight' ? ' (kg)' : ' (' . ($unit == 'in' ? 'in' : 'cm') . ')'))" 
/>
                                <x-text-input
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    type="number"
                                    min="{{ $min }}"
                                    max="{{ $max }}"
                                    step="any"
                                    class="block mt-1 w-full placeholder-gray-600"
                                    value="{{ $value }}"
                                    required
                                    data-male-min="{{ $range['male'][0] }}"
                                    data-male-max="{{ $range['male'][1] }}"
                                    data-female-min="{{ $range['female'][0] }}"
                                    data-female-max="{{ $range['female'][1] }}"
                                />
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center mt-12">
                        <x-primary-button type="submit" class="w-full">
                            {{ $avatar ? 'Update Avatar' : 'Save Avatar' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
                <!-- Measurement Tutorial Section -->
                <div id="tutorial" class="mt-20 bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-6 text-center">How to Measure Your Body</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                        <!-- Column 1 -->
                        <div class="space-y-4">
                            {{-- Bust Size --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/bust.png') }}" alt="Bust Size" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Bust Size</h3>
                                    <p class="text-gray-700">Wrap the tape measure around the fullest part of your chest. Keep it snug but not tight.</p>
                                </div>
                            </div>
                
                            {{-- Waist Size --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/waist.png') }}" alt="Waist Size" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Waist Size</h3>
                                    <p class="text-gray-700">Find the narrowest part of your waist (usually above the belly button) and measure around it.</p>
                                </div>
                            </div>
                
                            {{-- Inseam --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/inseam.png') }}" alt="Inseam" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Inseam</h3>
                                    <p class="text-gray-700">Stand straight with your legs slightly apart. Place the end of a measuring tape at the top of your inner thigh (crotch area) and extend it straight down to the floor.</p>
                                </div>
                            </div>
                        </div>
                
                        <!-- Column 2 -->
                        <div class="space-y-4">
                            {{-- Hip Size --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/hip.png') }}" alt="Hip Size" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Hip Size</h3>
                                    <p class="text-gray-700">Measure around the widest part of your hips and buttocks while standing naturally.</p>
                                </div>
                            </div>
                
                            {{-- Height --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/height.png') }}" alt="Height" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Height</h3>
                                    <p class="text-gray-700">Stand straight and measure from the floor to the top of your head without shoes.</p>
                                </div>
                            </div>
                
                            {{-- Weight --}}
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <img src="{{ asset('images/tutorial/weight.png') }}" alt="Weight" class="w-40 h-40 object-contain" />
                                <div>
                                    <h3 class="text-lg font-semibold">Weight</h3>
                                    <p class="text-gray-700">Weigh yourself using a digital or analog scale, preferably in the morning before eating.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <!-- JavaScript: Dynamic min/max based on selected sex and unit -->

            <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/examples/js/loaders/OBJLoader.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/examples/js/controls/OrbitControls.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const sexSelect = document.getElementById('sex');
                    const unitSelect = document.getElementById('unit');
                    const inputs = document.querySelectorAll('input[type="number"]');

                    function updateMinMax() {
                        const selectedSex = sexSelect.value.toLowerCase();
                        const selectedUnit = unitSelect.value;

                        inputs.forEach(input => {
                            const rawMin = parseFloat(input.getAttribute(`data-${selectedSex}-min`));
                            const rawMax = parseFloat(input.getAttribute(`data-${selectedSex}-max`));

                            if (!isNaN(rawMin) && !isNaN(rawMax)) {
                                if (input.id === 'weight') {
                                    input.min = rawMin;
                                    input.max = rawMax;
                                } else {
                                    const min = selectedUnit === 'in' ? (rawMin / 2.54).toFixed(2) : rawMin;
                                    const max = selectedUnit === 'in' ? (rawMax / 2.54).toFixed(2) : rawMax;
                                    input.min = min;
                                    input.max = max;
                                }
                            }
                        });

                    }

                    // Update when unit or sex changes
                    sexSelect.addEventListener('change', updateMinMax);
                    unitSelect.addEventListener('change', updateMinMax);

                    // Run on initial page load
                    updateMinMax();
                });
            </script>
                <!-- Avatar loading overlay -->
    </body>
</x-app-layout>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlayLoader = document.getElementById('avatar-loader');
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

            scene.add(new THREE.AmbientLight(0xffffff, 0.4));
            const directional = new THREE.DirectionalLight(0xffffff, 0.6);
            directional.position.set(3, 5, 2);
            scene.add(directional);
            const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
            backLight.position.set(-3, 3, -3);
            scene.add(backLight);

            let avatarObject = null;

            function loadOBJ(path, callback) {
                const loader = new THREE.OBJLoader();
                loader.load(path, function (object) {
                    object.traverse(child => {
                        if (child.isMesh) {
                            child.material = new THREE.MeshStandardMaterial({
                                color: 0xcccccc, roughness: 0.5, metalness: 0.1
                            });
                        }
                    });
                    object.scale.set(1.3, 1.3, 1.3);
                    object.position.y = 0;
                    callback(object);
                });
            }

            function replaceAvatar(object) {
                if (avatarObject) scene.remove(avatarObject);
                scene.add(object);
                avatarObject = object;
            }

            function captureAvatar(view, callback, pose = 'apose') {
    if (!avatarObject) return;

    controls.enabled = false;
    camera.position.set(0, 1.4, view === 'front' ? 3.5 : -3.5);
    controls.target.set(0, 1.2, 0);
    controls.update();
    renderer.render(scene, camera);

    // ✅ Delay screenshot by waiting for 3 frames (reliable render)
    let frameCount = 0;
    function waitFrames() {
        frameCount++;
        renderer.render(scene, camera);
        if (frameCount < 3) {
            requestAnimationFrame(waitFrames);
        } else {
            const dataURL = renderer.domElement.toDataURL('image/png');

            fetch('{{ route('avatar.upload') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: dataURL, view: view, pose: pose })
            })
            .then(response => response.json())
            .then(data => {
                console.log(`📸 Screenshot (${pose}-${view}) uploaded.`);
                if (callback) callback();
            })
            .catch(error => {
                console.error('❌ Upload error:', error);
            })
            .finally(() => {
                controls.enabled = true;
            });
        }
    }

    requestAnimationFrame(waitFrames);
}



            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }
            animate();
            if (SHOULD_CAPTURE && aPoseObjPath && aPoseLowerPath && tPoseObjPath) {
    overlayLoader.classList.remove('hidden');

    loadOBJ(aPoseObjPath, function (aposeAvatar) {
        replaceAvatar(aposeAvatar);
        setTimeout(() => {
            captureAvatar('front', () => {
                setTimeout(() => {
                    captureAvatar('back', () => {
                        setTimeout(() => {
                            loadOBJ(aPoseLowerPath, function (loweredAvatar) {
                                replaceAvatar(loweredAvatar);
                                setTimeout(() => {
                                    captureAvatar('front', () => {
                                        setTimeout(() => {
                                            captureAvatar('back', () => {
                                                setTimeout(() => {
                                                    loadOBJ(tPoseObjPath, function (tposeAvatar) {
                                                        replaceAvatar(tposeAvatar);
                                                        setTimeout(() => {
                                                            captureAvatar('front', () => {
                                                                setTimeout(() => {
                                                                    captureAvatar('back', () => {
                                                                        // ✅ Reset to front-facing after all captures
                                                                        camera.position.set(0, 1.4, 3.5);
                                                                        controls.target.set(0, 1.2, 0);
                                                                        controls.update();
                                                                        overlayLoader.classList.add('hidden');
                                                                    }, 'tpose');
                                                                }, 1000);
                                                            }, 'tpose');
                                                        }, 1000);
                                                    });
                                                }, 1000);
                                            }, 'apose_lower');
                                        }, 1000);
                                    }, 'apose_lower');
                                }, 1200);
                            });
                        }, 800);
                    }, 'apose');
                }, 1000);
            }, 'apose');
        }, 1200);
    });
}

        else if (tPoseObjPath) {
            // Load T-pose if no capture is needed
            loadOBJ(tPoseObjPath, function (tposeAvatar) {
                replaceAvatar(tposeAvatar);
            });
        }

        console.log("SHOULD_CAPTURE =", SHOULD_CAPTURE);

        
    });
    </script>

    <!-- hidden buttons -->
    <button id="captureFront" class="hidden">Capture Front</button>
    <button id="captureBack" class="hidden">Capture Back</button>
    
