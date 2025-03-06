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

<!-- userPermissionModal -->
<div class="modal fade" id="userPermissionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userPermissionModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userPermissionModalLabel">User Permissions</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="updateUserPermissions">
                    <div class="d-flex flex-column">
                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Dashboard
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read dashboard" id="readDashboard" wire:model="permissions" />
                                <label class="form-check-label" for="readDashboard"> Read </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Incoming Requests
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create incoming requests" id="createIncomingRequests" wire:model="permissions" />
                                <label class="form-check-label" for="createIncomingRequests"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read incoming requests" id="readIncomingRequests" wire:model="permissions" />
                                <label class="form-check-label" for="readIncomingRequests"> Read </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update incoming requests" id="updateIncomingRequests" wire:model="permissions" />
                                <label class="form-check-label" for="updateIncomingRequests"> Update </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Incoming Documents
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create incoming documents" id="createIncomingDocuments" wire:model="permissions" />
                                <label class="form-check-label" for="createIncomingDocuments"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read incoming documents" id="readIncomingDocuments" wire:model="permissions" />
                                <label class="form-check-label" for="readIncomingDocuments"> Read </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update incoming documents" id="updateIncomingDocuments" wire:model="permissions" />
                                <label class="form-check-label" for="updateIncomingDocuments"> Update </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Outgoing
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create outgoing" id="createOutgoing" wire:model="permissions" />
                                <label class="form-check-label" for="createOutgoing"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read outgoing" id="readOutgoing" wire:model="permissions" />
                                <label class="form-check-label" for="readOutgoing"> Read </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update outgoing" id="updateIncomingRequests" wire:model="permissions" />
                                <label class="form-check-label" for="updateIncomingRequests"> Update </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Calendar
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read calendar" id="readCalendar" wire:model="permissions" />
                                <label class="form-check-label" for="readCalendar"> Read </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> Accomplishments
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create accomplishments" id="createAccomplishments" wire:model="permissions" />
                                <label class="form-check-label" for="createAccomplishments"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read accomplishments" id="readAccomplishments" wire:model="permissions" />
                                <label class="form-check-label" for="readAccomplishments"> Read </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update accomplishments" id="updateAccomplishments" wire:model="permissions" />
                                <label class="form-check-label" for="updateAccomplishments"> Update </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> References
                        </li>
                        <div class="row py-2 ms-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read references" id="readReferences" wire:model="permissions" />
                                <label class="form-check-label" for="readReferences"> Read </label>
                            </div>
                        </div>

                        <li class="d-flex align-items-center py-2 ms-4">
                            <span class="bullet me-5"></span> User Management
                        </li>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create user" id="createUser" wire:model="permissions" />
                                <label class="form-check-label" for="createUser"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read user management" id="readUserManagement" wire:model="permissions" />
                                <label class="form-check-label" for="readUserManagement"> Read </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update user" id="updateUser" wire:model="permissions" />
                                <label class="form-check-label" for="updateUser"> Update </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="delete user" id="deleteUser" wire:model="permissions" />
                                <label class="form-check-label" for="deleteUser"> Delete </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read user permissions" id="readUserPermissions" wire:model="permissions" />
                                <label class="form-check-label" for="readUserPermissions"> Read user permissions </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="update user permissions" id="updateUserPermissions" wire:model="permissions" />
                                <label class="form-check-label" for="updateUserPermissions"> Update user permissions </label>
                            </div>
                        </div>

                        <!-- Reference: permissions -->
                        <li class="d-flex align-items-center py-2 ms-4">
                            <span class="bullet me-5"></span> User Permissions
                        </li>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="create permissions" id="createPermissions" wire:model="permissions" />
                                <label class="form-check-label" for="createPermissions"> Create </label>
                            </div>
                        </div>
                        <div class="row py-2 ms-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="read permissions" id="readPermissions" wire:model="permissions" />
                                <label class="form-check-label" for="readPermissions"> Read </label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                @can('update user permissions')
                <button type="submit" class="btn btn-primary">Save</button>
                @endcan
                </form>
            </div>

        </div>
    </div>
</div>