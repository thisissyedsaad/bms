@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                <h2 class="content-header-title">Clients</h2>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Add Client
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="content-body">
            <section>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('clients.index') }}" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="search">Search Clients</label>
                                    <input type="text" class="form-control" name="search" id="search" value="{{ request('search') }}" placeholder="Enter name, email, etc.">
                                </div>
                                <div class="form-group col-md-2 d-flex align-items-end">
                                </div>
                                <div class="form-group col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                                <div class="form-group col-md-2 d-flex align-items-end">
                                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-block">Reset</a>
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
                            <thead class="thead-light">
                                <tr>
                                    <th>Picture</th>
                                    <th>Client</th>
                                    <th>Email</th>
                                    <th>Whatsapp</th>
                                    <th>Mobile</th>
                                    <th>Source</th>
                                    <th>Is Active</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                    <tr>
                                        <td>
                                            @if($client->profile_photo)
                                                <img src="{{ Storage::url($client->profile_photo) }}" class="img-thumbnail" style="max-height: 50px;" alt="{{ $client->full_name }}">
                                            @else
                                                <span class="text-muted">No photo</span>
                                            @endif
                                        </td>
                                        <td>{{ $client->full_name ?? 'N/A' }}</td>
                                        <td>{{ $client->email ?? 'N/A' }}</td>
                                        <td>{{ $client->whatsapp_number ?? 'N/A' }}</td>
                                        <td>{{ $client->mobile_number ?? 'N/A' }}</td>
                                        <td>{{ Str::ucfirst($client->source->name) ?? 'N/A' }} ({{ Str::ucfirst($client->source->is_platform) == 1 ? 'Platform' : 'Non-platform' }}) </td>
                                        <td>
                                            @php
                                                $is_active = strtolower($client->is_active);
                                            @endphp
                                            @if ($is_active === '1')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $client->created_at->timezone('Asia/Karachi')->format('d-M-Y h:i A') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-icon mr-1" title="View"><i data-feather="eye"></i></a>
                                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-icon mr-1"><i data-feather="edit-2"></i></a>
                                                <a id="client_delete_id:{{ $client->id }}:{{ $client->full_name }}" onclick="deleteModel(this.id)" style="cursor: pointer;">
                                                    <i data-feather="trash-2" class="text-danger"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No clients found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $clients->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
        const [_, clientId, clientName] = clicked_id.split(":");
        $('#deleteForm').attr('action', '/admin/clients/' + clientId);
        $('#deleteModal .modal-body').text(`Are you sure you want to delete client "${clientName}"?`);
        $('#deleteModal').modal('show');
    }
</script>
@endsection
