@extends('layouts.admin')

@section('title', 'File Manager - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">File Manager</h3>
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('files.index') }}" class="text-decoration-none">Root</a></li>
                    @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item"><a href="{{ route('files.index', ['folder' => $crumb->id]) }}" class="text-decoration-none">{{ $crumb->name }}</a></li>
                    @endforeach
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('file.upload')
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                <i class="bi bi-folder-plus me-1"></i>New Folder
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload me-1"></i>Upload Files
            </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Folders -->
    @if($folders->count() > 0)
    <h6 class="fw-bold text-muted mb-3"><i class="bi bi-folder me-1"></i>Folders</h6>
    <div class="row g-3 mb-4">
        @foreach($folders as $folder)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('files.index', ['folder' => $folder->id]) }}" class="card border-0 shadow-sm text-decoration-none folder-card h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-folder-fill fs-1" style="color: {{ $folder->color }}"></i>
                    <p class="fw-semibold mb-0 mt-2 text-dark small">{{ $folder->name }}</p>
                    <small class="text-muted">{{ $folder->files_count }} files</small>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Files -->
    <h6 class="fw-bold text-muted mb-3"><i class="bi bi-files me-1"></i>Files</h6>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Size</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th class="pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $file)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $iconMap = [
                                            'pdf' => 'bi-file-pdf text-danger',
                                            'doc' => 'bi-file-word text-primary',
                                            'docx' => 'bi-file-word text-primary',
                                            'xls' => 'bi-file-excel text-success',
                                            'xlsx' => 'bi-file-excel text-success',
                                            'jpg' => 'bi-file-image text-warning',
                                            'jpeg' => 'bi-file-image text-warning',
                                            'png' => 'bi-file-image text-warning',
                                            'zip' => 'bi-file-zip text-secondary',
                                        ];
                                        $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                        $icon = $iconMap[$ext] ?? 'bi-file-earmark text-muted';
                                    @endphp
                                    <i class="bi {{ $icon }} fs-4"></i>
                                    <span class="fw-semibold small">{{ $file->original_name }}</span>
                                </div>
                            </td>
                            <td class="small text-muted">{{ $file->human_size }}</td>
                            <td class="small">{{ $file->uploader->name ?? 'Unknown' }}</td>
                            <td class="small text-muted">{{ $file->created_at->format('M d, Y') }}</td>
                            <td class="pe-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    @can('file.delete')
                                    <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Delete this file?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                No files uploaded yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($files->hasPages())
        <div class="card-footer bg-white border-0">{{ $files->links() }}</div>
        @endif
    </div>
</div>

<!-- New Folder Modal -->
<div class="modal fade" id="newFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Create Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('files.store') }}" method="POST">
                @csrf
                <input type="hidden" name="create_folder" value="1">
                <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Folder Name</label>
                        <input type="text" class="form-control" name="folder_name" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Files</label>
                        <input type="file" class="form-control" name="files[]" multiple required>
                        <small class="text-muted">Max 20MB per file</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .folder-card { transition: transform 0.2s ease; }
    .folder-card:hover { transform: translateY(-3px); }
</style>
@endpush
@endsection
