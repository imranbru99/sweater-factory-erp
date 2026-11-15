# Factory decisions

These are the choices a sweater factory would make when the paper register and the software disagree, recorded so later modules follow the same rules.

## Money

Stored as `decimal(18,4)`. Arithmetic goes through `App\Modules\Core\Support\Money` and bcmath. The company currency is BDT. Buyer orders may be USD or EUR. A rate is a separate row in `exchange_rates` with an effective date. A zero rate is rejected.

## Quantities

Every quantity will carry a unit. Fixed conversions are seeded for kg/g/lb, metre/cm/yard, and piece/dozen/gross. A cone, bag or carton has no fixed kilogram weight. That weight belongs to the lot, which arrives with the yarn module.

## Document numbers

`{PREFIX}-{BRANCH}-{YY}-{00000}`. The year is the fiscal year ending in that calendar year. The fiscal year starts in July. 16 November 2026 is FY 2026-27 and prints `27`. 30 June 2026 prints `26`. The sequence is locked per branch, document type and year. The first approvable document is the note sheet, prefix `NM`.

## Working time for approvals

The knitting shifts (day 08:00–17:00, night 20:00–05:00) are machine shifts. Approval deadlines use a separate clock: 08:00–16:00, Friday off, plus the holiday register. Eight working hours is one approval day. Eid and other lunar holidays are entered when the government gazette is published. They are not guessed.

## Bangla calendar

The 1987 Bangladesh revision. The year starts on 14 April. The first five months have 31 days. Falgun has 31 days when the Gregorian year that contains the following February is a leap year. 14 April 2026 is 1 Boishakh 1433.

## Branches

Head office `HO` in Dhaka, factory `GF1` in Gazipur, yarn warehouse `YW1`, finished-goods warehouse `FW1`. Masters such as gauge and colour are company-wide. Buyers, styles, machines and warehouses belong to a unit. A holiday with no unit applies to every unit. Admin, director, merchandising, commercial, accounts, HR and compliance see every unit. Shop-floor roles see the units assigned to them.

## Approvals

A note sheet is drafted, submitted, and then approved by the merchandising manager and the director. The manager's step escalates to the director after eight working hours. Escalation adds the director as someone who may act. It does not skip the step. A super administrator may act on any pending step, and the action is stored with their user id. A director cannot skip the manager unless a delegation row says so.

## Access

Two-factor authentication is required for admin, director and accounts. Other staff may turn it on. Buyer, supplier and sub-contractor accounts cannot open `/admin`. They have their own panels. A public visitor is a user type with no panel.

## Passwords in the demo

`php artisan factory:demo` and a non-interactive `factory:install` set the staff password to `password`. That is a local demonstration credential. Production install refuses to run non-interactively without `--admin-password`.
