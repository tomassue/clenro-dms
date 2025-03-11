<div>
    <!-- forwardToDivisionModal -->
    <div class="modal fade" id="forwardToDivisionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="forwardToDivisionModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="forwardToDivisionModalLabel">Forward <span class="text-capitalize">({{ $page }})</span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="form" wire:submit="forwardToDivision">
                        <div class="col-12 mb-10">
                            <label class="required fw-bold fs-6 mb-2">Division</label>
                            <div wire:ignore>
                                <div id="division-select"></div>
                            </div>
                            @error('division_id')
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
    VirtualSelect.init({
        ele: '#division-select',
        maxWidth: '100%',
        options: @json($division_select),
        multiple: true,
        search: false
    });

    let division_id = document.querySelector('#division-select');
    division_id.addEventListener('change', () => {
        let data = division_id.value;
        @this.set('division_id', data);
    });

    $wire.on('reset-division-select', () => {
        document.querySelector('#division-select').reset();
    });
</script>
@endscript