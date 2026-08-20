<nav x-data="{ open: false }" class="border-b border-border px-4 sm:px-6">
    <div class="flex w-full items-center justify-between border-b border-border py-3 sm:h-16 sm:py-0">

        <div class="shrink-0">
          <a href="/"> 
            <img src= "/images/logo.svg" alt= "Idea Logo" width="100">
          </a>

        </div>

        <button
            type="button"
            class="sm:hidden"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="mobile-navigation"
        >
            <span class="sr-only">Toggle navigation</span>
            Menu
        </button>

        @auth
        <div class= "hidden flex-wrap items-center gap-3 sm:flex sm:gap-x-6">

            <a href="/profile/edit" class="btn btn-outlined"> Edit Profile </a>
           <form action="/logout" method="POST">
               @csrf
               <button type="submit" class="btn btn-outlined border-red-500/60 text-red-500 hover:border-red-500 hover:text-red-600" data-test="logout-button"> Sign out </button>
           </form>
        </div>            
        @endauth
        @guest
        <div class= "hidden flex-wrap items-center gap-3 sm:flex sm:gap-x-6">
              <a href="/login" class="btn btn-outlined"> Sign in </a>
              <a href="/register" class="btn btn-outlined"> Register </a>
        </div>
        @endguest


    </div>

    <div
        id="mobile-navigation"
        x-show="open"
        x-cloak
        class="flex justify-between items-center gap-3 border-b border-border py-4 sm:hidden"
    >

        @auth
            <a href="/profile/edit" class="btn btn-outlined"> Edit Profile </a>
           <form action="/logout" method="POST">
               @csrf
               <button type="submit" class="btn btn-outlined border-red-500/60 text-red-500 hover:border-red-500 hover:text-red-600" data-test="logout-button"> Sign out </button>
           </form>
          
        @endauth
        @guest

           <a href="/login" class="btn btn-outlined"> Sign in </a>
           <a href="/register" class="btn btn-outlined"> Register </a>

        @endguest

    </div>  

</nav>