@props ([
    'title',
    'description',
    'route',
    'method',
])

<div class="flex min-h-[calc(100dvh-4rem)] items-center justify-center px-4">
    <div class="w-full max-w-md border border-border rounded-2xl py-8 px-12">

        <div class="text-center">

            <h1 class="text-3xl font-bold tracking-tight"> {{  $title }} </h1>
            <p class="mt-2 text-muted-foreground"> {{ $description }} </p>

        </div>

        <form action= "{{ $route }}" method= "{{  $method }}" class="mt-10 space-y-4">


        {{ $slot }}
        </form>
    </div>
</div>
