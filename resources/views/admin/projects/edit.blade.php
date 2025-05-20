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
                        <h2 class="content-header-title float-left mb-0">Edit Project</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="client_id">Client</label>
                                    <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" id="client_id" required>
                                        <option value="" disabled {{ old('client_id', $project->client_id) ? '' : 'selected' }}>-- Select Client --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}
                                                {{ $client->is_active ? '' : 'disabled' }}
                                                style="{{ $client->is_active ? '' : 'background-color: #d0d0d0;' }}">
                                                {{ $client->full_name }} ({{ $client->profile_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Project Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $project->title) }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="conversion_rate">Conversion Rate</label>
                                    <input type="number" step="0.000001" class="form-control @error('conversion_rate') is-invalid @enderror"
                                        id="conversion_rate" name="conversion_rate" value="{{ old('conversion_rate', $project->conversion_rate) }}">
                                    @error('conversion_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_amount">Total Amount</label>
                                    <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror"
                                        id="total_amount" name="total_amount" value="{{ old('total_amount', $project->total_amount) }}">
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="received_amount">Received Amount</label>
                                    <input type="number" step="0.01" class="form-control @error('received_amount') is-invalid @enderror"
                                        id="received_amount" name="received_amount" value="{{ old('received_amount', $project->received_amount) }}">
                                    @error('received_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <input type="text" class="form-control @error('currency') is-invalid @enderror"
                                        id="currency" name="currency" value="{{ old('currency', $project->currency) }}">
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="project_type">Project Type</label>
                                    <select class="form-control @error('project_type') is-invalid @enderror" name="project_type" id="project_type">
                                        <option value="fixed" {{ old('project_type', $project->project_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="hourly" {{ old('project_type', $project->project_type) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    </select>
                                    @error('project_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hourly_rate">Hourly Rate</label>
                                    <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror"
                                        id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $project->hourly_rate) }}">
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estimated_hours">Estimated Hours</label>
                                    <input type="number" step="0.01" class="form-control @error('estimated_hours') is-invalid @enderror"
                                        id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours', $project->estimated_hours) }}">
                                    @error('estimated_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="assigned_to">Assigned To</label>
                                    <input type="text" class="form-control @error('assigned_to') is-invalid @enderror"
                                        id="assigned_to" name="assigned_to" value="{{ old('assigned_to', $project->assigned_to) }}">
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date" value="{{ old('end_date', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="source_id">Source</label>
                                    <select class="form-control @error('source_id') is-invalid @enderror" name="source_id" id="source_id" required>
                                        <option value="" disabled {{ old('source_id', $project->source_id) ? '' : 'selected' }}>-- Select Source --</option>
                                        @foreach($sources as $source)
                                            <option value="{{ $source->id }}"
                                                {{ old('source_id', $project->source_id) == $source->id ? 'selected' : '' }}
                                                {{ $source->is_active ? '' : 'disabled' }}
                                                style="{{ $source->is_active ? '' : 'background-color: #d0d0d0;' }}">
                                                {{ $source->name }} ({{ Str::title(str_replace('_', ' ', $source->commission_type)) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('source_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                        <option value="ongoing" {{ old('status', $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="hold" {{ old('status', $project->status) == 'hold' ? 'selected' : '' }}>Hold</option>
                                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea
                                        class="form-control editor @error('details') is-invalid @enderror"
                                        id="details"
                                        name="details">{{ old('details', $project->details) }}</textarea>
                                    @error('details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary">Update Project</button>
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

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

    </style>
    
    <script>
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#details'), {
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