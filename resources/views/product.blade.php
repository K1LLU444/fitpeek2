<x-app-layout>
    <div class="min-h-screen bg-gray-100 pt-24">
        <main>
            @if($avatar)
                @include('partials.avatar_tryon', ['product' => $product, 'avatar' => $avatar])
            @else
                <div class="text-center text-gray-500 mt-10">
                    You need to create an avatar first. <a href="{{ route('createavatar') }}" class="text-blue-500 underline">Click here</a>
                </div>
            @endif
        </main>
    </div>
    <x-footer />
</x-app-layout>
