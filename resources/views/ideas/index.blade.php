<x-layout>


    <div class="w-full">

        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold sm:text-4xl"> Your Ideas </h1>

            <p class="text-sm text-muted-foreground mt-2">
                Make a plan! Capture your ideas and thoughts in one place.
            </p>

            <x-Ideacard x-data @click="$dispatch('open-modal', {name: 'create-idea'})" is="button" type="button"
                data-test="create-idea-button" class="mt-10 cursor-pointer flex items-center justify-center text-gray-200/50 h-32 text-left w-full">
                <p> Click here to create a new idea! </p>
            </x-Ideacard>

        </header>

        <div class="mb-6 flex flex-wrap gap-2">
            <a href="/ideas" class="btn {{ request('status') === null ? '' : 'btn-outlined' }}"> All
                <span class="ml-1 text-gray-600"> ({{ $counts['all'] }}) </span>
            </a>

            @foreach (App\IdeaStatus::cases() as $status)
                <a href="/ideas?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }}

                    <span class="pl-1 text-xs text-gray-600"> ({{ $counts[$status->value] }}) </span>
                </a>
            @endforeach

        </div>

        <div class= "mt-6 text-muted-foreground">
            <div class="grid  md:grid-cols-2 gap-6">

                @forelse ($ideas as $idea)
                    <x-Ideacard href="{{ route('idea.show', $idea) }}">
                        @if ($idea->image_path)

                        <div class="mb-4 -mx-4 -mt-4 h-48 overflow-hidden rounded-t-lg">
                            <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                                class="h-full w-full object-cover">
                        </div>
                        @endif                        
                        <h3 class="text-foreground text-lg"> {{ $idea->title }} </h3>

                        <x-idea.statuscard status="{{ $idea->status }}">
                            {{ $idea->status->label() }}
                        </x-idea.statuscard>
                        <p class="text-sm mt-2"> {{ $idea->description }} </p>
                        <div class= "text-xs mt-2"> {{ $idea->created_at->diffForHumans() }} </div>
                    </x-Ideacard>
                @empty

                    <x-Ideacard>
                        <p class="text-sm"> You have no ideas yet. </p>
                    </x-Ideacard>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal for creating a new idea -->
    <x-idea.modal >
        

    </x-idea.modal>

</x-layout>
