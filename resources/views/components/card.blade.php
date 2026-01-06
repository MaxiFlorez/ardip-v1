@props(['title', 'description' => null, 'icon' => null, 'actions' => null])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-gray-300 transition duration-200">
    <div class="p-4 md:p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-grow">
                <div class="flex items-center gap-2">
                    @if($icon)
                        <span class="text-2xl">{{ $icon }}</span>
                    @endif
                    <h3 class="text-base md:text-lg font-bold text-gray-900">
                        {{ $title }}
                    </h3>
                </div>
                
                @if($description)
                    <p class="mt-2 text-sm md:text-base text-gray-600">
                        {{ $description }}
                    </p>
                @endif
                
                @if(isset($content))
                    <div class="mt-3 text-sm md:text-base text-gray-700">
                        {{ $content }}
                    </div>
                @endif
            </div>
        </div>
        
        @if($actions)
            <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col md:flex-row gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
