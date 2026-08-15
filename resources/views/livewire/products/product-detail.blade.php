<x-modal name="product-detail-modal" focusable>
    @if($product)
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h3 class="text-xl font-bold text-foreground tracking-tight">{{ $product->name }}</h3>
                    <p class="text-sm text-muted-foreground font-mono">{{ $product->sku }}</p>
                </div>
                <div>
                    @if($product->is_active)
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                @if($product->image_url)
                    <div class="overflow-hidden rounded-md border border-gray-200 bg-gray-50">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-64 w-full object-contain">
                    </div>
                @endif

                <!-- Details -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <x-input-label value="{{ __('Brand') }}" hint="Brand or company this product belongs to. Example: Colorbar." class="text-muted-foreground" />
                        <p class="text-sm text-foreground font-medium">{{ $product->company?->short_name ?: ($product->company?->company_name ?? '-') }}</p>
                    </div>

                    <div class="space-y-1">
                        <x-input-label value="{{ __('Category') }}" hint="Product grouping for filtering and reports. Example: Lip Makeup." class="text-muted-foreground" />
                        <p class="text-sm text-foreground font-medium">{{ $product->category->name ?? '-' }}</p>
                    </div>

                    <div class="space-y-1">
                        <x-input-label value="{{ __('Unit') }}" hint="Measurement unit used for quantities. Example: pcs." class="text-muted-foreground" />
                        <p class="text-sm text-foreground font-medium">
                            @if($product->unit)
                                {{ $product->unit->name }} <span class="text-muted-foreground">({{ $product->unit->symbol }})</span>
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <div class="space-y-1">
                        <x-input-label value="{{ __('MRP') }}" hint="Maximum retail price before discounts. Example: 999." class="text-muted-foreground" />
                        <p class="text-sm text-foreground font-medium">@money($product->mrp)</p>
                    </div>

                    <div class="space-y-1">
                        <x-input-label value="{{ __('Minimum Quantity') }}" hint="Reorder alert level for inventory. Example: 10." class="text-muted-foreground" />
                        <p class="text-sm text-foreground font-medium">{{ $product->min_stock }}</p>
                    </div>
                </div>

                <div class="space-y-1">
                    <x-input-label value="{{ __('Description') }}" hint="Product details visible to users. Example: Long-wear matte finish." class="text-muted-foreground" />
                    <p class="text-sm text-foreground font-medium">
                        {{ $product->description ?: 'No description provided.' }}
                    </p>
                </div>

                <div class="space-y-1">
                    <x-input-label value="{{ __('Internal Notes') }}" hint="Private notes for staff. Example: Supplier revised price in July." class="text-muted-foreground" />
                    <div class="bg-gray-50 border border-secondary p-3 rounded-md">
                        <p class="text-sm text-foreground font-mono whitespace-pre-wrap leading-relaxed">{{ $product->notes ?: 'No notes.' }}</p>
                    </div>
                </div>

                <!-- Meta -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="text-sm font-medium leading-none text-muted-foreground">{{ __('Created At') }}</label>
                        <p class="text-sm text-foreground font-medium">{{ $product->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium leading-none text-muted-foreground">{{ __('Last Updated') }}</label>
                        <p class="text-sm text-foreground font-medium">{{ $product->updated_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-x-2 pt-4 border-t border-border">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'product-detail-modal' })">
                    {{ __('Close') }}
                </x-secondary-button>
                <x-primary-button type="button" x-on:click="$dispatch('close-modal', { name: 'product-detail-modal' }); $dispatch('edit-product', { product: {{ $product->id }} })">
                    <x-heroicon-o-pencil-square class="w-4 h-4 mr-2" />
                    {{ __('Edit Product') }}
                </x-primary-button>
            </div>
        </div>
    @else
        <div class="p-8 text-center flex flex-col items-center justify-center space-y-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            <span class="text-sm text-muted-foreground">{{ __('Loading details...') }}</span>
        </div>
    @endif
</x-modal>
