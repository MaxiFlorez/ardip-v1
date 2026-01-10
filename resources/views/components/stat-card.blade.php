@props(['title' => '', 'subtitle' => '', 'value' => ''])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600 transition duration-200 overflow-hidden">
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
            <div class="ml-4">
                {{ $icon ?? '' }}
            </div>
        </div>
    </div>
</div>
