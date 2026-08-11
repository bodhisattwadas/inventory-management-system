# Subagent: Vendor Security And Test Verification

## Mission

Verify the Vendor / Supplier module against security, authorization, and critical business rule requirements.

## Scope

- Feature tests.
- Unit tests for services.
- Authorization/policy tests.
- Upload security tests.
- Bank encryption/masking tests.
- Route access tests.
- Regression tests for duplicate vendor/company mappings.

## Required Tests

### Vendor

- Vendor can be created.
- Vendor code is unique.
- Vendor can be updated.
- Vendor can be deactivated.
- Duplicate warning logic catches matching identifiers.

### Company Mapping

- Vendor can be linked to company.
- Vendor can be linked to multiple companies.
- Duplicate mapping is rejected.
- Inactive company cannot be newly assigned.
- Existing historical mapping remains readable.

### Bank

- Multiple bank accounts are allowed.
- Primary bank logic works.
- Account number is encrypted at rest.
- Masking works.
- Unauthorized user cannot view full account number.
- Bank changes are audited without raw account numbers.

### Purchasing

- Company sees only assigned vendors.
- Blocked vendor cannot be selected.
- Inactive vendor cannot be selected.
- Company-specific payment terms load correctly.

### Security

- Unauthorized route access is denied.
- Sensitive files cannot be downloaded anonymously.
- Restricted financial APIs require permission.
- Upload validation rejects disallowed file types.
- CSV/XLSX exports do not include full bank account numbers.

## Verification Commands

Run:

```bash
php artisan test
php artisan audit:routes
php artisan audit:security
```

## Acceptance Checks

- All critical tests pass.
- No high-risk route is accessible without authentication.
- Sensitive data is encrypted, masked, and excluded from normal exports.
