@extends('dashboard')
@section('content')

<head>
    @push('styles')
    <!-- GitHub Markdown CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/4.0.0/github-markdown.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <!-- Custom GitHub-like Styles with Quill integration -->
    <style>
        :root {
            --color-text-primary: #24292e;
            --color-text-secondary: #586069;
            --color-border-primary: #e1e4e8;
            --color-border-secondary: #eaecef;
            --color-bg-primary: #ffffff;
            --color-bg-secondary: #f6f8fa;
            --color-state-open: #28a745;
            --color-state-closed: #d73a49;
            --color-state-merged: #6f42c1;
            --color-state-on-hold: #f66a0a;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            color: var(--color-text-primary);
            background-color: var(--color-bg-primary);
        }

        .container {
            max-width: 1280px;
        }

        /* Header styles */
        .issue-header {
            padding-bottom: 8px;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--color-border-primary);
        }

        .issue-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            word-break: break-word;
        }

        .issue-meta {
            color: var(--color-text-secondary);
            font-size: 14px;
        }

        .state-badge {
            display: inline-block;
            padding: 4px 8px;
            font-weight: 600;
            line-height: 20px;
            color: #fff;
            text-align: center;
            border-radius: 2em;
            font-size: 12px;
            margin-right: 8px;
        }

        .state-open {
            background-color: var(--color-state-open);
        }

        .state-closed {
            background-color: var(--color-state-closed);
        }

        .state-on-hold {
            background-color: var(--color-state-on-hold);
        }

        /* Comment/timeline styles */
        .timeline-item {
            position: relative;
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--color-border-primary);
        }

        .timeline-item:last-child {
            border-bottom: 0;
        }

        .comment {
            position: relative;
            background-color: var(--color-bg-primary);
            border: 1px solid var(--color-border-primary);
            border-radius: 6px;
        }

        .comment-header {
            padding: 8px 16px;
            background-color: var(--color-bg-secondary);
            border-bottom: 1px solid var(--color-border-primary);
            border-radius: 6px 6px 0 0;
            display: flex;
            align-items: center;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .comment-author {
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .comment-meta {
            color: var(--color-text-secondary);
            font-size: 12px;
            margin-left: 8px;
        }

        .comment-body {
            padding: 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        /* New comment form with Quill */
        .new-comment {
            margin-top: 16px;
            border: 1px solid var(--color-border-primary);
            border-radius: 6px;
        }

        .new-comment-header {
            padding: 8px 16px;
            background-color: var(--color-bg-secondary);
            border-bottom: 1px solid var(--color-border-primary);
            border-radius: 6px 6px 0 0;
            font-weight: 600;
        }

        .new-comment-body {
            padding: 16px;
        }

        /* Quill editor customization to match GitHub style */
        #editor-container {
            border: 1px solid var(--color-border-primary);
            border-radius: 6px;
            background-color: var(--color-bg-primary);
            min-height: 150px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        #editor-container .ql-editor {
            min-height: 150px;
            padding: 8px 16px;
            color: var(--color-text-primary);
            line-height: 1.5;
        }

        #editor-container .ql-toolbar {
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            border-color: var(--color-border-primary);
            background-color: var(--color-bg-secondary);
        }

        #editor-container .ql-container {
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
            border-color: var(--color-border-primary);
        }

        .form-actions {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }

        .btn {
            padding: 5px 16px;
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid;
        }

        .btn-primary {
            color: #fff;
            background-color: #2ea44f;
            border-color: rgba(27, 31, 35, 0.15);
        }

        .btn-primary:hover {
            background-color: #2c974b;
        }

        .btn-secondary {
            color: #24292e;
            background-color: #fafbfc;
            border-color: rgba(27, 31, 35, 0.15);
            margin-right: 8px;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
        }

        /* Markdown body adjustments */
        .markdown-body {
            font-size: 14px;
            line-height: 1.5;
        }

        .markdown-body img {
            max-width: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .issue-title {
                font-size: 20px;
            }
            
            .container {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
        /* Add these styles to your existing CSS */
        .attachments-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .attachment-container {
            position: relative;
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 4px;
            border: 1px solid var(--color-border-primary);
            cursor: pointer;
        }

        .attachment-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .attachment-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 12px;
            text-align: center;
            padding: 5px;
        }

        .attachment-container:hover .attachment-overlay {
            opacity: 1;
        }

        .attachment-container:hover .attachment-image {
            transform: scale(1.05);
        }

        /* Modal image styling */
        #modalImage {
            max-height: 70vh;
            max-width: 100%;
        }
    </style>
@endpush
</head>

