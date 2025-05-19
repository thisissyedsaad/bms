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
                            <h2 class="content-header-title float-left mb-0">Projects</h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
                    <div class="form-group breadcrum-right">
                        <a href="{{ route('projects.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i>
                            Add Project</a>
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
                <section id="filtering-section">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('projects.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="search">Search Projects</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                value="{{ request('search') }}" placeholder="Enter title, client name, etc.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="client_filter">Client</label>
                                            <select class="form-control" id="client_filter" name="client_id">
                                                <option value="">All Clients</option>
                                                @foreach($allClients as $client)
                                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                        {{ $client->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="project_type_filter">Project Type</label>
                                            <select class="form-control" id="project_type_filter" name="project_type">
                                                <option value="">All Types</option>
                                                <option value="fixed" {{ request('project_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                                <option value="hourly" {{ request('project_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status_filter">Status</label>
                                            <select class="form-control" id="status_filter" name="status">
                                                <option value="">All Statuses</option>
                                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Hold</option>
                                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_range">Date Range</label>
                                            <select class="form-control" id="date_range" name="date_range">
                                                <option value="">All Dates</option>
                                                <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                                <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                                <option value="last_year" {{ request('date_range') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                                                <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if(request('date_range') == 'custom')
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" disabled>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-block">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Project Title</th>
                                        <th>Client Name</th>
                                        <th>Project Type</th>
                                        <th>Assigned To</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Platform</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr>
                                            <td>{{ $project->title ?? 'N/A' }} </td>
                                            <td>
                                                <a href="{{ route('clients.show', $project->client->id) }}" class="mr-1" title="View">
                                                    {{ $project->client->full_name ?? 'N/A' }} 
                                                </a>
                                            </td>
                                            <td>{{ Str::ucfirst($project->project_type) ?? 'N/A' }}</td>
                                            <td>{{ Str::ucfirst($project->assigned_to) ?? 'N/A' }} </td>
                                            @php
                                                $formatDate = function($date) {
                                                    return $date ? \Carbon\Carbon::parse($date)->format('m-d-y') : 'N/A';
                                                };
                                            @endphp
                                            <td>{{ $formatDate($project->start_date) }}</td>
                                            <td>{{ $formatDate($project->end_date) }}</td>
                                            <td>{{ Str::ucfirst($project->platform) ?? 'N/A' }} </td>
                                            <td>
                                                @php
                                                    $status = strtolower($project->status);
                                                @endphp
                                                @if ($status === 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif ($status === 'ongoing')
                                                    <span class="badge badge-warning">Ongoing</span>
                                                @elseif ($status === 'hold')
                                                    <span class="badge badge-info">Hold</span>
                                                @elseif ($status === 'cancelled')
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @else
                                                    <span class="badge badge-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-icon mr-1"><i data-feather="edit-2"></i></a>
                                                    <a id="project_delete_id:{{ $project->id }}:{{ $project->title }}" onclick="deleteModel(this.id)" style="cursor: pointer;">
                                                        <i data-feather="trash-2" class="text-danger"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No projects found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $projects->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade modal-danger" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this client?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function deleteModel(clicked_id) {
        var model_id = clicked_id.split(":");
        var projectId = model_id[1];
        var projectName = model_id[2];
        
        const modal = $('#deleteModal');
        const form = $('#deleteForm');
        form.attr('action', '/admin/projects/' + projectId);
        modal.find('.modal-body').text(`Are you sure you want to delete project "${projectName}"?`);
        modal.modal('show');
    }

    // For Filter
    document.addEventListener('DOMContentLoaded', function () {
        const dateRangeSelect = document.getElementById('date_range');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function toggleCustomDateInputs() {
            startDateInput.disabled = dateRangeSelect.value !== 'custom';
            endDateInput.disabled = dateRangeSelect.value !== 'custom';
        }

        dateRangeSelect.addEventListener('change', toggleCustomDateInputs);
        toggleCustomDateInputs(); // Initialize on page load
    });
</script>
@endsection 