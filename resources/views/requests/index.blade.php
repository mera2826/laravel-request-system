<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Service Requests
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <h3 class="text-lg font-semibold">Request List</h3>

                    <a href="{{ route('requests.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Create Request
                    </a>
                </div>

                <form method="GET" action="{{ route('requests.index') }}" class="mb-6">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search item or purpose..."
                            class="border-gray-300 rounded-md shadow-sm w-full"
                        >

                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded">
                            Search
                        </button>
                    </div>
                </form>

                @if ($requests->isEmpty())
                    <p class="text-gray-600">No service requests found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Item</th>
                                    <th class="px-4 py-3 text-left">Quantity</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach ($requests as $serviceRequest)
                                    <tr>
                                        <td class="px-4 py-3">
                                            {{ $serviceRequest->id }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $serviceRequest->item_name }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $serviceRequest->quantity }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ ucfirst($serviceRequest->status) }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <a href="{{ route('requests.show', $serviceRequest) }}"
                                               class="text-blue-600 hover:underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>