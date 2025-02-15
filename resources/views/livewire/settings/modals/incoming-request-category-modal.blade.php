<!-- incomingRequestCategoryModal -->
<div class="modal fade" id="incomingRequestCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="incomingRequestCategoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="incomingRequestCategoryModalLabel">{{ $editMode ? 'Update' : 'Add' }} Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateIncomingRequestCategory' : 'createIncomingRequestCategory' }}">
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Category Name</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="incoming_request_category_name" />
                        @error('incoming_request_category_name')
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