# Subagent: Vendor Approval, Blocking, Status, And Audit Trail

## Mission

Implement vendor workflow, blocking behavior, status history, and full audit trail.

## Scope

- Vendor approval workflow fields/services/actions.
- Vendor block/unblock actions.
- Vendor activate/deactivate actions.
- `vendor_status_history` table.
- Generic `audit_logs` table or vendor-focused audit service.
- Sensitive change audit masking.

## Approval Workflow

Recommended states:

- Draft
- Submitted
- Under Review
- Approved
- Active
- Rejected
- On Hold
- Blocked
- Inactive

## Approval Fields

- Submitted By
- Submitted At
- Approved By
- Approved At
- Rejected By
- Rejected At
- Rejection Reason

## Blocking Fields

- Block Type
- Blocked Reason
- Blocked By
- Blocked At
- Unblock Reason
- Unblocked By
- Unblocked At

## Audit Fields

- Entity Type
- Entity ID
- Action
- Old Values
- New Values
- Changed By
- IP Address
- User Agent
- Changed At

## Security Rules

- Sensitive values must be masked in audit JSON.
- Bank account audit format should look like `XXXX1234 -> XXXX9876`.
- Do not write full secrets to logs.

## Acceptance Checks

- Vendor approval workflow is represented.
- Blocked vendor cannot be used for new purchases.
- Payment-blocked vendor cannot be used for payments.
- Important updates appear in audit history.
- Sensitive changes are audited without exposing secrets.
