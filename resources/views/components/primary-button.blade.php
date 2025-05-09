<button {{ $attributes->merge(['type' => 'submit', 'class' => 'align-center line-flex items-center px-4 py-2 bg-accent-color border border-transparent rounded-md font-semibold text-m text-white hover:bg-accent-hover focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
