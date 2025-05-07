@props(['image', 'text', 'price', 'link'])

<a href="{{ $link }}">
    <div class="bg-white shadow-md rounded-lg p-4 w-full h-[420px] max-w-xs
                transition-transform duration-300 ease-in-out transform 
                hover:-translate-y-1 hover:shadow-l hover:border hover:border-gray-200">
        
                <div class="w-[300px] h-[300px] bg-white rounded-md overflow-hidden flex items-center justify-center mb-2">
                <img src="{{ $image }}" alt="{{ $text }}" class="max-h-full max-w-full object-contain" />
                </div>


        <h3 class="text-md font-semibold">{{ $text }}</h3>
        <p class="text-gray-700 text-sm">Php {{ $price }}</p>
    </div>
</a>
