@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <x-filter.user />
            </div>
            <div class="content-body">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-body">
                                Error: {{ $error }}
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (\Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            {!! \Session::get('error') !!}
                        </div>
                    </div>
                @endif

                @if (\Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">
                            {!! \Session::get('success') !!}
                        </div>
                    </div>
                @endif
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6">
                                <h2>User Information</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex
                                    align-items-center justify-content-lg-end align-items-center flex-sm-nowrap
                                    flex-wrap mr-1">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button class="btn add-new btn-primary mt-50" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-toggle="modal"
                                            data-target="#modals-slide-in">
                                            <span>{{ trans('crud.create', ['model' => 'User']) }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0">
                            <table class="user-list-table table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        {{-- <th>Role</th> --}}
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        {{-- <td>{{$user->roles->pluck('name')->first()}}</td> --}}
                                        <td>{{ $user->is_active ? 'Enabled' : 'Disabled' }}</td>
                                        <td>
                                            @if ($user->email != 'admin@admin.com')
                                                <a id="user_edit:{{ $user->id }}"
                                                    href="{{ route('users.edit', ['user' => $user->id]) }}"><i
                                                        data-feather="edit"></i></a>
                                                <a id="user_delete_id:{{ $user->id }}:{{ $user->name }}"
                                                    onclick=deleteModel(this.id)><i data-feather="trash-2"
                                                        style="color:red;"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $users->links() }}
                        </div>
                        <!-- Modal to add new user starts-->
                        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST"
                                    action="{{ route('users.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="John Doe" name="name"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-email">Email</label>
                                            <input type="text" id="basic-icon-default-email"
                                                class="form-control dt-email" placeholder="john.doe@example.com"
                                                aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="email" />
                                            <small class="form-text text-muted"> You can use letters, numbers & periods
                                            </small>
                                        </div>
                                        {{-- <div class="form-group">
                                        <label class="form-label" for="user-role">User Role</label>
                                        <select id="user_role" name="user_role" class="select2 form-control">
                                            @foreach ($roles as $role)
                                            <option value="{{$role}}">{{$role}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                                        <button type="reset" class="btn btn-outline-secondary"
                                            data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Modal to add new user Ends-->
                    </div>
                    <!-- list section end -->

                    <!-- Modal -->
                    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel120" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel120">Delete User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete?
                                </div>
                                <form id="delete-user-form" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
            </div>
            </section>
            <!-- users list ends -->

        </div>
    </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        function deleteModel(id) {
            id = id.split(":")[1];
            $("#danger").modal();
            route = "{{ route('users.destroy', ['user' => ':id']) }}";
            // Replace :id with the actual value of id
            route = route.replace(':id', id);
            document.getElementById('delete-user-form').action = route;
        }
    </script>
@endSection
