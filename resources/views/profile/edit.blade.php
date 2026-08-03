<x-layout>
    <x-forms.form
        title="Edit Your Account"
        description="Update your profile details and password"
        route="{{ route('profile.update') }}"
        method="POST"
    >
        @csrf
        @method('PATCH')

        <x-forms.field
            name="name"
            label="Name"
            type="text"
            :value="$user->name"
            required
        />

        <x-forms.field
            name="email"
            label="Email"
            type="email"
            :value="$user->email"
            required
        />

        <x-forms.field
            name="password"
            label="New Password"
            type="password"
            placeholder="Leave blank to keep current password"
        />

        <x-forms.field
            name="password_confirmation"
            label="Confirm New Password"
            type="password"
            placeholder="Repeat your new password"
        />

        <button type="submit" class="btn w-full" data-test="update-profile-button">
            Save Changes
        </button>
    </x-forms.form>
</x-layout>