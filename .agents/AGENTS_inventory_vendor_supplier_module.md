# AGENTS.md
# Inventory Management Portal — Vendor / Supplier Management Module

## 1. Purpose

Build a professional Vendor / Supplier Management module for an Inventory Management Portal.

The module must support:

- One vendor serving one or many companies.
- Company names managed independently from a separate Company Master page.
- Vendor master information stored only once.
- Company-specific vendor settings stored separately.
- Multiple contacts, addresses, bank accounts, tax records, documents, and purchasing settings per vendor.
- Secure storage and display of financial information.
- Future integration with Purchase Orders, Goods Receipts, Vendor Bills, Payments, Returns, Inventory Items, and Vendor Performance.

The overall design should follow enterprise ERP patterns similar to Oracle NetSuite, but the user experience should remain simple and practical.

---

# 2. Core Business Rule

A Vendor is one master entity.

Example:

Vendor ID: VND-000001  
Vendor Name: ABC Industrial Supplies Pvt Ltd

Companies Covered:

- Company A
- Company B
- Company C

Do NOT create three duplicate vendor records.

Instead:

Vendor Master
    |
    +---- Vendor Company Mapping ---- Company A
    |
    +---- Vendor Company Mapping ---- Company B
    |
    +---- Vendor Company Mapping ---- Company C

The list of companies must always come from the Company Master module.

No company name should be hard-coded in vendor forms, database seed logic, dropdown options, validations, or source code.

---

# 3. Main Modules

Create the following sections.

1. Company Master
2. Vendor Master
3. Vendor-Company Mapping
4. Vendor Contacts
5. Vendor Addresses
6. Vendor Bank Accounts
7. Vendor Tax & Compliance
8. Vendor Documents
9. Vendor Purchasing Settings
10. Vendor Item Catalogue
11. Vendor Activity
12. Vendor Audit History
13. Vendor Status / Approval
14. Vendor Search & Reports

---

# 4. Company Master

Company Master must be maintained on a completely separate page.

## Company List Page

Route example:

/companies

Columns:

- Company Code
- Company Name
- Legal Name
- Company Type
- GSTIN / Tax ID
- PAN / Registration Number
- Email
- Phone
- City
- State
- Country
- Currency
- Status
- Actions

Actions:

- View
- Edit
- Activate
- Deactivate

Include:

- Search
- Filters
- Pagination
- Export
- Add Company

---

# 5. Add / Edit Company

Route:

/companies/create
/companies/{id}/edit

Fields:

## Basic Information

- Company Code
- Company Name
- Legal Name
- Short Name
- Company Type
- Parent Company, optional

## Registration

- Registration Number
- GSTIN
- PAN
- CIN, if applicable
- Tax Registration Number
- Incorporation Date

## Contact

- Primary Email
- Phone
- Website

## Registered Address

- Address Line 1
- Address Line 2
- City
- District
- State
- Postal Code
- Country

## Finance Defaults

- Base Currency
- Financial Year Start
- Default Payment Terms
- Default Purchase Tax
- Default Payable Account

## Status

- Active
- Inactive

Companies marked inactive must remain visible in old records but should not be selectable for new vendor assignments.

---

# 6. Vendor List Page

Route:

/vendors

This must be the main Vendor / Supplier page.

## Table Columns

- Vendor ID
- Vendor Name
- Legal Name
- Companies Covered
- Primary Contact
- Phone
- Email
- GST / Tax ID
- Payment Terms
- Outstanding, future
- Vendor Rating
- Status
- Actions

For Companies Covered:

Display company badges.

Example:

ABC Supplies

Companies:
[Alpha Ltd] [Beta Ltd] [+2]

Clicking "+2" should show remaining companies.

## Filters

Provide filters for:

- Vendor ID
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

## Search

Global vendor search should search:

- Vendor ID
- Vendor Name
- Legal Name
- Contact Name
- Email
- Phone
- GSTIN
- PAN
- Bank Account last four digits
- Company Name

## Actions

- View
- Edit
- Duplicate
- Activate / Deactivate
- Block / Unblock
- Approve
- Add Purchase Order, future
- Vendor Statement, future
- Audit History

---

# 7. Vendor ID

