<div>
    @include('livewire.templates.loading-state-indicator')

    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
            <div class="card card-dashed">
                <div class="card-header">
                    <h3 class="card-title">Pending Request</h3>
                </div>
                <div class="card-body text-center" style="font-size: 50px;">
                    {{ $pending_requests }}
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
            <div class="card card-dashed">
                <div class="card-header">
                    <h3 class="card-title">Forwarded Request</h3>
                </div>
                <div class="card-body text-center" style="font-size: 50px;">
                    {{ $forwarded_requests }}
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
            <div class="card card-dashed">
                <div class="card-header">
                    <h3 class="card-title">Completed Request</h3>
                </div>
                <div class="card-body text-center" style="font-size: 50px;">
                    {{ $completed_requests }}
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
            <div class="card card-dashed">
                <div class="card-header">
                    <h3 class="card-title">Total Requests</h3>
                </div>
                <div class="card-body text-center" style="font-size: 50px;">
                    {{ $total_requests }}
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row pt-5 g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
            <div class="card card-dashed">
                <div class="card-header">
                    <h3 class="card-title">Incoming Requests <span class=""></span></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th>No.</th>
                                    <th>Date Requested</th>
                                    <th>Office/Brgy/Org</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incoming_requests as $item)
                                <tr style="cursor: pointer" onclick="window.location='{{ route('incoming.requests') }}'">
                                    <td>{{ !is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "" ? $item->incomingRequest->incoming_request_no : $item->incoming_request_no }}</td>
                                    <td>{{ !is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "" ? $item->incomingRequest->formatted_date_requested : $item->formatted_date_requested }}</td>
                                    <td>{{ !is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "" ? $item->incomingRequest->office_or_barangay_or_organization_name : $item->office_or_barangay_or_organization_name }}</td>
                                    <td>{{ !is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "" ? $item->incomingRequest->category->incoming_request_category_name : $item->category->incoming_request_category_name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="5">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 mb-5">
                            <!-- Links() -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>