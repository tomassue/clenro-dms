<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
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
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <input type="search" wire:model.live="search" class="form-control" placeholder="Type a keyword..." aria-label="Type a keyword..." style="appearance: none; background-color: #fff; border: 1px solid #eff2f5; border-radius: 5px; font-size: 14px; line-height: 1.45; outline: 0; padding: 10px 13px;">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-incomingRequestModal')">Add Request</button>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>No.</th>
                                        <th>Date Requested</th>
                                        <th>Date Returned</th>
                                        <th>Office/Brgy/Org</th>
                                        <th>Category</th>
                                        <th>Sub-category</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_requests as $item)
                                    <tr>
                                        <td>{{ $item->incoming_request_no }}</td>
                                        <td>{{ $item->formatted_date_requested }}</td>
                                        <td>{{ $item->formatted_date_returned }}</td>
                                        <td>{{ $item->office_or_barangay_or_organization_name }}</td>
                                        <td>{{ $item->category->category_name }}</td>
                                        <td>{{ $item->sub_category->sub_category_name }}</td>
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
                                            @endif
                                            text-capitalize">
                                                {{ $item->status->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-secondary me-2 mb-2" wire:click="readIncomingRequest({{ $item->id }})">Edit</a>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-info mb-2" wire:click="viewIncomingRequest({{ $item->id }})">View</a>
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
    </div>
    <!--end::Row-->

    @include('livewire.incoming.modals.request-modal')
</div>

@script
<script>
    $wire.on('show-incomingRequestModal', () => {
        $('#incomingRequestModal').modal('show');

        //! NOT WORKING
        if ($editMode === false) {
            console.log('generate');
            $wire.generateIncomingRequestNo(); // Call generateIncomingRequestNo()
        }
        console.log('DONT generate');
    });

    $wire.on('hide-incomingRequestModal', () => {
        $('#incomingRequestModal').modal('hide');
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