<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo_fitpeek.svg') }}" alt="FitPeek Logo" class="h-20 w-auto">
            </div>
            <h1 class="text-2xl font-bold text-center mb-4">Verify Your Email</h1>
            <p class="text-gray-600 text-center mb-4 text-justify">
                Thank you for registering! Before you can begin, please verify your email address by clicking the link we just sent you. If you didn't receive the email, we're happy to send you another one.
            </p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="flex justify-center">
                    <x-primary-button type="submit" class="rounded-3xl text-white bg-[#F886B5] hover:bg-[#e075a3] transition duration-300 px-6 py-2 mt-8">
                        Resend Verification Email
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
