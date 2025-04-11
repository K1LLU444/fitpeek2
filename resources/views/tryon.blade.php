<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto mt-20 bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row gap-6">

                <!-- 3D Avatar Scene -->
                <div class="relative w-[300px] h-[600px] bg-gray-200 rounded-lg overflow-hidden">
                    <div id="avatar-canvas-container" class="absolute inset-0"></div>
                </div>

                <!-- Controls & Info -->
                <div class="flex-1">
                    <h2 class="text-xl font-bold">Product Name</h2>
                    <p class="text-gray-600 mt-1">Php 50</p>
                    <p class="mt-4 text-sm">Select your size to preview clothes on the avatar.</p>

                    <label for="topSizeSelect" class="block mt-4 font-medium">Top Size</label>
                    <select id="topSizeSelect" class="w-full p-2 border rounded">
                        @foreach ($topSizes as $size)
                            <option value='@json($size)'>{{ $size['size'] }}</option>
                        @endforeach
                    </select>

                    <label for="bottomSizeSelect" class="block mt-4 font-medium">Bottom Size</label>
                    <select id="bottomSizeSelect" class="w-full p-2 border rounded">
                        @foreach ($bottomSizes as $size)
                            <option value='@json($size)'>{{ $size['size'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/controls/OrbitControls.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r140/examples/js/loaders/GLTFLoader.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, 300 / 600, 0.1, 1000);
            camera.position.set(0, 1.4, 2.5);

            const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
            renderer.setSize(300, 600);
            renderer.setPixelRatio(window.devicePixelRatio);
            document.getElementById('avatar-canvas-container').appendChild(renderer.domElement);

            // Lighting
            scene.add(new THREE.AmbientLight(0xffffff, 0.9));

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.target.set(0, 1.4, 0);
            controls.enableDamping = true;

            // Load Avatar
            const loader = new THREE.GLTFLoader();
            loader.load(@json($avatarUrl), (gltf) => {
                const model = gltf.scene;
                model.position.set(0, 0.5, 0);
                scene.add(model);
            });

            // Load Clothes as Sprites
            const textureLoader = new THREE.TextureLoader();

            const topSprite = new THREE.Sprite(new THREE.SpriteMaterial({
                map: textureLoader.load('/images/top.png'),
                transparent: true
            }));
            topSprite.position.set(0, 1.5, 0);
            scene.add(topSprite);

            const bottomSprite = new THREE.Sprite(new THREE.SpriteMaterial({
                map: textureLoader.load('/images/bottom.png'),
                transparent: true
            }));
            bottomSprite.position.set(0, 0.85, 0);
            scene.add(bottomSprite);

            // Sizing logic
            function updateTopSize(data) {
                const scaleX = data.width / 26.0;
                const scaleY = data.length / 30.5;
                topSprite.scale.set(scaleX, scaleY, 1);
            }

            function updateBottomSize(data) {
                const waist = (data.waistline_min + data.waistline_max) / 2;
                const scaleX = waist / 36.0;
                const scaleY = data.length / 40.0;
                bottomSprite.scale.set(scaleX, scaleY, 1);
            }

            document.getElementById('topSizeSelect')?.addEventListener('change', function (e) {
                const data = JSON.parse(e.target.value);
                updateTopSize(data);
            });

            document.getElementById('bottomSizeSelect')?.addEventListener('change', function (e) {
                const data = JSON.parse(e.target.value);
                updateBottomSize(data);
            });

            // Set initial size
            const topInit = document.getElementById('topSizeSelect')?.value;
            const bottomInit = document.getElementById('bottomSizeSelect')?.value;
            if (topInit) updateTopSize(JSON.parse(topInit));
            if (bottomInit) updateBottomSize(JSON.parse(bottomInit));

            // Animate
            const animate = () => {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }
            animate();
        });
        </script>
    </body>
</x-app-layout>
