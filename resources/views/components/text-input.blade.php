@props(['disabled' => false])

@php
	$mergeAttrs = ['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'];
	// Si no viene "id" pero sí "name", usar name como id por accesibilidad
	if (! $attributes->has('id') && $attributes->has('name')) {
		$mergeAttrs['id'] = $attributes->get('name');
	}
@endphp

<input @disabled($disabled) {{ $attributes->merge($mergeAttrs) }}>
