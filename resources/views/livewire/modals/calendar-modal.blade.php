<!-- detailsModal -->
<div class="modal fade" id="detailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailsModalLabel">Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h5>Incoming Request</h5>
                    <div class="row">
                        <div class="col-4 fw-bold">Status:</div>
                        <div class="col-8">
                            <span class="badge 
                                @switch(strtolower($incoming_request_calendar_id->status->status_name ?? '-'))
                                    @case('pending')
                                        badge-light-danger
                                        @break
                                    @case('processed')
                                        badge-light-primary
                                        @break
                                    @case('forwarded')
                                        badge-light-warning
                                        @break
                                    @case('completed')
                                        badge-light-success
                                        @break
                                    @case('cancelled')
                                        badge-light-dark
                                        @break
                                    @default
                                        badge-light-secondary
                                @endswitch
                                text-capitalize">
                                {{ $incoming_request_calendar_id->status->status_name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Forwarded to:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->division->division_name ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">No.:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->incoming_request_no ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Office/Brgy/Org:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->office_or_barangay_or_organization_name ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Date requested:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->formatted_date_requested ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Category:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->category->incoming_request_category_name ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Date and Time:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->date_and_time ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Contact person name:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->contact_person_name ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Contact person number:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->contact_person_number ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Description:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->description ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Remarks:</div>
                        <div class="col-8">{{ $incoming_request_calendar_id->remarks ?? '-' }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Files</h5>
                    <div class="row">
                        @forelse ($preview_file_id as $file)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-file-earmark-text me-2"></i> {{ $file->file_name }}
                                    </h6>
                                    <p class="card-text text-muted">{{ $file->file_type }}</p>
                                    <a href="#" wire:click="readFile({{ $file->id }})" class="btn btn-primary btn-sm">Preview</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">No files available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
            </div>
        </div>
    </div>
</div>