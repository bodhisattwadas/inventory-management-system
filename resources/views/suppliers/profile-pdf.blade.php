@php
    $storeName = \App\Models\Setting::get('store_name', config('app.name'));
    $storeAddress = \App\Models\Setting::get('store_address', '-');
    $storePhone = \App\Models\Setting::get('store_phone', '-');
    $storeEmail = \App\Models\Setting::get('store_email', '-');
    $supplierAddress = collect([
        $supplier->address_line_1,
        $supplier->address_line_2,
        $supplier->city,
        $supplier->state,
        $supplier->postal_code,
        $supplier->country,
    ])->filter()->join(', ') ?: ($supplier->address ?: '-');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $supplier->name }} - Vendor Profile</title>
    <style>
        @page { margin: 18px 24px 46px; }
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 8.5px; line-height: 1.22; }
        .header { background: #173b68; color: #fff; padding: 12px 14px; border-radius: 5px; }
        .header h1 { font-size: 18px; margin: 0 0 2px; }
        .header p { margin: 0; color: #dbeafe; font-size: 8.5px; }
        .meta { float: right; text-align: right; font-size: 8px; color: #dbeafe; }
        .section { margin-top: 8px; page-break-inside: avoid; }
        .section h2 { font-size: 8.5px; text-transform: uppercase; color: #173b68; background: #eaf1f8; border-left: 3px solid #2d6da3; padding: 4px 6px; margin: 0 0 4px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 4px 5px; border: 1px solid #dce3ea; text-align: left; vertical-align: top; }
        th { font-size: 7px; text-transform: uppercase; color: #64748b; background: #f5f7fa; }
        .label { font-size: 7px; text-transform: uppercase; color: #64748b; }
        .value { font-weight: bold; margin-top: 1px; }
        .badge { display: inline-block; background: #e8f1fb; color: #173b68; padding: 1px 5px; border-radius: 7px; }
        .muted { color: #7b8794; }
        .two-col { width: 100%; }
        .two-col td { width: 50%; border: 0; padding: 0; vertical-align: top; }
        .two-col .left { padding-right: 5px; }
        .two-col .right { padding-left: 5px; }
        .footer { position: fixed; bottom: -34px; left: 0; right: 0; text-align: center; color: #5f6b7a; font-size: 7.5px; line-height: 1.25; border-top: 1px solid #dce3ea; padding-top: 5px; }
        .footer strong { color: #173b68; }
    </style>
</head>
<body>
    <div class="footer">
        <strong>{{ $storeName }}</strong> |
        {{ $storeAddress }} |
        Phone: {{ format_indian_phone($storePhone) }} |
        Email: {{ $storeEmail }}
    </div>

    <div class="header">
        <div class="meta">Generated {{ now()->format('d M Y, h:i A') }}<br>Account numbers shown in full</div>
        <h1>{{ $supplier->name }}</h1>
        <p>{{ $supplier->legal_name ?: 'Supplier / Vendor Profile' }}</p>
    </div>

    <table class="two-col">
        <tr>
            <td class="left">
                <div class="section">
                    <h2>Business Details</h2>
                    <table>
                        <tr><td><div class="label">Trade Name</div><div class="value">{{ $supplier->trade_name ?: '-' }}</div></td><td><div class="label">Supplier Type</div><div class="value">{{ $supplier->supplier_type ?: '-' }}</div></td></tr>
                        <tr><td><div class="label">Industry</div><div class="value">{{ $supplier->industry ?: '-' }}</div></td><td><div class="label">Status</div><div class="value"><span class="badge">{{ Str::title($supplier->status) }}</span></div></td></tr>
                        <tr><td><div class="label">Registration</div><div class="value">{{ $supplier->registration_number ?: '-' }}</div></td><td><div class="label">GST / Tax ID</div><div class="value">{{ $supplier->tax_id ?: '-' }}</div></td></tr>
                        <tr><td colspan="2"><div class="label">Website</div><div class="value">{{ $supplier->website ?: '-' }}</div></td></tr>
                    </table>
                </div>

                <div class="section">
                    <h2>Contact & Address</h2>
                    <table>
                        <tr><th>Contact</th><th>Email</th></tr>
                        <tr><td>{{ $supplier->contact_person ?: '-' }}</td><td>{{ $supplier->email ?: '-' }}</td></tr>
                        <tr><th>Phone 1</th><th>Phone 2</th></tr>
                        <tr><td>{{ format_indian_phone($supplier->phone) }}</td><td>{{ format_indian_phone($supplier->alternate_phone) }}</td></tr>
                        <tr><th colspan="2">Address</th></tr>
                        <tr><td colspan="2">{{ $supplierAddress }}</td></tr>
                    </table>
                </div>
            </td>
            <td class="right">
                <div class="section">
                    <h2>Bank Details</h2>
                    <table>
                        <tr><th>Bank</th><th>Branch</th></tr>
                        <tr><td>{{ $supplier->bank_name ?: '-' }}</td><td>{{ $supplier->bank_branch ?: '-' }}</td></tr>
                        <tr><th>Account</th><th>Type</th></tr>
                        <tr><td>{{ $supplier->full_account_number ?: '-' }}</td><td>{{ $supplier->account_type ?: '-' }}</td></tr>
                        <tr><th>IFSC</th><th>SWIFT / BIC</th></tr>
                        <tr><td>{{ $supplier->ifsc_code ?: '-' }}</td><td>{{ $supplier->swift_bic ?: '-' }}</td></tr>
                        <tr><th colspan="2">Beneficiary</th></tr>
                        <tr><td colspan="2">{{ $supplier->beneficiary_name ?: '-' }}</td></tr>
                    </table>
                </div>

                <div class="section">
                    <h2>Notes & Record</h2>
                    <table>
                        <tr><td>{{ Str::limit($supplier->notes ?: 'No notes.', 240) }}</td></tr>
                        <tr><td class="muted">Created {{ $supplier->created_at?->format('d M Y, h:i A') }} | Updated {{ $supplier->updated_at?->format('d M Y, h:i A') }}</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Brands / Companies Supplied</h2>
        <table>
            <thead><tr><th>Code</th><th>Brand / Company</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($supplier->companies as $company)
                    <tr><td>{{ $company->company_code }}</td><td>{{ $company->short_name ?: $company->company_name }}</td><td>{{ Str::title($company->status) }}</td></tr>
                @empty
                    <tr><td colspan="3" class="muted">No brands or companies assigned.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
