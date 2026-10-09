<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    You're logged in!
                </div>
            </div>

            <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">
                    Service Request System
                </h3>

                <p class="text-gray-600 mb-4">
                    Create and manage your service requests here.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('requests.index') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        View Service Requests
                    </a>

                    <a href="{{ route('requests.create') }}"
                       class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Create New Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>