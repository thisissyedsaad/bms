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
                            <div class="card text-center shadow rounded-lg border-0 bg-secondary text-white">
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
                            <div class="card text-center shadow rounded-lg border-0 bg-success text-white">
                                <div class="card-body">
                                    <i data-feather="check-circle" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalCompletedProjects}}</p>
                                    <p class="mb-0">Completed Projects</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cancelled Projects -->
                        <!-- <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="card text-center shadow rounded-lg border-0 bg-info text-white">
                                <div class="card-body">
                                    <i data-feather="check-circle" class="font-large-2 mb-1"></i>
                                    <p class="font-weight-bolder">{{$totalCancelledProjects}}</p>
                                    <p class="mb-0">Cancelled Projects</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </section>
                

                <section class="mt-3">
                    <div class="row">
                        <!-- Line Chart for Monthly Projects -->
                        <div class="col-xl-6 col-md-12">
                            <div class="card shadow rounded-lg">
                                <div class="card-header bg-transparent">
                                    <h4 class="card-title">Monthly Projects Trend</h4>
                                </div>
                                <div class="card-body" style="height: 300px;">
                                    <canvas id="monthlyProjectsChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Doughnut Chart for Project Status -->
                        <div class="col-xl-6 col-md-12">
                            <div class="card shadow rounded-lg">
                                <div class="card-header bg-transparent">
                                    <h4 class="card-title">Project Status Overview</h4>
                                </div>
                                <div class="card-body" style="height: 300px;">
                                    <canvas id="projectStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Monthly Projects Chart
            const ctxProjects = document.getElementById('monthlyProjectsChart').getContext('2d');
            const monthlyProjectsChart = new Chart(ctxProjects, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [{
                        label: 'Projects Created',
                        data: {!! json_encode($monthlyCounts) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: '#36A2EB',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // Project Status Chart
            const ctxStatus = document.getElementById('projectStatusChart').getContext('2d');
            const projectStatusChart = new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Completed', 'Cancelled', 'Hold'],
                    datasets: [{
                        label: 'Project Status',
                        data: [{{$totalActiveProjects}}, {{$totalCompletedProjects}}, {{$totalCancelledProjects}}, {{$totalHoldProjects}}],
                        backgroundColor: ['#FFC107', '#28C76F', '#EA5455', '#00CFE8'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        </script>
@endsection