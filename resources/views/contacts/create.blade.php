<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('contacts.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name">Contact Name</label>
                            <input type="text" name="name" id="name" placeholder="Contact Name">
                        </div>
                        <div>
                            <label for="phone">Contact Phone</label>
                            <input type="text" name="phone" id="phone" placeholder="Contact Phone">
                        </div>
                        <div>
                            <label for="category">Contact Category</label>
                            <select name="category" id="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
