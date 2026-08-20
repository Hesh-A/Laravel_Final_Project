<x-layout>


    <div class="mx-auto w-full max-w-6xl py-8">

        <header class="py-8 md:py-12">
           <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary">
               Make space for better ideas
           </p>
            <h1 class="mt-4 text-3xl font-bold sm:text-5xl"> Your Ideas </h1>


            <x-Ideacard x-data @click="$dispatch('open-modal', {name: 'create-idea'})" is="button" type="button"
                data-test="create-idea-button" 
                class="mt-10 cursor-pointer flex items-center 
                justify-center text-gray-200/50 h-32 text-left w-full
                shadow-2xl shadow-black/20 transition duration-300 hover:-translate-y-2 
                hover:border-primary/40 hover:shadow-xl 
                hover:shadow-primary/10
         
                ">
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
                    <x-Ideacard href="{{ route('idea.show', $idea) }}" class="cursor-pointer transition duration-300 hover:-translate-y-2 hover:border-primary/40 hover:shadow-xl hover:shadow-primary/10">
                        <div class="-mx-4 -mt-4 mb-4 flex h-48 items-center justify-center overflow-hidden rounded-t-lg bg-background/60">
                            @if ($idea->image_path)
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                                    class="h-full w-full object-cover">
                            @else
                                <span class="text-xs uppercase tracking-[0.18em] text-muted-foreground/60">No image</span>
                            @endif
                        </div>
                        <div class="grid gap-5 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-start">
                            <div>
                                <h3 class="text-lg text-foreground"> {{ $idea->title }} </h3>
                                <p class="mt-2 text-sm"> {{ $idea->description }} </p>
                                <span class="mt-2 block text-xs text-muted-foreground">
                                    Created By {{ $idea->user->name }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 sm:flex-col sm:items-end">

                                <x-idea.statuscard status="{{ $idea->status }}">
                                    {{ $idea->status->label() }}
                                </x-idea.statuscard>

                                <span class="text-xs text-muted-foreground">
                                    {{ $idea->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
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

    <x-idea.modal>
    </x-idea.modal>

</x-layout>
