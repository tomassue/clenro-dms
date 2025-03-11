<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-9">
            <!--begin::Mixed Widget 5-->
            <div class="card card-xxl-stretch">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Incoming Requests</span>
                        <!-- <span class="text-muted fw-bold fs-7">Manage users</span> -->
                    </h3>
                    <div class="card-toolbar">
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column">
                    <!--begin: FILTER SELECT -->
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-2">
                            <div class="col-12 mb-10">
                                <label class="fw-bold fs-6 mb-2">Filter</label>
                                <select class="form-select text-capitalize" aria-label="Select example" wire:model.live="filter_status">
                                    <option value="">-Status-</option>
                                    @foreach($status_select as $item)
                                    <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-light" wire:click="clear">Clear</button>
                        </div>
                    </div>
                    <!--end: FILTER SELECT -->
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <input type="search" wire:model.live="search" class="form-control" placeholder="Type a keyword..." aria-label="Type a keyword..." style="appearance: none; background-color: #fff; border: 1px solid #eff2f5; border-radius: 5px; font-size: 14px; line-height: 1.45; outline: 0; padding: 10px 13px;">
                        </div>
                        @can('create incoming requests')
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end" style="display: {{ auth()->user()->division_id != 1 && !empty(auth()->user()->division_id) ? 'none' : '' }}">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-incomingRequestModal')">Add Request</button>
                        </div>
                        @endcan
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>No.</th>
                                        <th>Date Requested</th>
                                        <th>Office/Brgy/Org</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_requests as $item)
                                    <tr>
                                        <td>{{ $item->incoming_request_no }}</td>
                                        <td>{{ $item->formatted_date_requested }}</td>
                                        <td>{{ $item->office_or_barangay_or_organization_name }}</td>
                                        <td>{{ $item->category->incoming_request_category_name }}</td>
                                        <td>
                                            <span class="badge 
                                            @if($item->status->status_name == 'pending')
                                            badge-light-danger
                                            @elseif($item->status->status_name == 'processed')
                                            badge-light-primary
                                            @elseif($item->status->status_name == 'forwarded')
                                            badge-light-warning
                                            @elseif($item->status->status_name == 'completed')
                                            badge-light-success
                                            @elseif($item->status->status_name == 'cancelled')
                                            badge-light-dark
                                            @else
                                            badge-secondary
                                            @endif
                                            text-capitalize">
                                                {{ $item->status->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <!--begin::Trigger-->
                                            <button type="button" style="white-space: nowrap;" class="btn btn-sm btn-icon-dark btn-outline mb-2"
                                                data-kt-menu-trigger="click"
                                                data-kt-menu-placement="bottom-start">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <!--end::Trigger-->

                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4"
                                                data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3" style="display: {{ $item->status->status_name == 'completed' ? '' : 'none' }};">
                                                    <a href="#" class="menu-link px-3" wire:click="viewIncomingRequest({{ $item->id }})">
                                                        View
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                @can('update incoming requests')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3" style="display: {{ $item->status->status_name == 'completed' ? 'none' : '' }};">
                                                    <a href="#" class="menu-link px-3 {{ ($item->status->status_name == 'completed' && (auth()->user()->division_id != 1 || !auth()->user()->division_id)) ? 'disabled-link' : '' }}" wire:click="readIncomingRequest({{ $item->id }})">
                                                        Edit
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3" style="display: {{ auth()->user()->division_id != 1 && !empty(auth()->user()->division_id) ? 'none' : '' }};">
                                                    <a href="#" class="menu-link px-3 {{ ($item->status->status_name == 'completed' && (auth()->user()->division_id != 1 || !auth()->user()->division_id)) ? 'disabled-link' : '' }}"
                                                        wire:click="forwardToDivision({{ $item->id }})">
                                                        Forward
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endcan

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" wire:click="readDocumentHistory({{ $item->id }})">
                                                        History
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="8">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 mb-5">
                                {{ $incoming_requests->links() }}
                            </div>
                        </div>
                    </div>
                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 5-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recents</h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body">
                    <!-- begin::Items -->
                    <div class="timeline-label">
                        @foreach ($recent_forwarded_incoming_requests as $item)
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bolder text-gray-800 fs-9">
                                {{ $item->updated_at->diffForHumans() }}
                            </div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless
                                @if($item->status->status_name == 'pending')
                                text-danger
                                @elseif($item->status->status_name == 'processed')
                                text-primary
                                @elseif($item->status->status_name == 'forwarded')
                                text-warning
                                @elseif($item->status->status_name == 'completed')
                                text-success
                                @elseif($item->status->status_name == 'cancelled')
                                text-dark
                                @endif
                                fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="fw-mormal timeline-content text-muted ps-3">
                                {{ $item->incoming_request_no }} &nbsp;
                                <span class="badge 
                                @if($item->status->status_name == 'pending')
                                badge-light-danger
                                @elseif($item->status->status_name == 'processed')
                                badge-light-primary
                                @elseif($item->status->status_name == 'forwarded')
                                badge-light-warning
                                @elseif($item->status->status_name == 'completed')
                                badge-light-success
                                @else
                                badge-light-dark
                                @endif 
                                text-capitalize">
                                    {{ $item->status->status_name }} &nbsp;
                                </span>
                                @foreach ($item->forwardedDivisions as $forwarded)
                                ({{ $forwarded->division->division_name ?? '-' }})
                                @endforeach
                            </div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                        @endforeach
                    </div>
                    <!-- end::Items -->
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    @include('livewire.incoming.modals.request-modal')

    @include('livewire.modals.document-history-modal')

    <livewire:components.forward-to-division-modal page="incoming requests" />
</div>

@script
<script>
    $wire.on('show-incomingRequestModal', () => {
        $('#incomingRequestModal').modal('show');

        //* $wire.propertyName is a way to access livewire component's properties.
        if (!$wire.editMode) {
            $wire.generateIncomingRequestNo();
        }
    });

    $wire.on('hide-incomingRequestModal', () => {
        $('#incomingRequestModal').modal('hide');
    });

    $wire.on('show-documentHistoryModal', () => {
        $('#documentHistoryModal').modal('show');
    });

    $wire.on('show-forwardToDivisionModal', () => {
        $('#forwardToDivisionModal').modal('show');
    });

    $wire.on('hide-forwardToDivisionModal', () => {
        $('#forwardToDivisionModal').modal('hide');
    });

    $wire.on('show-viewIncomingRequestModal', () => {
        $('#viewIncomingRequestModal').modal('show');
    });

    $wire.on('hide-viewIncomingRequestModal', () => {
        $('#viewIncomingRequestModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('read-file', (url) => {
        window.open(event.detail.url, '_blank'); // Open the signed URL in a new tab
    });

    /* -------------------------------------------------------------------------- */

    // Register the plugin 
    FilePond.registerPlugin(FilePondPluginFileValidateType); // for file type validation
    FilePond.registerPlugin(FilePondPluginFileValidateSize); // for file size validation
    FilePond.registerPlugin(FilePondPluginImagePreview); // for image preview

    // Turn input element into a pond with configuration options
    $('.files').filepond({
        // required: true,
        allowFileTypeValidation: true,
        acceptedFileTypes: ['image/jpg', 'image/png', 'application/pdf'],
        labelFileTypeNotAllowed: 'File of invalid type',
        allowFileSizeValidation: true,
        maxFileSize: '10MB',
        labelMaxFileSizeExceeded: 'File is too large',
        server: {
            // This will assign the data to the files[] property.
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                @this.upload('file_id', file, load, error, progress);
            },
            revert: (uniqueFileId, load, error) => {
                @this.removeUpload('file_id', uniqueFileId, load, error);
            }
        }
    });

    $wire.on('reset-files', () => {
        $('.files').each(function() {
            $(this).filepond('removeFiles');
        });
    });
</script>
@endscript