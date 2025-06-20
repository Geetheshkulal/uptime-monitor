@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

@endpush


<form method="POST" action="{{ route('admin.send.notification') }}">
    @csrf
    <h1 class="h3 mb-2 text-gray-800 ml-3">Send Notification to All Users</h1>
    <div class="form-group ml-3">
        <label for="message">Message</label>
        <textarea class="form-control w-25" name="message" placeholder="Enter your notification message" required rows="3"></textarea>
    </div>
    <div class="form-group ml-3">
        <label for="type">Notification Type</label>
        <select class="form-control w-25" name="type">
            <option value="announcement">Announcement</option>
            <option value="alert">Alert</option>
            <option value="update">Update</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary ml-3">Send to All Users</button>
</form>


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>


    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
@endpush
@endsection