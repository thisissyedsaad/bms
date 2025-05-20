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
                            <h2 class="content-header-title float-left mb-0">Add New Source</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <form id="sourceForm" action="{{ route('sources.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}">
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
                                            <option value="on_cost">On Cost</option>
                                            <option value="on_profit">On Profit</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                        @error('commission_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="commission_value">Commission Value (%)</label>
                                        <input type="number" step="0.01" class="form-control @error('commission_value') is-invalid @enderror"
                                            id="commission_value" name="commission_value" value="{{ old('commission_value') }}">
                                        @error('commission_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>       
                               
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <!-- <label for="is_platform">Is Platform</label> -->
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="is_platform" id="is_platform"
                                                value="1" {{ old('is_platform') ? 'checked' : '' }}>
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
                                        <!-- <label for="is_active">Is Active</label> -->
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                                value="1" {{ old('is_active') ? 'checked' : '' }}>
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
                                            name="notes">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary mr-1">Add</button>
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

        .iti {
            width: 100%;
        }

        .custom-file-label::after {
            content: "Browse";
        }
    </style>
    <script>
        // Initialize phone inputs
        const phoneInputs = document.querySelectorAll('.phone-input');
        const phoneInstances = [];
        phoneInputs.forEach(input => {
            const instance = window.intlTelInput(input, {
                preferredCountries: ['us', 'gb'],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });
            phoneInstances.push(instance);
        });

        // File input display
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name;
                var label = e.target.nextElementSibling;
                label.innerHTML = fileName;
            });
        });

        // Handle form submission to include full international number
        document.getElementById('sourceForm').addEventListener('submit', function (e) {
            // Get the full international number for both phone fields
            const mobileInput = document.getElementById('mobile_number');
            const whatsappInput = document.getElementById('whatsapp_number');

            if (mobileInput) {
                const mobileInstance = phoneInstances[0]; // First phone input is mobile
                if (mobileInstance && mobileInput.value.trim() !== '') {
                    mobileInput.value = mobileInstance.getNumber(); // Get full international number
                }
            }

            if (whatsappInput) {
                const whatsappInstance = phoneInstances[1]; // Second phone input is whatsapp
                if (whatsappInstance && whatsappInput.value.trim() !== '') {
                    whatsappInput.value = whatsappInstance.getNumber(); // Get full international number
                }
            }
        });

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