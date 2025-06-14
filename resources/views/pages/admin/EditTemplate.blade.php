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

            <div class="form-group">
                <label for="editor">Template Content</label>
                <div id="editor"></div>
                <textarea name="content" id="template-content" hidden></textarea>
            </div>
            <div>Template Variables</div>
            <div class="d-flex gap-3 flex-wrap mb-4 mt-2 buttons-container" id="variablesContainer"> 
            </div>
            <button type="submit" class="btn btn-primary">Save Template</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        let quill;

       
        function SetEditorAndVariables(value) {
            const variablesContainer = document.getElementById('variablesContainer');

            localStorage.setItem('selected_template_type', value); // Store selected template type in localStorage

            const templates = @json($templates);
            const currentTemplate= templates.find(template => template.template_name === value);
            variablesContainer.innerHTML = ''; // Clear existing variables

            JSON.parse(currentTemplate.variables).forEach(variable => {
                const variableSpan = document.createElement('span');
                variableSpan.className = 'badge badge-pill badge-primary p-2 cursor-pointer click-animate';
                variableSpan.textContent = variable;
                variableSpan.onclick = () => addVariable(variable);
                variablesContainer.appendChild(variableSpan);
            });

            quill.setContents([]); // Clear the editor content
            quill.root.innerHTML=currentTemplate.content; // Clear the editor text
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

            // Update hidden textarea whenever text changes
            quill.on('text-change', function () {
                contentField.value = quill.root.innerHTML.trim();
            });

            // Add form submit handler to ensure content is updated
            form.addEventListener('submit', function(e) {
                // Force update content before submission
                contentField.value = quill.root.innerHTML.trim();
            });



            //set the default template type from localStorage or the first template
            $('#templateSelector').val(
                localStorage.getItem('selected_template_type') 
                ? localStorage.getItem('selected_template_type') 
                : @json($templates)[0].template_name
            );

            //set value in the editor and variables initially
            SetEditorAndVariables(
                localStorage.getItem('selected_template_type')? localStorage.getItem('selected_template_type') :
                @json($templates)[0].template_name
            ); // Populate with the first template by default
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
