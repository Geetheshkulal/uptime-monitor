@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --gray: #858796;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            padding: 1rem 1.35rem;
        }

        .form-control, .custom-select {
            border-radius: 0.35rem;
            padding: 0.375rem 0.75rem; /* Bootstrap default for perfect centering */
            border: 1px solid #d1d3e2;
            height: calc(1.5em + 0.75rem + 2px); /* Match Bootstrap's input height */
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-control:focus, .custom-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        /* Quill Editor Container */
        #editor-container {
            height: 300px;
            margin-bottom: 20px;
        }

        /* Error Styling */
        .is-invalid {
            border-color: var(--danger) !important;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.875rem;
        }

        /* Attachment Styling */
        .attachment-preview {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .attachment-preview i {
            margin-right: 10px;
            color: var(--gray);
        }

        .file-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .file-preview {
            display: flex;
            align-items: center;
            margin-left: 15px;
        }

        .remove-attachment {
            margin-left: 15px;
            color: var(--danger);
            cursor: pointer;
        }

        .remove-attachment:hover {
            opacity: 0.8;
        }

        .img-thumbnail {
            max-width: 100px;
            max-height: 60px;
            object-fit: contain;
            margin-left: 10px;
        }

         .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 6px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }

    </style>
@endpush
</head>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Ticket</h1>
         <a href="{{(!(auth()->user()->hasRole('user') || auth()->user->hasRole('subuser')))?route('tickets'):route('display.tickets') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        @if (Auth::user()->role == 'user')   
        <a href="{{ route('display.tickets') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-ticket-alt fa-sm text-white-50"></i> View All Tickets
        </a>
        @endif
    </div>

    @if ($canCreateTicket)
        <!-- Show the form for users who can create tickets OR superadmins -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ticket Information</h6>
            </div>
            <div class="card-body">
                <form id="ticketForm" method="POST" action="{{route('store.tickets')}}" enctype="multipart/form-data">
                    @csrf

                <!-- Subject -->
                <div class="form-group row">
                    <label for="subject" class="col-md-2 col-form-label">Subject*</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Priority -->
                <div class="form-group row">
                        <label for="priority" class="col-md-2 col-form-label">Priority*</label>
                        <div class="col-md-4">
                            <select class="custom-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                <option value="" selected disabled>Select priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- To select user -->
                        @if(!(auth()->user()->hasRole('user') || auth()->user()->hasRole('subuser')))
                            <label for="userSelect">For User*</label>
                            <div class="col-md-4">
                                <select class="js-example-basic-single form-control" id="userSelect" name="forUser" style="width: 300px;">
                                    <option value="">All Users</option>
                                    @foreach($allUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} | {{$user->email}} | {{$user->phone}}</option>
                                    @endforeach
                                </select>
                                @error('forUser')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif      
                </div>

                <!-- Description (Quill Editor) -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Description*</label>
                    <div class="col-md-10">
                        <!-- Quill Editor Container -->
                        <div id="editor-container" class="@error('description') is-invalid @enderror"></div>
                        <!-- Hidden input to store the HTML content -->
                        <input type="hidden" id="description" name="description" value="{{ old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Attachments -->
                <div class="form-group row">
                    <label for="attachments" class="col-md-2 col-form-label">Attachments</label>
                    <div class="col-md-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror" id="attachments" accept="image/*" name="attachments[]" multiple>
                            <label class="custom-file-label" for="attachments">Choose files (max 5MB each)</label>
                            @error('attachments')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            You can upload up to 3 files (images only)
                        </small>
                        <!-- Attachment preview container -->
                        <div id="attachment-preview-container" class="mt-2"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Ticket
                        </button>
                       
                    </div>
                </div>
            </form>
        </div>
    </div>
    @else 
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                <h4>You cannot create a new ticket</h4>
                <p class="mb-0">You currently have open or on-hold tickets. Please resolve them first.</p>
                <a href="{{ route('display.tickets') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-ticket-alt mr-2"></i> View My Tickets
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Custom Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill Editor
        const quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Describe your issue in detail...',
            theme: 'snow'
        });

        // Update hidden input with Quill content
        quill.on('text-change', function() {
            document.getElementById('description').value = quill.root.innerHTML;
        });

        // File input label update
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            const files = e.target.files;
            let fileNames = [];
            for (let i = 0; i < files.length; i++) {
                fileNames.push(files[i].name);
            }
            const label = document.querySelector('.custom-file-label');
            label.textContent = fileNames.join(', ') || 'Choose files';
            
            // Display preview of selected files
            showAttachmentPreviews(files);
        });

       

        // Remove invalid class when user starts typing/selecting
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });

    // Updated showAttachmentPreviews function (replace both existing ones)
    function showAttachmentPreviews(files) {
        const container = document.getElementById('attachment-preview-container');
        container.innerHTML = '';

        if (files.length === 0) return;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const previewDiv = document.createElement('div');
            previewDiv.className = 'attachment-preview mb-2';

            // Create basic file info section
            const fileInfo = document.createElement('div');
            fileInfo.className = 'd-flex align-items-center';
            fileInfo.innerHTML = `
                <i class="fas fa-file-image mr-2"></i>
                <span class="mr-2">${file.name} (${formatFileSize(file.size)})</span>
                <span class="remove-attachment ml-auto" data-index="${i}" style="cursor:pointer;">
                    <i class="fas fa-times"></i>
                </span>
            `;
            previewDiv.appendChild(fileInfo);

            // Add image preview if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.createElement('img');
                    imgPreview.src = e.target.result;
                    imgPreview.alt = file.name;
                    imgPreview.className = 'img-thumbnail mt-2';
                    imgPreview.style.width = '100px';
                    previewDiv.appendChild(imgPreview);
                };
                reader.readAsDataURL(file);
            }

            container.appendChild(previewDiv);
        }

        // Add click event listeners to all remove buttons
        document.querySelectorAll('.remove-attachment').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                removeFileFromInput(index);
            });
        });
    }
    

    // Function to remove file from input
    function removeFileFromInput(index) {
        const input = document.getElementById('attachments');
        const files = Array.from(input.files);
        files.splice(index, 1);
        
        // Create new DataTransfer to update files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
        
        // Update the label and preview
        document.querySelector('.custom-file-label').textContent = 
            files.length > 0 ? files.map(f => f.name).join(', ') : 'Choose files';
            showAttachmentPreviews(dataTransfer.files);
    }

    // Function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i]);
    }

    // Show attachment previews (updated for images)


</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
<script>
    $('.js-example-basic-single').select2({
            placeholder: "Select a user",
            allowClear: true
        });
</script>
@endpush

@endsection
