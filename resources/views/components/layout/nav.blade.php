<nav class= "border-b border-border px-4 sm:px-6">
  <div class= "flex w-full flex-col gap-3 py-3 sm:h-16 sm:flex-row sm:items-center sm:justify-between sm:gap-0 sm:py-0">

        <div class="shrink-0">
          <a href="/"> 
            <img src= "/images/logo.svg" alt= "Idea Logo" width="100">
          </a>

        </div>
        @auth
        <div class= "flex flex-wrap items-center gap-3 sm:gap-x-6">

            <a href="/profile/edit" class="btn btn-secondary"> Edit Profile </a>
           <form action="/logout" method="POST">
               @csrf
               <button type="submit" class="btn btn-danger hover:bg-red-600" data-test="logout-button"> Sign out </button>
           </form>
        </div>            
        @endauth
        @guest
        <div class= "flex flex-wrap items-center gap-3 sm:gap-x-6">
           <a href="/login" class="btn btn-secondary"> Sign in </a>
           <a href="/register" class="btn"> Register </a>
        </div>
        @endguest


    </div>

</nav>