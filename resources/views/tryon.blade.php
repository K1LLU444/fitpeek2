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

                <!-- ✅ Insert Recommended Size below description -->
                @if(isset($recommendedSize))
                    <p class="text-sm font-semibold mt-2 text-center md:text-left text-green-600">
                        Recommended Size: {{ $recommendedSize }}
                    </p>
                @endif

                <label for="size" class="block text-sm font-medium mt-4 text-center md:text-left">Sizes</label>
                <select id="size" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                    <option disabled {{ isset($recommendedSize) ? '' : 'selected' }}>--Pick your Size--</option>
                    <option {{ (isset($recommendedSize) && strtolower($recommendedSize) == 'small') ? 'selected' : '' }}>Small</option>
                    <option {{ (isset($recommendedSize) && strtolower($recommendedSize) == 'medium') ? 'selected' : '' }}>Medium</option>
                    <option {{ (isset($recommendedSize) && strtolower($recommendedSize) == 'large') ? 'selected' : '' }}>Large</option>
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

        @if($avatar && $avatar->avatar_path)
            <!-- OBJ Loading Scripts -->
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
                    renderer.setPixelRatio(window.devicePixelRatio);
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
                
                    const loader = new THREE.OBJLoader();
                    loader.load('{{ asset($avatar->avatar_path) }}', function (object) {
                        object.traverse(function (child) {
                            if (child.isMesh) {
                                child.material = new THREE.MeshStandardMaterial({
                                    color: 0xcccccc,
                                    roughness: 0.5,
                                    metalness: 0.1,
                                });
                                child.material.needsUpdate = true;
                            }
                        });
                
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
    </body>
</x-app-layout>
