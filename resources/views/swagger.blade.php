<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Documentation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">API Documentation</h3>
                    <div class="mt-4">
                        <iframe src="{{ url('api/documentation') }}" style="width: 100%; height: 800px; border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>