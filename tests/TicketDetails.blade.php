@extends('dashboard')
@section('content')

<head>
    @push('styles')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
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
            align-items: center;
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .attachment-preview i {
            margin-right: 10px;
            color: var(--gray);
        }

        .remove-attachment {
            margin-left: auto;
            color: var(--danger);
            cursor: pointer;
        }
    </style>

<style>
    /* GitHub-like timeline styling */
    .timeline {
        position: relative;
        padding-left: 60px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-badge {
        position: absolute;
        left: -60px;
        top: 0;
    }
    .timeline-panel {
        position: relative;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .markdown-body {
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif;
        font-size: 16px;
        line-height: 1.5;
        word-wrap: break-word;
    }
</style>

    
@endpush
</head>


<div class="container mt-4">
    <!-- Header with back and edit buttons -->
    <div class="row w-100 mb-4">
        <div class="col-md-6 text-left">
            <a href="{{ route('tickets') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left" style="color: #ffffff;"></i> Back
            </a>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#editTicketModal">
                <i class="fas fa-edit" style="color: #ffffff;"></i> Edit Ticket
            </button>
        </div>
    </div>

    <!-- Ticket title and metadata -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h4 class="mb-0">{{ $ticket->title }}</h4>
            <div class="text-muted small mt-1">
                <span>#{{ $ticket->id }} opened on {{ $ticket->created_at->format('F j, Y') }} by {{ $ticket->user->name }}</span>
                <span class="badge badge-{{ $ticket->status == 'open' ? 'success' : 'secondary' }} ml-2">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="markdown-body">
                {!! nl2br(e($ticket->message)) !!}
            </div>
        </div>
    </div>

    <!-- Comments section -->
    <div class="mb-4">
        <h5 class="mb-3"><i class="far fa-comment mr-2"></i> {{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}</h5>
        
        <!-- Comment list -->
        <div class="timeline">
            @foreach($comments as $comment)
            <div class="timeline-item">
                <div class="timeline-badge">
                    <img src="{{ Avatar::create(auth()->user()->name)->toBase64() }}" class="rounded-circle" alt="{{ $comment->user->name }}" width="40" height="40">
                </div>
                <div class="timeline-panel card">
                    <div class="card-header bg-white border-bottom-0">
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="text-muted small ml-2">commented on {{ $comment->created_at->format('F j, Y') }}</span>
                        @if(auth()->id() == $comment->user_id)
                        <div class="float-right">
                            <button class="btn btn-sm btn-link text-muted"><i class="far fa-edit"></i></button>
                            <button class="btn btn-sm btn-link text-muted"><i class="far fa-trash-alt"></i></button>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="markdown-body">
                            {!! nl2br(e($comment->comment_message)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

   <!-- Add comment form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add Comment</h6>
    </div>
    <div class="card-body">
        <form id="ticketForm" method="POST" action="{{ route('admin.comments.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            
            <div class="form-group">
                <!-- Quill editor container matching comment box dimensions -->
                <div id="editor-container" style="height: 150px; width: 100%; border-radius: 6px; border: 1px solid #d1d5da; background-color: #ffffff;"></div>
                <input type="hidden" id="description" name="description" value="{{ old('description') }}" required>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Add Comment
                </button>
                <button type="reset" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-undo mr-2"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>
</div>



<!-- Edit Ticket Modal -->
<div class="modal fade @if(session()->has('errors')) show @endif" id="editTicketModal" tabindex="-1" role="dialog" aria-labelledby="editTicketModalLabel" aria-hidden="true" @if(session()->has('errors')) style="display: block;" @endif>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTicketModalLabel">Edit Ticket #{{ $ticket->id }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" id="editTicketForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                   
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="form-group">
                        <label for="message" class="font-weight-bold">Description</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message', $ticket->message) }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="font-weight-bold">Status</label>
                                <select class="form-control selectpicker @error('status') is-invalid @enderror" id="status" name="status" required data-style="btn-status">
                                    <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }} data-content="<span class='badge badge-success'>Open</span>">Open</option>
                                    <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }} data-content="<span class='badge badge-secondary'>Closed</span>">Closed</option>
                                    <option value="on hold" {{ old('status', $ticket->status) == 'on hold' ? 'selected' : '' }} data-content="<span class='badge badge-warning'>On Hold</span>">On Hold</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority" class="font-weight-bold">Priority</label>
                                <select class="form-control selectpicker @error('priority') is-invalid @enderror" id="priority" name="priority" required data-style="btn-priority">
                                    <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }} data-content="<span class='badge badge-info'>Low</span>">Low</option>
                                    <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }} data-content="<span class='badge badge-primary'>Medium</span>">Medium</option>
                                    <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }} data-content="<span class='badge badge-danger'>High</span>">High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="assigned_user_id" class="font-weight-bold">Assigned To</label>
                        <select class="form-control selectpicker @error('assigned_user_id') is-invalid @enderror" id="assigned_user_id" name="assigned_user_id" data-live-search="true" data-style="btn-user">
                            <option value="" {{ old('assigned_user_id', $ticket->assigned_user_id) == null ? 'selected' : '' }} data-content="<span class='text-muted'>Unassigned</span>">Unassigned</option>
                            @foreach($supportUsers as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_user_id', $ticket->assigned_user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Custom Scripts -->
<script>

    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 4000
            });
        @endif
    });


    document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill Editor with GitHub-like styling
    const quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        },
        placeholder: 'Write your comment here...',
        theme: 'snow'
    });

    // Update hidden input with Quill content
    quill.on('text-change', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    // Style adjustments to match GitHub's comment boxes
    const editor = document.querySelector('#editor-container .ql-editor');
    if (editor) {
        editor.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif';
        editor.style.fontSize = '16px';
        editor.style.lineHeight = '1.5';
        editor.style.padding = '16px';
        editor.style.minHeight = '150px'; // Match comment box height
    }
});

    // Function to show attachment previews
    function showAttachmentPreviews(files) {
        const container = document.getElementById('attachment-preview-container');
        container.innerHTML = '';
        
        if (files.length === 0) return;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const previewDiv = document.createElement('div');
            previewDiv.className = 'attachment-preview';
            
            let iconClass = 'fa-file';
            if (file.type.startsWith('image/')) {
                iconClass = 'fa-file-image';
            } else if (file.type.includes('pdf')) {
                iconClass = 'fa-file-pdf';
            } else if (file.type.includes('word') || file.type.includes('document')) {
                iconClass = 'fa-file-word';
            } else if (file.type.includes('excel') || file.type.includes('spreadsheet')) {
                iconClass = 'fa-file-excel';
            }
            
            previewDiv.innerHTML = `
                <i class="fas ${iconClass}"></i>
                <span>${file.name} (${formatFileSize(file.size)})</span>
                <span class="remove-attachment" data-index="${i}">
                    <i class="fas fa-times"></i>
                </span>
            `;
            
            container.appendChild(previewDiv);
        }
        
        // Add event listeners to remove buttons
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
</script>
@endpush

@endsection