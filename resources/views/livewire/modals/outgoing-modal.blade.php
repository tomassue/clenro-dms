<!-- outgoingModal -->
<div class="modal fade" id="outgoingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="outgoingModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="outgoingModalLabel">{{ $editMode ? 'Update' : 'Add' }} Outgoing</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateOutgoing' : 'createOutgoing' }}">
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
                        <label class="{{ $editMode ? '' : 'required' }} fw-bold fs-6 mb-2">Type</label>
                        <select class="form-select" aria-label="Select example" wire:model.live="type" {{ $editMode ? 'disabled' : '' }}>
                            <option value="">Open this select menu</option>
                            @if(auth()->user()->division_id == 1 || empty(auth()->user()->division_id))
                            <option value="voucher">Voucher</option>
                            <option value="ris">RIS</option>
                            <option value="procurement">Procurement</option>
                            <option value="payroll">Payroll</option>
                            <option value="other">Other</option>
                            @else
                            <option value="other">Other</option>
                            @endif
                        </select>
                        @error('type')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="separator my-10 text-uppercase" style="display:{{ $type ? '' : 'none' }}">{{ $type }}</div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'other' || $type == 'ris' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Document Name</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="document_name" />
                        @error('document_name')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'payroll' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Payroll Type</label>
                        <select class="form-select" aria-label="Select example" wire:model="payroll_type">
                            <option value="">Open this select menu</option>
                            <option value="job order">Job Order</option>
                            <option value="regular">Regular</option>
                        </select>
                        @error('payroll_type')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'procurement' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">PR No.</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="pr_no" />
                        @error('pr_no')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'procurement' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">PO No.</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="po_no" />
                        @error('po_no')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'ris' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">PPMP Code</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="ppmp_code" />
                        @error('ppmp_code')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type == 'voucher' ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Voucher Name</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="voucher_name" />
                        @error('voucher_name')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Date</label>
                        <input type="date" class="form-control mb-3 mb-lg-0" wire:model="date" />
                        @error('date')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Details</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="details" />
                        @error('details')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Destination</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="destination" />
                        @error('destination')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type ? '' : 'none' }}">
                        <label class="required fw-bold fs-6 mb-2">Person Responsible</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="person_responsible" />
                        @error('person_responsible')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10" style="display: {{ $type ? '' : 'none' }}">
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