@props([
    'headers' => [],
])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    @foreach($headers as $index => $header)
                        @php
                            // Check if this is likely an actions column (last column with "Acciones" text)
                            $isActionsColumn = is_string($header) && 
                                              (str_contains(strtolower($header), 'acciones') || str_contains(strtolower($header), 'actions'));
                            $alignment = $isActionsColumn ? 'text-right' : 'text-left';
                        @endphp
                        <th scope="col" class="px-6 py-3 {{ $alignment }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
