<x-layout>
<x-forms.form title="Login to Your Account" description="Login to get started" route="/login" method="POST">
            @csrf
                <x-forms.field name="email" label="Email" type="email" />
                <x-forms.field name="password" label="Password" type="password" />

                <button type="submit" class="btn w-full"> Sign in </button>



</x-forms.form>
</x-layout>