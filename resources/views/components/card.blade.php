<div class="bg-white p-4 rounded-lg shadow-md h-[350px] w-[300px]">
    <div class="bg-gray-300 h-[250px] flex items-center justify-center rounded-md">
        <img src="{{ $image ?? 'default.jpg' }}" alt="Image" class="w-full h-full object-cover">
    </div>
    <p class="mt-2 text-sm">{{ $text ?? 'Default Text' }}</p>
    <p class="text-lg font-semibold">₱{{ $price ?? '0' }}</p>
</div>
