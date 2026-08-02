<x-layout>


    <div class="w-full">

        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold sm:text-4xl"> Your Ideas </h1>

            <p class="text-sm text-muted-foreground mt-2">
                Make a plan! Capture your ideas and thoughts in one place.
            </p>

            <x-Ideacard x-data @click="$dispatch('open-modal', {name: 'create-idea'})" is="button" type="button"
                data-test="create-idea-button" class="mt-10 cursor-pointer h-32 text-left w-full">
                <p> Hey whats up!!! </p>
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
    <x-modal modalName="create-idea" label="Create New Idea" title="Create A New Idea">

        <form 
        x-data="{
            status: 'pending',
            newLink: '',
            links: [],
            newStep: '',
            steps: [],
            hasImage: false
        
        }" 
           action="{{ route('idea.store') }}"
           method="POST"
           class="space-y-4"
           x-bind:enctype="hasImage ? 'multipart/form-data' : false"
        >
            @csrf

            <div class="space-y-6 mt-6">
                <x-forms.field name="title" placeholder="Enter a title for your idea" label="Title" type="text"
                    required />


                <div class="space-y-2">
                    <label for="status" class="label"> Status </label>
                    <div class="flex items-center gap-x-3">
                        @foreach (App\IdeaStatus::cases() as $status)
                            <button class="btn btn-outlined hover:bg-gray-700/60 flex-1 items-center" type="button"
                                data-test="status-button-{{ $status->value }}"
                                @click="status = @js($status->value)"
                                :class="status === @js($status->value) ? 'bg-primary/95 text-black' : ''">

                                {{ $status->label() }}

                            </button>
                        @endforeach

                        <input type="hidden" name="status" :value="status" />
                    </div>
                    <x-forms.error name="status" />
                </div>

                <x-forms.field name="description" placeholder="Enter a description for your idea.." label="Description"
                    type="textarea" />


                <div class="space-y-3">

                    <label for="image" class="label"> Image </label>

                    <input 
                     type= "file"
                     id="image"
                     name="image"
                     accept="image/*"  
                     @change="hasImage = $event.target.files.length > 0" 
                    > 
                    </input>
                    <x-forms.error name="image" />
                    


                </div>

                <!--Steps area -->

                <div>

                    <fieldset class="space-y-3">

                        <legend class="label"> Actionable Steps </legend>

                        <template x-for= "(step,index) in steps" :key="step">

                         <div class="flex gap-x-3">
                            <input name="steps[]" 
                             x-model="step"
                             class="input flex-1"
                             readonly
                            />
                            <button 
                                type="button"
                                class="btn btn-outlined text-sm text-red-500/60 hover:text-red-700 flex items-center gap-x-2"
                                @click="steps.splice(index, 1)"
                                aria-label="Delete the Step"
                                >
                                <x-icons.delete-bin />
                                Remove Step
                            </button>                            
                         </div>
                        
                        </template>


                        <div class="flex gap-x-3">
                            <input x-model="newStep"
                              type="text"
                              id="new-step"
                              data-test="new-step"
                              placeholder="What needs to be done??" autoComplete="off" class="input flex-1 text-sm" />
                            <button 
                                type="button"
                                class="btn btn-outlined hover:bg-gray-700/60 flex items-center gap-x-2"
                                @click="steps.push(newStep.trim()); newStep = '';"
                                :disabled="!newStep.trim()"
                                data-test="add-new-step-button"
                                aria-label="Add New Step"
                                >
                                <x-icons.close class="rotate-45" />
                                Add Step
                            </button>
                        </div>
                    </fieldset>

                </div>


                 <!--links input field and add button -->

                <div>

                    <fieldset class="space-y-3">

                        <template x-for= "(link,index) in links" :key="link">

                         <div class="flex gap-x-3">
                            <input name="links[]" 
                             x-model="link"
                             class="input flex-1"
                             readonly
                            />
                            <button 
                                type="button"
                                class="btn btn-outlined text-sm text-red-500/60 hover:text-red-700 flex items-center gap-x-2"
                                @click="links.splice(index, 1)"
                                aria-label="Delete the Link"
                                >
                                <x-icons.delete-bin />
                                Remove Link
                            </button>                            
                         </div>
                        
                        </template>

                        <legend class="label"> Links </legend>

                        <div class="flex gap-x-3">
                            <input x-model="newLink"
                             type="url"
                              id="new-url"
                              data-test="new-link"
                                placeholder="https://www.example.com" autoComplete="url" class="input flex-1" />
                            <button 
                                type="button"
                                class="btn btn-outlined hover:bg-gray-700/60 flex items-center gap-x-2"
                                @click="links.push(newLink.trim()); newLink = '';"
                                :disabled="!newLink.trim()"
                                data-test="add-new-link-button"
                                aria-label="Add New Link"
                                >
                                <x-icons.close class="rotate-45" />
                                Add Link
                            </button>
                        </div>
                    </fieldset>

                </div>




                <div class="flex items-center justify-end gap-x-3 mt-3">
                    <button type="button" class="btn btn-outlined hover:bg-gray-700/60"
                        @click="$dispatch('close-modal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn hover:bg-primary/80" data-test="store-idea-button">
                        Create Idea
                    </button>
                </div>




            </div>

        </form>

    </x-modal>

</x-layout>
