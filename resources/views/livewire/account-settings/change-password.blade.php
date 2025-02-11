<div>
    <div class="card card-flush shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Change Password</h3>
            <div class="card-toolbar">
                <!-- Button here -->
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="updatePassword">
                <div class="mb-10">
                    <label class="form-label required">Current Password</label>
                    <input type="password" class="form-control" wire:model="current_password">
                    @error('current_password')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                    @enderror
                </div>

                <!--begin::Main wrapper-->
                <div class="mb-10">
                    <div class="fv-row" data-kt-password-meter="true">
                        <!--begin::Wrapper-->
                        <div class="mb-1">
                            <!--begin::Label-->
                            <label class="form-label required fw-bold fs-6 mb-2">
                                New Password
                            </label>
                            <!--end::Label-->

                            <!--begin::Input wrapper-->
                            <div class="position-relative mb-3">
                                <input class="form-control form-control-lg form-control-solid"
                                    type="password" placeholder="" name="new_password" autocomplete="off" wire:model="new_password" />

                                <!--begin::Visibility toggle-->
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    data-kt-password-meter-control="visibility">
                                    <i class="bi bi-eye-slash fs-2"></i>

                                    <i class="bi bi-eye fs-2 d-none"></i>
                                </span>
                                <!--end::Visibility toggle-->
                            </div>
                            <!--end::Input wrapper-->

                            <!--begin::Highlight meter-->
                            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                            </div>
                            <!--end::Highlight meter-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Hint-->
                        <div class="text-muted">
                            Use 8 or more characters with a mix of letters, numbers & symbols.
                        </div>
                        <!--end::Hint-->
                        @error('new_password')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                </div>
                <!--end::Main wrapper-->

                <div class="mb-10">
                    <label class="form-label required">Confirm Password</label>
                    <input type="password" class="form-control" wire:model.live="confirm_password">
                    @error('confirm_password')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="text_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                    @enderror
                </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Update</button>
            </form>
        </div>
    </div>
</div>