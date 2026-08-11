# Subagent: Vendor Tax, Compliance, And Documents

## Mission

Implement vendor tax/compliance records and document upload management.

## Scope

- `vendor_tax_details` migration/model/service/UI.
- `vendor_documents` migration/model/service/UI.
- Document upload validation and storage.
- Expiry alert data support.
- Verification fields.

## Vendor Tax Fields

- Vendor ID
- Company ID, nullable
- Country
- Tax Type
- Tax Registration Number
- GSTIN
- PAN
- VAT Number
- TIN
- Withholding Tax Applicable
- Withholding Tax Code
- Tax Exempt
- Tax Exemption Number
- Tax Exemption Expiry
- MSME Registered
- MSME Number
- MSME Category
- Effective From
- Effective To
- Verified
- Verified By
- Status

## Vendor Document Fields

- Vendor ID
- Company ID, nullable
- Document Type
- Document Number
- Issue Date
- Expiry Date
- File Path
- Status
- Verified
- Verified By
- Verified At
- Notes
- Uploaded By

## Security Rules

- Validate extension, MIME type, and max size.
- Generate unique storage names.
- Store original filename only as metadata.
- Financial/tax documents require authenticated access.
- Do not trust client-side file validation.

## Acceptance Checks

- Vendor can have multiple tax records.
- Vendor can have multiple documents.
- Document expiry dates can be queried for alert windows.
- Verified metadata is recorded.
