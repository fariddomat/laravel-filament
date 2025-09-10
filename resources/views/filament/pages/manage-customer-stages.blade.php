<x-filament-panels::page>
    <x-filament::card>
        <div class="w-full h-full flex space-x-4 rtl:space-x-reverse overflow-x-auto">
            @if($statuses->isEmpty())
                <p class="text-gray-500 italic">No pipeline stages available.</p>
            @else
                @foreach ($statuses as $status)
                    <div class="h-full flex-1 min-w-[200px]">
                        <div class="bg-blue-100 rounded px-2 flex flex-col h-full" id="{{ $status['id'] }}">
                            <div class="p-2 text-sm font-semibold text-gray-900 bg-blue-600 text-white rounded-t">
                                {{ $status['title'] }}
                            </div>
                            <div id="{{ $status['kanbanRecordsId'] }}"
                                 data-status-id="{{ $status['id'] }}"
                                 class="space-y-2 p-2 flex-1 overflow-y-auto min-h-[200px]">
                                @if($status['records']->isEmpty())
                                    <p class="text-gray-500 italic">No customers in this stage.</p>
                                @else
                                    @foreach ($status['records'] as $record)
                                        <div id="{{ $record['id'] }}"
                                             class="shadow bg-white dark:bg-gray-800 p-2 rounded border cursor-move"
                                             draggable="true">
                                            <p>{{ $record['title'] }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div x-data x-init="() => {
            @foreach ($statuses as $status)
                Sortable.create(document.getElementById('{{ $status['kanbanRecordsId'] }}'), {
                    group: '{{ $status['group'] }}',
                    animation: 150,
                    ghostClass: 'bg-blue-200',
                    setData: function(dataTransfer, dragEl) {
                        dataTransfer.setData('id', dragEl.id);
                    },
                    onEnd: function(evt) {
                        const sameContainer = evt.from === evt.to;
                        const orderChanged = evt.oldIndex !== evt.newIndex;
                        if (sameContainer && !orderChanged) return;

                        const recordId = evt.item.id;
                        const toStatusId = evt.to.dataset.statusId;
                        @this.dispatch('statusChangeEvent', { id: recordId, pipeline_stage_id: toStatusId });
                    },
                });
            @endforeach
        }">
        </div>
    </x-filament::card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endpush
</x-filament-panels::page>
