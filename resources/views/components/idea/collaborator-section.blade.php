 @if ($pendingCollaborators->isNotEmpty())
    <div x-data="{ open: false }" class="rounded-lg border border-border/60 bg-card p-4">
            <button type="button" @click="open = !open" class="flex w-full items-center justify-between text-left text-sm font-medium">
                   <span>Pending approvals ({{ $pendingCollaborators->count() }})</span>
                   <span x-text="open ? '-' : '+'" class="text-muted-foreground"></span>
            </button>

            <x-Ideacard x-show="open" x-collapse class="mt-3 space-y-2">
                    @forelse ($pendingCollaborators as $collaborator)
                        <div class="flex items-center justify-between rounded-md border border-border/50 px-3 py-2 text-sm">
                             <span>{{ $collaborator->user->name }}</span>
                             <span class="rounded-full bg-amber-500/10 px-2 py-1 text-xs text-amber-600">Pending</span>
                             <form action="{{ route('collaborator.approve', $collaborator) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary text-xs">Approve</button>
                             </form>
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">No pending collaborators.</p>
                    @endforelse
            </x-Ideacard>
    </div>
   @endif