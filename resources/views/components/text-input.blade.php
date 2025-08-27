@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300    focus:border-brand-500  focus:ring-brand-500  rounded-md shadow-sm']) }}>
