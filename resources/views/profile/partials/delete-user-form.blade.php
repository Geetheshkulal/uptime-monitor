<section>
    <h2 class="text-lg font-medium text-danger">Delete Account</h2>
    <p class="mt-1 text-sm text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently removed. Please enter your password to confirm.
    </p>

    <button class="btn btn-danger my-3 " x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <p class="text-lg font-medium text-gray-900">Are you sure you want to delete your account?</p>

            <div class="mt-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Enter your password">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-danger small" />
            </div>

            <div class="mt-4 d-flex justify-content-end" style=" gap:4px">
                <button type="submit" class="btn btn-danger">Delete Account</button>
                <button type="button" class="btn btn-secondary me-2" x-on:click="$dispatch('close')">Cancel</button>
            </div>
        </form>
    </x-modal>
</section>
