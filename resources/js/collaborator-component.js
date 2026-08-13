export function registerCollaboratorComponent(Alpine) {
            Alpine.data('collaboratorComponent', (initialPendingCollaborators = [], ideaId) => ({
                open: false,
                pendingCollaborators: initialPendingCollaborators,
                seenCollaboratorIds: new Set(initialPendingCollaborators.map((collaborator) => String(collaborator.id))),
                init() {
                    if (!window.Echo) {
                        return;
                    }

                    const channel = window.Echo.channel(`idea.${ideaId}`);

                    channel.listen('.collaborator.requested', (event) => {
                        const collaborator = event?.collaborator;
                        const collaboratorId = collaborator?.id ? String(collaborator.id) : null;

                        if (!collaboratorId || this.seenCollaboratorIds.has(collaboratorId)) {
                            return;
                        }

                        this.seenCollaboratorIds.add(collaboratorId);

                        this.pendingCollaborators.unshift({
                            id: collaborator.id,
                            idea_id: collaborator.idea_id,
                            status: collaborator.status ?? 'pending',
                            approve_url: collaborator.approve_url ?? null,
                            user: {
                                id: collaborator.user?.id ?? collaborator.user_id ?? null,
                                name: collaborator.user?.name ?? 'New collaborator',
                            },
                        });
                    });

                    channel.listen('.collaborator.approved', (event) => {
                        const collaboratorId = event?.collaborator?.id ? String(event.collaborator.id) : null;

                        if (!collaboratorId) {
                            return;
                        }

                        this.pendingCollaborators = this.pendingCollaborators.filter((collaborator) => String(collaborator.id) !== collaboratorId);
                        this.seenCollaboratorIds.delete(collaboratorId);
                    });
                },
            }));
        }