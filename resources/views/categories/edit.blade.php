<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Category') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :default="$category" method="PUT" :action="route('categories.update', $category->id)" class="md:max-w-lg mx-auto p-4 bg-white rounded-md ">

                <x-splade-input name="name" label="Category name" />

                <x-splade-input name="slug" label="Slug" />

                <x-splade-submit class="mt-3" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
