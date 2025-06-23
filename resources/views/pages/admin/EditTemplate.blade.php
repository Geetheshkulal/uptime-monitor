@extends('dashboard')

@section('content')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        #templateForm {
            margin-top: 1.5rem;
        }
        #editor {
            height: 300px;
        }
        .ql-variable-tag {
            background-color: #d6f0ff;
            color: #007acc;
            border-radius: 4px;
            padding: 2px 6px;
            margin: 0 2px;
            display: inline-block;
            font-weight: 500;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .click-animate {
            transition: transform 0.1s ease;
        }
        .click-animate:active {
            transform: scale(0.95);
        }
        .buttons-container{
            gap:20px;
        }
        .editor-container {
            display: flex;
            gap: 30px;
        }
        .editor-column {
            flex: 1;
        }
        .preview-column {
            flex: 1;
            border-left: 1px solid #eee;
            padding-left: 30px;
        }
        .preview-content {
            padding: 20px;
            border-radius: 5px;
            min-height: 300px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        /* WhatsApp message style */
        .whatsapp-message {
            max-width: 75%;
            margin-left: auto;
            margin-bottom: 15px;
        }
        .whatsapp-bubble {
            background: #e1ffc7;
            border-radius: 7.5px 0 7.5px 7.5px;
            padding: 8px 12px;
            position: relative;
            color: #111;
            font-family: "Segoe UI", Helvetica, Arial, sans-serif;
            font-size: 14.2px;
            line-height: 19px;
            word-wrap: break-word;
            white-space: pre-wrap;
            box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
        }
        .whatsapp-meta {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 4px;
        }
        .whatsapp-time {
            color: #667781;
            font-size: 11px;
            margin-right: 4px;
        }
        .whatsapp-ticks {
            color: #4fc3f7;
            font-size: 14px;
        }
        
        /* Telegram message style */
       .telegram-message {
            max-width: 75%;
            margin-left: auto; /* Changed from margin-right to left */
            margin-bottom: 15px;
        }
        .telegram-bubble {
            background: #ffffff;
            border-radius: 7.5px 0 7.5px 7.5px; /* Adjusted for right alignment */
            padding: 8px 12px;
            position: relative;
            color: #000;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 15px;
            line-height: 20px;
            word-wrap: break-word;
            white-space: pre-wrap;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
            border: 1px solid #e6e6e6;
        }
        .telegram-meta {
            display: flex;
            justify-content: flex-end; /* Keep time on the right */
            align-items: center;
            margin-top: 4px;
        }
        .telegram-time {
            color: #999999;
            font-size: 11px;
        }
        
        /* Default preview style */
        .default-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Templates</h1>
        </div>

        <form id="templateForm" action="{{ route('templates.store') }}" method="POST">
            @csrf

            <div class="form-group row">
                <label for="templateSelector" class="col-sm-2 col-form-label font-weight-bold">Template Type</label>
                <div class="col-sm-6">
                    <select class="form-control custom-select" id="templateSelector" name="template_type" onchange="SetEditorAndVariables(this.value)">
                        @foreach($templates as $template)
                            <option value="{{ $template->template_name }}"
                                {{ old('template_type') == $template->template_name ? 'selected' : '' }}>
                                {{ $template->template_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="editor-container">
                <div class="editor-column">
                    <div class="section-title">Template Content</div>
                    <div id="editor"></div>
                    <textarea name="content" id="template-content" hidden></textarea>
                    
                    <div class="mt-3">Template Variables</div>
                    <div class="d-flex gap-3 flex-wrap mb-4 mt-2 buttons-container" id="variablesContainer"> 
                    </div>
                    <button type="submit" class="btn btn-primary">Save Template</button>
                </div>
                
                <div class="preview-column">
                    <div class="section-title">Preview</div>
                    <div class="preview-content" id="previewContent">
                        <!-- Preview content will be displayed here -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        let quill;

        function formatTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            return `${hours}:${minutes} ${ampm}`;
        }

        function generateWhatsAppPreview(content) {
            return `
                <div class="whatsapp-message">
                    <div class="whatsapp-bubble">${content}</div>
                    <div class="whatsapp-meta">
                        <span class="whatsapp-time">${formatTime()}</span>
                        <span class="whatsapp-ticks">✓✓</span>
                    </div>
                </div>
            `;
        }

        function generateTelegramPreview(content) {
            return `
                <div class="telegram-message">
                    <div class="telegram-bubble">${content}</div>
                    <div class="telegram-meta">
                        <span class="telegram-time">${formatTime()}</span>
                    </div>
                </div>
            `;
        }

        function SetEditorAndVariables(value) {
            const variablesContainer = document.getElementById('variablesContainer');
            const previewContent = document.getElementById('previewContent');

            localStorage.setItem('selected_template_type', value);

            const templates = @json($templates);
            const currentTemplate = templates.find(template => template.template_name === value);
            variablesContainer.innerHTML = '';

            JSON.parse(currentTemplate.variables).forEach(variable => {
                const variableSpan = document.createElement('span');
                variableSpan.className = 'badge badge-pill badge-primary p-2 cursor-pointer click-animate';
                variableSpan.textContent = variable;
                variableSpan.onclick = () => addVariable(variable);
                variablesContainer.appendChild(variableSpan);
            });

            quill.setContents([]);
            quill.root.innerHTML = currentTemplate.content;
            
            let previewHtml;
            if (value.toLowerCase().startsWith('whatsapp')) {
                previewHtml = generateWhatsAppPreview(currentTemplate.content);
            } else if (value.toLowerCase().startsWith('telegram')) {
                previewHtml = generateTelegramPreview(currentTemplate.content);
            } else {
                previewHtml = `<div class="default-preview">${currentTemplate.content}</div>`;
            }
            
            previewContent.innerHTML = previewHtml;
        }   

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('templateForm');
            const contentField = document.getElementById('template-content');
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic'],
                    ]
                }
            });

            quill.on('text-change', function () {
                contentField.value = quill.root.innerHTML.trim();
                
                const previewContent = document.getElementById('previewContent');
                const templateName = document.getElementById('templateSelector').value.toLowerCase();
                let previewHtml;
                
                if (templateName.startsWith('whatsapp')) {
                    previewHtml = generateWhatsAppPreview(quill.root.innerHTML);
                } else if (templateName.startsWith('telegram')) {
                    previewHtml = generateTelegramPreview(quill.root.innerHTML);
                } else {
                    previewHtml = `<div class="default-preview">${quill.root.innerHTML}</div>`;
                }
                
                previewContent.innerHTML = previewHtml;
            });

            form.addEventListener('submit', function(e) {
                contentField.value = quill.root.innerHTML.trim();
            });

            $('#templateSelector').val(
                localStorage.getItem('selected_template_type') 
                ? localStorage.getItem('selected_template_type') 
                : @json($templates)[0].template_name
            );

            SetEditorAndVariables(
                localStorage.getItem('selected_template_type')? localStorage.getItem('selected_template_type') :
                @json($templates)[0].template_name
            );
        });
    </script>

    @verbatim
        <script>
            const Embed = Quill.import('blots/embed');

            class VariableBlot extends Embed {
                static create(variableName) {
                    const node = super.create();
                    node.setAttribute('data-name', variableName);
                    node.innerText = `{{${variableName}}}`;
                    node.setAttribute('contenteditable', 'false');
                    return node;
                }

                static value(node) {
                    return node.getAttribute('data-name');
                }
            }

            VariableBlot.blotName = 'variable';
            VariableBlot.tagName = 'span';
            VariableBlot.className = 'ql-variable-tag';

            Quill.register(VariableBlot);

            function addVariable(variableName) {
                if (!quill) return;

                const range = quill.getSelection(true);
                if (range) {
                    quill.insertEmbed(range.index, 'variable', variableName);
                    quill.setSelection(range.index + 1); // move cursor after variable
                }
            }
        </script>
    @endverbatim 

    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(@json($error));
            @endforeach
        @endif

        @if (session('status'))
            toastr.success(@json(session('status')));
        @endif
    </script>
@endpush