Vendor ID must be unique.

Recommended format:

VND-000001
VND-000002
VND-000003

Vendor ID should normally be generated automatically.

Provide a system setting if manual Vendor IDs are required later.

Vendor ID must never change automatically after the vendor is created.

Vendor Name and Vendor ID are separate fields.

Whenever a vendor is shown inside dropdowns, display:

VND-000001 : ABC Industrial Supplies Pvt Ltd

---

# 8. Add Vendor Page

Route:

/vendors/create

Use a tabbed or section-based form.

Recommended tabs:

1. General
2. Companies
3. Contacts
4. Addresses
5. Tax & Compliance
6. Bank Details
7. Purchasing
8. Items
9. Documents
10. Notes

---

# 9. Vendor — General Information

Fields:

## Identity

- Vendor ID
- Vendor Name
- Legal Business Name
- Trade Name
- Vendor Type
- Vendor Category
- Business Type
- Parent Vendor, optional
- Vendor Group

Vendor Type examples:

- Manufacturer
- Distributor
- Wholesaler
- Service Provider
- Contractor
- Importer
- Local Supplier
- Logistics Provider
- Other

Vendor Category must come from a configurable Vendor Category Master.

Do not hard-code categories.

## Business Information

- Registration Number
- Incorporation Date
- Website
- Business Description
- Industry
- Years in Business
- Number of Employees, optional

## Primary Communication

- Primary Contact Person
- Primary Phone
- Alternate Phone
- Primary Email
- Accounts Email
- Purchase Order Email
- Website

## Status

- Draft
- Pending Approval
- Approved
- Active
- On Hold
- Blocked
- Inactive

Additional flags:

- Preferred Vendor
- Purchase Enabled
- Payment Enabled
- Allow Purchase Orders
- Allow Direct Purchase
- Allow Advance Payment
- Blacklisted

Blacklisted vendors must require a reason.

---

# 10. Companies Covered

This section is critical.

The company list must be loaded from Company Master.

Use searchable multi-select.

Example:

Companies Covered

[✓] Alpha Industries Pvt Ltd
[✓] Beta Manufacturing Ltd
[ ] Gamma Retail Ltd
[✓] Delta Services Pvt Ltd

There should also be:

- Select All
- Clear All
- Search Company

One company can optionally be marked as:

Primary Company

However, the portal must support vendors without a primary company if business rules allow it.

---

# 11. Vendor-Company Mapping

Create a many-to-many relation.

Table:

vendor_companies

Suggested fields:

- id
- vendor_id
- company_id
- is_primary
- vendor_code_for_company
- account_reference
- payment_terms_id
- credit_limit
- credit_currency_id
- purchase_currency_id
- default_tax_code_id
- default_payable_account_id
- default_expense_account_id
- purchase_enabled
- payment_enabled
- preferred_vendor
- minimum_order_value
- free_shipping_threshold
- lead_time_days
- price_level
- discount_percent
- withholding_tax_code_id
- effective_from
- effective_to
- status
- notes
- created_by
- updated_by
- created_at
- updated_at

Unique constraint:

vendor_id + company_id

A vendor must never be linked to the same company twice.

---

# 12. Company-Specific Vendor Settings

When a company is assigned to a vendor, allow a detail drawer/modal/page.

Example:

Vendor:
ABC Industrial Supplies

Company:
Alpha Industries

Settings:

- Internal Vendor Code
- Vendor Account Number
- Purchase Currency
- Payment Currency
- Payment Terms
- Credit Limit
- Minimum Order Value
- Tax Code
- Withholding Tax
- Payable Account
- Default Expense Account
- Purchase Enabled
- Payment Enabled
- Preferred Vendor
- Default Buyer
- Lead Time
- Price Level
- Default Ship-To Location
- Notes

Important:

Global vendor fields should not be copied into every company relation.

Only company-specific values belong here.

---

# 13. Vendor Contacts

A vendor may have multiple people.

Table:

vendor_contacts

Fields:

- id
- vendor_id
- contact_type
- salutation
- first_name
- last_name
- designation
- department
- phone
- alternate_phone
- mobile
- email
- secondary_email
- whatsapp_number, optional
- company_id, nullable
- is_primary
- receives_po
- receives_payment_advice
- receives_rfq
- receives_statement
- active
- notes

