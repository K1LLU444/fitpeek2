<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Choose Front & Back Images</h2>

        <form method="POST" action="{{ route('products.select-images') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($images as $img)
                    <div class="flex flex-col items-center border p-4 rounded shadow">
                        <img src="{{ $img }}" class="w-40 h-40 object-cover mb-2 rounded">
                        <div class="space-x-3 text-sm">
                            <label><input type="radio" name="front_image" value="{{ $img }}" required> Front</label>
                            <label><input type="radio" name="back_image" value="{{ $img }}" required> Back</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-right mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Save Selection
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
