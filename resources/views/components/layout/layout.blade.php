<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-background text-foreground overflow-x-hidden">
  
    <x-layout.nav />

 <main class="w-full flex-1 space-y-6 px-4 py-6 sm:px-6 sm:py-7">

   {{ $slot }}

 </main>


  @session('success')
    <x-layout.toast
      type="success"
      x-data="{ show: true }"
      x-init="setTimeout(() => show = false, 3000)"
      x-show="show"
      x-transition.opacity.duration.500ms
    >
      {{ $value }}
    </x-layout.toast>
  @endsession

  @session('error')
    <x-layout.toast
      type="error"
      x-data="{ show: true }"
      x-init="setTimeout(() => show = false, 3000)"
      x-show="show"
      x-transition.opacity.duration.500ms
    >
      {{ $value }}
    </x-layout.toast>
  @endsession

  <x-layout.footer />

</body>
</html>
