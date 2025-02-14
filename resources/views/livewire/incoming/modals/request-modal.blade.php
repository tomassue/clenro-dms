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
                    <!--begin: CHANGE STATUS -->
                    <div class="col-12 mb-10" style="display: {{ $editMode ? '' : 'none' }};">
                        <label class="required fw-bold fs-6 mb-2">Status</label>
                        <select class="form-select text-capitalize" aria-label="Select example" wire:model="status_id">
                            <option value="">Open this select menu</option>
                            @foreach($status_select as $item)
                            <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <!--end: CHANGE STATUS -->

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
                        <label class="required fw-bold fs-6 mb-2">Category</label>
                        <select class="form-select" aria-label="Select example" wire:model.live="category_id">
                            <option value="">Open this select menu</option>
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
                        <select class="form-select" aria-label="Select example" wire:model.live="sub_category_id">
                            <option value="">Open this select menu</option>
                            @foreach($sub_category_select as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $sub_category_id ? 'selected' : '' }}>{{ $item->sub_category_name }}</option>
                            @endforeach
                        </select>
                        @error('sub_category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Date and Time</label>
                        <input type="datetime-local" class="form-control mb-3 mb-lg-0" wire:model="date_and_time" />
                        @error('date_and_time')
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
                        <label class="required fw-bold fs-6 mb-2">Contact Number</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="contact_person_number" />
                        @error('contact_person_number')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Description</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="description" />
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
                        @error('file_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $editMode ? '' : 'none' }}">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th width="80%">File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($preview_file_id as $item)
                                <tr>
                                    <td>{{ $item->file_name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info" wire:click="readFile({{ $item->id }})">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">No files.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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