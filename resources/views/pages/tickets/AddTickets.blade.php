@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
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
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
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
@endpush
</head>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Ticket</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-ticket-alt fa-sm text-white-50"></i> View All Tickets
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ticket Information</h6>
        </div>
        <div class="card-body">
            <form id="ticketForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf

                <!-- Subject -->
                <div class="form-group row">
                    <label for="subject" class="col-md-2 col-form-label">Subject*</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="subject" name="subject" required>
                        <div class="invalid-feedback">Please provide a subject for your ticket.</div>
                    </div>
                </div>

                <!-- Priority -->
                <div class="form-group row">
                    <label for="priority" class="col-md-2 col-form-label">Priority*</label>
                    <div class="col-md-4">
                        <select class="custom-select" id="priority" name="priority" required>
                            <option value="" selected disabled>Select priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                        <div class="invalid-feedback">Please select a priority level.</div>
                    </div>

                    <!-- Category -->
                    <label for="category" class="col-md-2 col-form-label">Category*</label>
                    <div class="col-md-4">
                        <select class="custom-select" id="category" name="category" required>
                            <option value="" selected disabled>Select category</option>
                            <option value="technical">Technical Issue</option>
                            <option value="billing">Billing Inquiry</option>
                            <option value="feature">Feature Request</option>
                            <option value="general">General Question</option>
                        </select>
                        <div class="invalid-feedback">Please select a category.</div>
                    </div>
                </div>

                <!-- Description (Quill Editor) -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Description*</label>
                    <div class="col-md-10">
                        <!-- Quill Editor Container -->
                        <div id="editor-container"></div>
                        <!-- Hidden input to store the HTML content -->
                        <input type="hidden" id="description" name="description" required>
                        <div class="invalid-feedback">Please provide a detailed description.</div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="form-group row">
                    <label for="attachments" class="col-md-2 col-form-label">Attachments</label>
                    <div class="col-md-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="attachments" name="attachments[]" multiple>
                            <label class="custom-file-label" for="attachments">Choose files (max 5MB each)</label>
                        </div>
                        <small class="form-text text-muted">
                            You can upload up to 3 files (images, documents, or logs)
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
                        <button type="reset" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
                    ['link', 'image', 'video'],
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

        // Form validation
        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate subject
            if (!document.getElementById('subject').value.trim()) {
                document.getElementById('subject').classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate priority
            if (!document.getElementById('priority').value) {
                document.getElementById('priority').classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate category
            if (!document.getElementById('category').value) {
                document.getElementById('category').classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate description
            if (!quill.getText().trim()) {
                document.querySelector('#editor-container').classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first invalid field
                document.querySelector('.is-invalid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        // Remove invalid class when user starts typing/selecting
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
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