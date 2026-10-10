<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Service Request
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <p class="font-semibold">Please correct the following errors:</p>
                        <ul class="list-disc ml-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('requests.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="item_name" class="block font-medium text-sm text-gray-700">
                            Item Name
                        </label>
                        <input
                            id="item_name"
                            name="item_name"
                            type="text"
                            value="{{ old('item_name') }}"
                            maxlength="150"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block font-medium text-sm text-gray-700">
                            Quantity
                        </label>
                        <input
                            id="quantity"
                            name="quantity"
                            type="number"
                            min="1"
                            value="{{ old('quantity', 1) }}"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="purpose" class="block font-medium text-sm text-gray-700">
                            Purpose</label>
                        <textarea
                            id="purpose"
                            name="purpose"
                            rows="4"
                            maxlength="2000"
                            required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >{{ old('purpose') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Submit Request
                        </button>

                        <a href="{{ route('requests.index') }}"
                           class="text-gray-600 hover:underline">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>