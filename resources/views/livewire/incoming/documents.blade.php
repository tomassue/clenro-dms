<div>
    @include('livewire.templates.loading-state-indicator')

    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-9">
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
                    <div class="row g-5 justify-content-between mb-10">
                        <!--begin: FILTER SELECT -->
                        <div class="col-sm-12 col-md-12 col-lg-2">
                            <div class="col-12">
                                <label class="fw-bold fs-6 mb-2">Filter</label>
                                <select class="form-select text-capitalize" aria-label="Select example" wire:model.live="filter_status">
                                    <option value="">-Status-</option>
                                    @foreach($status_select as $item)
                                    <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--end: FILTER SELECT -->
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-light" wire:click="clear">Clear</button>
                        </div>
                    </div>
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <input type="search" wire:model.live="search" class="form-control" placeholder="Type a keyword..." aria-label="Type a keyword..." style="appearance: none; background-color: #fff; border: 1px solid #eff2f5; border-radius: 5px; font-size: 14px; line-height: 1.45; outline: 0; padding: 10px 13px;">
                        </div>
                        @can('create incoming documents')
                        <div class="col-sm-12 col-md-12 col-lg-3 text-end" style="display: {{ auth()->user()->division_id != 1 && !empty(auth()->user()->division_id) ? 'none' : '' }};">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-incomingDocumentModal')">Add Document</button>
                        </div>
                        @endcan
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_documents as $item)
                                    <tr>
                                        <td>{{ $item->category->incoming_document_category_name }}</td>
                                        <td>{{ $item->formatted_date }}</td>
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
                                            @else
                                            badge-secondary
                                            @endif
                                            text-capitalize">
                                                {{ $item->status->status_name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-flush dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li style="display: {{ $item->status->status_name == 'completed' ? '' : 'none' }};">
                                                        <a class="dropdown-item"
                                                            wire:click="viewIncomingDocument({{ $item->id }})">
                                                            View
                                                        </a>
                                                    </li>

                                                    @can('update incoming documents')
                                                    <li style="display: {{ $item->status->status_name == 'completed' ? 'none' : '' }};">
                                                        <a class="dropdown-item {{ ($item->status->status_name == 'completed' && (auth()->user()->division_id != 1 || !auth()->user()->division_id)) ? 'disabled-link' : '' }}"
                                                            wire:click="readIncomingDocument({{ $item->id }})">
                                                            View
                                                        </a>
                                                    </li>

                                                    <li style="display: {{ auth()->user()->division_id != 1 && !empty(auth()->user()->division_id) || $item->status->status_name == 'completed' ? 'none' : '' }};">
                                                        <a class="dropdown-item {{ ($item->status->status_name == 'completed' && (auth()->user()->division_id != 1 || !auth()->user()->division_id)) ? 'disabled-link' : '' }}"
                                                            wire:click="forwardToDivision({{ $item->id }})">
                                                            Forward
                                                        </a>
                                                    </li>
                                                    @endcan

                                                    <li>
                                                        <a class="dropdown-item"
                                                            wire:click="readDocumentHistory({{ $item->id }})">
                                                            History
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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
                        @foreach ($recent_forwarded_incoming_documents as $item)
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
                                {{ $item->info }} &nbsp;
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

    @include('livewire.incoming.modals.document-modal')

    @include('livewire.modals.document-history-modal')

    <livewire:components.forward-to-division-modal page="incoming documents" />
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

    $wire.on('show-forwardToDivisionModal', () => {
        // $wire.incoming_document_id = id.id; // $wire.propertyName is a way to access livewire component's properties. id.id is a way to access the id property of the id object.
        // $wire.checkForwardedToDivision(id.id);
        $('#forwardToDivisionModal').modal('show');
    });

    $wire.on('hide-forwardToDivisionModal', (id) => {
        $('#forwardToDivisionModal').modal('hide');
    });

    $wire.on('show-viewIncomingDocumentModal', () => {
        $('#viewIncomingDocumentModal').modal('show');
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
        acceptedFileTypes: ['image/jpeg', 'image/png', 'application/pdf'],
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