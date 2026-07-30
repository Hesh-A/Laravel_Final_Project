<x-layout>
   <div class="mx-auto w-full max-w-4xl py-8">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

         <a href= "{{ route('idea.index') }}"
            class="btn btn-outlined flex items-center gap-x-2 text-muted-foreground hover:text-foreground">
            <x-icons.arrow-back />
            Back to Ideas
         </a>

         <div class="flex flex-wrap items-center gap-3">
         <a href=" "
            class="btn btn-outlined flex items-center gap-x-2 text-muted-foreground hover:text-foreground">
            <x-icons.external />
            Edit Idea
            </a>
         
          <form method="POST" action="{{ route('idea.destroy', $idea) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outlined text-red-500/60 flex items-center gap-x-2 hover:text-red-500" data-test="delete-idea-button">
                <x-icons.delete-bin />
                Delete Idea</button>
          </form>
         </div>

      </div>

     <div class="mt-8 space-y-6">
      <h1 class="text-3xl font-bold sm:text-4xl"> {{ $idea->title }} </h1>

      <div class= "mt-2 flex gap-x-3  items-center">
         <x-idea.statuscard status="{{ $idea->status }}">
            {{ $idea->status->label() }}
         </x-idea.statuscard>
         <div class= " text-muted-foreground text-sm"> Created: {{ $idea->created_at->diffForHumans() }} 
            
         </div>
         

      </div>
      <x-Ideacard>

         <div class="cursor-pointer text-foreground"> {{ $idea->description }} </div>
    
      </x-Ideacard>
     
       @if ($idea->links)
        <h2 class="text-xl font-bold mt-6 mb-2"> Links </h2>
        <div class= "space-y-3">
            @foreach ($idea->links as $link)
                <x-Ideacard :href="$link" class="cursor-pointer break-all text-primary
                 hover:text-primary flex items-center gap-x-3
                 font-medium hover:underline">

                 <x-icons.external class="text-muted-foreground" />
                    {{ $link }}
            
                 </x-Ideacard>
            @endforeach
        </div>
       @endif

    </div>

   </div>
</x-layout>