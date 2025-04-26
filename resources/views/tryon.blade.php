<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto mt-20 bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row gap-6">

                <!-- Avatar Viewer -->
                <div class="relative w-[300px] h-[600px] bg-gray-200 rounded-lg overflow-hidden">
                    <div id="avatar-canvas-container" class="absolute inset-0"></div>
                </div>

                <!-- Info Panel -->
                <div class="flex-1">
                    <h2 class="text-xl font-bold">Try-On Mode</h2>
                    <p class="text-sm text-gray-500 mt-2">Preview your generated avatar below.</p>
                    <a href="{{ asset($avatar->avatar_path) }}" download class="text-blue-600 mt-4 inline-block">⬇ Download Avatar (.obj)</a>
                </div>

            </div>
        </div>
    </body>
</x-app-layout>

@if($avatar && $avatar->avatar_path)
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.140.0/examples/js/loaders/OBJLoader.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(300, 600);
            document.getElementById('avatar-canvas-container').appendChild(renderer.domElement);

            scene.add(new THREE.AmbientLight(0xffffff, 0.7));
            const light = new THREE.DirectionalLight(0xffffff, 0.8);
            light.position.set(5, 10, 7.5);
            scene.add(light);

            const loader = new THREE.OBJLoader();
            loader.load('{{ asset($avatar->avatar_path) }}', function (object) {
                object.scale.set(1.3, 1.3, 1.3);
                object.position.y = -1.2;
                scene.add(object);
            });

            camera.position.set(0, 1.4, 3.5);

            function animate() {
                requestAnimationFrame(animate);
                renderer.render(scene, camera);
            }
            animate();
        });
    </script>
@endif
