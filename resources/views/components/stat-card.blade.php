@props(['title' => '', 'subtitle' => '', 'value' => '', 'color' => 'gray', 'icon' => '📊'])

@php
    $colorClasses = [
        'gray' => 'bg-white border-gray-200 text-gray-900',
        'green' => 'bg-white border-green-500 text-green-600 border-l-4',
        'blue' => 'bg-white border-blue-500 text-blue-600 border-l-4',
        'red' => 'bg-white border-red-500 text-red-600 border-l-4',
        'indigo' => 'bg-white border-indigo-500 text-indigo-600 border-l-4',
    ];
    
    $bgColor = match($color) {
        'green' => 'bg-green-50',
        'blue' => 'bg-blue-50',
        'red' => 'bg-red-50',
        'indigo' => 'bg-indigo-50',
        default => 'bg-gray-50'
    };
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-gray-300 transition duration-200 overflow-hidden">
    <div class="p-4 md:p-6">
        <div class="flex items-start justify-between">
            <div class="flex-grow">
                <p class="text-sm md:text-base font-medium text-gray-600 dark:text-gray-400">
                    {{ $title }}
                </p>
                <p class="mt-2 text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $value }}
                </p>
                @if($subtitle)
                    <p class="mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            <div class="text-3xl md:text-4xl">
                {{ $icon }}
            </div>
        </div>
    </div>
</div>
