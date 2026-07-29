<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class= "bg-background text-foreground">
  
    <x-layout.nav />

 <main class="px-6 py-7 mx-auto max-w-7xl space-y-6">

   {{ $slot }}

 </main>


  @session('success')
    
    <div x-data = "{ show: true }"
      x-init = "setTimeout(() => show = false, 3000)"
      x-show = "show"
      x-transition.opacity.duration.500ms
      class = "fixed bottom-3 right-3 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">

            {{ $value }}
    </div>
  @endsession

</body>
</html>
