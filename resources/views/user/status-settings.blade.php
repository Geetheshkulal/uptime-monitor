@extends('dashboard')

@section('content')
<div class="container py-4">

    <div class="page-title-box d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Status Page Settings</h1>
    <a href="{{ route('status') }}" class="btn btn-primary ms-3" style="padding: 0.5rem 1rem;">
        <i class="fas fa-arrow-left me-2"></i> Back to Status Page
    </a>
</div><br>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('user.status-settings.update') }}">
                @csrf

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               id="enablePublicStatus" name="enable_public_status"
                               {{ $user->enable_public_status ? 'checked' : '' }}
                               onchange="togglePublicStatusFields()">
                        <label class="form-check-label fw-bold" for="enablePublicStatus">
                            Enable Public Status Page
                        </label>
                    </div>
                    <small class="text-muted">
                        When enabled, all your monitors will be visible at the public URL
                    </small>
                </div>

                <div id="publicStatusFields" style="display: {{ $user->enable_public_status ? 'block' : 'none' }};">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Public URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   id="publicUrl" value="{{ $publicUrl }}" readonly>
                            <button class="btn btn-outline-secondary" 
                                    type="button" onclick="copyToClipboard('publicUrl')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Embed Code</label>
                        <div class="input-group">
                            <textarea class="form-control" id="embedCode" 
                                      rows="3" readonly>{{ $iframeCode }}</textarea>
                            <button class="btn btn-outline-secondary" 
                                    type="button" onclick="copyToClipboard('embedCode')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        White List
                        <div class="container px-0">
                            <div class="row">
                                <div class="col-md-6 d-flex">
                                    <input type="text" class="form-control me-2" placeholder="Enter something">
                                    <button class="btn btn-primary">Add</button>
                                </div>
                        </div>
                    </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> All your monitors will be visible on the page where you will embed this code 
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
function togglePublicStatusFields() {
    const checkbox = document.getElementById('enablePublicStatus');
    const fieldsDiv = document.getElementById('publicStatusFields');
    fieldsDiv.style.display = checkbox.checked ? 'block' : 'none';
}

function copyToClipboard(elementId) {
    const element = document.getElemengivr giigtById(elementId);
    element.select();
    document.execCommand('copy');
    
    if (typeof toastr !== 'undefined') {
        toastr.success('Copied to clipboard');
    } else {
        alert('Copied to clipboard!');
    }
}

@if(session('success'))
    toastr.success("{{ session('success') }}");
@endif

</script>
@endsection