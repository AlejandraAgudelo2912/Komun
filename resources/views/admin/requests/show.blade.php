<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Request') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h1 class="text-2xl font-bold mb-4">{{ $request->title }}</h1>
                    <p class="text-gray-600 mb-4">Description: {{ $request->description }}</p>
                    <p class="text-gray-600 mb-4">Created At: {{ $request->created_at }}</p>
                    <p class="text-gray-600 mb-4">Updated At: {{ $request->updated_at }}</p>
                    <p class="text-gray-600 mb-4">Status:
                        @if($request->status == 'pending')
                            <span class="text-yellow-500">Pending</span>
                        @elseif($request->status == 'approved')
                            <span class="text-green-500">Approved</span>
                        @elseif($request->status == 'rejected')
                            <span class="text-red-500">Rejected</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