Contact Types:

- Primary
- Sales
- Accounts
- Purchase
- Technical
- Support
- Management
- Logistics
- Other

If company_id is NULL, the contact is available globally for that vendor.

If company_id is set, the contact is specifically used for that company-vendor relationship.

---

# 14. Vendor Addresses

A vendor can have multiple addresses.

Table:

vendor_addresses

Fields:

- id
- vendor_id
- address_type
- address_name
- attention
- address_line_1
- address_line_2
- landmark
- city
- district
- state
- postal_code
- country
- phone
- email
- company_id, nullable
- is_default
- active

Address Types:

- Registered Office
- Billing
- Remittance
- Warehouse
- Dispatch
- Returns
- Branch
- Factory
- Other

Support more than one warehouse or branch address.

---

# 15. Vendor Bank Accounts

A vendor can have multiple bank accounts.

Table:

vendor_bank_accounts

Fields:

- id
- vendor_id
- company_id, nullable
- account_name
- bank_name
- bank_branch
- account_number_encrypted
- account_number_last4
- account_type
- routing_number
- ifsc_code
- swift_bic
- iban
- micr_code
- branch_code
- country
- currency_id
- beneficiary_name
- beneficiary_address
- payment_method
- is_primary
- is_verified
- verification_status
- verification_date
- verified_by
- verification_notes
- active
- effective_from
- effective_to
- created_by
- updated_by
- timestamps

Only one active primary bank account should normally exist for the same vendor/company/currency combination.

Support:

- Primary Account
- Secondary Account
- Inactive Account

---

# 16. Bank Data Security

Bank details are sensitive.

Follow these rules:

- Encrypt account number at rest.
- Never store raw bank account number in logs.
- Mask account number in UI.

Example:

XXXXXXXX1234

- Full account number should only be viewable by authorized roles.
- Require permission for revealing full bank details.
- Record every bank detail change in audit history.
- Record who verified the bank account.
- Bank account changes may optionally require approval.
- Do not include complete bank account numbers in CSV exports by default.
- Do not expose bank information through unrestricted APIs.
- Never show full bank information on general vendor list screens.

---

# 17. Vendor Tax & Compliance

Create:

vendor_tax_details

Fields:

- id
- vendor_id
- company_id, nullable
- country
- tax_type
- tax_registration_number
- GSTIN
- PAN
- VAT_number
- TIN
- withholding_tax_applicable
- withholding_tax_code
- tax_exempt
- tax_exemption_number
- tax_exemption_expiry
- MSME_registered
- MSME_number
- MSME_category
- effective_from
- effective_to
- verified
- verified_by
- status

For Indian implementations, support:

- GSTIN
- PAN
- MSME / Udyam
- TDS / withholding settings

But design the database so country-specific tax fields can be extended later.

---

# 18. Vendor Documents

Create:

vendor_documents

Possible document types:

- GST Certificate
- PAN Card
- Udyam / MSME Certificate
- Certificate of Incorporation
- Trade License
- Bank Proof
- Cancelled Cheque
- Agreement
- NDA
- Insurance
- Quality Certificate
- ISO Certificate
- Product Certification
- Tax Certificate
- Contract
- Other

Fields:

- id
- vendor_id
- company_id, nullable
- document_type
- document_number
- issue_date
- expiry_date
- file_path
- status
- verified
- verified_by
- verified_at
- notes
- uploaded_by
- timestamps

Document expiry alerts should be supported.

Examples:

30 days before expiry
15 days before expiry
7 days before expiry

---

# 19. Purchasing Settings

Vendor purchasing settings should include:

- Default Payment Terms
- Default Purchase Currency
- Minimum Order Value
- Standard Lead Time
- Delivery Terms
- Freight Terms
- Incoterms
- Default Purchase Tax
- Default Warehouse
- Default Buyer
- Purchase Order Required
- GRN Required
- Bill Without PO Allowed
- Over-Receipt Allowed
- Under-Receipt Allowed
- Quantity Tolerance
- Price Tolerance
- Allow Backorders
- Allow Partial Delivery
- Allow Advance Payment
- Preferred Vendor
- Purchase Hold

