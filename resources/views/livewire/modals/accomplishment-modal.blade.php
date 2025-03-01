<!-- accomplishmentModal -->
<div class="modal fade" id="accomplishmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="accomplishmentModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>