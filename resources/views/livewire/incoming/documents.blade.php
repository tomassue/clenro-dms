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
                        <span class="card-label fw-bolder fs-3 mb-1">Incoming Documents</span>
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
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-incomingDocumentModal')">Add Document</button>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Category</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_documents as $item)
                                    <tr>
                                        <td>{{ $item->category->category_name }}</td>
                                        <td>{{ $item->info }}</td>
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
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-secondary me-2 mb-2" wire:click="readIncomingDocument({{ $item->id }})">Edit</a>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-info mb-2" wire:click="readDocumentHistory({{ $item->id }})">History</a>
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
                                {{ $incoming_documents->links() }}
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

    @include('livewire.incoming.modals.document-modal')

    @include('livewire.modals.document-history-modal')
</div>

@script
<script>
    $wire.on('show-incomingDocumentModal', () => {
        $('#incomingDocumentModal').modal('show');
    });

    $wire.on('hide-incomingDocumentModal', () => {
        $('#incomingDocumentModal').modal('hide');
    });

    $wire.on('show-documentHistoryModal', () => {
        $('#documentHistoryModal').modal('show');
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