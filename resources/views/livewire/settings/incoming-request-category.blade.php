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
                        <span class="card-label fw-bolder fs-3 mb-1">Incoming Request Category</span>
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
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-incomingRequestCategoryModal')">Add Category</button>
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
                                    @forelse($incoming_request_categories as $index=>$item)
                                    <tr>
                                        <td>{{ $item->incoming_request_category_name }}</td>
                                        <td>
                                            <span class="badge {{ $item->deleted_at ? 'badge-light-danger' : 'badge-light-success' }}">{{ $item->deleted_at ? 'Inactive' : 'Active' }}</span>
                                        </td>
                                        <td>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm btn-secondary me-2 mb-2" wire:click="readIncomingRequestCategory({{ $item->id }})">Edit</a>
                                            <a type="button" style="white-space: nowrap;" class="btn btn-sm {{ $item->deleted_at ? 'btn-info' : 'btn-danger' }} mb-2" wire:click="{{ $item->deleted_at ? 'restoreIncomingRequestCategory(' . $item->id . ')' : 'deleteIncomingRequestCategory(' . $item->id . ')' }}">{{ $item->deleted_at ? 'Activate' : 'Deactivate' }}</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="5">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 mb-5">
                                {{ $incoming_request_categories->links() }}
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

    @include('livewire.settings.modals.incoming-request-category-modal')
</div>

@script
<script>
    $wire.on('show-incomingRequestCategoryModal', () => {
        $('#incomingRequestCategoryModal').modal('show');
    });

    $wire.on('hide-incomingRequestCategoryModal', () => {
        $('#incomingRequestCategoryModal').modal('hide');
    });
</script>
@endscript