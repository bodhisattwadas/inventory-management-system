# Subagent: Company Master

## Mission

Implement the Company Master foundation used by Vendor / Supplier Management.

## Scope

- `companies` table migration.
- `Company` model.
- Company DTO/service layer.
- Company list page.
- Company create/edit/view workflows.
- AJAX company search endpoint for vendor assignment.
- Seeder/factory/test data with realistic but non-hard-coded business names.

## Required Fields

- Company Code
- Company Name
- Legal Name
- Short Name
- Company Type
- Parent Company, nullable
- Registration Number
- GSTIN
- PAN
- CIN
- Tax Registration Number
- Incorporation Date
- Primary Email
- Phone
- Website
- Address Line 1
- Address Line 2
- City
- District
- State
- Postal Code
- Country
- Base Currency
- Financial Year Start
- Default Payment Terms
- Default Purchase Tax
- Default Payable Account
- Status

## Business Rules

- Company Code must be unique.
- Inactive companies remain visible in old records.
- Inactive companies must not be selectable for new vendor assignments.
- Company names must only come from Company Master, never hard-coded vendor logic.
- Do not physically delete companies once referenced.

## UI Requirements

- Route: `/companies`
- Add Company action.
- View, Edit, Activate, Deactivate actions.
- Search, filters, pagination, export.
- ERP-style compact table layout.

## Acceptance Checks

- Admin can create unlimited companies.
- Inactive companies are readable but excluded from new assignment selectors.
- Company search endpoint returns only active companies by default.
- Tests cover create, update, activation, deactivation, and delete protection.
