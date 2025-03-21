<div>
    @include('livewire.templates.loading-state-indicator')

    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
            <!--begin::Mixed Widget 5-->
            <div class="card card-xxl-stretch">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Outgoing</span>
                        <!-- <span class="text-muted fw-bold fs-7">Manage users</span> -->
                    </h3>
                    <div class="card-toolbar">
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column">
                    <div class="row g-4 justify-content-between mb-10">
                        <!--begin: FILTER SELECT -->
                        <div class="col-sm-12 col-md-12 col-lg-2">
                            <div class="col-12">
                                <label class="fw-bold fs-6">Filter</label>
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
                        @can('create outgoing')
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-outgoingModal')">Add Outgoing</button>
                        </div>
                        @endcan
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Document No.</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Document</th>
                                        <th>Destination</th>
                                        <th>Person Responsible</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($outgoing as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            @php
                                            switch ($item->type_type) {
                                            case 'App\Models\OutgoingVoucherModel':
                                            echo 'Voucher';
                                            break;
                                            case 'App\Models\OutgoingRisModel':
                                            echo 'RIS';
                                            break;
                                            case 'App\Models\OutgoingProcurementModel':
                                            echo 'Procurement';
                                            break;
                                            case 'App\Models\OutgoingPayrollModel':
                                            echo 'Payroll';
                                            break;
                                            case 'App\Models\OutgoingOthersModel':
                                            echo 'Others';
                                            break;
                                            default:
                                            echo '-';
                                            }
                                            @endphp
                                        </td>
                                        <td>{{ $item->formatted_date }}</td>
                                        <td>{{ $item->details }}</td>
                                        <td>{{ $item->destination }}</td>
                                        <td>{{ $item->person_responsible }}</td>
                                        <td>
                                            <span class="badge 
                                            @if($item->status->status_name == 'processing')
                                            badge-light-danger
                                            @elseif($item->status->status_name == 'forwarded')
                                            badge-light-warning
                                            @elseif($item->status->status_name == 'completed')
                                            badge-light-success
                                            @elseif($item->status->status_name == 'returned')
                                            badge-light-dark
                                            @endif
                                            text-capitalize">
                                                {{ $item->status->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            @can('update outgoing')
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-secondary me-2 mb-2" wire:click="readOutgoing({{ $item->id }})">Edit</a>
                                            @endcan
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-info mb-2" wire:click="readOutgoingHistory({{ $item->id }})">History</a>
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
                                {{ $outgoing->links() }}
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

    @include('livewire.modals.outgoing-modal')

    @include('livewire.modals.document-history-modal')

</div>

@script
<script>
    $wire.on('show-outgoingModal', () => {
        $('#outgoingModal').modal('show');
    });

    $wire.on('hide-outgoingModal', () => {
        $('#outgoingModal').modal('hide');
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