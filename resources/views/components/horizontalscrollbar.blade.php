<div class="bg-white rounded-lg shadow-md p-4 min-w-[250px] mx-2">
    <div class="bg-gray-200 h-48 flex items-center justify-center rounded-md">
        <img src="{{ $image ?? '#' }}" alt="Placeholder" class="w-full h-full object-cover {{ $image ? '' : 'hidden' }}">
    </div>
    <p class="mt-2 text-sm">{{ $text ?? 'Default Text' }}</p>
    <p class="text-lg font-semibold">${{ $price ?? '0' }}</p>
</div>