Where appropriate, these can be overridden at vendor-company level.

---

# 20. Payment Terms Master

Do not hard-code payment terms.

Create:

payment_terms

Fields:

- id
- term_code
- term_name
- days
- discount_days
- discount_percent
- description
- active

Examples:

Immediate
Net 7
Net 15
Net 30
Net 45
Net 60
Advance 50%
Custom

Custom payment terms should allow descriptive notes.

---

# 21. Payment Methods

Create configurable Payment Method Master.

Examples:

- Bank Transfer
- NEFT
- RTGS
- IMPS
- UPI
- Cheque
- Wire Transfer
- ACH
- Cash
- Other

Do not hard-code payment method logic into vendor records.

---

# 22. Vendor Item Catalogue

Build this even if Item Master is implemented later.

Relation:

vendor_items

Fields:

- id
- vendor_id
- company_id, nullable
- item_id
- vendor_item_code
- vendor_item_name
- vendor_part_number
- manufacturer_part_number
- unit_of_measure
- minimum_order_quantity
- order_multiple
- purchase_price
- currency_id
- tax_code_id
- lead_time_days
- minimum_lead_time_days
- maximum_lead_time_days
- last_purchase_price
- effective_from
- effective_to
- preferred
- active
- notes

One inventory item can have many vendors.

One vendor can supply many inventory items.

---

# 23. Preferred Vendor Logic

Preferred Vendor can be defined:

- Globally
- Per Company
- Per Item
- Per Company + Item

Priority:

Company + Item preferred vendor
    >
Item preferred vendor
    >
Company preferred vendor
    >
Global preferred vendor

Never remove alternatives just because one preferred vendor exists.

---

# 24. Vendor View Page

Route:

/vendors/{vendor}

Header:

Vendor Name
Vendor ID
Status
Preferred Vendor
Purchase Enabled
Payment Enabled

Quick summary cards:

- Companies Covered
- Active Contacts
- Active Bank Accounts
- Open Purchase Orders
- Outstanding Payables
- Items Supplied
- Last Purchase Date
- Vendor Rating

Future finance values may be placeholders until accounting modules are added.

Tabs:

Overview
Companies
Contacts
Addresses
Bank Details
Tax & Compliance
Purchasing
Items
Documents
Transactions
Performance
Notes
Audit History

---

# 25. Vendor Overview

Display:

## Basic Details

- Vendor ID
- Vendor Name
- Legal Name
- Vendor Type
- Category
- Website
- Registration Number
- Status

## Companies Covered

Display all assigned companies.

## Primary Contact

Name
Phone
Email

## Primary Address

## Primary Bank

Show only masked number.

## Payment Information

- Payment Terms
- Purchase Currency
- Payment Currency

## Purchasing Status

- Purchase Enabled
- Preferred Vendor
- Purchase Hold

---

# 26. Vendor Approval Workflow

Recommended workflow:

Draft
    ↓
Submitted
    ↓
Under Review
    ↓
Approved
    ↓
Active

Possible side states:

Rejected
On Hold
Blocked
Inactive

Vendor should not be usable for Purchase Orders until approved and active, unless an administrator explicitly overrides the rule.

Approval data:

- submitted_by
- submitted_at
- approved_by
- approved_at
- rejected_by
- rejected_at
- rejection_reason

---

# 27. Sensitive Change Approval

Optionally require approval for:

- Bank Account Added
- Bank Account Changed
- GSTIN Changed
- PAN Changed
- Payment Terms Changed
- Credit Limit Changed
- Payment Method Changed
- Vendor Reactivated
- Vendor Unblocked

Store old and new values in audit history.

---

# 28. Vendor Blocking

Vendor blocking must support different levels.

Block Types:

- Block New Purchase Orders
- Block Payments
- Block All Transactions

Fields:

- block_type
- blocked_reason
- blocked_by
- blocked_at
- unblock_reason
- unblocked_by
- unblocked_at

Do not delete a vendor just because the vendor is no longer used.

Use inactive or blocked state.

---

# 29. Duplicate Detection

Before creating a vendor, check possible duplicates using:

- Vendor Name
- Legal Name
- GSTIN
- PAN
- Tax Number
- Email
- Phone
- Bank Account Fingerprint

