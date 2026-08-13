export function registerCommentsComponent(Alpine) {
    Alpine.data('commentsComponent', (initialComments = [], ideaId, currentUserId = null, ideaOwnerId = null) => ({
        comments: initialComments,
        seenCommentIds: new Set(initialComments.map((comment) => String(comment.id))),

        init() {
            if (!window.Echo) {
                return;
            }

            const channel = window.Echo.channel(`idea.${ideaId}`);

            channel.listen('.comment.created', (event) => {
                const comment = event?.comment;
                const incomingCommentId = comment?.id != null ? String(comment.id) : null;

                if (!incomingCommentId || this.seenCommentIds.has(incomingCommentId)) {
                    return;
                }

                this.seenCommentIds.add(incomingCommentId);

                const incomingUserId = comment.user?.id ?? comment.user_id ?? null;
                const canDelete = currentUserId !== null
                    && (Number(currentUserId) === Number(ideaOwnerId) || Number(currentUserId) === Number(incomingUserId));

                const deleteUrl = comment.delete_url ?? null;

                this.comments.unshift({
                    id: comment.id,
                    content: comment.content ?? 'New comment',
                    created_at: 'just now',
                    can_delete: canDelete && Boolean(deleteUrl),
                    delete_url: deleteUrl,
                    user: {
                        id: incomingUserId,
                        name: comment.user?.name ?? 'New user',
                    },
                });
            });

            channel.listen('.comment.deleted', (event) => {
                const deletedCommentId = event?.comment?.id;
                const normalizedDeletedCommentId = deletedCommentId != null ? String(deletedCommentId) : null;

                if (!normalizedDeletedCommentId) {
                    return;
                }

                this.comments = this.comments.filter((comment) => Number(comment.id) !== Number(deletedCommentId));
                this.seenCommentIds.delete(normalizedDeletedCommentId);
            });
        },
    }));
}
