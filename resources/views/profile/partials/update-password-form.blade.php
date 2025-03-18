<section>
    <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
    <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input id="current_password" name="current_password" type="password" class="form-control">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="text-danger small" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" name="password" type="password" class="form-control">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="text-danger small" />
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="text-danger small" />
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</section>
