<x-app-layout>
    <body class="bg-gray-100 pt-24 px-4">
        <div class="mt-16 mb-10 p-5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 w-full min-h-screen">

            <!-- Avatar Preview (Left Side) -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white p-6 rounded-lg shadow-xl h-full">
                    <h2 class="text-lg font-semibold mb-4">Avatar Preview</h2>
                    <div class="min-h-[400px] lg:min-h-[990px] bg-gray-200 rounded-md flex items-center justify-center text-gray-500 w-full">
    No avatar preview yet.
</div>

                </div>
            </div>

            <!-- Measurement Form (Right Side) -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white p-6 sm:p-8 rounded-lg shadow-2xl">
                    <h2 class="text-xl sm:text-2xl font-bold text-center mb-4 sm:mb-6">Enter your Measurements</h2>

                    <form method="{{ $avatar ? 'POST' : 'POST' }}" 
                          action="{{ $avatar ? route('avatar.update', $avatar->id) : route('avatar.store') }}">
                        @csrf
                        @if($avatar)
                            @method('PUT')
                        @endif

                        <!-- Name -->
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

                        <!-- Sex -->
                        <div class="mt-4">
                            <x-input-label for="sex" :value="__('Sex')" />
                            <select id="sex" name="sex" class="block mt-1 w-full p-2 border rounded-md shadow-inner">
                                <option value="Male" {{ isset($avatar) && $avatar->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ isset($avatar) && $avatar->sex == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Measurement Sliders -->
                        @php
                            $fields = ['bust_size', 'shoulder_width', 'waist_size', 'hip_size', 'height', 'weight', 'arm_length', 'leg_length', 'thigh_circumference'];
                        @endphp

                        <div class="grid grid-cols-1 gap-4">
                            @foreach($fields as $field)
                                @php
                                    $min = 30;
                                    $max = 200;
                                    $step = 1;
                                    $value = old($field, $avatar->$field ?? 100);
                                @endphp
                                <div class="mt-4" x-data="{ val: {{ $value }} }">
                                    <x-input-label for="{{ $field }}" :value="__(ucwords(str_replace('_', ' ', $field)))" />
                                    
                                    <div class="flex items-center gap-2 mt-1">
                                        <!-- Slider -->
                                        <input type="range"
                                               id="{{ $field }}"
                                               name="{{ $field }}"
                                               x-model="val"
                                               min="{{ $min }}"
                                               max="{{ $max }}"
                                               step="{{ $step }}"
                                               class="w-full">

                                        <!-- Number input + cm label -->
                                        <div class="flex items-center gap-1 w-[70px]">
                                            <input type="number"
                                                   x-model="val"
                                                   min="{{ $min }}"
                                                   max="{{ $max }}"
                                                   step="{{ $step }}"
                                                   class="w-[50px] text-sm border rounded px-1 py-0.5">
                                            <span class="text-sm text-gray-600">cm</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center mt-12">
                            <x-primary-button type="submit" class="w-full">
                                {{ $avatar ? 'Save' : 'Create Avatar' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </body>
</x-app-layout>