If a possible duplicate is found, show a warning.

Do not automatically reject unless a unique legal identifier is duplicated.

---

# 30. Vendor Notes

Support internal notes.

Fields:

- vendor_id
- company_id, nullable
- note_type
- subject
- note
- visibility
- created_by
- created_at

Types:

- General
- Purchase
- Payment
- Quality
- Delivery
- Compliance
- Risk
- Internal

---

# 31. Vendor Performance

Design database hooks for future vendor performance tracking.

Metrics:

- Total Purchase Value
- Number of Purchase Orders
- Average Lead Time
- On-Time Delivery %
- Late Delivery %
- Rejection %
- Return %
- Price Variance
- Quantity Variance
- Quality Score
- Service Score
- Compliance Score
- Overall Rating

Do not manually type these values if the system can calculate them from transactions.

---

# 32. Vendor Rating

Allow:

1 to 5 score

Possible components:

- Price
- Quality
- Delivery
- Service
- Compliance

Recommended:

overall_rating =
weighted average of the rating components

Weight values should later be configurable.

---

# 33. Audit History

Create a full audit trail.

Track:

- Vendor Created
- Vendor Updated
- Company Added
- Company Removed
- Contact Added
- Address Changed
- Bank Added
- Bank Changed
- Bank Verified
- Tax Information Changed
- Document Uploaded
- Vendor Approved
- Vendor Blocked
- Vendor Unblocked
- Vendor Activated
- Vendor Deactivated
- Payment Settings Changed

Fields:

- entity_type
- entity_id
- action
- old_values
- new_values
- changed_by
- ip_address
- user_agent
- changed_at

Sensitive values such as full bank account numbers must not be written into audit JSON.

---

# 34. Role Based Permissions

Recommended permissions:

## Vendor Viewer

- View vendors
- View contacts
- View addresses
- View companies
- View masked bank details

## Procurement User

- Create vendor draft
- Edit vendor procurement details
- Assign companies
- Add contacts
- Add items

## Procurement Manager

- Approve purchasing information
- Block purchases
- Change preferred vendor

## Accounts User

- View payment information
- Maintain bank information
- Maintain payment terms
- View tax information

## Accounts Manager

- Verify bank details
- Approve bank changes
- Block vendor payments

## Compliance User

- Manage tax information
- Manage certificates
- Verify documents

## Administrator

- Full access

Full bank account viewing must use a separate permission.

---

# 35. Database Tables

Minimum tables:

companies

vendors

vendor_companies

vendor_contacts

vendor_addresses

vendor_bank_accounts

vendor_tax_details

vendor_documents

vendor_items

vendor_notes

vendor_ratings

vendor_status_history

payment_terms

payment_methods

vendor_categories

currencies

countries

tax_codes

audit_logs

Later integrations:

items

purchase_requisitions

purchase_orders

purchase_order_items

goods_receipts

goods_receipt_items

vendor_bills

vendor_payments

purchase_returns

vendor_credits

---

# 36. Vendors Table

Suggested fields:

- id
- vendor_code
- vendor_name
- legal_name
- trade_name
- vendor_type
- vendor_category_id
- vendor_group_id
- parent_vendor_id
- business_type
- registration_number
- incorporation_date
- website
- industry
- business_description
- primary_email
- accounts_email
- po_email
- primary_phone
- alternate_phone
- default_payment_terms_id
- default_currency_id
- default_payment_method_id
- preferred_vendor
- purchase_enabled
- payment_enabled
- allow_advance_payment
- status
- approval_status
- risk_level
- blocked
- blocked_reason
- blacklisted
- blacklist_reason
- created_by
- updated_by
- approved_by
- approved_at
- created_at
- updated_at
- deleted_at

Use soft delete if the framework supports it.

---

# 37. Database Relationships

Company:

hasMany VendorCompany

belongsToMany Vendor through VendorCompany

Vendor:

belongsToMany Company through VendorCompany

hasMany Contacts

hasMany Addresses

hasMany BankAccounts

hasMany TaxDetails

hasMany Documents

hasMany VendorItems

hasMany Notes

hasMany Ratings

hasMany StatusHistory

VendorCompany:

