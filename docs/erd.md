# Foundation ERD

Phase 1 covers the registers the rest of the factory hangs from. Later documents (orders, lots, bundles) will point at these rows.

```mermaid
erDiagram
    branches ||--o{ users : employs
    branches ||--o{ branch_user : grants
    users ||--o{ branch_user : "may work at"
    roles ||--o{ role_user : assigned
    users ||--o{ role_user : has
    roles ||--o{ permission_role : allows
    permissions ||--o{ permission_role : named
    branches ||--o{ internal_memos : files
    users ||--o{ internal_memos : requests
    approval_flows ||--o{ approval_steps : contains
    roles ||--o{ approval_steps : acts
    internal_memos ||--o{ approval_requests : submits
    approval_flows ||--o{ approval_requests : uses
    approval_requests ||--o{ approval_actions : records
    users ||--o{ approval_delegations : delegates
    branches ||--o{ warehouses : holds
    warehouses ||--o{ locations : racks
    buyers ||--o{ buyer_contacts : people
    buyers ||--o{ buying_agents : agents
    suppliers ||--o{ supplier_contacts : people
    buyers ||--o{ styles : develops
    gauges ||--o{ styles : "knit gauge"
    gauges ||--o{ machines : "machine gauge"
    size_groups ||--o{ sizes : grades
    unit_of_measures ||--o{ unit_conversions : from
    currencies ||--o{ exchange_rates : quotes
```

`audits`, `attachments`, `notifications` and `issues` (later) attach to any of these by morph.

Traceability, once production exists: a buyer complaint points at a bundle, the bundle points at a knitting job, the job points at a yarn lot, the lot points at a supplier. The lot and the bundle are the two atoms. They are not tables yet.
