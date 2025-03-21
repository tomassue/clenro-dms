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
                        <span class="card-label fw-bolder fs-3 mb-1">Accomplishments</span>
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
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <input type="text" class="form-control" name="datefilter" value="" placeholder="MM/DD/YYYY - MM/DD/YYYY" />
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-4 text-end">
                            <button type="button" class="btn btn-icon btn-info" wire:click="previewPDF">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z" />
                                </svg>
                            </button>
                        </div>

                    </div>
                    <div class="row mt-3 justify-content-end">
                        @can('create accomplishments')
                        <div class="col-sm-12 col-md-12 col-lg-4 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-accomplishmentModal')">Add Accomplishment</button>
                        </div>
                        @endcan
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Accomplishments Category</th>
                                        <th>Date</th>
                                        <th>Details</th>
                                        <th>No. of Participants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accomplishments as $item)
                                    <tr>
                                        <td>{{ $item->accomplishment_category->accomplishment_category_name }}</td>
                                        <td>{{ $item->formatted_date }}</td>
                                        <td>{{ $item->details }}</td>
                                        <td>{{ $item->no_of_participants ?? '' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-flush dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('update accomplishments')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            wire:click="readAccomplishment({{ $item->id }})">
                                                            Edit
                                                        </a>
                                                    </li>
                                                    @endcan

                                                    <li>
                                                        <a class="dropdown-item"
                                                            wire:click="readAccomplishmentHistory({{ $item->id }})">
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
                                {{ $accomplishments->links() }}
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

    <!-- pdfModal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pdfModalModalLabel">PDF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ $pdf }}" type="application/pdf" width="100%" height="700px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.modals.accomplishment-modal')
</div>

@script
<script>
    $wire.on('show-accomplishmentModal', () => {
        $('#accomplishmentModal').modal('show');
    });

    $wire.on('hide-accomplishmentModal', () => {
        $('#accomplishmentModal').modal('hide');
    });

    $wire.on('show-accomplishmentHistoryModal', () => {
        $('#accomplishmentHistoryModal').modal('show');
    });

    $wire.on('hide-accomplishmentHistoryModal', () => {
        $('#accomplishmentHistoryModal').modal('hide');
    });

    $wire.on('show-pdfModal', () => {
        $('#pdfModal').modal('show');
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

    /* -------------------------------------------------------------------------- */

    $(function() {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            let date_range_filter = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');

            $(this).val(date_range_filter);
            @this.set('date_range_filter', date_range_filter); // Directly set the Livewire property
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');

            @this.set('date_range_filter', ''); // Clear the Livewire property
        });
    });
</script>
@endscript