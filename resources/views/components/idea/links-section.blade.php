@props(['idea'])

@if ($idea->links)
    <h2 class="text-xl font-bold mt-6 mb-2"> Links </h2>
    <div class="space-y-3">
        @forelse ($idea->links as $link)
            <x-Ideacard :href="$link"
                class="cursor-pointer break-all text-primary/80 hover:text-primary flex items-center gap-x-3 font-medium">
                <x-icons.external class="text-muted-foreground" />
                {{ $link }}
            </x-Ideacard>
        @empty
            <p class="text-sm text-muted-foreground">No links available.</p>
        @endforelse
    </div>
@endif
