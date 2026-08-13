<x-modal name="vendor-detail-modal" focusable>
    @if ($vendor)
        <div class="p-6 max-h-[85vh] overflow-y-auto">
            <div class="mb-6 border-b border-gray-200 pb-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $vendor->vendor_name }}</h3>
                        <p class="text-sm text-muted-foreground">{{ $vendor->vendor_code }} · {{ Str::title($vendor->status) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('vendors.profile.pdf', $vendor) }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                            <x-heroicon-o-arrow-down-tray class="mr-1.5 h-4 w-4" />
                            Download Profile PDF
                        </a>
                        <span class="rounded bg-gray-100 px-2 py-1 text-xs">{{ Str::title($vendor->approval_status) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
                <div class="rounded-md border p-3"><div class="text-xs text-gray-500">Companies</div><div class="text-lg font-semibold">{{ $vendor->companies->count() }}</div></div>
                <div class="rounded-md border p-3"><div class="text-xs text-gray-500">Contacts</div><div class="text-lg font-semibold">{{ $vendor->contacts->where('active', true)->count() }}</div></div>
                <div class="rounded-md border p-3"><div class="text-xs text-gray-500">Bank Accounts</div><div class="text-lg font-semibold">{{ $vendor->bankAccounts->where('active', true)->count() }}</div></div>
                <div class="rounded-md border p-3"><div class="text-xs text-gray-500">Items</div><div class="text-lg font-semibold">{{ $vendor->items->where('active', true)->count() }}</div></div>
            </div>

            <div class="space-y-6">
                <section>
                    <h4 class="text-sm font-semibold mb-2">Companies</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($vendor->companies as $company)
                            <span class="rounded bg-blue-50 px-2 py-1 text-xs text-blue-800">{{ $company->company_name }}</span>
                        @empty
                            <span class="text-sm text-gray-500">No companies assigned.</span>
                        @endforelse
                    </div>
                </section>

                <section>
                    <h4 class="text-sm font-semibold mb-2">Bank Details</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="text-left border-b"><th class="py-2">Bank</th><th>Account</th><th>Primary</th><th>Verified</th></tr></thead>
                            <tbody>
                                @forelse ($vendor->bankAccounts as $account)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $account->bank_name }}</td>
                                        <td>{{ $account->masked_account_number }}</td>
                                        <td>{{ $account->is_primary ? 'Yes' : 'No' }}</td>
                                        <td>{{ $account->is_verified ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="py-2 text-gray-500" colspan="4">No bank accounts recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    @endif
</x-modal>
