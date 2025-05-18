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
                            <h2 class="content-header-title float-left mb-0">Clients</h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
                    <div class="form-group breadcrum-right">
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i>
                            Add Client</a>
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
                            <form action="{{ route('clients.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="search">Search Clients</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                   value="{{ request('search') }}" placeholder="Enter name, email, etc.">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="platform_filter">Platform</label>
                                            <select class="form-control" id="platform_filter" name="platform">
                                                <option value="">All Platforms</option>
                                                <option value="direct" {{ request('platform') == 'direct' ? 'selected' : '' }}>Direct</option>
                                                <option value="upwork" {{ request('platform') == 'upwork' ? 'selected' : '' }}>Upwork</option>
                                                <option value="reference" {{ request('platform') == 'reference' ? 'selected' : '' }}>Reference</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-block">Reset</a>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Picture</th>
                                        <th>Clients</th>
                                        <th>Email</th>
                                        <th>Whatsapp</th>
                                        <th>Mobile</th>
                                        <th>Platform</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clients as $client)
                                        <tr>
                                            <td>
                                                @if($client->profile_photo)
                                                    <img src="{{ Storage::url($client->profile_photo) }}" 
                                                        alt="{{ $client->full_name }}"
                                                        class="img-thumbnail" style="max-height: 50px;">
                                                @else
                                                    <span class="text-muted">No photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $client->full_name ?? 'N/A' }} </td>
                                            <td>{{ $client->email ?? 'N/A' }} </td>
                                            <td>{{ $client->whatsapp_number ?? 'N/A' }}</td>
                                            <td>{{ $client->mobile_number ?? 'N/A' }} </td>
                                            <td>{{ $client->platform ?? 'N/A' }} </td>
                                            <td>{{ $client->created_at->timezone('Asia/Karachi')->format('d-M-Y h:i A') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('clients.show', $client) }}" class="mr-1" title="View">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('clients.edit', $client) }}" class="mr-1">
                                                        <i data-feather="edit-2"></i>
                                                    </a>
                                                    <a id="client_delete_id:{{ $client->id }}:{{ $client->profile_name }}"
                                                        onclick="deleteModel(this.id)" style="cursor: pointer;">
                                                        <i data-feather="trash-2" style="color:red;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No clients found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $clients->links() }}
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
        var clientId = model_id[1];
        var clientName = model_id[2];
        
        const modal = $('#deleteModal');
        const form = $('#deleteForm');
        form.attr('action', '/admin/clients/' + clientId);
        modal.find('.modal-body').text(`Are you sure you want to delete client "${clientName}"?`);
        modal.modal('show');
    }
</script>
@endsection 