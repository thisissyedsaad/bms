@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-left mb-0">Client Details</h2>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4 mb-2 text-center">
                            @if($client->profile_photo)
                                <img src="{{ asset('storage/' . $client->profile_photo) }}" class="img-fluid rounded" style="max-height: 200px;" alt="Profile Photo">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" class="img-fluid rounded" style="max-height: 200px;" alt="Default Photo">
                            @endif
                        </div>

                        <div class="col-md-8">
                            <h4>{{ $client->profile_name }}</h4>
                            <p><strong>Full Name:</strong> {{ $client->full_name }}</p>
                            <p><strong>Email:</strong> {{ $client->email }}</p>
                            <p><strong>Mobile Number:</strong> {{ $client->mobile_number }}</p>
                            <p><strong>WhatsApp Number:</strong> {{ $client->whatsapp_number }}</p>
                            <p><strong>Source:</strong> {{ $client->source->name }}</p>
                            <p><strong>Reference By:</strong> {{ $client->reference_by }}</p>
                            <p><strong>Tagline:</strong> {{ $client->tagline }}</p>
                            <p><strong>Website:</strong>
                                @if($client->website)
                                    <a href="{{ $client->website }}" target="_blank">{{ $client->website }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-2">
                        <h5>Profile Description</h5>
                        <p>{!! $client->profile_description !!}</p>
                    </div>

                    <hr>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <h5>Address Information</h5>
                            <p><strong>Address Line 1:</strong> {{ $client->address->address_line_1 }}</p>
                            <p><strong>Address Line 2:</strong> {{ $client->address->address_line_2 }}</p>
                            <p><strong>City:</strong> {{ $client->address->city }}</p>
                            <p><strong>Country:</strong> {{ $client->address->country->name }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <h4>Associated Projects</h4>

                        @if($projects->count())
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Project Title</th>
                                            <th>Project Type</th>
                                            <th>Assigned To</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $index => $project)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $project->title }}</td>
                                                <td>{{ Str::ucfirst($project->project_type) ?? 'N/A' }}</td>
                                                <td>{{ Str::ucfirst($project->assigned_to) ?? 'N/A' }} </td>
                                                @php
                                                    $formatDate = function($date) {
                                                        return $date ? \Carbon\Carbon::parse($date)->format('m-d-y') : 'N/A';
                                                    };
                                                @endphp
                                                <td>{{ $formatDate($project->start_date) }}</td>
                                                <td>{{ $formatDate($project->end_date) }}</td>
                                                <td>{{ Str::ucfirst($project->source->name) ?? 'N/A' }} ({{ Str::ucfirst($project->source->is_platform) == 1 ? 'Platform' : 'Non-platform' }}) </td>
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
                                                    <!-- <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">View</a> -->
                                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No projects found for this client.</p>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $projects->links('pagination::bootstrap-4') }}
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to Clients</a>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">Edit Client</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