belongsTo Vendor

belongsTo Company

---

# 38. Form Behaviour

## Company selector

Use searchable multi-select.

Do not load thousands of companies into the page at once.

Use server-side search when company count becomes large.

## Vendor selector

Use:

Vendor ID : Vendor Name

Example:

VND-000245 : Eastern Industrial Supply

## Unsaved changes

Warn user before leaving an edited vendor form.

## Validation

Use both:

- Client-side validation
- Server-side validation

Server-side validation is mandatory.

---

# 39. Validation Rules

Examples:

Vendor Name:
required

Vendor Code:
required
unique

Primary Email:
valid email

Phone:
validated based on reasonable format

Company IDs:
must exist
must be active when adding a new assignment

GSTIN:
unique where required by business rule

PAN:
validate format when country = India

Bank Account:
required when payment method requires bank transfer

IFSC:
validate when Indian banking details are used

SWIFT/BIC:
allow for international bank accounts

IBAN:
allow for applicable countries

Credit Limit:
numeric
>= 0

Payment Term:
must exist and be active

---

# 40. Delete Rules

Do not hard-delete a vendor once used in any transaction.

If vendor has no transactions:

Administrator may delete a draft vendor.

If vendor has transaction history:

Only:

- Deactivate
- Block
- Archive

Do not allow Company Master records to be deleted if referenced by vendor mappings or transactions.

Use inactive status.

---

# 41. Company Removal From Vendor

If a vendor-company relationship has never been used:

Allow removal.

If the relationship has purchase or payment history:

Do not physically delete it.

Set:

status = inactive
effective_to = current date

Keep transaction history intact.

---

# 42. API Design

Suggested API endpoints:

GET /api/vendors

POST /api/vendors

GET /api/vendors/{vendor}

PUT /api/vendors/{vendor}

POST /api/vendors/{vendor}/companies

DELETE /api/vendors/{vendor}/companies/{company}

GET /api/vendors/{vendor}/contacts

POST /api/vendors/{vendor}/contacts

GET /api/vendors/{vendor}/addresses

POST /api/vendors/{vendor}/addresses

GET /api/vendors/{vendor}/bank-accounts

POST /api/vendors/{vendor}/bank-accounts

PUT /api/vendors/{vendor}/bank-accounts/{account}

POST /api/vendors/{vendor}/bank-accounts/{account}/verify

GET /api/vendors/{vendor}/documents

POST /api/vendors/{vendor}/documents

GET /api/vendors/{vendor}/items

POST /api/vendors/{vendor}/items

POST /api/vendors/{vendor}/approve

POST /api/vendors/{vendor}/block

POST /api/vendors/{vendor}/unblock

POST /api/vendors/{vendor}/activate

POST /api/vendors/{vendor}/deactivate

---

# 43. Company APIs

GET /api/companies

POST /api/companies

GET /api/companies/{company}

PUT /api/companies/{company}

GET /api/companies/search?q=

Vendor company selector must use this master data.

---

# 44. Import / Export

Support CSV/XLSX import later.

Vendor import should allow:

- Vendor Master
- Company Assignments
- Contacts
- Addresses
- Bank Accounts
- Tax Details
- Vendor Items

Import process must:

- Validate data first
- Show preview
- Show errors
- Allow correction
- Prevent duplicates
- Create audit record
- Provide final import summary

Do not silently skip invalid rows.

---

# 45. Dashboard Widgets

Useful vendor dashboard widgets:

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

---

# 46. Reports

Prepare architecture for:

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

---

# 47. Vendor by Company Report

Important report.

Filters:

- Company
- Vendor
- Status
- Category
- Purchase Enabled
- Payment Enabled
- Preferred Vendor

Columns:

- Company
- Vendor ID
- Vendor Name
- Internal Vendor Code
- Payment Terms
- Purchase Currency
- Credit Limit
- Lead Time
- Purchase Enabled
- Payment Enabled
- Preferred
- Status

---

# 48. Company Page — Vendors Tab

On Company View:

/companies/{company}

Add Vendors tab.

Display all vendors serving the company.

Columns:

- Vendor ID
- Vendor Name
- Category
- Payment Terms
- Credit Limit
- Lead Time
- Preferred
- Purchase Enabled
- Payment Enabled
- Status

