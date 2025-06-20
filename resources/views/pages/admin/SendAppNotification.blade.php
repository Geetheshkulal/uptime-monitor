@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

@endpush


<form method="POST" action="{{ route('admin.send.notification') }}">
    @csrf
    <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control w-50" name="message" placeholder="Enter your notification message" required rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="type">Notification Type</label>
        <select class="form-control w-50" name="type">
            <option value="announcement">Announcement</option>
            <option value="alert">Alert</option>
            <option value="update">Update</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Send to All Users</button>
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