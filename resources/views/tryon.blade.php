<style>
    .scroll-container {
        -ms-overflow-style: none;
        scrollbar-width: none;
        overflow-x: scroll;
    }
    .scroll-container::-webkit-scrollbar {
        display: none;
    }
    #avatar-canvas-container canvas {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }
</style>

<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">

        <!-- Avatar + Product Details -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-5xl mx-auto mt-20 gap-6 shadow-md">
            <!-- Avatar Preview -->
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-200 rounded-lg p-4">
                <div class="w-full h-[300px] sm:h-[400px] bg-gray-300 rounded-md flex items-center justify-center">
                    <div id="avatar-canvas-container" class="w-full h-full"></div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-center md:text-left">Product Name</h2>
                <span class="inline-block bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-md mt-2">Tag</span>
                <div class="flex justify-center md:justify-start items-center mt-2">
                    <span class="text-lg font-semibold">Php</span>
                    <span class="text-3xl font-bold ml-1">50</span>
                </div>
                <p class="text-gray-700 text-sm sm:text-base mt-4 leading-relaxed text-center md:text-left">
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore..."
                </p>
                <p class="text-sm font-semibold mt-4 text-center md:text-left">Recommended Size: Medium</p>
                <label for="size" class="block text-sm font-medium mt-4 text-center md:text-left">Sizes</label>
                <select id="size" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                    <option>--Pick your Size--</option>
                    <option>Small</option>
                    <option>Medium</option>
                    <option>Large</option>
                </select>
                <button class="w-full bg-accent-color text-white font-semibold py-2 mt-4 rounded-lg hover:bg-accent-hover transition-all duration-300">
                    Try-on
                </button>
            </div>
        </div>

        <!-- Recommended Products Section -->
        <div class="bg-gray-100 p-4 sm:p-6 mt-10">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Recommended</h2>
                <div class="relative">
                    <div id="scrollContainerPrev" class="scroll-container whitespace-nowrap p-2 flex scroll-smooth">
                        <div class="flex space-x-4 pb-4">
                            @for ($i = 1; $i <= 8; $i++)
                                <x-horizontalscrollbar 
                                    class="w-[180px] md:w-[200px] lg:w-[250px]" 
                                    image="image.jpg" 
                                    text="Product {{ $i }}" 
                                    price="{{ $i * 10 }}" />
                            @endfor
                        </div>
                    </div>

                    <!-- Scroll Buttons -->
                    <button id="prevBtnPrev" class="hidden md:block absolute top-1/2 left-0 transform -translate-y-1/2 bg-white hover:bg-gray-200 p-2 rounded-full shadow z-10">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="nextBtnPrev" class="hidden md:block absolute top-1/2 right-0 transform -translate-y-1/2 bg-white hover:bg-gray-200 p-2 rounded-full shadow z-10">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <x-footer />

        <!-- Avatar Data -->
        <script>
            window.avatarModelURL = @json($avatarUrl);
            window.avatarMorphData = @json($bodyData);
        </script>

        <!-- Three.js Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/controls/OrbitControls.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/loaders/GLTFLoader.js"></script>

        <!-- Avatar Rendering with Soft Gray Color Fix -->
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('avatar-canvas-container');
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            container.appendChild(renderer.domElement);

            function resizeCanvas() {
                const width = container.clientWidth;
                const height = container.clientHeight;
                renderer.setSize(width, height);
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.outputEncoding = THREE.sRGBEncoding;
            renderer.toneMapping = THREE.ACESFilmicToneMapping;
            renderer.toneMappingExposure = 0.5;

            // Balanced Lighting
            scene.add(new THREE.AmbientLight(0xffffff, 0.3));
            const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
            dirLight.position.set(0, 2, 2);
            scene.add(dirLight);

            const fillLight = new THREE.DirectionalLight(0xffffff, 0.3);
            fillLight.position.set(-2, 1, 0);
            scene.add(fillLight);

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.target.set(0, 1.4, 0);
            camera.position.set(0, 1.4, 2.5);

            const loader = new THREE.GLTFLoader();
            loader.load(window.avatarModelURL, (gltf) => {
                const model = gltf.scene;
                model.position.set(0, 0.5, 0);

                // Material adjustments for soft gray
                model.traverse((child) => {
                    if (child.isMesh && child.material) {
                        child.material.toneMapped = true;
                        child.material.metalness = 0.1;
                        child.material.roughness = 0.6;
                        child.material.color.setScalar(0.9); // soft gray tone
                        child.material.needsUpdate = true;
                    }
                });

                scene.add(model);

                const skinnedMesh = model.getObjectByProperty('type', 'SkinnedMesh') || model.children[0];
                if (skinnedMesh && skinnedMesh.morphTargetInfluences) {
                    const morphDict = skinnedMesh.morphTargetDictionary;
                    const morphInfluences = skinnedMesh.morphTargetInfluences;
                    const data = window.avatarMorphData;

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

                    for (let key in data) {
                        const value = data[key];
                        const range = morphRanges[key];
                        if (range && morphDict[key] !== undefined) {
                            const normalized = (value - range[0]) / (range[1] - range[0]);
                            morphInfluences[morphDict[key]] = THREE.MathUtils.clamp(normalized, 0, 1);
                        }
                    }
                }
            }, undefined, (error) => {
                console.error('GLTF load error:', error);
            });

            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }

            animate();

            // Scroll Buttons
            document.getElementById('nextBtnPrev')?.addEventListener('click', () => {
                document.getElementById('scrollContainerPrev').scrollBy({ left: 300, behavior: 'smooth' });
            });
            document.getElementById('prevBtnPrev')?.addEventListener('click', () => {
                document.getElementById('scrollContainerPrev').scrollBy({ left: -300, behavior: 'smooth' });
            });
        });
        </script>
    </body>
</x-app-layout>
