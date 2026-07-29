<x-layout>

    <div>


        <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold"> Your Ideas </h1>

        <p class="text-sm text-muted-foreground mt-2"> Make a plan! Capture your ideas and thoughts in one place. </p>
        </header>
    </div>

    <div class="grid md:grid-cols-2 gap-6 text-muted-foreground">

        @forelse ($ideas as $idea)
            <x-Ideacard href="{{route('idea.show', $idea)}}">
                <h3 class="text-foreground text-lg"> {{  $idea->title }} </h3>

                <x-idea.statuscard status="{{ $idea->status }}">
                    {{ $idea->status->label() }}
                </x-idea.statuscard>
                <p class="text-sm mt-2"> {{  $idea->description }} </p>
                <div class= "text-xs mt-2"> {{ $idea->created_at->diffForHumans() }} </div>
            </x-Ideacard>
        @empty

            <x-Ideacard>
                <p class="text-sm"> You have no ideas yet. </p>
            </x-Ideacard>

        @endforelse
    </div>

</x-layout>