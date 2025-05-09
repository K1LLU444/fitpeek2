<x-app-layout>
    <div class="max-w-xl mx-auto mt-24">
        <h1 class="text-2xl font-semibold mb-6">Upload Image to Remove Background</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('clean.process') }}" method="POST" enctype="multipart/form-data" class="mb-8">
            @csrf
            <input type="file" name="image" class="mb-4 block w-full">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Upload & Process</button>
        </form>

        @isset($cleanedImage)
            <div class="mt-6">
                <h2 class="text-lg font-semibold mb-2">Processed Image:</h2>
                <img src="{{ $cleanedImage }}" alt="Cleaned" class="border rounded shadow">
            </div>
        @endisset
    </div>
</x-app-layout>
