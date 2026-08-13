export function registerIdeaAccessComponent(Alpine) {
    Alpine.data('ideaAccessComponent', (ideaId, currentUserId = null, canEditInitial = false) => ({
        canEdit: Boolean(canEditInitial),
        approvalMessage: null,
        approvalMessageTimeout: null,

        showApprovalMessage() {
            this.approvalMessage = 'Access granted: you can now edit this idea.';

            if (this.approvalMessageTimeout) {
                clearTimeout(this.approvalMessageTimeout);
            }

            this.approvalMessageTimeout = setTimeout(() => {
                this.approvalMessage = null;
                this.approvalMessageTimeout = null;
            }, 6000);
        },

        dismissApprovalMessage() {
            this.approvalMessage = null;

            if (this.approvalMessageTimeout) {
                clearTimeout(this.approvalMessageTimeout);
                this.approvalMessageTimeout = null;
            }
        },

        init() {
            if (!window.Echo || currentUserId === null || this.canEdit) {
                return;
            }

            const channel = window.Echo.private(`App.Models.User.${currentUserId}`);

            channel.listen('.collaborator.approved', (event) => {
                const collaborator = event?.collaborator;

                if (!collaborator) {
                    return;
                }

                if (Number(collaborator.idea_id) !== Number(ideaId)) {
                    return;
                }

                if (Number(collaborator.user_id) !== Number(currentUserId)) {
                    return;
                }

                this.canEdit = true;
                this.showApprovalMessage();
            });
        },
    }));
}
