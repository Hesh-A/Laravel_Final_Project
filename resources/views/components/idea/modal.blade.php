@props(['idea' => new App\Models\Idea])


<x-modal modalName="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
      label="{{ $idea->exists ? 'Edit Idea' : 'Create New Idea' }}" 
      title="{{ $idea->exists ? 'Edit Idea' : 'Create A New Idea' }}">

        <form 
        x-data="{
            status: @js(old('status', $idea->status?->value)),
            newLink: '',
            links: @js(old('links', $idea->links ?? [])),
            newStep: '',
            steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'is_completed']) ?? [])),
            hasImage: false
        
        }" 
           action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
           method="POST"
           class="space-y-4"
           x-bind:enctype="hasImage ? 'multipart/form-data' : false"
        >
            @csrf

            @if ($idea->exists)
                @method('PATCH')
                
            @endif

            <div class="space-y-6 mt-6">
                <x-forms.field name="title" 
                placeholder="Enter a title for your idea" 
                label="Title" 
                type="text"
                :value="$idea->title"
                required 
             
                />


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

                <x-forms.field name="description" 
                placeholder="Enter a description for your idea.." 
                label="Description"
                type="textarea"
                :value="$idea->description"
                />


                <div class="space-y-3">

                    <label for="image" class="label"> Image </label>

                    @if ($idea->image_path)
                        <div class="mb-4 mt-4 h-48 overflow-hidden rounded-t-lg">
                            <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                                class="h-48 w-full object-cover">
                        </div>

                        <div>

                           <button 
                            class="btn btn-outlined w-full hover:bg-gray-700/60 hover:text-red-700 flex items-center justify-center gap-x-2"
                            form="delete-image-form"
                            data-test="delete-image-button"
                            >
                                <x-icons.delete-bin class="h-4 w-4" />
                                Remove Image
                            </button>
                        </div>
                    @endif

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

                        <template x-for= "(step,index) in steps" :key="step.id || index">

                         <div class="flex gap-x-3">
                            <input :name="`steps[${index}][description]`" 
                             x-model="step.description"
                             class="input flex-1"
                          
                            />

                            <input type="hidden"
                             :name="`steps[${index}][is_completed]`" 
                             x-model="step.is_completed ? '1' : '0'"
                             class="input flex-1"
                             readonly
                            />                            
                            <button 
                                type="button"
                                class="btn btn-outlined text-sm hover:bg-gray-700/60 hover:text-red-700 flex items-center gap-x-2"
                                @click="steps.splice(index, 1)"
                                aria-label="Delete the Step"
                                data-test="delete-step-button"
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
                                @click="steps.push({description: newStep.trim(), is_completed: false}); newStep = '';"
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
                                class="btn btn-outlined text-sm hover:text-red-700 hover:bg-gray-700/60 flex items-center gap-x-2"
                                @click="links.splice(index, 1)"
                                aria-label="Delete the Link"
                                data-test="delete-link-button"
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
                    <button
                        type="submit"
                        class="btn hover:bg-primary/80"
                        data-test="{{ $idea->exists ? 'update-idea-button' : 'store-idea-button' }}"
                    >
                        {{ $idea->exists ? 'Update Idea' : 'Create Idea' }}
                    </button>
                </div>




            </div>

        </form>
       @if($idea->image_path)
        <form id="delete-image-form" method="POST" action="{{ route('idea.delete.image', $idea) }}">

            @csrf
            @method('DELETE')
        </form>
         @endif

    </x-modal>
