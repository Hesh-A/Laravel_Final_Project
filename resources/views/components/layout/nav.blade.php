<nav class= "border-b border-border px-6">
    <div class= "max-w-7xl mx-auto h-16 flex items-center justify-between">

        <div>
          <a href="/"> 
            <img src= "/images/logo.svg" alt= "Idea Logo" width="100">
          </a>

        </div>
        @auth
        <div class= "flex gap-x-6 items-center">
           <form action="/logout" method="POST">
               @csrf
               <button type="submit" class="btn btn-danger"> Sign out </button>
           </form>
        </div>            
        @endauth
        @guest
        <div class= "flex gap-x-6 items-center">
           <a href="/login" class="btn btn-secondary"> Sign in </a>
           <a href="/register" class="btn"> Register </a>
        </div>
        @endguest


    </div>

</nav>