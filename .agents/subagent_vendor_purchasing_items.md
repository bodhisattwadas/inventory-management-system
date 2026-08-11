# Subagent: Vendor Purchasing Settings And Item Catalogue

## Mission

Implement purchasing defaults, payment method dependencies, payment terms, and vendor item catalogue.

## Scope

- `payment_terms` master.
- `payment_methods` master.
- Supporting master tables as needed: currencies, countries, tax codes.
- Vendor purchasing settings fields.
- `vendor_items` migration/model/service/UI.

## Payment Terms Fields

- Term Code
- Term Name
- Days
- Discount Days
- Discount Percent
- Description
- Active

## Payment Methods

Configurable methods such as Bank Transfer, NEFT, RTGS, IMPS, UPI, Cheque, Wire Transfer, ACH, Cash, Other.

## Vendor Items Fields

- Vendor ID
- Company ID, nullable
- Item ID
- Vendor Item Code
- Vendor Item Name
- Vendor Part Number
- Manufacturer Part Number
- Unit Of Measure
- Minimum Order Quantity
- Order Multiple
- Purchase Price
- Currency ID
- Tax Code ID
- Lead Time Days
- Minimum Lead Time Days
- Maximum Lead Time Days
- Last Purchase Price
- Effective From
- Effective To
- Preferred
- Active
- Notes

## Business Rules

- Do not hard-code payment terms or payment methods.
- Company-specific settings can override vendor defaults.
- One item can have many vendors.
- One vendor can supply many items.
- Preferred vendor priority: Company + Item, Item, Company, Global.
- Never remove alternative vendors because one preferred vendor exists.

## Acceptance Checks

- Payment terms are configurable.
- Payment methods are configurable.
- Vendor item catalogue supports company-specific prices.
- Blocked/inactive vendors cannot be selected for new purchasing.
