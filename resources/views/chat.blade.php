@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4 text-center">🎯 Get Your Outfit Recommendation</h2>

            <form method="POST" action="{{ route('chat.suggestOutfitForm') }}">
                @csrf

                <!-- Event Select -->
                <label class="block mb-2">Event:</label>
                <select name="event" id="eventSelect" class="w-full mb-4 p-2 border rounded" required onchange="toggleOtherEventInput()">
                    <option value="">Select...</option>
                    <option value="wedding">Wedding</option>
                    <option value="birthday">Birthday</option>
                    <option value="office">Office</option>
                    <option value="beach">Beach</option>
                    <option value="gala">Formal Gala</option>
                    <option value="other">Other (please specify)</option>
                </select>

                <input type="text" name="event_other" autocomplete="off" id="eventOtherInput" placeholder="Describe the event..." 
                    class="w-full mb-4 p-2 border rounded hidden" />

                <!-- Weather Select -->
                <label class="block mb-2">Weather:</label>
                <select name="weather" class="w-full mb-4 p-2 border rounded" required>
                    <option value="">Select...</option>
                    <option value="sunny">Sunny</option>
                    <option value="rainy">Rainy</option>
                    <option value="cloudy">Cloudy</option>
                    <option value="cold">Cold</option>
                    <option value="hot">Hot</option>
                    <option value="windy">Windy</option>
                </select>

                <!-- Style Select -->
                <label class="block mb-2">Style Preference:</label>
                <select name="style" class="w-full mb-4 p-2 border rounded" required>
                    <option value="">Select...</option>
                    <option value="casual">Casual</option>
                    <option value="formal">Formal</option>
                    <option value="streetwear">Streetwear</option>
                    <option value="minimalist">Minimalist</option>
                </select>

                <!-- Extra Notes Input -->
                <label class="block mb-2">Extra Notes (optional):</label>
                <textarea name="notes" id="notes" rows="3" autocomplete="off" placeholder="Describe anything extra you want (e.g., sleeveless, dark color, specific vibe)..."
                    class="w-full mb-4 p-2 border rounded"></textarea>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Show My Outfit!
                </button>
            </form>




            @if (session('suggestion'))
                <div class="mt-6 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center justify-center">
                        🎉 Your Outfit Suggestion
                    </h3>

                    <div class="prose prose-sm sm:prose-base max-w-none text-gray-800">
                        {!! Str::markdown(session('suggestion.message')) !!}
                    </div>
                </div>
            @endif


        </div>
    </div>

    <script>
    function toggleOtherEventInput() {
        const eventSelect = document.getElementById('eventSelect');
        const eventOtherInput = document.getElementById('eventOtherInput');
        if (eventSelect.value === 'other') {
            eventOtherInput.classList.remove('hidden');
            eventOtherInput.required = true;
        } else {
            eventOtherInput.classList.add('hidden');
            eventOtherInput.required = false;
        }
    }
    </script>
</x-app-layout>
