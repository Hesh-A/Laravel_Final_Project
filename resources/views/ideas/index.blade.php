
<x-layout>


    <div class="w-full">

        <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold sm:text-4xl"> Your Ideas </h1>

        <p class="text-sm text-muted-foreground mt-2">
             Make a plan! Capture your ideas and thoughts in one place.
         </p>
         
        <x-Ideacard 
            x-data
            @click="$dispatch('open-modal', {name: 'create-idea'})"
            is="button"
            type="button"
            data-test="create-idea-button"
            class="mt-10 cursor-pointer h-32 text-left w-full">
             <p> Hey whats up!!! </p>
        </x-Ideacard>

        </header>

        <div class="mb-6 flex flex-wrap gap-2">
             <a href="/ideas" 
             class="btn {{ request('status') === null ? '' : 'btn-outlined' }}"> All             
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
    <div class="grid gap-6 lg:grid-cols-2">

        @forelse ($ideas as $idea)
            <x-Ideacard href="{{route('idea.show', $idea)}}">
                <h3 class="mb-2 text-foreground text-lg"> {{  $idea->title }} </h3>

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
    </div>
    </div>

   <!-- Modal for creating a new idea -->
    <x-modal modalName="create-idea" label="Create New Idea" title="Create A New Idea">

        <form x-data="{status: 'pending'}" action="{{ route('idea.store') }}" method="POST">
            @csrf
           
            <div class="space-y-6 mt-6">
            <x-forms.field name="title"
             placeholder="Enter a title for your idea"
             label="Title"
             type="text"
             required

            />


            <div class="space-y-2">
              <label for="status" class="label"> Status </label>
              <div class="flex items-center gap-x-3">
                 @foreach (App\IdeaStatus::cases() as $status)
                     <button 
                     class="btn btn-outlined hover:bg-gray-700/60 flex-1 items-center"
                     type="button"
                     data-test="status-button-{{ $status->value }}"
                     @click="status = @js($status->value)"
                     :class="status === @js($status->value) ? 'bg-green-700/60' : ''"
                     >
                           
                                {{ $status->label() }}
                            
                     </button>
        
                 @endforeach

                <input type="hidden" name="status" :value="status" />
              </div>
              <x-forms.error name="status" />
            </div>

            <x-forms.field name="description"
             placeholder="Enter a description for your idea.."
             label="Description" type="textarea" />
            

            <div class="flex items-center justify-end gap-x-3">
              <button type="button" class="btn btn-outlined hover:bg-gray-700/60"
               @click="$dispatch('close-modal')">
                Cancel 
              </button>                
              <button type="submit"
               class="btn hover:bg-primary/80" data-test="store-idea-button">
               Create Idea 
              </button>
            </div>


            </div>

        </form>

    </x-modal>

</x-layout>