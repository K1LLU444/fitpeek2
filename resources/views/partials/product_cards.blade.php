@forelse ($products as $product)
    <div class="flex justify-center">
        @php
            $fallbackImage = $product->back_image ?? null;
            $displayImage = $product->front_image ?: $fallbackImage;
            $imageSrc = Str::startsWith($displayImage, ['http://', 'https://'])
                ? $displayImage
                : asset($displayImage);
        @endphp

        <x-card 
            :link="route('product.show', $product->id)"
            image="{{ $displayImage ? $imageSrc : asset('images/placeholder.png') }}"
            text="{{ $product->product_name }}"
            price="{{ $product->price }}"
        />
    </div>
@empty
    <p class="text-center text-gray-500 col-span-full">No products found.</p>
@endforelse
