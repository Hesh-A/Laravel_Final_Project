@props(['idea'])


<div class="mt-6" x-data="newCommentsComponent({{ $idea->id }}) ">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">Comments (<span x-text="newComments.length"></span>)</h2>
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
        @foreach ($idea->comments as $comment)
            <x-Ideacard>
                <div class="flex justify-between items-center gap-x-3">
                    <p class="text-sm">{{ $comment->content }}</p>
                    @if ($idea->user_id === auth()->id() || $comment->user_id === auth()->id())
                        <form action="{{ route('comment.destroy', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-outlined text-red-500/60 flex items-center gap-x-2 hover:text-red-500"
                                data-test="delete-comment-button">
                                <x-icons.delete-bin />
                            </button>
                        </form>
                    @endif
                </div>
                <div class="mt-2 flex items-center gap-x-3">
                    <span class="inline-flex items-center rounded-full bg-secondary/15 px-2 py-1 text-xs text-secondary">{{ $comment->user->name }}</span>
                    <span class="text-xs text-muted-foreground">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            </x-Ideacard>
        @endforeach
    </div>

    <div class="mt-6" x-show="newComments.length > 0">
        <h3 class="text-sm font-semibold">New comments</h3>
        <ul class="mt-2 space-y-1 text-sm">
            <template x-for="comment in newComments" :key="comment.id">
                <li x-text="comment.content"></li>
            </template>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('newCommentsComponent', (ideaId) => ({
            newComments: [],
            seenCommentIds: new Set(),
            init() {
                if (!window.Echo) {
                    return;
                }

                window.Echo.channel(`idea.${ideaId}`).listen('.comment.created', (event) => {
                    const comment = event?.comment;

                    if (!comment?.id || this.seenCommentIds.has(comment.id)) {
                        return;
                    }

                    this.seenCommentIds.add(comment.id);
                    this.newComments.unshift({
                        id: comment.id,
                        content: comment.content ?? 'New comment',
                    });
                });
            },
        }));
    });
</script>


