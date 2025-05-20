<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Changelog - CheckMySite</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <!-- Quill Editor CSS -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding-top: 70px;
    }

    .custom-section {
      padding-top: 30px;
    }

    /* Navbar */
    .navbar {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      z-index: 1030;
    }

    /* Changelog content */
    .version-section {
      padding: 2rem 0;
      border-bottom: 1px solid #eee;
      scroll-margin-top: 100px;
    }

    .version-title {
      font-weight: 600;
      color: #6777ef;
      margin-bottom: 1rem;
    }

    .version-date {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .version-subtitle {
      font-weight: 500;
      color: #495057;
      margin-bottom: 1rem;
    }

    /* Sidebar */
    .changelog-sidebar {
      position: sticky;
      top: 100px;
      max-height: calc(100vh - 120px);
      overflow-y: auto;
    }

    .version-nav {
      max-height: 400px;
      overflow-y: auto;
    }

    .version-nav .nav-link {
      padding: 0.3rem 0.5rem;
      font-size: 0.85rem;
      color: #6c757d;
      cursor: pointer;
      display: flex;
      align-items: center;
    }

    /* Pagination */
    .pagination {
      justify-content: center;
      margin-top: 2rem;
    }

    .pagination .page-item {
      margin: 0 3px;
    }

    .pagination .page-link {
      color: #6777ef;
      border: 1px solid #dee2e6;
      border-radius: 4px;
    }

    .pagination .page-item.active .page-link {
      background-color: #6777ef;
      border-color: #6777ef;
      color: white;
    }

    .pagination-info {
      text-align: center;
      color: #6c757d;
      margin-bottom: 1rem;
    }

    .change-content {
      word-break: break-word;
    }

    /* Responsive - Sidebar above on mobile */
    @media (max-width: 992px) {
      .row {
        display: flex;
        flex-direction: column;
      }

      .col-lg-3 {
        order: -1;
        margin-bottom: 2rem;
      }

      .changelog-sidebar {
        position: static;
        max-height: none;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <div class="container">
      <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="/">
        <i class="fas fa-heartbeat me-2"></i>CheckMySite
      </a>
    </div>
  </nav>

  <!-- Main Section -->
  <section class="custom-section">
    <div class="container">
      <div class="action-buttons mb-4">
        <a href="/" class="btn btn-secondary">
          <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @hasrole('superadmin')
            <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addChangelogModal">
            <i class="fas fa-plus me-2"></i> Add Changelog
            </button>
        @endhasrole
      </div>

      <div class="text-center mb-5">
        <h2 class="page-title animate__animated animate__fadeInDown">CheckMySite Changelog</h2>
        <p class="text-muted animate__animated animate__fadeInUp">Track all updates and improvements</p>
      </div>

      <div class="row">
        <!-- Main changelog content -->
        <div class="col-lg-9">
          @foreach($changelogs as $changelog)
          <div id="v{{ str_replace('.', '-', $changelog->version) }}" class="version-section">
            <div class="d-flex gap-2 align-items-center flex-wrap">
              <h3 class="version-title mb-0">
                Version {{ $changelog->version }}
                @if($latestDate->eq($changelog->created_at))
                <span class="badge bg-primary ms-2">Latest</span>
                @endif
              </h3>

              @hasrole('superadmin')
                {{-- <form action="{{ route('changelog.destroy', $changelog->id) }}" method="POST" class="d-inline-block ms-auto" onsubmit="return confirm('Are you sure you want to delete version {{ $changelog->version }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger me-2">
                    <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form> --}}
                  
                <button type="button" class="btn btn-sm btn-danger me-2 d-inline-block ms-auto" data-bs-toggle="modal" data-bs-target="#deleteChangelogModal{{ $changelog->id }}">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
              
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editChangelogModal{{ $changelog->id }}">
                <i class="fas fa-edit"></i> Edit
              </button>
              @endhasrole
            </div>
            <p class="version-date mt-2">
              Released on {{ \Carbon\Carbon::parse($changelog->created_at)->format('j F, Y') }}
            </p>
            <h4 class="version-subtitle">{{ $changelog->title }}</h4>
            <div class="change-content">
              {!! $changelog->description !!}
            </div>
          </div>

          <!-- Edit Modal -->
          <div class="modal fade" id="editChangelogModal{{ $changelog->id }}" tabindex="-1" aria-labelledby="editChangelogModalLabel{{ $changelog->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form method="POST" action="{{ route('changelog.update', $changelog->id) }}" class="edit-changelog-form" data-id="{{ $changelog->id }}">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title" id="editChangelogModalLabel{{ $changelog->id }}">Edit Changelog - Version {{ $changelog->version }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="editVersionNumber{{ $changelog->id }}" class="form-label">Version Number</label>
                      <input type="text" class="form-control" id="editVersionNumber{{ $changelog->id }}" name="editversionNumber" value="{{ $changelog->version }}"  />
                       @error('editversionNumber')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                      <label for="editVersionTitle{{ $changelog->id }}" class="form-label">Version Title</label>
                      <input type="text" class="form-control" id="editVersionTitle{{ $changelog->id }}" name="editversionTitle" value="{{ $changelog->title }}"  />
                       @error('editversionTitle')
                            <div class="text-danger mt-1">{{ $message }}</div>
                       @enderror
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Description*</label>
                      <div id="edit-editor-container-{{ $changelog->id }}"></div>
                      <input type="hidden" id="edit-description-{{ $changelog->id }}" name="editdescription" />
                       @error('editdescription')
                        <div class="text-danger mt-1">{{ $message }}</div>
                       @enderror
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Changelog</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

      <!-- Delete Modal -->
<div class="modal fade" id="deleteChangelogModal{{ $changelog->id }}" tabindex="-1" aria-labelledby="deleteChangelogModalLabel{{ $changelog->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('changelog.destroy', $changelog->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title" id="deleteChangelogModalLabel{{ $changelog->id }}">Delete Changelog - Version {{ $changelog->version }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to permanently delete changelog version <strong>{{ $changelog->version }}</strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

          @endforeach

          <!-- Pagination -->
          {{-- @if ($changelogs->lastPage() > 1)
          <nav aria-label="Changelog pagination">
            <ul class="pagination">
              <li class="page-item {{ $changelogs->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $changelogs->previousPageUrl() }}" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              @for ($page = 1; $page <= $changelogs->lastPage(); $page++)
              <li class="page-item {{ $changelogs->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $changelogs->url($page) }}">{{ $page }}</a>
              </li>
              @endfor
              <li class="page-item {{ $changelogs->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $changelogs->nextPageUrl() }}" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
            <p class="pagination-info">Page {{ $changelogs->currentPage() }} of {{ $changelogs->lastPage() }}</p>
          </nav>
          @endif --}}
           @if ($changelogs->lastPage() > 1)
            <nav aria-label="Changelog pagination">
                <ul class="pagination">
                    <li class="page-item {{ $changelogs->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $changelogs->previousPageUrl() }}{{ $search ? '&search='.$search : '' }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @for ($page = 1; $page <= $changelogs->lastPage(); $page++)
                    <li class="page-item {{ $changelogs->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $changelogs->url($page) }}{{ $search ? '&search='.$search : '' }}">{{ $page }}</a>
                    </li>
                    @endfor
                    <li class="page-item {{ $changelogs->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $changelogs->nextPageUrl() }}{{ $search ? '&search='.$search : '' }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
                <p class="pagination-info">Page {{ $changelogs->currentPage() }} of {{ $changelogs->lastPage() }}</p>
            </nav>
            @endif
        </div>

        <!-- Sidebar -->
        {{-- <div class="col-lg-3">
          <div class="changelog-sidebar card p-3 shadow-sm rounded">
            <h5 class="mb-3">Versions</h5>
            <input type="text" id="searchVersion" class="form-control mb-3" placeholder="Search version..." aria-label="Search versions" />

            <nav id="versionNav" class="version-nav">
              @foreach($changelogs as $changelog)
              <a href="#v{{ str_replace('.', '-', $changelog->version) }}" class="nav-link version-link" data-version="{{ $changelog->version }}">
                {{ $changelog->version }}
              </a>
              @endforeach
            </nav>
          </div>
        </div> --}}

         <!-- Sidebar -->
<div class="col-lg-3">
    <div class="changelog-sidebar card p-3 shadow-sm rounded">
        <h5 class="mb-3">Versions</h5>
        {{-- <form id="versionSearchForm" method="GET" action="{{ route('changelog.page') }}"> --}}
                <form id="versionSearchForm" method="GET" action="{{ url()->current() }}">
                  <input type="text" id="searchVersion" name="search" class="form-control mb-3" 
                        placeholder="Search version..." aria-label="Search versions"
                        value="{{ $search ?? '' }}" />
              </form>

              <!-- NEW VERSION NAVIGATION -->
              <nav id="versionNav" class="version-nav">
                  @if($search ?? false)
                      <!-- When searching, show all matching versions -->
                      @foreach($changelogs as $changelog)
                          <a href="{{ $changelogs->url($changelogs->currentPage()) }}#v{{ str_replace('.', '-', $changelog->version) }}" 
                            class="nav-link version-link" 
                            data-version="{{ $changelog->version }}">
                              {{ $changelog->version }}
                          </a>
                      @endforeach
                  @else
                      <!-- Normal paginated view -->
                      @foreach($changelogs as $changelog)
                          <a href="#v{{ str_replace('.', '-', $changelog->version) }}" 
                            class="nav-link version-link" 
                            data-version="{{ $changelog->version }}">
                              {{ $changelog->version }}
                          </a>
                      @endforeach
                  @endif
              </nav>
          </div>
      </div>

      </div>
    </div>
  </section>

  <!-- Add Changelog Modal -->
  <div class="modal fade" id="addChangelogModal" tabindex="-1" aria-labelledby="addChangelogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST" action="{{ route('add.changelog') }}" id="addChangelogForm">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addChangelogModalLabel">Add Changelog</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="addVersionNumber" class="form-label">Version Number</label>
              <input type="text" class="form-control @error('versionNumber') is-invalid @enderror" id="addVersionNumber" name="versionNumber" value="{{ old('versionNumber') }}" placeholder="e.g., 1.0.0"  />
              @error('versionNumber')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="addVersionTitle" class="form-label">Version Title</label>
              <input type="text" class="form-control @error('versionTitle') is-invalid @enderror" value="{{ old('versionTitle') }}" id="addVersionTitle" name="versionTitle" placeholder="Short description"  />
              @error('versionTitle')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Description*</label>
              <div id="add-editor-container" class="@error('description') is-invalid @enderror"></div>
              <input type="hidden" id="add-description" name="description" value="{{ old('description') }}"/>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Changelog</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

  <script>
    // Initialize Quill editor for Add modal
    const addQuill = new Quill('#add-editor-container', {
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link'],
          ['clean']
        ]
      },
      placeholder: 'Describe the changes in detail...',
      theme: 'snow'
    });

    function stripTags(original) {
      return original.replace(/(<([^>]+)>)/gi, "");
    }

    // On Add form submit, sync Quill content to hidden input
    document.getElementById('addChangelogForm').addEventListener('submit', function(e) {
      const value= addQuill.root.innerHTML.trim();
      if(stripTags(value).trim()===''){
        document.getElementById('add-description').value = '';
      }else{
        document.getElementById('add-description').value = value;
      } 
    });

    // Keep track of initialized edit editors to avoid duplicates
    const editEditors = {};

    // Function to initialize or get Quill editor for a given changelog ID
    function getEditQuill(id) {
      if (!editEditors[id]) {
        editEditors[id] = new Quill(`#edit-editor-container-${id}`, {
          modules: {
            toolbar: [
              [{ header: [1, 2, 3, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ list: 'ordered' }, { list: 'bullet' }],
              ['link'],
              ['clean']
            ]
          },
          placeholder: 'Describe the changes in detail...',
          theme: 'snow'
        });

        // On edit form submit, sync Quill content
        const form = document.querySelector(`form.edit-changelog-form[data-id="${id}"]`);
        form.addEventListener('submit', function(e) {
          const descInput = document.getElementById(`edit-description-${id}`);
          descInput.value = editEditors[id].root.innerHTML.trim();

          if (stripTags(descInput.value).trim()==='') {
            descInput.value = ''
          }
        });
      }
      return editEditors[id];
    }

    // When an edit modal is shown, populate the fields and initialize the editor
    document.querySelectorAll('[id^=editChangelogModal]').forEach(modal => {
      modal.addEventListener('show.bs.modal', event => {
        const id = modal.id.replace('editChangelogModal', '');

        // Get changelog data from the modal's form inputs (already filled by blade)
        const versionInput = document.getElementById(`editVersionNumber${id}`);
        const titleInput = document.getElementById(`editVersionTitle${id}`);
        const descriptionInput = document.getElementById(`edit-description-${id}`);

        // Initialize editor
        const quill = getEditQuill(id);

        // Set Quill content from hidden input or from original description div
        if (descriptionInput.value) {
          quill.root.innerHTML = descriptionInput.value;
        } else {
          // Fallback - if hidden input empty, find original changelog description in main content and set it
          const originalDesc = document.querySelector(`#v${versionInput.value.replace(/\./g, '-')} .change-content`);
          if (originalDesc) {
            quill.root.innerHTML = originalDesc.innerHTML.trim();
          } else {
            quill.root.innerHTML = '';
          }
          descriptionInput.value = quill.root.innerHTML;
        }

        // Also sync version and title inputs (probably redundant since blade fills them)
        versionInput.value = versionInput.value;
        titleInput.value = titleInput.value;
      });
    });

    // Version sidebar search filter
    // document.getElementById('searchVersion').addEventListener('input', function() {
    //   const filter = this.value.toLowerCase();
    //   document.querySelectorAll('.version-link').forEach(link => {
    //     const version = link.getAttribute('data-version').toLowerCase();
    //     if (version.includes(filter)) {
    //       link.style.display = '';
    //     } else {
    //       link.style.display = 'none';
    //     }
    //   });
    // });



     // Version sidebar search filter
        let searchTimer;
        document.getElementById('searchVersion').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('versionSearchForm').submit();
            }, 500);
        });
  </script>
</body>
</html>
