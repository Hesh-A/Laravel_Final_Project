<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Authorized | Idea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-background text-foreground">
    <main class="mx-auto flex w-full max-w-3xl flex-1 items-center px-4 py-12 sm:px-6">
        <section class="w-full border-l-4 border-danger py-6 pl-6 sm:pl-8" aria-labelledby="error-title">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-danger">403 error</p>
            <h1 id="error-title" class="mt-3 text-3xl font-bold sm:text-4xl">You are not authorized to do that.</h1>
            <p class="mt-4 max-w-xl text-muted-foreground">
                Your account does not have permission to access this action. You can return to your workspace or go back to the home page.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('idea.index') }}" class="btn text-center">Back to workspace</a>
                <a href="{{ route('home') }}" class="btn btn-outlined text-center">Go home</a>
            </div>
        </section>
    </main>
</body>
</html>
