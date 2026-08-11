# Subagent: Vendor Master

## Mission

Implement the core Vendor Master without duplicating vendors per company.

## Scope

- `vendors` table migration.
- `Vendor` model.
- Vendor DTO/service layer.
- Vendor list page.
- Vendor create/edit/view workflow.
- Vendor code generation.
- Vendor status fields and flags.
- Vendor category master dependency.

## Core Rule

One vendor is one master entity. A vendor serving multiple companies must be represented through `vendor_companies`, not duplicate vendor rows.

## Required Fields

- Vendor Code, format `VND-000001`
- Vendor Name
- Legal Name
- Trade Name
- Vendor Type
- Vendor Category ID
- Vendor Group ID, nullable
- Parent Vendor ID, nullable
- Business Type
- Registration Number
- Incorporation Date
- Website
- Industry
- Business Description
- Primary Contact Person
- Primary Email
- Accounts Email
- Purchase Order Email
- Primary Phone
- Alternate Phone
- Default Payment Terms ID
- Default Currency ID
- Default Payment Method ID
- Preferred Vendor
- Purchase Enabled
- Payment Enabled
- Allow Advance Payment
- Status
- Approval Status
- Risk Level
- Blocked
- Blocked Reason
- Blacklisted
- Blacklist Reason
- Created By
- Updated By
- Approved By
- Approved At

## Business Rules

- Vendor Code must be unique and stable after creation.
- Generate Vendor Code automatically unless a later setting allows manual codes.
- Vendor Category must come from Vendor Category Master.
- Blacklisted vendors require a reason.
- Blocked vendors require a reason.
- Vendors with transaction history must be blocked/deactivated, not hard-deleted.

## UI Requirements

- Route: `/vendors`
- Route: `/vendors/create`
- Route: `/vendors/{vendor}`
- Route: `/vendors/{vendor}/edit`
- Vendor dropdown display format: `VND-000001 : Vendor Name`
- Table shows company badges from mappings.

## Acceptance Checks

- Vendor can be created once and linked to many companies.
- Vendor code is unique.
- Vendor can be updated without changing code.
- Vendor can be deactivated.
- Duplicate detection warns on matching legal identifiers.
