@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Edit Source</h2>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <div class="alert-body">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="content-body">
                <!-- Basic Info -->
                <div class="card">
                    <div class="card-body">
                        <form id="sourceForm" action="{{ route('sources.update', $source) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name"
                                            value="{{ old('name', $source->name) }}" >
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="commission_type">Commission Type</label>
                                        <select class="form-control @error('commission_type') is-invalid @enderror"
                                            id="commission_type" name="commission_type">
                                            <option value="on_cost" {{ old('commission_type', $source->commission_type) == 'on_cost' ? 'selected' : '' }}>On Cost</option>
                                            <option value="on_profit" {{ old('commission_type', $source->commission_type) == 'on_profit' ? 'selected' : '' }}>On Profit</option>
                                            <option value="fixed" {{ old('commission_type', $source->commission_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        </select>
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('commission_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="commission_value">Commission Value</label>
                                        <input type="text" class="form-control @error('commission_value') is-invalid @enderror"
                                            id="commission_value" name="commission_value"
                                            value="{{ old('commission_value', $source->commission_value) }}" >
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('commission_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                              
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="is_platform" id="is_platform" value="1"
                                                {{ old('is_platform', $source->is_platform ? 'checked' : '') }}>
                                            <label class="form-check-label" for="is_platform">
                                                Is Platform ?
                                            </label>
                                        </div>
                                        @error('is_platform')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                                {{ old('is_active', $source->is_active ? 'checked' : '') }}>
                                            <label class="form-check-label" for="is_active">
                                                Is Active ?
                                            </label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>                           

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea
                                            class="form-control editor @error('notes') is-invalid @enderror"
                                            id="notes"
                                            name="notes">{{ old('notes', $source->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>                               
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary mr-1">Update</button>
                                <a href="{{ route('sources.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <style>
        .ck-editor__editable {
            min-height: 400px !important;
            max-height: 800px !important;
        }
        .iti { width: 100%; }
        .app-link-item, .payment-link-item, .social-link-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .custom-file-label::after {
            content: "Browse";
        }
    </style>
    
    <script>
    
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#notes'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
@endsection