# Subagent: Vendor Bank Accounts And Financial Security

## Mission

Implement vendor bank accounts with strict encryption, masking, verification, and audit behavior.

## Scope

- `vendor_bank_accounts` migration/model/service/UI.
- Account number encryption.
- Account number last-four indexing.
- Masked display by default.
- Bank verification workflow hooks.
- Audit entries for bank changes.

## Required Fields

- Vendor ID
- Company ID, nullable
- Account Name
- Bank Name
- Bank Branch
- Account Number Encrypted
- Account Number Last4
- Account Type
- Routing Number
- IFSC Code
- SWIFT/BIC
- IBAN
- MICR Code
- Branch Code
- Country
- Currency ID
- Beneficiary Name
- Beneficiary Address
- Payment Method
- Is Primary
- Is Verified
- Verification Status
- Verification Date
- Verified By
- Verification Notes
- Active
- Effective From
- Effective To
- Created By
- Updated By

## Security Rules

- Encrypt full account number at rest.
- Never log raw account number.
- Store last four digits separately for search.
- Mask account numbers in UI and exports.
- Full account reveal requires a separate permission.
- Audit bank changes without full secret values.
- Do not expose full bank details through list APIs.
- Do not put bank data in URLs.

## Business Rules

- One active primary account per vendor/company/currency combination where practical.
- Support secondary and inactive accounts.
- Bank account changes may require approval later.

## Acceptance Checks

- Multiple accounts are allowed.
- Primary account logic works.
- Masking works.
- Unauthorized full-account reveal is denied.
- Bank changes are audited safely.
