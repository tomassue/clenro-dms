<!-- incomingRequestModal -->
<div class="modal fade" id="incomingRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="incomingRequestModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="incomingRequestModalLabel">{{ $editMode ? 'Update' : 'Add' }} Incoming Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateIncomingRequest' : 'createIncomingRequest' }}">
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Incoming Request No.</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="incoming_request_no" disabled />
                        @error('incoming_request_no')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Office/Brgy/Org</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="office_or_barangay_or_organization_name" />
                        @error('office_or_barangay_or_organization_name')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Date Requested</label>
                        <input type="date" class="form-control mb-3 mb-lg-0" wire:model="date_requested" />
                        @error('date_requested')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Return Date</label>
                        <input type="date" class="form-control mb-3 mb-lg-0" wire:model="date_returned" />
                        @error('date_returned')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Category</label>
                        <select class="form-select" aria-label="Select example" wire:model.live="category_id">
                            <option>Open this select menu</option>
                            @foreach($category_select as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10" style="display: {{ $sub_category_select->count() != 0 ? '' : 'none'}}">
                        <label class="required fw-bold fs-6 mb-2">Sub-category</label>
                        <select class="form-select" aria-label="Select example" wire:model="sub_category_id">
                            <option>Open this select menu</option>
                            @foreach($sub_category_select as $item)
                            <option value="{{ $item->id }}">{{ $item->sub_category_name }}</option>
                            @endforeach
                        </select>
                        @error('sub_category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Venue</label>
                        <select class="form-select" aria-label="Select example" wire:model="venue_id">
                            <option>Open this select menu</option>
                            @foreach($venue_select as $item)
                            <option value="{{ $item->id }}">{{ $item->venue_name }}</option>
                            @endforeach
                        </select>
                        @error('venue_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Time Started</label>
                        <input type="time" class="form-control mb-3 mb-lg-0" wire:model="time_started" />
                        @error('time_started')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Time Ended</label>
                        <input type="time" class="form-control mb-3 mb-lg-0" wire:model="time_ended" />
                        @error('time_ended')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Contact Person (Name)</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="contact_person_name" />
                        @error('contact_person_name')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Contact Person (No.)</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="contact_person_number" />
                        @error('contact_person_number')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Description</label>
                        <textarea class="form-control" wire:model="description" rows="4"></textarea>
                        @error('description')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="fw-bold fs-6 mb-2">File(s)</label>
                        <div wire:ignore>
                            <input type="file" class="form-control files" multiple data-allow-reorder="true">
                        </div>
                        @error('description')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>