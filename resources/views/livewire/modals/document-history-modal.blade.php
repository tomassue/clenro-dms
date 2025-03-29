<!-- documentHistoryModal -->
<div class="modal fade" id="documentHistoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="documentHistoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="documentHistoryModalLabel">History</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Updated by</th>
                                <th>Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($document_history as $history)
                            <tr>
                                <td>{{ $history['created_at'] }}</td>
                                <td>{{ $history['causer'] }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($history['changes'] as $change)
                                        <li>
                                            <strong>{{ $change['field'] }}:</strong>
                                            <span class="text-danger">{{ $change['old'] }}</span> →
                                            <span class="text-success">{{ $change['new'] }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No record/s</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
            </div>
        </div>
    </div>
</div>