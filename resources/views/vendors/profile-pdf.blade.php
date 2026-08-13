<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $vendor->vendor_name }} - Vendor Profile</title>
    <style>
        @page { margin: 28px 32px 42px; }
        * { box-sizing: border-box; }
        body { color: #172033; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.45; }
        .header { background: #173b68; color: #fff; padding: 22px 24px; border-radius: 8px; }
        .header h1 { margin: 0 0 4px; font-size: 23px; }
        .header p { margin: 0; color: #dbeafe; }
        .badge { display: inline-block; margin-left: 6px; padding: 3px 8px; border-radius: 10px; background: #e8f1fb; color: #173b68; font-size: 8px; font-weight: bold; }
        .summary { width: 100%; margin: 14px 0; border-collapse: separate; border-spacing: 6px 0; }
        .summary td { width: 25%; padding: 10px; background: #f3f6fa; border: 1px solid #dde5ee; text-align: center; }
        .summary strong { display: block; color: #173b68; font-size: 16px; }
        .section { margin-top: 14px; page-break-inside: avoid; }
        .section h2 { margin: 0 0 7px; padding: 6px 9px; color: #173b68; background: #eaf1f8; border-left: 4px solid #2d6da3; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { padding: 6px 7px; border: 1px solid #dce3ea; vertical-align: top; }
        table.data th { background: #f4f6f8; color: #4b5563; font-size: 8px; text-align: left; }
        .label { color: #697386; font-size: 8px; text-transform: uppercase; }
        .value { margin-top: 2px; font-weight: bold; }
        .muted { color: #788495; }
        .yes { color: #087443; font-weight: bold; } .no { color: #9b2c2c; font-weight: bold; }
        .footer { position: fixed; bottom: -26px; left: 0; right: 0; color: #7b8794; font-size: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="footer">Vendor profile generated {{ now()->format('d M Y, h:i A') }} · Confidential business record</div>

    <div class="header">
        <h1>{{ $vendor->vendor_name }}</h1>
        <p>{{ $vendor->vendor_code }} · {{ $vendor->legal_name ?: 'Vendor Profile' }}</p>
    </div>

    <table class="summary"><tr>
        <td><strong>{{ $vendor->companies->count() }}</strong>Companies</td>
        <td><strong>{{ $vendor->contacts->count() }}</strong>Contacts</td>
        <td><strong>{{ $vendor->bankAccounts->count() }}</strong>Bank Accounts</td>
        <td><strong>{{ $vendor->items->count() }}</strong>Items</td>
    </tr></table>

    <div class="section">
        <h2>Business Profile</h2>
        <table class="data">
            <tr>
                <td><div class="label">Legal Name</div><div class="value">{{ $vendor->legal_name ?: '-' }}</div></td>
                <td><div class="label">Trade Name</div><div class="value">{{ $vendor->trade_name ?: '-' }}</div></td>
                <td><div class="label">Vendor Type</div><div class="value">{{ $vendor->vendor_type ?: '-' }}</div></td>
                <td><div class="label">Category</div><div class="value">{{ $vendor->category?->category_name ?? '-' }}</div></td>
            </tr>
            <tr>
                <td><div class="label">Business Type</div><div class="value">{{ $vendor->business_type ?: '-' }}</div></td>
                <td><div class="label">Industry</div><div class="value">{{ $vendor->industry ?: '-' }}</div></td>
                <td><div class="label">Registration No.</div><div class="value">{{ $vendor->registration_number ?: '-' }}</div></td>
                <td><div class="label">Incorporated</div><div class="value">{{ $vendor->incorporation_date?->format('d M Y') ?? '-' }}</div></td>
            </tr>
            <tr><td colspan="2"><div class="label">Website</div><div class="value">{{ $vendor->website ?: '-' }}</div></td><td colspan="2"><div class="label">Description</div><div class="value">{{ $vendor->business_description ?: '-' }}</div></td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Contact Information</h2>
        <table class="data">
            <tr><td>{{ $vendor->primary_contact_person ?: '-' }}</td><td>{{ $vendor->primary_email ?: '-' }}</td><td>{{ $vendor->primary_phone ?: '-' }}</td><td>{{ $vendor->alternate_phone ?: '-' }}</td></tr>
            <tr><th>Primary contact</th><th>Primary email</th><th>Primary phone</th><th>Alternate phone</th></tr>
            <tr><td colspan="2">{{ $vendor->accounts_email ?: '-' }}</td><td colspan="2">{{ $vendor->po_email ?: '-' }}</td></tr>
            <tr><th colspan="2">Accounts email</th><th colspan="2">Purchase order email</th></tr>
        </table>
    </div>

    <div class="section">
        <h2>Status & Controls</h2>
        <table class="data"><tr>
            <td>Status <span class="badge">{{ Str::title($vendor->status) }}</span></td>
            <td>Approval <span class="badge">{{ Str::title($vendor->approval_status) }}</span></td>
            <td>Risk: <strong>{{ Str::title($vendor->risk_level ?: 'Not set') }}</strong></td>
            <td>Preferred: <span class="{{ $vendor->preferred_vendor ? 'yes' : 'no' }}">{{ $vendor->preferred_vendor ? 'Yes' : 'No' }}</span></td>
        </tr><tr>
            <td>Purchase enabled: {{ $vendor->purchase_enabled ? 'Yes' : 'No' }}</td><td>Payment enabled: {{ $vendor->payment_enabled ? 'Yes' : 'No' }}</td>
            <td>Advance payment: {{ $vendor->allow_advance_payment ? 'Yes' : 'No' }}</td><td>Blocked / Blacklisted: {{ $vendor->blocked ? 'Blocked' : ($vendor->blacklisted ? 'Blacklisted' : 'No') }}</td>
        </tr>
        @if($vendor->blocked_reason || $vendor->blacklist_reason || $vendor->rejection_reason)
            <tr><td colspan="4"><strong>Remarks:</strong> {{ $vendor->blocked_reason ?: ($vendor->blacklist_reason ?: $vendor->rejection_reason) }}</td></tr>
        @endif
        </table>
    </div>

    <div class="section">
        <h2>Companies & Commercial Terms</h2>
        <table class="data"><thead><tr><th>Company</th><th>Reference</th><th>Credit Limit</th><th>Lead Time</th><th>Status</th></tr></thead><tbody>
        @forelse($vendor->companies as $company)
            <tr><td>{{ $company->company_name }}{{ $company->pivot->is_primary ? ' (Primary)' : '' }}</td><td>{{ $company->pivot->vendor_code_for_company ?: '-' }}</td><td>{{ $company->pivot->credit_limit !== null ? number_format($company->pivot->credit_limit, 2) : '-' }}</td><td>{{ $company->pivot->lead_time_days !== null ? $company->pivot->lead_time_days.' days' : '-' }}</td><td>{{ Str::title($company->pivot->status) }}</td></tr>
        @empty <tr><td colspan="5" class="muted">No companies assigned.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Contacts</h2>
        <table class="data"><thead><tr><th>Name</th><th>Role</th><th>Phone</th><th>Email</th><th>Flags</th></tr></thead><tbody>
        @forelse($vendor->contacts as $contact)
            <tr><td>{{ trim($contact->first_name.' '.$contact->last_name) }}</td><td>{{ collect([$contact->designation, $contact->department])->filter()->join(' / ') ?: '-' }}</td><td>{{ $contact->mobile ?: ($contact->phone ?: '-') }}</td><td>{{ $contact->email ?: '-' }}</td><td>{{ $contact->is_primary ? 'Primary; ' : '' }}{{ $contact->receives_po ? 'Receives PO' : '' }}</td></tr>
        @empty <tr><td colspan="5" class="muted">No contacts recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Addresses</h2>
        <table class="data"><thead><tr><th>Type</th><th>Address</th><th>Contact</th></tr></thead><tbody>
        @forelse($vendor->addresses as $address)
            <tr><td>{{ Str::title($address->address_type ?: 'Address') }}{{ $address->is_default ? ' (Default)' : '' }}</td><td>{{ collect([$address->address_line_1,$address->address_line_2,$address->landmark,$address->city,$address->district,$address->state,$address->postal_code,$address->country])->filter()->join(', ') }}</td><td>{{ collect([$address->phone,$address->email])->filter()->join(' / ') ?: '-' }}</td></tr>
        @empty <tr><td colspan="3" class="muted">No addresses recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Bank Accounts</h2>
        <table class="data"><thead><tr><th>Bank / Branch</th><th>Account</th><th>Type</th><th>Beneficiary</th><th>IFSC / SWIFT</th><th>Verification</th></tr></thead><tbody>
        @forelse($vendor->bankAccounts as $account)
            <tr><td>{{ $account->bank_name }}{{ $account->bank_branch ? ' / '.$account->bank_branch : '' }}</td><td>{{ $account->masked_account_number }}</td><td>{{ $account->account_type ?: '-' }}</td><td>{{ $account->beneficiary_name ?: '-' }}</td><td>{{ $account->ifsc_code ?: ($account->swift_bic ?: '-') }}</td><td>{{ Str::title($account->verification_status) }}{{ $account->is_primary ? ' · Primary' : '' }}</td></tr>
        @empty <tr><td colspan="6" class="muted">No bank accounts recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Tax & Compliance</h2>
        <table class="data"><thead><tr><th>Type / Country</th><th>Registration</th><th>GSTIN</th><th>PAN</th><th>MSME</th><th>Status</th></tr></thead><tbody>
        @forelse($vendor->taxDetails as $tax)
            <tr><td>{{ $tax->tax_type ?: '-' }} / {{ $tax->country ?: '-' }}</td><td>{{ $tax->tax_registration_number ?: '-' }}</td><td>{{ $tax->gstin ?: '-' }}</td><td>{{ $tax->pan ?: '-' }}</td><td>{{ $tax->msme_registered ? ($tax->msme_number ?: 'Registered') : 'No' }}</td><td>{{ Str::title($tax->status) }}{{ $tax->verified ? ' · Verified' : '' }}</td></tr>
        @empty <tr><td colspan="6" class="muted">No tax details recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Supplied Items</h2>
        <table class="data"><thead><tr><th>Vendor Code</th><th>Item</th><th>Part No.</th><th>MOQ</th><th>Price</th><th>Lead Time</th></tr></thead><tbody>
        @forelse($vendor->items as $item)
            <tr><td>{{ $item->vendor_item_code ?: '-' }}</td><td>{{ $item->vendor_item_name ?: '-' }}</td><td>{{ $item->vendor_part_number ?: '-' }}</td><td>{{ $item->minimum_order_quantity ?? '-' }}</td><td>{{ $item->purchase_price !== null ? number_format($item->purchase_price, 2) : '-' }}</td><td>{{ $item->lead_time_days !== null ? $item->lead_time_days.' days' : '-' }}</td></tr>
        @empty <tr><td colspan="6" class="muted">No supplied items recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Documents</h2>
        <table class="data"><thead><tr><th>Type</th><th>Number / File</th><th>Issued</th><th>Expires</th><th>Status</th></tr></thead><tbody>
        @forelse($vendor->documents as $document)
            <tr><td>{{ Str::title($document->document_type) }}</td><td>{{ $document->document_number ?: ($document->original_filename ?: '-') }}</td><td>{{ $document->issue_date?->format('d M Y') ?? '-' }}</td><td>{{ $document->expiry_date?->format('d M Y') ?? '-' }}</td><td>{{ Str::title($document->status) }}{{ $document->verified ? ' · Verified' : '' }}</td></tr>
        @empty <tr><td colspan="5" class="muted">No documents recorded.</td></tr> @endforelse
        </tbody></table>
    </div>

    <div class="section">
        <h2>Status History & Record Metadata</h2>
        <table class="data"><thead><tr><th>Date</th><th>From</th><th>To</th><th>Reason</th></tr></thead><tbody>
        @forelse($vendor->statusHistory->sortByDesc('created_at') as $history)
            <tr><td>{{ $history->created_at?->format('d M Y, h:i A') }}</td><td>{{ Str::title($history->from_status ?: '-') }}</td><td>{{ Str::title($history->to_status) }}</td><td>{{ $history->reason ?: '-' }}</td></tr>
        @empty <tr><td colspan="4" class="muted">No status changes recorded.</td></tr> @endforelse
        </tbody></table>
        <p class="muted">Created: {{ $vendor->created_at?->format('d M Y, h:i A') }} · Last updated: {{ $vendor->updated_at?->format('d M Y, h:i A') }}</p>
    </div>
</body>
</html>
