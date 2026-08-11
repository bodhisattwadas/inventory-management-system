# Subagent: Vendor Search, Reports, And Dashboard Widgets

## Mission

Implement search/report architecture for vendor management without compromising data security.

## Scope

- Vendor list global search.
- Vendor filters.
- Reports pages/exports.
- Dashboard widgets.
- Query indexes where needed.

## Vendor List Search Fields

- Vendor Code
- Vendor Name
- Legal Name
- Contact Name
- Email
- Phone
- GSTIN
- PAN
- Bank Account Last Four Digits
- Company Name

## Required Filters

- Vendor Code
- Vendor Name
- Company
- Vendor Type
- Vendor Category
- City
- State
- Country
- GSTIN / Tax ID
- Payment Terms
- Currency
- Active / Inactive
- Approved / Pending / Blocked
- Preferred Vendor
- Purchase Enabled
- Payment Enabled

## Reports To Prepare

- Vendor Master Report
- Vendors by Company
- Companies by Vendor
- Vendor Contact Report
- Vendor Bank Verification Report
- Vendor Tax Compliance Report
- Vendor Document Expiry Report
- Preferred Vendor Report
- Blocked Vendor Report
- Vendor Item Price List
- Vendor Purchase History
- Vendor Outstanding Report
- Vendor Performance Report

## Dashboard Widgets

- Total Vendors
- Active Vendors
- Pending Approval
- Blocked Vendors
- Preferred Vendors
- Vendors Missing Bank Details
- Vendors Missing Tax Details
- Expiring Documents
- Vendors Without Company Assignment
- Vendors Added This Month

## Security Rules

- Do not export full bank account numbers by default.
- Do not expose sensitive documents in anonymous/public URLs.
- Mask financial data in table/list views.

## Acceptance Checks

- Vendor can be searched by company.
- Reports can filter by company/vendor/status/category.
- Exported bank data remains masked.
