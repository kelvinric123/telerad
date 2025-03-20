@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Report Templates</h1>
            <a href="{{ route('report-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring ring-indigo-300 disabled:opacity-25 transition">
                New Template
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(isset($activeModality) && $activeModality)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 mb-4 rounded relative" role="alert">
                <span class="block sm:inline font-semibold">Showing templates for modality: {{ $activeModality }}</span>
                <a href="{{ route('report-templates.index') }}" class="ml-2 underline">Clear filter</a>
            </div>
        @endif
        
        <div class="mb-6 flex flex-wrap gap-2">
            <span class="text-sm text-gray-700 my-auto">Quick filters:</span>
            @php
                $modalityMap = [
                    'CR' => 'Computed Radiography',
                    'CT' => 'Computed Tomography', 
                    'DX' => 'Digital Radiography',
                    'MG' => 'Mammography',
                    'MR' => 'Magnetic Resonance',
                    'NM' => 'Nuclear Medicine',
                    'US' => 'Ultrasound',
                    'XA' => 'X-Ray Angiography'
                ];
            @endphp
            @foreach($modalityMap as $code => $name)
                <a href="{{ route('report-templates.index', ['modality' => $code]) }}" 
                    class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium 
                    {{ isset($activeModality) && $activeModality === $code 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300' 
                    }}">
                    {{ $code }}
                </a>
            @endforeach
        </div>
        
        @forelse($groupedTemplates as $modality => $templates)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">
                        @if($modality)
                            {{ $modality }} Templates
                            @if(isset($modalityMap[$modality]))
                                <span class="text-sm text-gray-500 font-normal">{{ $modalityMap[$modality] }}</span>
                            @endif
                        @else
                            General Templates
                        @endif
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Section
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Default
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($templates as $template)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $template->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($template->section) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($template->is_default)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Default
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $template->user ? $template->user->name : 'System' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                            <a href="{{ route('report-templates.edit', $template->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            
                                            <form action="{{ route('report-templates.destroy', $template->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this template?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-500">No templates found. Create your first template using the "New Template" button above.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 