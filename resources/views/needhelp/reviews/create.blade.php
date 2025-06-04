<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calificar al Asistente') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ $requestModel->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $requestModel->description }}</p>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Fecha de Completado</p>
                                <p class="font-medium">{{ $requestModel->completed_at?->format('d/m/Y') ?? 'No especificada' }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-lg font-semibold mb-4">Asistentes a Calificar</h4>
                            @foreach($acceptedApplicants as $applicant)
                                <div class="border rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <h5 class="font-medium">{{ $applicant->name }}</h5>
                                            <p class="text-sm text-gray-600">{{ $applicant->pivot->message }}</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('needhelp.reviews.store', $requestModel) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="assistant_id" value="{{ $applicant->id }}">

                                        <div>
                                            <label for="rating_{{ $applicant->id }}" class="block text-sm font-medium text-gray-700">Calificación</label>
                                            <div class="mt-2 flex items-center space-x-4">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="relative">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                                        <svg class="w-8 h-8 cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="comment_{{ $applicant->id }}" class="block text-sm font-medium text-gray-700">Comentario (opcional)</label>
                                            <textarea name="comment" id="comment_{{ $applicant->id }}" rows="3" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Cuéntanos tu experiencia con este asistente..."></textarea>
                                            @error('comment')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Enviar Calificación
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('needhelp.requests.show', $requestModel) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Volver a la Solicitud
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const ratingStars = document.querySelectorAll('input[name="rating"] + svg');

            ratingInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    // Reset all stars
                    ratingStars.forEach(star => star.classList.remove('text-yellow-400'));
                    
                    // Color stars up to selected rating
                    for(let i = 0; i <= index; i++) {
                        ratingStars[i].classList.add('text-yellow-400');
                    }
                });

                // Hover effect
                ratingStars[index].addEventListener('mouseenter', function() {
                    for(let i = 0; i <= index; i++) {
                        ratingStars[i].classList.add('text-yellow-400');
                    }
                });

                ratingStars[index].addEventListener('mouseleave', function() {
                    const selectedRating = document.querySelector('input[name="rating"]:checked');
                    if (selectedRating) {
                        const selectedIndex = Array.from(ratingInputs).indexOf(selectedRating);
                        ratingStars.forEach((star, i) => {
                            star.classList.toggle('text-yellow-400', i <= selectedIndex);
                        });
                    } else {
                        ratingStars.forEach(star => star.classList.remove('text-yellow-400'));
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 