<div class="bg-white shadow-lg rounded-lg p-6 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
    <div class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded-lg">
        <img src="{{ $image ?? '#' }}" alt="Image" class="w-full h-full object-cover rounded-lg">
    </div>
    <div class="text-center sm:text-left">
        <h3 class="text-lg font-semibold">{{ $name ?? 'Team Member' }}</h3>
        <p class="text-sm text-gray-600">
            {{ $description ?? 'Description of the team member.' }}
        </p>
    </div>
</div>
