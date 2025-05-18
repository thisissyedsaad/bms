@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
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
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Account Information</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('users.update', [$user->id]) }}" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <input type="hidden" value="{{ $user->id }}" name="user_id" />
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="username">Full name</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Username" value="{{ $user->name }}"
                                                                name="name" id="name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="name">E-mail</label>
                                                            <input type="email" class="form-control" placeholder="email"
                                                                value="{{ $user->email }}" name="email" id="email" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control select2" id="is_active"
                                                                name="is_active">
                                                                <option value="1"
                                                                    {{ $user->is_active == 1 ? 'selected="selected"' : '' }}>
                                                                    Enabled</option>
                                                                <option value="0"
                                                                    {{ $user->is_active == 0 ? 'selected="selected"' : '' }}>
                                                                    Disabled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="user-role">User Role</label>
                                                    <select id="user_role" name="user_role"  class="form-control select2">
                                                        @foreach ($roles as $role)

                                                            <option value="{{$role}}" {{( $role == $user_role) ? 'selected="selected"' : ''}}>{{$role}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> --}}
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <div
                                                                class="input-group input-group-merge form-password-toggle ">
                                                                <input type="password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    aria-describedby="login-password" name="password"
                                                                    id="password" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="confirm-password">Confirm Password</label>
                                                            <div
                                                                class="input-group input-group-merge form-password-toggle ">
                                                                <input type="password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    aria-describedby="login-password"
                                                                    name="password_confirmation" id="confirm-password" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-sm-row flex-column mt-1">
                                                        <div class="form-group">
                                                            <output name="result" id="result"
                                                                style="color:red"></output>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <button type="submit" id='submit'
                                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>

                </section>
                <!-- users edit ends -->

            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="password"]').keyup(function() {
                var password = $('#password').val();
                var confirm_password = $('#confirm-password').val();
                if (password == confirm_password) {
                    $('#result').html('');
                    $('#submit').prop('disabled', false);
                } else {
                    $('#result').html('Passwords do not match!');
                    $('#submit').prop('disabled', true);
                }
            });
        });
    </script>
@endSection
