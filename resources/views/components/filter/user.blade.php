<div class="accordion">
    <div class="accordion-header" onclick="toggleAccordion('panel1')">
        <i data-feather="filter" class="mr-1"></i>Filter
    </div>
    <div id="panel1" class="card accordion-content @if (Request::query('name') || Request::query('email') || Request::query('status')) active @endif">
        <form action="">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Name </label>
                            <input value="{{ Request::query('name') }}" name="name" placeholder="Search by name"
                                type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email </label>
                            <input value="{{ Request::query('email') }}" name="email" placeholder="Search by Email"
                                type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select name="status" class="form-control">
                                <option value="" disabled selected> -- Select Status -- </option>
                                <option value="1" {{ Request::query('status') == '1' ? 'selected' : null }}>
                                    Enabled
                                </option>
                                <option value="0" {{ Request::query('status') == '0' ? 'selected' : null }}>
                                    Disabled
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        @if (Request::query('name') || Request::query('email') || Request::query('status'))
                            <a href="{{ route('users.index') }}" class="btn btn-outline-primary mr-1">
                                Reset
                            </a>
                        @endif
                        <button style="width: 100px;" type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
