<div>
    @include('livewire.templates.loading-state-indicator')

    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl-12">
            <!--begin::Mixed Widget 5-->
            <div class="card card-xxl-stretch">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Users</span>
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
                        @can('create user')
                        <div class="col-sm-12 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('show-userModal')">Add User</button>
                        </div>
                        @endcan
                    </div>
                    <div class="mt-5">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Name</th>
                                        <th>Division</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index=>$item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->division->division_name ?? '-' }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <span class="badge {{ $item->deleted_at ? 'badge-light-danger' : 'badge-light-success' }}">{{ $item->deleted_at ? 'Inactive' : 'Active' }}</span>
                                        </td>
                                        <td>
                                            <!--begin::Trigger-->
                                            <button type="button" style="white-space: nowrap;" class="btn btn-sm btn-icon-dark btn-outline mb-2"
                                                data-kt-menu-trigger="click"
                                                data-kt-menu-placement="bottom-start">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <!--end::Trigger-->

                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4"
                                                data-kt-menu="true">
                                                @can('update user')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" wire:click="readUser({{ $item->id }})">
                                                        Edit
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endcan

                                                @can('read user permissions')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" wire:click="readUserPermissions({{ $item->id }})">
                                                        Permissions
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endcan

                                                @can('update user')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" wire:click="resetPassword({{ $item->id }})">
                                                        Reset Password
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endcan

                                                @can('delete user')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" wire:click="{{ $item->deleted_at ? 'restoreUser(' . $item->id . ')' : 'deleteUser(' . $item->id . ')' }}">
                                                        {{ $item->deleted_at ? 'Activate' : 'Deactivate' }}
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endcan
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class=" text-center" colspan="5">No records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 mb-5">
                                {{ $users->links() }}
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

    @include('livewire.settings.modals.user-management-modal')
</div>

@script
<script>
    $wire.on('show-userModal', () => {
        $('#userModal').modal('show');
    });

    $wire.on('hide-userModal', () => {
        $('#userModal').modal('hide');
    });

    $wire.on('show-userPermissionModal', () => {
        $('#userPermissionModal').modal('show');
    });

    $wire.on('hide-userPermissionModal', () => {
        $('#userPermissionModal').modal('hide');
    });
</script>
@endscript