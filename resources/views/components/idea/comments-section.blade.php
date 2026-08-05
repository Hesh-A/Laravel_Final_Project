@props(['idea'])

<div class="mt-6 flex items-center justify-between">
    <h2 class="text-xl font-bold">Comments ({{ $idea->comments->count() }})</h2>
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

@if ($idea->comments->count())
    <div class="space-y-3">
        @foreach ($idea->comments as $comment)
            <x-Ideacard>
                <div class="flex justify-between items-center gap-x-3">
                    <p class="text-sm">{{ $comment->content }}</p>
                    <form action="{{ route('comment.destroy', $comment) }}" method="POST">
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
                    <span class="inline-flex items-center rounded-full bg-secondary/15 px-2 py-1 text-xs text-secondary">
                        {{ $comment->user->name }}
                    </span>
                    <span class="text-xs text-muted-foreground">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            </x-Ideacard>
        @endforeach
    </div>
@endif
