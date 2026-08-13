<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class VendorProfileController extends Controller
{
    public function download(Vendor $vendor): Response
    {
        $vendor->load([
            'category', 'companies', 'contacts', 'addresses',
            'bankAccounts.company', 'taxDetails', 'documents',
            'items', 'statusHistory',
        ]);

        $filename = Str::slug($vendor->vendor_code . '-' . $vendor->vendor_name) . '-profile.pdf';

        return Pdf::loadView('vendors.profile-pdf', compact('vendor'))
            ->setPaper('a4')
            ->download($filename);
    }
}
