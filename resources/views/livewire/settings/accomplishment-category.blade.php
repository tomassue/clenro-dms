<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
            <!--begin::Mixed Widget 5-->
            <div class="card card-xxl-stretch">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Accomplishment Category</span>
                        <!-- <span class="text-muted fw-bold fs-7">Manage users</span> -->
                    </h3>
                    <div class="card-toolbar">
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column">
                    <div class="row g-5 justify-content-between">
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <input type="search" wire:model.live="search" class="form-control" placeholder="Type a keyword..." aria-label="Type a keyword..." style="appearance: none; background-color: #fff; border: 1px solid #eff2f5; border-radius: 5px; font-size: 14px; line-height: 1.45; outline: 0; padding: 10px 13px;">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-accomplishmentCategoryModal')">Add Category</button>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accomplishment_categories as $item)
                                    <tr>
                                        <td>{{ $item->accomplishment_category_name }}</td>
                                        <td>
                                            <span class="badge {{ $item->deleted_at ? 'badge-light-danger' : 'badge-light-success' }}">
                                                {{ $item->deleted_at ? 'Inactive' : 'Active' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-secondary me-2 mb-2" wire:click="readDivision({{ $item->id }})">Edit</a>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm {{ $item->deleted_at ? 'btn-info' : 'btn-danger' }} mb-2" wire:click="{{ $item->deleted_at ? 'restoreDivision(' . $item->id . ')' : 'deleteDivision(' . $item->id . ')' }}">{{ $item->deleted_at ? 'Activate' : 'Deactivate' }}</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="3">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 mb-5">
                                {{ $accomplishment_categories->links() }}
                            </div>
                        </div>
                    </div>
                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 5-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!-- accomplishmentCategoryModal -->
    <div class="modal fade" id="accomplishmentCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="accomplishmentCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="accomplishmentCategoryModalLabel">{{ $editMode ? 'Update' : 'Add' }} Accomplishment Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="form" wire:submit="{{ $editMode ? 'updateAccomplishmentCategory' : 'createAccomplishmentCategory' }}">
                        <div class="col-12 mb-10">
                            <label class="required fw-bold fs-6 mb-2">Name</label>
                            <input type="text" class="form-control mb-3 mb-lg-0" wire:model="accomplishment_category_name" />
                            @error('accomplishment_category_name')
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
</div>

@script
<script>
    $wire.on('show-accomplishmentCategoryModal', () => {
        $('#accomplishmentCategoryModal').modal('show');
    });

    $wire.on('hide-accomplishmentCategoryModal', () => {
        $('#accomplishmentCategoryModal').modal('hide');
    });
</script>
@endscript