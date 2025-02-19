<!-- userModal -->
<div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">{{ $editMode ? 'Update' : 'Add' }} User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateUser' : 'createUser' }}">
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Name</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="name" />
                        @error('name')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Username</label>
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="username" />
                        @error('username')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Division</label>
                        <select class="form-select" aria-label="Select example" wire:model="division_id">
                            <option>Open this select menu</option>
                            @foreach ($division_select as $item)
                            <option value="{{ $item->id }}">{{ $item->division_name }}</option>
                            @endforeach
                        </select>
                        @error('division_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">E-mail</label>
                        <input type="email" class="form-control mb-3 mb-lg-0" wire:model="email" />
                        @error('email')
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