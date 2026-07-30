@props([
    'modalName',
    'title',
])
   
<div
     x-data="{ show: false, name: @js($modalName) }"
     x-show="show"
     @open-modal.window="if($event.detail.name === name) show = true"
     @close-modal.window="show = false"
     @keydown.escape.window="show = false"

     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-4 -translate-x-4"
     x-transition:enter-end="opacity-100"

     x-transition:leave="ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 -translate-y-4 -translate-x-4"
     style="display: none;"

     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-{{ $modalName }}-title"
     :aria-hidden="!show"

     tabindex="-1"

    >

    <x-Ideacard @click.away="show = false"  class="shadow-xl max-w-2xl w-full max-h-[80dvh] overflow-auto">
        <div class="flex items-center justify-between mb-6">
        <h2 id="modal-{{ $modalName }}-title" class="text-lg font-semibold text-foreground"> {{ $title }} </h2>

        <button aria-label="Close-modal">

            <x-icons.close class="h-4 w-4 text-muted-foreground hover:text-foreground" @click="show = false" />
        
        </button>
        </div>

        <div>
            {{ $slot }}
        </div>
          

     </x-Ideacard>
    
</div>