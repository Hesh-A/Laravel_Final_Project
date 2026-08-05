@props(['idea'])

@if ($idea->steps->count())
    <h2 class="text-xl font-bold mt-6 mb-2"> Actionable Steps </h2>
    <div class="space-y-3">
        @foreach ($idea->steps as $step)
            <x-Ideacard>
                <form action="{{ route('step.update', $step) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center gap-x-3">
                        <button type="submit" role="checkbox"
                            aria-checked="{{ $step->is_completed ? 'true' : 'false' }}"
                            class="size-4 flex items-center justify-center rounded-lg text-primary-foreground border border-primary hover:bg-primary/30 {{ $step->is_completed ? 'bg-primary' : '' }}">
                            &check;
                        </button>
                        <span class="{{ $step->is_completed ? 'line-through text-muted-foreground' : '' }}">
                            {{ $step->description }}
                        </span>
                    </div>
                </form>
            </x-Ideacard>
        @endforeach
    </div>
@endif
