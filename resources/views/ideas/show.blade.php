<x-layout>
    @php
        $canEditIdea = $idea->user_id === auth()->id() || $idea->isCollaborator(auth()->user());
    @endphp

    <div class="mx-auto w-full max-w-6xl py-8" x-data="ideaAccessComponent({{ $idea->id }}, {{ auth()->id() ?? 'null' }}, {{ $canEditIdea ? 'true' : 'false' }})">

        <x-layout.toast
            type="success"
            :closable="true"
            dismiss-action="dismissApprovalMessage"
            x-cloak
            x-show="approvalMessage"
            x-transition.opacity.duration.500ms
        >
            <p x-text="approvalMessage"></p>
        </x-layout.toast>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <a href= "{{ route('idea.index') }}"
                class="btn btn-outlined flex w-full items-center justify-center gap-x-2 text-muted-foreground hover:text-foreground sm:w-auto">
                <x-icons.arrow-back />
                Back to Ideas
            </a>

            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:flex-wrap sm:items-center">
                <button
                    x-data
                    x-show="canEdit"
                    @click="$dispatch('open-modal', {name: 'edit-idea'})"
                    class="btn btn-outlined flex w-full items-center justify-center gap-x-2 text-muted-foreground hover:text-foreground sm:w-auto"
                    data-test="edit-idea-button"
                    >
                    <x-icons.external />
                    Edit Idea
                </button>

                <form method="POST" action="{{ route('ideas.collaboration.request', $idea) }}" x-show="!canEdit" class="w-full sm:w-auto">
                    @csrf
                    <button
                        class="btn btn-outlined flex w-full items-center justify-center gap-x-2 text-muted-foreground hover:text-foreground sm:w-auto"
                        data-test="edit-idea-button"
                        >
                        <x-icons.external />
                        Request Edit Access
                    </button>
                </form>

                @if ($idea->user_id === auth()->id())
                <form method="POST" action="{{ route('idea.destroy', $idea) }}" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-outlined flex w-full items-center justify-center gap-x-2 border-red-500/60 text-red-500/60 hover:border-red-500 hover:text-red-500 sm:w-auto"
                        data-test="delete-idea-button">
                        <x-icons.delete-bin />
                        Delete Idea
                    </button>
                </form>
                @endif


            </div>

        </div>



        <div class="mt-8 space-y-6">
            
            @if ($idea->image_path)

            <div class="w-full h-64 sm:h-96 overflow-hidden rounded-lg">
                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                 class="w-full h-auto rounded-lg object-cover">
            </div>
            @endif
            <h1 class="text-3xl font-bold sm:text-4xl"> {{ $idea->title }} </h1>

            <p class="mt-2 text-xs">
            <span class="inline-flex items-center rounded-full bg-secondary/15 px-2 py-1 text-secondary">
            Created by {{ $idea->user->name }}
            </span>
           </p>            

            <div class= "mt-2 flex gap-x-3  items-center">
                <x-idea.statuscard status="{{ $idea->status }}">
                    {{ $idea->status->label() }}
                </x-idea.statuscard>
                <div class= " text-muted-foreground text-sm"> Created: {{ $idea->created_at->diffForHumans() }}

                </div>
            </div>

            @if ($idea->user_id === auth()->id())
                <x-idea.collaborator-section :idea="$idea" :pendingCollaborators="$pendingCollaborators" />
            @endif
            
        
            <x-Ideacard>

                <div class="cursor-pointer text-foreground"> {{ $idea->description }} </div>

            </x-Ideacard>

            <h2 class="text-xl font-bold mt-6 mb-2"> Whiteboard </h2>

            <div 
            
                data-whiteboard
                 data-idea-id="{{ $idea->id }}"
                 data-can-edit="{{ $canEditIdea ? 'true' : 'false' }}"
                 data-initial-drawing='@json($idea->whiteboard?->drawing_data)'
                 data-save-url="{{ route('ideas.whiteboard.update', $idea) }}"
             >


            </div>

            <x-idea.steps-section :idea="$idea" />

            <x-idea.links-section :idea="$idea" />

            <x-idea.comments-section :idea="$idea" />

        </div>
      <!-- Modal for editing an idea -->
      <x-idea.modal :idea="$idea" />
      <!-- Modal for creating a comment -->
      <x-comments.modal :idea="$idea" />


    </div>
</x-layout>
