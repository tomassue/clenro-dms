<!-- subCategoryModal -->
<div class="modal fade" id="subCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="subCategoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subCategoryModalLabel">{{ $editMode ? 'Update' : 'Add' }} Sub-category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateSubCategory' : 'createSubCategory' }}">
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Category</label>
                        <select class="form-select" aria-label="Select example" wire:model="category_id">
                            <option>Open this select menu</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Sub-category</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="sub_category_name" />
                        @error('sub_category_name')
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