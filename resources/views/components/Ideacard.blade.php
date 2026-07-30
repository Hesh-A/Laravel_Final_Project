@props ([
     'is' => 'a',
])
<{{ $is }} {{ $attributes->merge(['class' => 'border border-border p-4 rounded-lg bg-card md:text-sm block']) }}>
   {{ $slot }}
</{{ $is }}>