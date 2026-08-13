<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 rounded-lg border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
        <div class="space-y-2">
            <x-input-label for="supplier_id" :value="__('Supplier / Vendor')" required />
            <select id="supplier_id" name="supplier_id" x-init="initSupplierSelect($el)" x-model="supplier_id" autocomplete="off">
                <option value=""></option>
                @if(old('supplier_id'))
                    @php($oldSupplier = \App\Models\Supplier::find(old('supplier_id')))
                    @if($oldSupplier)
                        <option value="{{ $oldSupplier->id }}" selected>{{ $oldSupplier->name }}</option>
                    @endif
                @elseif(isset($purchase) && $purchase->supplier)
                    <option value="{{ $purchase->supplier_id }}" selected>{{ $purchase->supplier->name }}</option>
                @endif
            </select>
            <x-input-error :messages="$errors->get('supplier_id')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="invoice_number" :value="__('PO Reference (Optional)')" />
            <x-text-input id="invoice_number" name="invoice_number" :value="old('invoice_number', $purchase->invoice_number ?? '')" placeholder="Leave empty for draft" />
            <x-input-error :messages="$errors->get('invoice_number')" />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="space-y-2">
                <x-input-label for="purchase_date" :value="__('PO Date')" required />
                <x-text-input id="purchase_date" type="date" name="purchase_date" :value="old('purchase_date', $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : date('Y-m-d'))" />
                <x-input-error :messages="$errors->get('purchase_date')" />
            </div>
            <div class="space-y-2">
                <x-input-label for="due_date" :value="__('Expected Delivery')" />
                <x-text-input id="due_date" type="date" name="due_date" :value="old('due_date', $purchase->due_date ? \Carbon\Carbon::parse($purchase->due_date)->format('Y-m-d') : '')" />
                <x-input-error :messages="$errors->get('due_date')" />
            </div>
        </div>

        <div class="md:col-span-2 space-y-2">
            <x-input-label for="notes" :value="__('PO Notes / Terms')" />
            <textarea id="notes" name="notes" rows="2" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Terms, delivery instructions, or vendor notes...">{{ old('notes', $purchase->notes ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('notes')" />
        </div>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="space-y-2">
                <x-input-label for="company_id" :value="__('Brand')" required />
                <select id="company_id" name="company_id" x-init="initCompanySelect($el)" x-model="company_id" autocomplete="off">
                    <option value=""></option>
                    @if(old('company_id'))
                        @php($oldCompany = \App\Models\Company::find(old('company_id')))
                        @if($oldCompany)
                            <option value="{{ $oldCompany->id }}" selected>{{ $oldCompany->company_code }} : {{ $oldCompany->short_name ?: $oldCompany->company_name }}</option>
                        @endif
                    @elseif(isset($purchase) && $purchase->company)
                        <option value="{{ $purchase->company_id }}" selected>{{ $purchase->company->company_code }} : {{ $purchase->company->short_name ?: $purchase->company->company_name }}</option>
                    @endif
                </select>
                <x-input-error :messages="$errors->get('company_id')" />
                <p class="text-xs text-gray-500">Select a vendor first to load its brands.</p>
            </div>
            <div class="space-y-2 md:col-span-2">
                <x-input-label for="master_product_search" :value="__('Product Search')" />
                <select id="master_product_search" x-init="initMasterSearch($el)" placeholder="Select a brand first..." autocomplete="off"></select>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Brand</th>
                            <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">MRP</th>
                            <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">MRP Discount %</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Subtotal</th>
                            <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <template x-for="(item, index) in items" :key="item.key">
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900" x-text="item.product_name"></div>
                                    <div class="text-xs text-gray-500" x-text="item.product_code"></div>
                                    <input type="hidden" :name="`items[${index}][product_id]`" :value="item.product_id">
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700" x-text="item.brand || '-'"></td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.quantity" @input="calculateLine(index)" class="w-20 rounded-md border-gray-300 text-center text-sm" min="1">
                                </td>
                                <td class="px-4 py-3 text-right text-sm" x-text="window.formatMoney(item.mrp || 0)"></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <input type="number" :name="`items[${index}][discount_percent]`" x-model.number="item.discount_percent" @input="calculateLine(index)" class="w-24 rounded-md border-gray-300 text-right text-sm" min="0" max="100" step="0.01">
                                        <span class="text-sm text-gray-500">%</span>
                                    </div>
                                    <input type="hidden" :name="`items[${index}][unit_price]`" :value="item.unit_price">
                                    <input type="hidden" :name="`items[${index}][selling_price]`" :value="item.mrp || item.unit_price || 0">
                                    <div class="mt-1 text-xs text-gray-500" x-text="'Agreed: ' + window.formatMoney(item.unit_price)"></div>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold" x-text="window.formatMoney(item.subtotal)"></td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" @click="removeItem(index)" class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="items.length === 0">
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-gray-500">
                                    Search products above to add them from Product Master.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-right font-bold">Total PO</td>
                            <td class="px-4 py-4 text-right text-lg font-bold text-blue-600" x-text="window.formatMoney(total)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-x-4 border-t border-gray-200 pt-6">
        <a href="{{ route('purchases.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
            {{ __('Cancel') }}
        </a>

        <x-primary-button class="flex items-center gap-2" ::disabled="loading">
            <span x-text="loading ? 'Processing...' : ({{ isset($purchase->id) ? '`Update Purchase Order`' : '`Create Purchase Order`' }})"></span>
        </x-primary-button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseForm', (initialData) => ({
            items: (initialData.items || []).map(i => ({
                ...i,
                key: i.key || Math.random().toString(36).slice(2),
                mrp: parseInt(i.mrp || i.selling_price || 0),
                discount_percent: i.discount_percent ?? ((parseInt(i.mrp || i.selling_price || 0) > 0)
                    ? Math.max(0, Math.min(100, Number((100 - ((parseInt(i.unit_price) || 0) / parseInt(i.mrp || i.selling_price || 0) * 100)).toFixed(2))))
                    : 0),
                subtotal: parseInt(i.subtotal) || ((parseInt(i.quantity) || 0) * (parseInt(i.unit_price) || 0))
            })),
            supplier_id: initialData.supplier_id || '',
            company_id: initialData.company_id || '',
            loading: false,
            errors: initialData.errors || {},

            submitForm(e) {
                if (this.loading) return;
                this.loading = true;
                e.target.submit();
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            calculateLine(index) {
                const item = this.items[index];
                item.discount_percent = Math.max(0, Math.min(100, parseFloat(item.discount_percent) || 0));
                item.unit_price = Math.round((parseInt(item.mrp) || 0) * (1 - item.discount_percent / 100));
                item.subtotal = (parseInt(item.quantity) || 0) * (parseInt(item.unit_price) || 0);
            },

            get total() {
                return this.items.reduce((sum, item) => sum + (parseInt(item.subtotal) || 0), 0);
            },

            waitForTomSelect(callback) {
                if (window.TomSelect) callback();
                else setTimeout(() => this.waitForTomSelect(callback), 50);
            },

            initSupplierSelect(el) {
                this.initRemoteSelect(el, '{{ route("ajax.suppliers.search") }}', value => {
                    if (this.supplier_id == value) return;
                    this.supplier_id = value;
                    this.company_id = '';
                    this.items = [];
                    const brandSelect = document.getElementById('company_id')?.tomselect;
                    brandSelect?.clear(true);
                    brandSelect?.clearOptions();
                    if (value) brandSelect?.load('');
                    this.updateProductSearchState();
                }, 'Select Supplier / Vendor...');
            },

            initCompanySelect(el) {
                this.waitForTomSelect(() => {
                    new TomSelect(el, {
                        placeholder: 'Select Brand...',
                        preload: false,
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        load: (query, callback) => {
                            if (!this.supplier_id) return callback();
                            this.fetchOptions('{{ route("ajax.companies.search") }}', { q: query, supplier_id: this.supplier_id }, callback);
                        },
                        onChange: value => {
                            if (this.company_id != value) this.items = [];
                            this.company_id = value;
                            this.updateProductSearchState();
                        }
                    });
                });
            },

            fetchOptions(url, data, callback) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                }).then(r => r.json()).then(callback).catch(() => callback());
            },

            updateProductSearchState() {
                const search = document.getElementById('master_product_search')?.tomselect;
                if (!search) return;
                search.clear(true);
                search.clearOptions();
                this.company_id ? search.enable() : search.disable();
                search.settings.placeholder = this.company_id ? 'Search products for selected brand...' : 'Select a brand first...';
                search.inputState();
                if (this.company_id) search.load('');
            },

            initRemoteSelect(el, url, onChange, placeholder) {
                this.waitForTomSelect(() => {
                    new TomSelect(el, {
                        placeholder,
                        preload: 'focus',
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        onChange,
                        load(query, callback) {
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ q: query })
                            }).then(r => r.json()).then(callback).catch(() => callback());
                        }
                    });
                });
            },

            addProduct(product) {
                const existingIndex = this.items.findIndex(i => i.product_id == product.value);
                if (existingIndex !== -1) {
                    this.items[existingIndex].quantity += 1;
                    this.calculateLine(existingIndex);
                    return;
                }

                const price = parseInt(product.price || product.mrp || 0);
                this.items.push({
                    key: Math.random().toString(36).slice(2),
                    product_id: product.value,
                    product_name: product.text,
                    product_code: product.sku,
                    brand: product.brand,
                    mrp: parseInt(product.mrp || price || 0),
                    discount_percent: 0,
                    quantity: 1,
                    unit_price: price,
                    subtotal: price
                });
            },

            initMasterSearch(el) {
                this.waitForTomSelect(() => {
                    new TomSelect(el, {
                        placeholder: 'Search Product from Product Master...',
                        preload: 'focus',
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        load: (query, callback) => {
                            if (!this.company_id) return callback();
                            this.fetchOptions('{{ route("ajax.products.search") }}', {
                                q: query,
                                company_id: this.company_id,
                                for_purchase: true
                            }, callback);
                        },
                        onItemAdd: (value) => {
                            const data = el.tomselect.options[value];
                            if (data) this.addProduct(data);
                            el.tomselect.clear(true);
                            el.tomselect.focus();
                        }
                    });
                    this.updateProductSearchState();
                });
            }
        }));
    });
</script>
@endpush
