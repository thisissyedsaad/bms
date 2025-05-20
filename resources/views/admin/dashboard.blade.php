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
                            <h2 class="content-header-title float-left mb-0">Dashboard</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif
                <!-- Dashboard Stats -->
                <section id="dashboard-stats">
                    <div class="row">
                        <!-- Total Clients -->
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="card text-center shadow rounded-lg border-0 bg-primary text-white">
                                <div class="card-body">
                                    <i data-feather="users" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalClients}}</p>
                                    <p class="mb-0">Total Clients</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Projects -->
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="card text-center shadow rounded-lg border-0 bg-success text-white">
                                <div class="card-body">
                                    <i data-feather="briefcase" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalProjects}}</p>
                                    <p class="mb-0">Total Projects</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Projects -->
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="card text-center shadow rounded-lg border-0 bg-warning text-white">
                                <div class="card-body">
                                    <i data-feather="activity" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalActiveProjects}}</p>
                                    <p class="mb-0">Active Projects</p>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Projects -->
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="card text-center shadow rounded-lg border-0 bg-info text-white">
                                <div class="card-body">
                                    <i data-feather="check-circle" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalCompletedProjects}}</p>
                                    <p class="mb-0">Completed Projects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
