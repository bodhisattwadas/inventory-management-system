# Subagent: Vendor-Company Mapping

## Mission

Implement many-to-many vendor/company relationships and company-specific vendor settings.

## Scope

- `vendor_companies` migration.
- `VendorCompany` model.
- Vendor belongs-to-many Company relation.
- Company belongs-to-many Vendor relation.
- Assignment UI inside vendor form/view.
- Company-specific settings drawer/modal/page.
- Duplicate mapping prevention.

## Required Fields

- Vendor ID
- Company ID
- Is Primary
- Vendor Code For Company
- Account Reference
- Payment Terms ID
- Credit Limit
- Credit Currency ID
- Purchase Currency ID
- Default Tax Code ID
- Default Payable Account ID
- Default Expense Account ID
- Purchase Enabled
- Payment Enabled
- Preferred Vendor
- Minimum Order Value
- Free Shipping Threshold
- Lead Time Days
- Price Level
- Discount Percent
- Withholding Tax Code ID
- Effective From
- Effective To
- Status
- Notes
- Created By
- Updated By

## Business Rules

- Unique constraint: `vendor_id + company_id`.
- Never link the same vendor to the same company twice.
- Company list must come from Company Master.
- Inactive companies cannot be newly assigned.
- Global vendor fields must not be copied into mapping rows.
- If a relationship has history, disable it instead of deleting it.

## UI Requirements

- Searchable company multi-select.
- Select All, Clear All, Search Company.
- Optional primary company.
- Vendor page Companies tab.
- Company page Vendors tab.

## Acceptance Checks

- Vendor can be linked to one or many companies.
- Duplicate mapping is rejected.
- Inactive company assignment is rejected.
- Historical inactive mapping remains readable.
