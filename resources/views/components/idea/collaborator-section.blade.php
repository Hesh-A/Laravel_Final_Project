@php
    $initialPendingCollaborators = $pendingCollaborators->map(function ($collaborator) {
        return [
            'id' => $collaborator->id,
            'idea_id' => $collaborator->idea_id,
            'status' => $collaborator->status->value,
            'approve_url' => route('collaborator.approve', $collaborator),
            'user' => [
                'id' => $collaborator->user->id,
                'name' => $collaborator->user->name,
            ],
        ];
    })->values();
@endphp

<div
    x-data="collaboratorComponent(@js($initialPendingCollaborators), {{ $idea->id }})"
    x-show="pendingCollaborators.length > 0"
    class="rounded-lg border border-border/60 bg-card p-4"
>
    <button type="button" @click="open = !open" class="flex w-full items-center justify-between text-left text-sm font-medium">
        <span>Pending approvals (<span x-text="pendingCollaborators.length"></span>)</span>
        <span x-text="open ? '-' : '+'" class="text-muted-foreground"></span>
    </button>

    <x-Ideacard x-show="open" x-collapse class="mt-3 space-y-2">
        <template x-for="collaborator in pendingCollaborators" :key="collaborator.id">
            <div class="flex items-center justify-between rounded-md border border-border/50 px-3 py-2 text-sm">
                <span x-text="collaborator.user.name"></span>
                <span class="rounded-full bg-amber-500/10 px-2 py-1 text-xs text-amber-600">Pending</span>
                <form :action="collaborator.approve_url" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary text-xs">Approve</button>
                </form>
            </div>
        </template>
    </x-Ideacard>
</div>