<div class="container mt-4">
    <!-- Header with back button and edit option -->
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="{{ route('display.tickets') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to tickets
            </a>
        </div>
        @hasrole('superadmin')
        <div>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editTicketModal">
                <i class="fas fa-pencil-alt"></i> Edit
            </button>
        </div>
        @endhasrole
    </div>

    <!-- Ticket header -->
    <div class="issue-header">
        <h1 class="issue-title">
            <span class="state-badge state-{{ $ticket->status == 'open' ? 'open' : ($ticket->status == 'closed' ? 'closed' : 'on-hold') }}">
                {{ ucfirst($ticket->status) }}
            </span>
            {{ $ticket->title }}
        </h1>
        <div class="issue-meta">
            <span>#{{ $ticket->id }} opened on {{ $ticket->created_at->format('M j, Y') }} by {{ $ticket->user->name }}</span>
            @if($ticket->priority)
            <span class="ml-2">• Priority: {{ ucfirst($ticket->priority) }}</span>
            @endif
        </div>
    </div>

    <!-- Main ticket content -->
    <div class="timeline-item">
        <div class="comment">
            <div class="comment-header">
                <img src="{{ Avatar::create($ticket->user->name)->toBase64() }}" class="comment-avatar" alt="{{ $ticket->user->name }}">
                <span class="comment-author">{{ $ticket->user->name }}</span>
                <span class="comment-meta">commented on {{ $ticket->created_at->format('M j, Y') }}</span>
            </div>
            <div class="comment-body markdown-body">
                {!! $ticket->message !!}
                <hr>
                <div class="attachments-gallery">
                    @foreach($ticket->attachments as $attachment)
                        @if($attachment)
                            <div class="attachment-container" data-image="{{ Storage::url($attachment) }}">
                                <img src="{{ Storage::url($attachment) }}" class="attachment-image" alt="Attachment">
                                <div class="attachment-overlay">
                                    <div>
                                        <i class="fas fa-search-plus fa-lg mb-2"></i>
                                        <div>Click to view</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Comments section -->
    @if($comments->count() > 0)
    <div class="mt-4">
        <h4 class="mb-3">{{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}</h4>
        
        @foreach($comments as $comment)
        <div class="timeline-item">
            <div class="comment">
                <div class="comment-header">
                    <img src="{{ Avatar::create($comment->user->name)->toBase64() }}" class="comment-avatar" alt="{{ $comment->user->name }}">
                    <span class="comment-author">{{ $comment->user->name }}</span>
                    <span class="comment-meta">commented on {{ $comment->created_at->format('M j, Y') }}</span>
                    @if(auth()->id() == $comment->user_id)
                    <div class="ml-auto">
                        <button class="btn btn-sm btn-outline mr-1"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline"><i class="fas fa-trash"></i></button>
                    </div>
                    @endif
                </div>
                <div class="comment-body markdown-body">
                    {!! $comment->comment_message !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Add comment form with Quill editor -->
    <div class="new-comment mt-4">
        <div class="new-comment-header">
            Add comment
        </div>
        <div class="new-comment-body">
            <form id="ticketForm" method="POST" action="{{ route('admin.comments.store') }}">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                
                <div class="form-group">
                    <!-- Quill editor container -->
                    <div id="editor-container"></div>
                    <input type="hidden" id="description" name="description" value="{{ old('description') }}" required>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Comment</button>
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
            <form method="POST" action="{{ route('tickets.update', $ticket->id) }}" id="editTicketForm">
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
                    
                    {{-- <div class="form-group">
                        <label for="message" class="font-weight-bold">Description</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message', $ticket->message) }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                     <!-- Description Field with Quill Editor -->
                     <div class="form-group">
                        <label for="message" class="font-weight-bold">Description</label>
                        <div id="quill-editor">{!! old('message', $ticket->message) !!}</div>
                        <input type="hidden" id="message" name="message" value="{{ old('message', $ticket->message) }}">
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

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attachment Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Attachment">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a id="downloadBtn" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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

        // Initialize Quill Editor with GitHub-like styling
        const quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Leave a comment...',
            theme: 'snow'
        });

        // Update hidden input with Quill content when form is submitted
        const form = document.getElementById('ticketForm');
        form.onsubmit = function() {
            const description = document.querySelector('input[name=description]');
            description.value = quill.root.innerHTML;
            return true;
        };

        // Style adjustments to match GitHub's comment boxes
        const editor = document.querySelector('#editor-container .ql-editor');
        if (editor) {
            editor.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif';
            editor.style.fontSize = '14px';
            editor.style.lineHeight = '1.5';
            editor.style.padding = '8px 16px';
            editor.style.minHeight = '150px';
            editor.style.color = '#24292e';
        }

    });

        
    document.addEventListener("DOMContentLoaded", function() {
        // Image modal functionality
        const imageModal = $('#imageModal');
        const modalImage = document.getElementById('modalImage');
        const downloadBtn = document.getElementById('downloadBtn');
        
        // Handle attachment clicks
        document.querySelectorAll('.attachment-container').forEach(container => {
            container.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image');
                console.log(imageUrl)
                modalImage.src = imageUrl;
                downloadBtn.href = imageUrl;
                downloadBtn.setAttribute('download', imageUrl.split('/').pop());
                imageModal.modal('show');
            });
        });
        
        // Handle modal image loading errors
        modalImage.onerror = function() {
            this.src = 'https://via.placeholder.com/800x600?text=Image+Not+Available';
            downloadBtn.style.display = 'none';
        };
        
        // Reset download button when modal is hidden
        imageModal.on('hidden.bs.modal', function() {
            downloadBtn.style.display = 'block';
        });
    });

   // The below script initializes the Quill editor for the edit ticket modal
    document.addEventListener('DOMContentLoaded', function() {

        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Describe the ticket in detail...'
        });
    
        // Set initial content
        quill.clipboard.dangerouslyPasteHTML(document.getElementById('message').value);
    
        // Update hidden input before form submission
        document.getElementById('editTicketForm').onsubmit = function() {
            const description = document.getElementById('message');
            description.value = quill.root.innerHTML;
            return true;
        };
    
        // If modal is shown with errors, reinitialize Quill with the submitted content
        @if(session()->has('errors'))
            quill.clipboard.dangerouslyPasteHTML('{{ old('message') }}');
        @endif
    });
</script>


@endpush

@endsection