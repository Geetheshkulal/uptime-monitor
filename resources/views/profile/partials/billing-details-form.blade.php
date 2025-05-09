<section>

    <h2 class="text-lg font-medium text-gray-900">Billing Information</h2>
    <p class="mt-1 text-sm text-gray-600">Update your billing address and location details.</p>

    <form method="post" action="{{ route('update.billing.info') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input id="address" name="address" type="text" class="form-control form-control-sm" value="{{ old('address', $user->address) }}">
                <x-input-error :messages="$errors->get('address')" class="text-danger small" />
            </div>

            <div class="col-md-6 mb-3">
                <label for="city" class="form-label">City</label>
                <input id="city" name="city" type="text" class="form-control form-control-sm" value="{{ old('city', $user->city) }}">
                <x-input-error :messages="$errors->get('city')" class="text-danger small" />
            </div>

            <div class="col-md-4 mb-3">
                <label for="state" class="form-label">State</label>
                <input id="state" name="state" type="text" class="form-control form-control-sm" value="{{ old('state', $user->state) }}">
                <x-input-error :messages="$errors->get('state')" class="text-danger small" />
            </div>

            <div class="col-md-4 mb-3">
                <label for="pincode" class="form-label">Pincode</label>
                <input id="pincode" name="pincode" type="text" class="form-control form-control-sm" value="{{ old('pincode', $user->pincode) }}">
                <x-input-error :messages="$errors->get('pincode')" class="text-danger small" />
            </div>

            <div class="col-md-4 mb-3">
                <label for="country" class="form-label">Country</label>
                <input id="country" name="country" type="text" class="form-control form-control-sm" value="{{ old('country', $user->country) }}">
                <x-input-error :messages="$errors->get('country')" class="text-danger small" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-sm">Save Billing Info</button>
    </form>
</section>
