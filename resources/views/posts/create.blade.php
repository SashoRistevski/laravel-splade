<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create new Post') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :action="route('posts.store')" class="md:max-w-lg mx-auto p-4 bg-white rounded-md">

                <x-splade-select name="category_id" label="Category" :options="$categories"/>

                <x-splade-input name="title" label="Post title"/>

                <x-splade-input name="slug" label="Slug"/>

                <x-splade-textarea name="description" label="Description" autosize/>

                <x-splade-submit class="mt-3"/>
            </x-splade-form>
        </div>
    </div>
</x-app-layout>

