# Vendor / Supplier Module Subagents

These subagents break the Vendor / Supplier Management module into focused implementation and verification roles.

Use them together with `AGENTS_inventory_vendor_supplier_module.md`. The source spec remains authoritative when there is a conflict.

## Recommended Execution Order

1. `subagent_company_master.md`
2. `subagent_vendor_master.md`
3. `subagent_vendor_company_mapping.md`
4. `subagent_vendor_contacts_addresses.md`
5. `subagent_vendor_tax_documents.md`
6. `subagent_vendor_bank_security.md`
7. `subagent_vendor_purchasing_items.md`
8. `subagent_vendor_approval_audit.md`
9. `subagent_vendor_reports_search.md`
10. `subagent_vendor_security_tests.md`

## Shared Rules

- Do not hard-code company names, IDs, payment terms, vendor categories, currencies, countries, tax codes, or payment methods.
- Use migrations, models, DTOs, services, Livewire components, Blade views, and tests consistent with this Laravel codebase.
- Keep sensitive financial data encrypted at rest and masked by default.
- Preserve historical relationships instead of deleting records once referenced.
- Keep controllers and Livewire components thin; put business rules in service classes.
- Do not modify unrelated modules unless required for integration.
