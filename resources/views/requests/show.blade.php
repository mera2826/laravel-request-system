<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Request Details #{{ $serviceRequest->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <dl class="space-y-4">
                    <div>
                        <dt class="font-semibold text-gray-700">Request ID</dt>
                        <dd>{{ $serviceRequest->id }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Requester</dt>
                        <dd>{{ $serviceRequest->requester_name }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Requester Email</dt>
                        <dd>{{ $serviceRequest->requester_email }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Item</dt>
                        <dd>{{ $serviceRequest->item_name }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Quantity</dt>
                        <dd>{{ $serviceRequest->quantity }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Purpose</dt>
                        <dd class="whitespace-pre-line">{{ $serviceRequest->purpose }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-gray-700">Status</dt>
                        <dd>{{ ucfirst($serviceRequest->status) }}</dd>
                    </div>
                </dl>

                @can('updateStatus', $serviceRequest)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold mb-3">Update Request Status</h3>

                        @if ($errors->any())
                            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                                <ul class="list-disc ml-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('requests.updateStatus', $serviceRequest) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <label for="status" class="block font-medium text-sm text-gray-700">
                                New Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option value="pending" @selected($serviceRequest->status === 'pending')>
                                    Pending
                                </option>
                                <option value="approved" @selected($serviceRequest->status === 'approved')>
                                    Approved
                                </option>
                                <option value="rejected" @selected($serviceRequest->status === 'rejected')>
                                    Rejected
                                </option>
                            </select>

                            <button
                                type="submit"
                                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                Update Status
                            </button>
                        </form>
                    </div>
                @endcan

                <div class="mt-6">
                    <a href="{{ route('requests.index') }}"
                       class="text-blue-600 hover:underline">
                        Back to Requests
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>