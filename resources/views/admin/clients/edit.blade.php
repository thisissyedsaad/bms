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
                            <h2 class="content-header-title float-left mb-0">Edit client</h2>
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
                    <div class="card-header">
                        <h4 class="card-title">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <form id="clientForm" action="{{ route('clients.update', $client) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                            id="full_name" name="full_name"
                                            value="{{ old('full_name', $client->full_name) }}" >
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profile_name">Profile Name</label>
                                        <input type="text" class="form-control @error('profile_name') is-invalid @enderror"
                                            id="profile_name" name="profile_name"
                                            value="{{ old('profile_name', $client->profile_name) }}" >
                                        @error('profile_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $client->email) }}" >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo</label>
                                        @if($client->profile_photo)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($client->profile_photo) }}"
                                                    alt="{{ $client->full_name }}" class="img-thumbnail"
                                                    style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('profile_photo') is-invalid @enderror"
                                                id="profile_photo" name="profile_photo" accept="image/*">
                                            <label class="custom-file-label" for="profile_photo">Choose new file</label>
                                        </div>
                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone Numbers Row -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile_number">Mobile Number</label>
                                                <input type="tel"
                                                    class="form-control phone-input @error('mobile_number') is-invalid @enderror"
                                                    id="mobile_number" name="mobile_number"
                                                    value="{{ old('mobile_number', $client->mobile_number) }}">
                                                @error('mobile_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="whatsapp_number">WhatsApp Number</label>
                                                <input type="tel"
                                                    class="form-control phone-input @error('whatsapp_number') is-invalid @enderror"
                                                    id="whatsapp_number" name="whatsapp_number"
                                                    value="{{ old('whatsapp_number', $client->whatsapp_number) }}">
                                                @error('whatsapp_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="platform">Platform</label>
                                        <input type="text" class="form-control @error('platform') is-invalid @enderror"
                                            id="platform" name="platform" value="{{ old('platform', $client->platform) }}"
                                            maxlength="50">
                                        @error('platform')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_by">Reference By</label>
                                        <input type="text" class="form-control @error('reference_by') is-invalid @enderror"
                                            id="reference_by" name="reference_by" value="{{ old('reference_by', $client->reference_by) }}">
                                        @error('reference_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tagline">Tagline</label>
                                        <input type="text" class="form-control @error('tagline') is-invalid @enderror"
                                            id="tagline" name="tagline" value="{{ old('tagline', $client->tagline) }}"
                                            maxlength="50">
                                        <small class="text-muted">Maximum 50 characters</small>
                                        @error('tagline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" value="{{ old('website', $client->website) }}"
                                            placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="profile_description">Profile Description</label>
                                        <textarea
                                            class="form-control editor @error('profile_description') is-invalid @enderror"
                                            id="profile_description"
                                            name="profile_description">{{ old('profile_description', $client->profile_description) }}</textarea>
                                        @error('profile_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address Section -->
                                <div class="col-12 mt-2">
                                    <h4>Address Information</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line_1">Address Line 1</label>
                                        <input type="text" class="form-control @error('address_line_1') is-invalid @enderror"
                                            id="address_line_1" name="address_line_1" 
                                            value="{{ old('address_line_1', $client->address->address_line_1 ?? '') }}">
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('address_line_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line_2">Address Line 2</label>
                                        <input type="text" class="form-control @error('address_line_2') is-invalid @enderror"
                                            id="address_line_2" name="address_line_2" 
                                            value="{{ old('address_line_2', $client->address->address_line_2 ?? '') }}">
                                        <small class="text-muted">Not visible publicly</small>
                                        @error('address_line_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $client->address->city ?? '') }}">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="show_city" id="show_city" value="1"
                                                {{ old('show_city', $client->address && $client->address->show_city ? 'checked' : '') }}>
                                            <label class="form-check-label" for="show_city">
                                                Show city publicly
                                            </label>
                                        </div>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country_id">Country</label>
                                        <select class="form-control @error('country_id') is-invalid @enderror" id="country_id" name="country_id">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id', $client->address->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="show_country" id="show_country" value="1"
                                                {{ old('show_country', $client->address && $client->address->show_country ? 'checked' : '') }}>
                                            <label class="form-check-label" for="show_country">
                                                Show country publicly
                                            </label>
                                        </div>
                                        @error('country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary mr-1">Update</button>
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Check if any service checkboxes have error after validation
            const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]');
            let hasServiceError = document.querySelector('.text-danger.small');
            
            if (hasServiceError) {
                // Scroll to the services section
                const servicesSection = document.querySelector('.form-group label.font-weight-bold');
                if (servicesSection) {
                    servicesSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            // Check for app links values
            const appLinksInputs = document.querySelectorAll('input[name^="app_links["]');
            let hasAppLinkValues = false;
            appLinksInputs.forEach(input => {
                if (input.value) {
                    hasAppLinkValues = true;
                }
            });
            
            // Check for payment links values
            const paymentLinksInputs = document.querySelectorAll('input[name^="payment_links["]');
            let hasPaymentLinkValues = false;
            paymentLinksInputs.forEach(input => {
                if (input.value) {
                    hasPaymentLinkValues = true;
                }
            });
            
            // Check for social links values
            const socialLinksInputs = document.querySelectorAll('input[name^="social_links["]');
            let hasSocialLinkValues = false;
            socialLinksInputs.forEach(input => {
                if (input.value) {
                    hasSocialLinkValues = true;
                }
            });
            
            // No modals are auto-opened in edit view
        });

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
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name;
                var label = e.target.nextElementSibling;
                label.innerHTML = fileName;
            });
        });

        // Handle form submission to include full international number
        document.getElementById('clientForm').addEventListener('submit', function(e) {
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
            .create(document.querySelector('#profile_description'), {
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