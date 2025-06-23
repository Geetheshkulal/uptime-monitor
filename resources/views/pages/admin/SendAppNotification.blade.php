@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

<style>
#notificationToggle {
    position: fixed;
    right: 0;
    top: 12%;
    background: #084bbf;
    color: white;
    padding: 10px 35px;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    cursor: pointer;
    z-index: 1050;
}

#notificationSidebar {
    position: fixed;
    right: -260px;
    top: 20%;
    height: 100%;
    width: 260px;
    background-color: #1363d1;
    color: white;
    overflow-y: auto;
    transition: right 0.3s ease-in-out;
    z-index: 1049;
    box-shadow: -2px 0 5px rgba(0,0,0,0.5);
}

#notificationToggle:hover + #notificationSidebar,
#notificationSidebar:hover {
    right: 0;
}

.notification-item {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 10px 0;
    font-size: 14px;
}

</style>

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

<!-- Notification Toggle Button (fixed on right) -->
<div id="notificationToggle">
    🔔 Recent 5 Notification
</div>

<!-- Notification Sidebar (initially collapsed) -->
<div id="notificationSidebar">
    <h6 class="text-white px-3 pt-3">Latest Notifications</h6>

    @foreach($LatestNotifications as $note)
        @php
            $data = is_array($note->data) ? $note->data : json_decode($note->data, true);
            $type = $data['type'] ?? 'info';
            $iconMap = [
                'alert' => '⚠️',
                'announcement' => '📣',
                'update' => '🔄',
            ];
            $icon = $iconMap[$type] ?? '🔔';
        @endphp

        <div class="notification-item px-3 text-white">
            <strong>{{ $icon }} {{ ucfirst($type) }}:</strong><br>
            {{ \Illuminate\Support\Str::limit($data['message'] ?? '', 50) }}
            <div class="small text-gray-300 mt-1">
                {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
            </div>
        </div>
    @endforeach
</div>



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