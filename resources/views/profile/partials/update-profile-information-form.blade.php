<section>

    <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
    <p class="mt-1 text-sm text-gray-600">Update your account's profile information.</p>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}">
            <x-input-error :messages="$errors->get('name')" class="text-danger small" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}">
            <x-input-error :messages="$errors->get('email')" class="text-danger small" />
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}">
            <x-input-error :messages="$errors->get('phone')" class="text-danger small" />
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</section>

