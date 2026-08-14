<x-app-layout title="Purchase Order Details">
    @php
        $storeName = \App\Models\Setting::get('store_name', config('app.name'));
        $storeAddress = \App\Models\Setting::get('store_address', '-');
        $storePhone = \App\Models\Setting::get('store_phone', '-');
        $storeEmail = \App\Models\Setting::get('store_email', '-');
        $supplierAddress = collect([
            $purchase->supplier?->address_line_1,
            $purchase->supplier?->address_line_2,
            $purchase->supplier?->city,
            $purchase->supplier?->state,
            $purchase->supplier?->postal_code,
            $purchase->supplier?->country,
        ])->filter()->join(', ') ?: ($purchase->supplier?->address ?: '-');
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Purchase Order Details') }} #{{ $purchase->invoice_number ?: $purchase->id }}
            </h2>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('purchases.index') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    {{ __('Back') }}
                </a>
                @if(in_array($purchase->status, [\App\Enums\PurchaseStatus::DRAFT, \App\Enums\PurchaseStatus::ORDERED]))
                    <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-blue-600 px-3 text-xs font-semibold text-white hover:bg-blue-700">
                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                        {{ __('Edit') }}
                    </a>
                @endif
                @if($purchase->status === \App\Enums\PurchaseStatus::ORDERED)
                    <button type="button" x-data x-on:click="$dispatch('open-receive-modal')" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-green-600 px-3 text-xs font-semibold text-white hover:bg-green-700">
                        <x-heroicon-o-inbox-arrow-down class="h-4 w-4" />
                        {{ __('Receive') }}
                    </button>
                @endif
                <a href="{{ route('purchases.print', $purchase) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                    {{ __('Download PDF') }}
                </a>
                @if($purchase->status === \App\Enums\PurchaseStatus::ORDERED)
                    <button type="button" x-data x-on:click="$dispatch('open-purchase-confirmation', {
                        url: '{{ route('purchases.cancel', $purchase) }}',
                        method: 'PATCH',
                        title: 'Cancel Purchase Order',
                        message: 'Are you sure you want to cancel this purchase order?',
                        buttonText: 'Cancel',
                        buttonClass: '!bg-red-600 hover:!bg-red-700 focus:!ring-red-500'
                    })" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-red-600 px-3 text-xs font-semibold text-white hover:bg-red-700">
                        <x-heroicon-o-x-circle class="h-4 w-4" />
                        {{ __('Cancel') }}
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $storeName }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Store details used on the purchase order PDF.') }}</p>
                        <div class="mt-4 space-y-2 text-sm text-slate-700">
                            <p><span class="font-medium text-gray-500">{{ __('Address') }}:</span> {{ $storeAddress }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Phone') }}:</span> {{ $storePhone }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Email') }}:</span> {{ $storeEmail }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Order From / Supplier') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Supplier details included on the purchase order PDF.') }}</p>
                        <div class="mt-4 space-y-2 text-sm text-slate-700">
                            <p class="font-semibold text-gray-900">{{ $purchase->supplier->name ?? '-' }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Contact') }}:</span> {{ $purchase->supplier->contact_person ?: '-' }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Phone') }}:</span> {{ $purchase->supplier->phone ?: '-' }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Email') }}:</span> {{ $purchase->supplier->email ?: '-' }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('GST / Tax ID') }}:</span> {{ $purchase->supplier->tax_id ?: '-' }}</p>
                            <p><span class="font-medium text-gray-500">{{ __('Address') }}:</span> {{ $supplierAddress }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Info Card -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="p-6">
                    <!-- Header Info -->
                    <div class="flex items-start justify-between border-b border-gray-100 pb-4 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Purchase Order Information') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Vendor order details before goods are received.') }}</p>
                        </div>
                        <div class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                            ID: #{{ $purchase->id }}
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Supplier -->
                        <x-detail-item label="Supplier / Vendor" :value="$purchase->supplier->name">
                            <x-heroicon-o-building-storefront class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <x-detail-item label="Brand / Company" :value="$purchase->company ? ($purchase->company->short_name ?: $purchase->company->company_name) : '-'">
                            <x-heroicon-o-building-office-2 class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- PO Reference -->
                        <x-detail-item label="PO Reference" :value="$purchase->invoice_number ?? '-'">
                            <x-heroicon-o-document-text class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- PO Date -->
                        <x-detail-item label="PO Date" :value="$purchase->purchase_date->format('d M Y')">
                            <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- Expected Delivery -->
                        <x-detail-item label="Expected Delivery" :value="$purchase->due_date ? $purchase->due_date->format('d M Y') : '-'">
                            <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- Status -->
                        <div>
                            <label class="text-sm font-medium leading-none text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $purchase->status->color() }}">
                                    {{ $purchase->status->label() }}
                                </span>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <x-detail-item label="Total PO Amount" :value="format_money($purchase->total)">
                            <x-heroicon-o-banknotes class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- Created By -->
                        <x-detail-item label="Created By" :value="$purchase->creator->name ?? 'Unknown'">
                            <x-heroicon-o-user class="w-4 h-4 text-gray-400" />
                        </x-detail-item>

                        <!-- Proof Image -->
                        @if($purchase->proof_image)
                            <div>
                                <label class="text-sm font-medium leading-none text-gray-500">Proof of Receipt</label>
                                <div class="mt-1">
                                    <a href="{{ Storage::url($purchase->proof_image) }}" target="_blank" class="text-indigo-600 hover:underline text-sm flex items-center gap-1">
                                        <x-heroicon-o-paper-clip class="w-4 h-4" />
                                        View Image
                                    </a>
                                </div>
                            </div>
                        @else
                            <x-detail-item label="Proof of Receipt" value="-" />
                        @endif
                    </div>

                    <!-- Notes -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="space-y-1">
                            <label class="text-sm font-medium leading-none text-gray-500">
                                Notes
                            </label>
                            <div class="bg-gray-50 p-3 rounded-md border border-gray-100">
                                <p class="text-sm text-slate-700 italic leading-relaxed">{{ $purchase->notes ?: 'No additional notes.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table Section -->
                    <div class="mt-6 border-t overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Code</th>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3">Brand</th>
                                    <th class="px-6 py-3">Unit</th>
                                    <th class="px-6 py-3 text-center">Quantity</th>
                                    <th class="px-6 py-3 text-right">MRP</th>
                                    <th class="px-6 py-3 text-right">Order Price</th>
                                    <th class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($purchase->items as $item)
                                    <tr class="bg-white hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $item->product->product_code ?? $item->product->sku ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $item->product->company->short_name ?? $item->product->company->company_name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $item->product->unit->symbol ?? $item->product->unit->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ number_format($item->quantity) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @money($item->selling_price)
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @money($item->unit_price)
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium">
                                            @money($item->subtotal)
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold">
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-right">Total PO</td>
                                    <td class="px-6 py-4 text-right text-indigo-600 text-lg">
                                        @money($purchase->total)
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Workflow -->
            <div x-data="{
                actionUrl: '',
                actionMethod: '',
                modalTitle: '',
                modalMessage: '',
                confirmButtonText: '',
                confirmButtonClass: '',

                init() {
                    window.addEventListener('open-purchase-confirmation', event => {
                        this.confirmAction(
                            event.detail.url,
                            event.detail.method,
                            event.detail.title,
                            event.detail.message,
                            event.detail.buttonText,
                            event.detail.buttonClass
                        );
                    });
                },

                confirmAction(url, method, title, message, btnText, btnClass) {
                    // Manual DOM manipulation to ensure reliability
                    document.getElementById('confirmation-form').action = url;
                    document.getElementById('confirmation-method').value = method;

                    this.modalTitle = title;
                    this.modalMessage = message;
                    this.confirmButtonText = btnText;
                    this.confirmButtonClass = btnClass;
                    $dispatch('open-modal', { name: 'confirmation-modal' });
                }
            }" class="flex flex-col sm:flex-row justify-end gap-4">

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <a href="{{ route('purchases.index') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        {{ __('Back') }}
                    </a>
                    @if(in_array($purchase->status, [\App\Enums\PurchaseStatus::DRAFT, \App\Enums\PurchaseStatus::ORDERED]))
                        <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-blue-600 px-3 text-xs font-semibold text-white hover:bg-blue-700">
                            <x-heroicon-o-pencil-square class="h-4 w-4" />
                            {{ __('Edit') }}
                        </a>
                    @endif
                    @if($purchase->status === \App\Enums\PurchaseStatus::ORDERED)
                        <button type="button" @click="$dispatch('open-receive-modal')" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-green-600 px-3 text-xs font-semibold text-white hover:bg-green-700">
                            <x-heroicon-o-inbox-arrow-down class="h-4 w-4" />
                            {{ __('Receive') }}
                        </button>
                    @endif
                    <a href="{{ route('purchases.print', $purchase) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                        {{ __('Download PDF') }}
                    </a>
                    @if($purchase->status === \App\Enums\PurchaseStatus::ORDERED)
                        <button type="button" @click="confirmAction('{{ route('purchases.cancel', $purchase) }}', 'PATCH', 'Cancel Purchase Order', 'Are you sure you want to cancel this purchase order?', 'Cancel', '!bg-red-600 hover:!bg-red-700 focus:!ring-red-500')" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-red-600 px-3 text-xs font-semibold text-white hover:bg-red-700">
                            <x-heroicon-o-x-circle class="h-4 w-4" />
                            {{ __('Cancel') }}
                        </button>
                    @endif
                </div>

                @if($purchase->status === \App\Enums\PurchaseStatus::ORDERED)
                    <div x-data="{ open: @if($errors->has('invoice_number') || $errors->has('proof_image')) true @else false @endif }" x-on:open-receive-modal.window="open = true">
                        <!-- Modal Backdrop -->
                        <div x-show="open"
                             style="display: none;"
                             x-transition.opacity
                             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center p-4">

                            <!-- Modal Content -->
                            <div @click.outside="open = false"
                                 x-transition.scale
                                 class="relative bg-white rounded-lg max-w-md w-full p-6 shadow-xl">

                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Receive Purchase #{{ $purchase->invoice_number ?? $purchase->id }}
                                </h3>

                                <form
                                    action="{{ route('purchases.mark-received', $purchase) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    x-data="{ submitting: false }"
                                    @submit="submitting = true"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <div class="space-y-4">
                                        <!-- Invoice Section -->
                                        @if($purchase->invoice_number)
                                            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                                <span class="block text-xs font-medium text-gray-500 uppercase">PO Reference</span>
                                                <span class="text-sm font-semibold text-gray-900">{{ $purchase->invoice_number }}</span>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                <x-input-label for="invoice_number" :value="__('Final Invoice Number')" required hint="Supplier invoice number received with the goods. Example: INV-2026-001." />
                                                <x-text-input
                                                    id="invoice_number"
                                                    name="invoice_number"
                                                    :value="old('invoice_number')"
                                                    required
                                                    placeholder="INV...."
                                                />
                                                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                                            </div>
                                        @endif

                                        <!-- Proof Section -->
                                        @if($purchase->proof_image)
                                            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                                <span class="block text-xs font-medium text-gray-500 uppercase mb-1">Proof of Receipt</span>
                                                <a href="{{ Storage::url($purchase->proof_image) }}" target="_blank" class="text-indigo-600 hover:underline text-sm flex items-center gap-1">
                                                    <x-heroicon-o-paper-clip class="w-4 h-4" />
                                                    View Uploaded Image
                                                </a>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                <x-input-label for="proof_image" :value="__('Upload Proof of Receipt')" required hint="Image proof for received goods or supplier invoice. Example: invoice-photo.jpg." />
                                                <input
                                                    id="proof_image"
                                                    type="file"
                                                    name="proof_image"
                                                    accept="image/*"
                                                    required
                                                    class="block w-full text-sm text-gray-500
                                                        file:mr-4 file:py-2 file:px-4
                                                        file:rounded-md file:border-0
                                                        file:text-sm file:font-semibold
                                                        file:bg-indigo-50 file:text-indigo-700
                                                        hover:file:bg-indigo-100"
                                                />
                                                <p class="text-xs text-gray-500">Image (JPG, PNG) max 2MB.</p>
                                                <x-input-error :messages="$errors->get('proof_image')" class="mt-2" />
                                            </div>
                                        @endif

                                        @if($purchase->invoice_number && $purchase->proof_image)
                                            <p class="text-xs text-green-600 mt-3 font-medium flex items-center">
                                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                                Data complete. Ready to receive.
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-6 flex justify-end gap-3">
                                        <x-secondary-button type="button" @click="open = false" x-bind:disabled="submitting">
                                            Cancel
                                        </x-secondary-button>
                                        <x-primary-button
                                            class="!bg-green-600 hover:!bg-green-700 focus:!ring-green-500"
                                            x-bind:class="submitting ? 'opacity-75 cursor-not-allowed' : ''"
                                            x-bind:disabled="submitting"
                                        >
                                            <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            {{ __('Confirm Receipt') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Shared Confirmation Modal -->
                <x-modal name="confirmation-modal">
                    <div class="p-6" x-data="{ submitting: false }">
                        <h2 class="text-lg font-medium text-gray-900" x-text="modalTitle"></h2>

                        <p class="mt-1 text-sm text-gray-600" x-text="modalMessage"></p>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close-modal', { name: 'confirmation-modal' })" x-bind:disabled="submitting">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <form id="confirmation-form" method="POST" class="ml-3" x-ref="confirmForm" @submit.prevent>
                                @csrf
                                <input type="hidden" id="confirmation-method" name="_method" value="">

                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 text-white shadow-sm bg-primary"
                                    x-bind:class="confirmButtonClass + (submitting ? ' opacity-75 cursor-not-allowed' : '')"
                                    x-bind:disabled="submitting"
                                    @click="submitting = true; $refs.confirmForm.submit()"
                                >
                                    <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="confirmButtonText"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>

            </div>
        </div>
    </div>
</x-app-layout>
