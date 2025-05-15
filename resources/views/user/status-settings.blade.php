@extends('dashboard')
@section('content')
@push('styles')
<style>
    .tag {
      display: inline-flex;
      align-items: center;
      background-color: #e9ecef;
      color: #495057;
      padding: 0.25rem 0.5rem;
      border-radius: 20px;
      margin: 0.25rem;
      font-size: 0.9rem;
    }
    .tag .remove-btn {
      background: none;
      border: none;
      font-size: 1rem;
      margin-left: 0.4rem;
      cursor: pointer;
      line-height: 1;
      color: #6c757d;
    }
    .tag .remove-btn:hover {
      color: #dc3545;
    }
</style>
@endpush
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

                        <div id="tag-container">

                        </div>


                        <div class="container px-0 mt-2">
                            <div class="row">
                                <div class="col-md-6 d-flex">
                                    <input type="text" class="form-control me-2" id="ipInput" placeholder="Enter IP Address">
                                    <button class="btn btn-primary" id="addIP">Add</button>
                                </div>
                        </div>
                    </div>
                    </div>

                    
                    <input type="hidden" name="whitelist" id="whitelist-data" value='@json($whitelist->whitelist ?? [])' />

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
        const element = document.getElementById(elementId);
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

<script>
    let whitelistedIPs = @json($whitelist->whitelist ?? []);
    
    function isValidIPv4(ip) {
        const regex = /^(25[0-5]|2[0-4][0-9]|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4][0-9]|1\d\d|[1-9]?\d)){3}$/;
        return regex.test(ip);
    }


    function renderIPs(){
        $('#tag-container').empty();
            whitelistedIPs.forEach((ip,index)=>{
                $('#tag-container').append(
                    `
                        <span class="tag">
                            ${ip}
                            <button class="remove-btn" data-index="${index}"> × </button>
                        </span>
                    `
                );
            });
        $('#whitelist-data').val(JSON.stringify(whitelistedIPs));
    }

    renderIPs();

    $('#addIP').click(function(event){
        event.preventDefault();
        const val = $('#ipInput').val().trim();

         if (!isValidIPv4(val)) {
            toastr.error('Invalid IP address.');
            return;
        }

        if(whitelistedIPs.includes(val)){
            toastr.error('This IP already exists.')
        }else{
            whitelistedIPs.push(val);
             $('#ipInput').val('');
        }
        renderIPs();
    })

      $(document).on('click', '.remove-btn', function (event) {
        event.preventDefault();
        const index = $(this).data('index');
        whitelistedIPs.splice(index, 1);
        renderIPs();
    });
</script>
@endsection