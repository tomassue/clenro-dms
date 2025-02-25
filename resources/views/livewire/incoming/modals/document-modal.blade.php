<!-- incomingDocumentModal -->
<div class="modal fade" id="incomingDocumentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="incomingDocumentModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="incomingDocumentModalLabel">{{ $editMode ? 'Update' : 'Add' }} Incoming Document</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form" wire:submit="{{ $editMode ? 'updateIncomingDocument' : 'createIncomingDocument' }}">
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
                        <label class="required fw-bold fs-6 mb-2">Category</label>
                        <select class="form-select" aria-label="Select example" wire:model.live="category_id">
                            <option value="">Open this select menu</option>
                            @foreach($incoming_document_category_select as $item)
                            <option value="{{ $item->id }}">{{ $item->incoming_document_category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-10">
                        <label class="required fw-bold fs-6 mb-2">Document Info</label>
                        <!-- <textarea class="form-control" wire:model="info" rows="4"></textarea> -->
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="info">
                        @error('info')
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
                        <label class="fw-bold fs-6 mb-2">Remarks</label>
                        <!-- <textarea class="form-control" wire:model="remarks" rows="4"></textarea> -->
                        <input type="text" class="form-control mb-3 mb-lg-0" wire:model="remarks">
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

<!-- viewIncomingDocumentModal -->
<div
    class="modal fade"
    id="viewIncomingDocumentModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="viewIncomingDocumentModalLabel"
    aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewIncomingDocumentModalLabel">
                    View Incoming Document
                </h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <!-- Status -->
                <div class="col-12 mb-3">
                    <label class="fw-bold fs-6 mb-2">Status</label>
                    <div class="form-control-plaintext text-capitalize">
                        {{ $status_id ?? 'N/A' }}
                    </div>
                </div>

                <!-- Category -->
                <div class="col-12 mb-3">
                    <label class="fw-bold fs-6 mb-2">Category</label>
                    <div class="form-control-plaintext">
                        {{ $category_id ?? 'N/A' }}
                    </div>
                </div>

                <!-- Document Info -->
                <div class="col-12 mb-3">
                    <label class="fw-bold fs-6 mb-2">Document Info</label>
                    <div class="form-control-plaintext">
                        {{ $info }}
                    </div>
                </div>

                <!-- Date -->
                <div class="col-12 mb-3">
                    <label class="fw-bold fs-6 mb-2">Date</label>
                    <div class="form-control-plaintext">
                        {{ $date }}
                    </div>
                </div>

                <!-- Remarks -->
                <div class="col-12 mb-3">
                    <label class="fw-bold fs-6 mb-2">Remarks</label>
                    <div class="form-control-plaintext">
                        {{ $remarks ?? 'N/A' }}
                    </div>
                </div>

                <!-- Files -->
                <div class="col-12 mb-3">
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
                                    <a href="#" class="btn btn-sm btn-info" wire:click="readFile({{ $item->id }})">
                                        View
                                    </a>
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
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                    wire:click="clear">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>