Action:

Assign Vendor

This should link an existing vendor.

Do not create duplicate vendor masters.

---

# 49. Vendor Page — Companies Tab

On:

/vendors/{vendor}

Display:

- Company Name
- Company Vendor Code
- Payment Terms
- Purchase Currency
- Credit Limit
- Lead Time
- Preferred
- Purchase Enabled
- Payment Enabled
- Status
- Actions

Actions:

- View Settings
- Edit Settings
- Disable Relationship

---

# 50. Inventory Integration

This vendor module must be designed for the following future flow:

Company
    ↓
Purchase Requisition
    ↓
Vendor Selection
    ↓
RFQ
    ↓
Vendor Quotation
    ↓
Purchase Order
    ↓
Goods Receipt / GRN
    ↓
Quality Check
    ↓
Inventory Receipt
    ↓
Vendor Bill
    ↓
Payment

All purchase transactions must know:

- company_id
- vendor_id

Where applicable also store:

- vendor_company relationship context
- currency
- payment terms
- bank account used
- tax settings

Historical transactions must keep their original transaction values even if the vendor master changes later.

---

# 51. Multi-Company Purchasing Rule

When creating a Purchase Order:

1. User selects Company.
2. System displays only vendors assigned and enabled for that company.
3. User selects Vendor.
4. System loads company-specific vendor settings.
5. System loads available vendor items.
6. System loads payment terms.
7. System loads currency.
8. System loads tax defaults.
9. System loads shipping/default purchasing values.
10. User completes Purchase Order.

Do not show vendors that are:

- Inactive
- Blocked for purchasing
- Not approved
- Not assigned to the selected company

unless user has override permission.

---

# 52. Payment Rule

When a vendor payment is created later:

1. Select Company.
2. Select Vendor.
3. Show only bank accounts valid for that vendor/company.
4. Default to the active primary bank account.
5. Allow authorized user to select a secondary account.
6. Store selected bank account ID on the payment.
7. Store a transaction-safe snapshot of required bank/payment reference data.

Changing the vendor's primary bank later must not modify old payments.

---

# 53. UI Design Requirements

Use clean ERP-style UI.

Avoid one extremely long form.

Use tabs and cards.

Vendor create/edit page:

Header:
Vendor Name
Vendor ID
Status

Tabs:
General
Companies
Contacts
Addresses
Bank
Tax
Purchasing
Items
Documents
Notes

Use:

- Searchable dropdowns
- Multi-select
- Toggles
- Badges
- Tables
- Confirmation modals
- Inline validation
- Status history
- Activity timeline

Make the UI usable on desktop first, but responsive on tablet and mobile.

---

# 54. Important UX Rules

- Do not expose internal database IDs to normal users.
- Always show human-readable Vendor ID and Company Code.
- Mask sensitive values.
- Show inactive records clearly.
- Warn before disabling a vendor-company relationship.
- Warn before changing primary bank account.
- Ask for reason when blocking vendor.
- Ask for reason when blacklisting vendor.
- Do not allow silent destructive actions.
- Display who last updated important financial information.

---

# 55. Search Performance

Add indexes for:

vendors.vendor_code

vendors.vendor_name

vendors.legal_name

vendors.primary_email

vendors.primary_phone

vendor_companies.vendor_id

vendor_companies.company_id

vendor_contacts.email

vendor_contacts.phone

vendor_tax_details.tax_registration_number

vendor_bank_accounts.account_number_last4

vendor_items.vendor_id

vendor_items.item_id

Use composite unique index:

vendor_companies(vendor_id, company_id)

---

# 56. Security Requirements

Apply:

- Authentication
- Authorization
- Role-based permissions
- CSRF protection
- Input validation
- File upload validation
- Secure file access
- Sensitive field encryption
- Rate limiting for sensitive APIs
- Audit logs
- Login/session security

Never:

- Log full bank account details
- Return full bank details in normal API list calls
- Put bank details in URLs
- Store secrets in source code
- Trust client-side permission checks

---

# 57. Document Upload Security

Allowed document types should be configurable.

Validate:

- File extension
- MIME type
- Maximum file size

Generate unique storage names.

