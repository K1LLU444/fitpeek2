<div class="bg-white rounded-lg shadow-md p-4 min-w-[250px] mx-2 
                transition-transform duration-300 ease-in-out transform 
                hover:-translate-y-1 hover:shadow-l hover:border hover:border-gray-200">
    <div class="h-48 flex items-center justify-center rounded-md">
        <img src="{{ $image ?? '#' }}" alt="Placeholder" class="w-full h-full object-cover {{ $image ? '' : 'hidden' }}">
    </div>
    <p class="text-md font-semibold">{{ $text ?? 'Default Text' }}</p>
    <p class="text-gray-700 text-sm">Php {{ $price ?? '0' }}</p>
</div>
