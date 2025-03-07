<!-- accomplishmentModal -->
<div class="modal fade" id="accomplishmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="accomplishmentModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="accomplishmentModalLabel">{{ $editMode ? 'Update' : 'Add' }} Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateAccomplishment' : 'createAccomplishment' }}">
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Accomplishment Category</label>
                        <select class="form-select" aria-label="Select example" wire:model="accomplishment_category_id">
                            <option>Open this select menu</option>
                            @foreach($accomplishment_category_select as $item)
                            <option class="text-capitalize" value="{{ $item->id }}">{{ $item->accomplishment_category_name }}</option>
                            @endforeach
                        </select>
                        @error('accomplishment_category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Date</label>
                        <input type="date" class="form-control mb-3 mb-lg-0" wire:model="date" />
                        @error('date')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Details</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="details" />
                        @error('details')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">No. of participants</label>
                        <input type="number" class="form-control mb-3 mb-lg-0" wire:model="no_of_participants" />
                        @error('no_of_participants')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="fw-bold fs-6 mb-2">Remarks</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="remarks" />
                        @error('remarks')
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

<!-- accomplishmentHistoryModal -->
<div class="modal fade" id="accomplishmentHistoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="accomplishmentHistoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="accomplishmentHistoryModalLabel">Outgoing History</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Causer</th>
                                <th>Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outgoing_history as $history)
                            <tr>
                                <td>{{ $history['created_at'] }}</td>
                                <td>{{ $history['causer'] }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($history['changes'] as $change)
                                        <li>
                                            <strong>{{ $change['field'] }}:</strong>
                                            <span class="text-danger">{{ $change['old'] }}</span> →
                                            <span class="text-success">{{ $change['new'] }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
            </div>
        </div>
    </div>
</div>