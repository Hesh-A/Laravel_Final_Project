@props([
    'type' => 'success',
    'closable' => false,
    'dismissAction' => null,
])

<div
    {{ $attributes->class([
        'fixed bottom-3 right-3 z-50 rounded-lg px-6 py-4 text-white shadow-lg',
        'bg-red-500' => $type === 'error',
        'bg-green-500' => $type !== 'error',
    ]) }}
    role="status"
    aria-live="polite"
>
    @if ($closable)
        <div class="flex items-start justify-between gap-3">
            <div>{{ $slot }}</div>
            <button
                type="button"
                @if ($dismissAction)
                    @click="{{ $dismissAction }}"
                @endif
                class="text-white/80 hover:text-white"
                aria-label="Dismiss toast"
            >
                x
            </button>
        </div>
    @else
        {{ $slot }}
    @endif
</div>