@props(['product'])

@php
    $histories = $product?->priceHistories?->sortByDesc('created_at') ?? collect();
@endphp

<div class="space-y-3 border-t border-gray-200 pt-5">
    <div>
        <h4 class="text-sm font-semibold text-gray-900">{{ __('Price Change History') }}</h4>
        <p class="mt-1 text-xs text-gray-500">{{ __('MRP changes with user and source.') }}</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase text-gray-500">{{ __('Date') }}</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase text-gray-500">{{ __('Changed By') }}</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase text-gray-500">{{ __('Source') }}</th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase text-gray-500">{{ __('MRP') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($histories as $history)
                    <tr>
                        <td class="px-3 py-2 text-gray-700">{{ $history->created_at?->format('d/m/Y h:i A') ?: '-' }}</td>
                        <td class="px-3 py-2 font-medium text-gray-900">{{ $history->changedBy?->name ?: 'System' }}</td>
                        <td class="px-3 py-2">
                            <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700">
                                {{ Str::headline($history->source) }}
                            </span>
                            @if($history->reference)
                                <div class="mt-1 text-xs text-gray-500">{{ $history->reference }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right font-medium">{{ $history->old_mrp === null ? '-' : format_money($history->old_mrp) }} <span class="text-gray-400">to</span> {{ $history->new_mrp === null ? '-' : format_money($history->new_mrp) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-sm text-gray-500">{{ __('No price changes recorded yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
