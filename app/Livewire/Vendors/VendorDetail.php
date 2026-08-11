<?php

namespace App\Livewire\Vendors;

use App\Models\Vendor;
use Livewire\Attributes\On;
use Livewire\Component;

class VendorDetail extends Component
{
    public ?Vendor $vendor = null;

    public function render()
    {
        return view('livewire.vendors.vendor-detail');
    }

    #[On('show-vendor')]
    public function show(Vendor $vendor): void
    {
        $this->vendor = $vendor->load([
            'companies',
            'contacts',
            'addresses',
            'bankAccounts',
            'taxDetails',
            'documents',
            'items',
            'statusHistory',
        ]);
        $this->dispatch('open-modal', name: 'vendor-detail-modal');
    }
}
