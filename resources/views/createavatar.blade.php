<x-app-layout>
    <body class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="mx-auto mt-20 mb-20 bg-white p-6 sm:p-8 rounded-lg shadow-2xl w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
            <h2 class="text-xl sm:text-2xl font-bold text-center mb-4 sm:mb-6">Enter your Measurements</h2>

            <!-- Form will use POST if no avatar exists, otherwise it will use PUT -->
            <form method="{{ $avatar ? 'POST' : 'POST' }}" 
                  action="{{ $avatar ? route('avatar.update', $avatar->id) : route('avatar.store') }}">
                @csrf
                @if($avatar)
                    @method('PUT')
                @endif

                <div class="mt-4">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name"
                                  class="block mt-1 w-full placeholder-gray-600"
                                  placeholder="Enter your name"
                                  type="text"
                                  name="name"
                                  value="{{ old('name', $avatar->name ?? '') }}"
                                  required />
                </div>

                <div class="mt-4">
                    <x-input-label for="sex" :value="__('Sex')" />
                    <select id="sex" name="sex" class="block mt-1 w-full p-2 border rounded-md shadow-inner">
                        <option value="Male" {{ isset($avatar) && $avatar->sex == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ isset($avatar) && $avatar->sex == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Measurement Fields -->
                @php
                    $fields = ['bust_size', 'shoulder_width', 'waist_size', 'hip_size', 'height', 'weight', 'arm_length', 'leg_length', 'thigh_circumference'];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($fields as $field)
                        <div class="mt-4">
                            <x-input-label for="{{ $field }}" :value="__(ucwords(str_replace('_', ' ', $field)))" />
                            <x-text-input id="{{ $field }}"
                                          class="block mt-1 w-full placeholder-gray-600"
                                          placeholder="cm"
                                          type="text"
                                          name="{{ $field }}"
                                          value="{{ old($field, $avatar->$field ?? '') }}"
                                          required />
                        </div>
                    @endforeach
                </div>

                <!-- Button Section -->
                <div class="flex justify-center mt-12">
                    <x-primary-button type="submit" class="w-full">
                        {{ $avatar ? 'Save' : 'Create Avatar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </body>
</x-app-layout>
