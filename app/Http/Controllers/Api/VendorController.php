<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = (string) $request->input('q', $request->input('search', ''));

        $vendors = Vendor::query()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($subQuery) use ($query) {
                    $subQuery->where('vendor_code', 'like', "%{$query}%")
                        ->orWhere('vendor_name', 'like', "%{$query}%")
                        ->orWhere('legal_name', 'like', "%{$query}%");
                });
            })
            ->orderBy('vendor_name')
            ->limit(20)
            ->get(['id', 'vendor_code', 'vendor_name']);

        return response()->json($vendors->map(fn (Vendor $vendor) => [
            'id' => $vendor->id,
            'text' => $vendor->dropdownLabel(),
        ]));
    }
}
