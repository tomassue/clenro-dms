<!-- documentHistoryModal -->
<div class="modal fade" id="documentHistoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="documentHistoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="documentHistoryModalLabel">Document History</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-rounded table-striped border gy-7 gs-7 align-middle">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th>Date</th>
                                <th>Status</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($document_history as $item)
                            <tr>
                                <td>{{ $item['updated_at'] }}</td>
                                <td>
                                    <span class="badge 
                                            @if($item['status'] == 'pending' || $item['status'] == 'processing')
                                            badge-light-danger
                                            @elseif($item['status'] == 'processed')
                                            badge-light-primary
                                            @elseif($item['status'] == 'forwarded')
                                            badge-light-warning
                                            @elseif($item['status'] == 'completed')
                                            badge-light-success
                                            @elseif($item['status'] == 'cancelled')
                                            badge-light-dark
                                            @endif
                                            text-capitalize">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td>{{ $item['updated_by'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">No history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>