<x-layout>
<x-forms.form title="Register an Account" description="Create an account to get started " route="/register" method="POST">
            @csrf
                <x-forms.field name="name" label="Name" type="text" />
                <x-forms.field name="email" label="Email" type="email" />
                <x-forms.field name="password" label="Password" type="password" />

                <button type="submit" class="btn w-full"> Register </button>



</x-forms.form>
</x-layout>