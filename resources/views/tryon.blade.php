<style>
    /* Hide Scrollbar */
    .scroll-container {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        overflow-x: scroll;
        position: relative;
    }
    .scroll-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }
</style>

<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 gap-4 sm:gap-6 shadow-md">
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-200 rounded-lg p-4 sm:p-6">
<<<<<<< HEAD
                <div class="bg-gray-300 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">  
                    <div id="avatar-canvas-container" class="w-full h-full">
                        
                    </div>
                </div>                             
=======
                <div class="bg-gray-300 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">
                        <p>No avatar found</p>
                </div>
>>>>>>> c096a890cf9ccef05de4b472e901f3cfa22a6255
            </div>

            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-center md:text-left">Product Name</h2>
                <span class="inline-block bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-md mt-2">
                    Tag
                </span>
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

        <!-- Horizontal Scroll Section -->
        <div class="bg-gray-100 p-4 sm:p-6 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Recommended</h2>

                <!-- Scrollable container with Next/Prev buttons -->
                <div class="relative">
                    <div id="scrollContainerPrev" class="scroll-container whitespace-nowrap p-2 flex scroll-smooth">
                        <div class="flex space-x-4 pb-4">
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 1" price="5" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 2" price="10" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 1" price="5" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 2" price="10" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
                        </div>
                    </div>

                    <!-- Next Button (Right) -->
                    <button id="nextBtnPrev" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Previous Button (Left) -->
                    <button id="prevBtnPrev" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
                </div>
            </div>
        </div>
        
<!-- JavaScript for Scrolling Functionality -->
<script>
    // Scroll Next/Previous for REcommended
    document.getElementById('nextBtnPrev').addEventListener('click', function () {
        document.getElementById('scrollContainerPrev').scrollBy({ left: 300, behavior: 'smooth' });
    });

    document.getElementById('prevBtnPrev').addEventListener('click', function () {
        document.getElementById('scrollContainerPrev').scrollBy({ left: -300, behavior: 'smooth' });
    });

</script>
        <x-footer />
        <script>
            window.avatarModelURL = @json($avatarUrl);
            window.avatarMorphData = @json($bodyData);
        </script>
        
        <!-- Fully working Three.js + OrbitControls + GLTFLoader -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/controls/OrbitControls.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/loaders/GLTFLoader.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                renderer.setSize(350, 400);
                renderer.setPixelRatio(window.devicePixelRatio);
                document.getElementById('avatar-canvas-container').appendChild(renderer.domElement);
                renderer.outputEncoding = THREE.sRGBEncoding;
                renderer.toneMapping = THREE.ACESFilmicToneMapping;
                renderer.toneMappingExposure = 0.5;


                // Ambient light for overall base illumination
                const ambientLight = new THREE.AmbientLight(0xffffff, 1);
                scene.add(ambientLight);

                // Directional light from front
                const frontLight = new THREE.DirectionalLight(0xffffff, 0.8);
                frontLight.position.set(0, 1.5, 2);
                scene.add(frontLight);

                // Directional light from back
                const backLight = new THREE.DirectionalLight(0xffffff, 0.8);
                backLight.position.set(0, 1.5, -2);
                scene.add(backLight);

                // Optional: Side light to flatten shadows even more
                const sideLight = new THREE.DirectionalLight(0xffffff, 0.5);
                sideLight.position.set(2, 1.5, 0);
                scene.add(sideLight);

        
                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.target.set(0, 1.4, 0);
        
                const loader = new THREE.GLTFLoader();
                loader.load(window.avatarModelURL, function (gltf) {
                    const model = gltf.scene;
                    model.position.set(0, 0.5, 0);
                    model.rotation.y = 0;
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
                }, undefined, function (error) {
                    console.error('GLTF load error:', error);
                });
        
                camera.position.set(0, 1.4, 2.5);
        
                function animate() {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                }
                animate();
            });
        </script>
        
    </body>
</x-app-layout>
