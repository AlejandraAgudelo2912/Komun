<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($verifications->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        {{ __('No pending verifications') }}
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($verifications as $verification)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $verification->assistant->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $verification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <a href="{{ asset('storage/' . $verification->dni_front_path) }}" 
                                       target="_blank"
                                       class="block aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $verification->dni_front_path) }}" 
                                             alt="{{ __('Front ID') }}"
                                             class="w-full h-full object-cover">
                                    </a>
                                    <a href="{{ asset('storage/' . $verification->dni_back_path) }}" 
                                       target="_blank"
                                       class="block aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $verification->dni_back_path) }}" 
                                             alt="{{ __('Back ID') }}"
                                             class="w-full h-full object-cover">
                                    </a>
                                    <a href="{{ asset('storage/' . $verification->selfie_path) }}" 
                                       target="_blank"
                                       class="block aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $verification->selfie_path) }}" 
                                             alt="{{ __('Selfie') }}"
                                             class="w-full h-full object-cover">
                                    </a>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <form method="POST" 
                                          action="{{ route('verificator.verifications.approve', $verification->id) }}"
                                          class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                                            {{ __('Approve') }}
                                        </button>
                                    </form>

                                    <form method="POST" 
                                          action="{{ route('verificator.verifications.reject', $verification->id) }}"
                                          class="flex-1">
                                        @csrf
                                        <div class="space-y-2">
                                            <input type="text" 
                                                   name="reason" 
                                                   placeholder="{{ __('Rejection reason') }}" 
                                                   required
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            <button type="submit" 
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                                                {{ __('Reject') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
