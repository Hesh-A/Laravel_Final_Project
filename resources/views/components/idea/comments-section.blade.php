@props(['idea'])

@php
    $initialComments = $idea->comments->map(function ($comment) use ($idea) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'created_at' => $comment->created_at->diffForHumans(),
            'delete_url' => route('comment.destroy', $comment),
            'can_delete' => $idea->user_id === auth()->id() || $comment->user_id === auth()->id(),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
            ],
        ];
    })->values();
@endphp

<div class="mt-6" x-data="commentsComponent(@js($initialComments), {{ $idea->id }}, {{ auth()->id() ?? 'null' }}, {{ $idea->user_id }})">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">Comments (<span x-text="comments.length"></span>)</h2>
        @if ($idea->user_id !== auth()->id())
            <button
                x-data
                @click="$dispatch('open-modal', {name: 'create-comment'})"
                class="btn btn-outlined flex items-center gap-x-2 text-muted-foreground hover:text-foreground"
                data-test="comment-button">
                <x-icons.message-bubble />
                Add Comment
            </button>
        @endif
    </div>

    <div class="mt-6 space-y-3">
        <template x-for="comment in comments" :key="comment.id">
            <x-Ideacard>
                <div class="flex items-center justify-between gap-x-3">
                    <p class="text-sm" x-text="comment.content"></p>
                    <form :action="comment.delete_url" method="POST" x-show="comment.can_delete && comment.delete_url">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-outlined text-red-500/60 flex items-center gap-x-2 hover:text-red-500"
                            data-test="delete-comment-button">
                            <x-icons.delete-bin />
                        </button>
                    </form>
                </div>
                <div class="mt-2 flex items-center gap-x-3">
                    <span class="inline-flex items-center rounded-full bg-secondary/15 px-2 py-1 text-xs text-secondary" x-text="comment.user.name"></span>
                    <span class="text-xs text-muted-foreground" x-text="comment.created_at"></span>
                </div>
            </x-Ideacard>
        </template>
    </div>
</div>