Do not trust uploaded filename.

Keep original filename as metadata only.

Documents containing financial or tax details should require authenticated access.

---

# 58. Audit Requirements

For every important vendor update store:

Who changed it?

What changed?

When?

From what value?

To what value?

From which IP/session where available?

For sensitive financial fields:

Audit that the field changed without storing the full secret value.

Example:

Bank Account:
XXXX1234 → XXXX9876

---

# 59. Implementation Sequence

Build in this order:

Phase 1:
Company Master

Phase 2:
Vendor Master

Phase 3:
Vendor-Company Mapping

Phase 4:
Contacts and Addresses

Phase 5:
Tax and Compliance

Phase 6:
Bank Accounts

Phase 7:
Purchasing Settings

Phase 8:
Vendor Items

Phase 9:
Approval, Blocking and Audit Trail

Phase 10:
Reports and Import/Export

Phase 11:
Purchase Order integration

---

# 60. Acceptance Criteria

The Vendor module is acceptable when:

1. Admin can create unlimited companies from Company Master.
2. Company names are never hard-coded.
3. Admin can create a vendor once.
4. One vendor can be linked to multiple companies.
5. The same vendor-company link cannot be created twice.
6. Vendor can have multiple contacts.
7. Vendor can have multiple addresses.
8. Vendor can have multiple bank accounts.
9. Primary and secondary bank accounts are supported.
10. Vendor bank account numbers are encrypted and masked.
11. Vendor can have company-specific payment terms.
12. Vendor can have company-specific credit limits.
13. Vendor can have company-specific purchase settings.
14. Vendor can supply multiple inventory items.
15. Vendor can be searched by company.
16. Company page can show all assigned vendors.
17. Vendor page can show all assigned companies.
18. Inactive companies cannot be newly assigned.
19. Historical relationships are retained.
20. Blocked vendor cannot be used for new purchases.
21. Payment-blocked vendor cannot be used for new payments.
22. Important changes appear in audit history.
23. Bank changes can be restricted by role.
24. Vendor approval workflow is supported.
25. The module is ready for Purchase Order integration.

---

# 61. Coding Agent Rules

When implementing this module:

- Do not hard-code company names.
- Do not hard-code database IDs.
- Do not create duplicate vendors per company.
- Use relational tables correctly.
- Use foreign keys.
- Use migrations.
- Use database transactions for multi-table writes.
- Use server-side validation.
- Use authorization policies/middleware.
- Use reusable service classes for business rules.
- Keep controllers/actions thin.
- Keep financial data security separate from UI masking.
- Preserve historical records.
- Use soft deletion/status changes where transaction history exists.
- Write tests for critical business rules.
- Do not modify unrelated modules.
- Follow existing project folder structure and coding conventions.
- Reuse existing UI components before introducing new ones.
- Never expose secret configuration or credentials.
- Never remove database constraints merely to bypass an error.

---

# 62. Tests Required

At minimum test:

## Vendor

- Vendor can be created.
- Vendor code is unique.
- Vendor can be updated.
- Vendor can be deactivated.

## Company Mapping

- Vendor can be linked to company.
- Vendor can be linked to multiple companies.
- Duplicate mapping is rejected.
- Inactive company cannot be newly assigned.
- Existing historical mapping remains readable.

## Bank

- Multiple bank accounts allowed.
- Primary bank logic works.
- Masking works.
- Unauthorized user cannot view full account number.
- Bank changes are audited.

## Purchasing

- Company sees only assigned vendors.
- Blocked vendor cannot be selected.
- Inactive vendor cannot be selected.
- Company-specific payment terms load correctly.

## Security

- Unauthorized route access denied.
- Sensitive files cannot be downloaded anonymously.
- Restricted financial APIs require permission.

---

# 63. NetSuite-Inspired Design Principle

The design should follow this principle:

One Vendor Master
+
Multiple Company Relationships
+
Multiple Bank / Contact / Address Records
+
Company-Specific Commercial Settings
+
Transaction-Level Historical Values

This gives the portal a strong base for a complete inventory and procure-to-pay system.

Do not attempt to copy Oracle NetSuite screens exactly.

Use the business concepts while creating a simpler and cleaner workflow for this application.
