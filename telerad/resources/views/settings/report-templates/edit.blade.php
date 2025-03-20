@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Report Template</h1>
            <a href="{{ route('report-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition">
                Back to Templates
            </a>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('report-templates.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Template Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}" required
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="modality" class="block text-sm font-medium text-gray-700 mb-1">Modality (optional)</label>
                        <select id="modality" name="modality" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Modalities</option>
                            @foreach($modalityMap as $code => $name)
                                <option value="{{ $code }}" {{ old('modality', $template->modality) === $code ? 'selected' : '' }}>{{ $code }}: {{ $name }}</option>
                            @endforeach
                        </select>
                        @error('modality')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <select id="section" name="section" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="findings" {{ old('section', $template->section) === 'findings' ? 'selected' : '' }}>Findings</option>
                            <option value="impression" {{ old('section', $template->section) === 'impression' ? 'selected' : '' }}>Impression</option>
                            <option value="recommendations" {{ old('section', $template->section) === 'recommendations' ? 'selected' : '' }}>Recommendations</option>
                        </select>
                        @error('section')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Template Content</label>
                        <textarea id="content" name="content" rows="12" required
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('content', $template->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input id="is_default" name="is_default" type="checkbox" value="1" {{ old('is_default', $template->is_default) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_default" class="ml-2 block text-sm text-gray-700">
                                Set as default template for this modality and section
                            </label>
                        </div>
                        @error('is_default')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring ring-indigo-300 disabled:opacity-25 transition">
                            Update Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 