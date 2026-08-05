@props(['idea'])

<x-modal modalName="create-comment" title="Add a Comment">
    <form 
        action="{{ route('comment.store', $idea) }}"
        method="POST"
        class="space-y-4"
    >

    @csrf

    <x-forms.field
            label="Comment"
            name="content"
            type="textarea"
            placeholder="Add your comment here..."
            required
        />

        <div class="flex justify-end gap-4">
            <button class="btn btn-outlined" type="button" @click="$dispatch('close-modal')"> Cancel </button>
            <button class="btn btn-primary" type="submit"> Submit </button>
        </div>
    </form>
</x-modal>