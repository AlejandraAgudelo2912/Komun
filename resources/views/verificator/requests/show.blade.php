<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Details') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Title') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Category') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Priority') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @switch($requestModel->priority)
                                    @case('low') {{ __('Low') }} @break
                                    @case('medium') {{ __('Medium') }} @break
                                    @case('high') {{ __('High') }} @break
                                @endswitch
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Deadline') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->deadline->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @switch($requestModel->status)
                                    @case('pending') {{ __('Pending') }} @break
                                    @case('in_progress') {{ __('In Progress') }} @break
                                    @case('completed') {{ __('Completed') }} @break
                                    @case('cancelled') {{ __('Cancelled') }} @break
                                @endswitch
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->description }}</dd>
                        </div>
                        @if($requestModel->verification_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Verification Notes') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->verification_notes }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6 flex items-center gap-4">
                        <a href="{{ route('verificator.requests.edit', $requestModel) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('verificator.requests.destroy', $requestModel) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <a href="{{ route('verificator.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
