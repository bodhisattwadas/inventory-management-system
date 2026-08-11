# Subagent: Vendor Contacts And Addresses

## Mission

Implement vendor contact and address management with optional company-specific records.

## Scope

- `vendor_contacts` migration/model/service/UI.
- `vendor_addresses` migration/model/service/UI.
- Contacts and Addresses tabs on Vendor View.
- Inline create/edit/deactivate actions.

## Vendor Contacts Fields

- Vendor ID
- Contact Type
- Salutation
- First Name
- Last Name
- Designation
- Department
- Phone
- Alternate Phone
- Mobile
- Email
- Secondary Email
- WhatsApp Number
- Company ID, nullable
- Is Primary
- Receives PO
- Receives Payment Advice
- Receives RFQ
- Receives Statement
- Active
- Notes

## Vendor Addresses Fields

- Vendor ID
- Address Type
- Address Name
- Attention
- Address Line 1
- Address Line 2
- Landmark
- City
- District
- State
- Postal Code
- Country
- Phone
- Email
- Company ID, nullable
- Is Default
- Active

## Business Rules

- `company_id = null` means global to the vendor.
- `company_id` set means specific to that vendor-company relationship.
- Only one active primary contact per vendor/company/contact purpose where practical.
- Only one active default address per vendor/company/address type where practical.
- Deactivate referenced records instead of hard-deleting.

## UI Requirements

- Vendor View tabs: Contacts, Addresses.
- Show global and company-specific badges.
- Search/filter by contact type, company, active status.

## Acceptance Checks

- Multiple contacts are allowed.
- Multiple addresses are allowed.
- Company-specific contacts/addresses do not duplicate global vendor data.
