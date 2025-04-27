@props(['image', 'text', 'price','link'])
<a href="{{ $link }}">
    <div class="bg-white shadow-md rounded-lg p-4 w-full max-w-xs
                transition-transform duration-300 ease-in-out transform 
                hover:-translate-y-1 hover:shadow-l hover:border hover:border-gray-200">
        <img src="{{ $image }}" alt="{{ $text }}" class="w-full h-auto rounded-md mb-2">
        <h3 class="text-md font-semibold">{{ $text }}</h3>
        <p class="text-gray-700 text-sm">Php {{ $price }}</p>@props(['image', 'text', 'price', 'link'])
</a>

</div>