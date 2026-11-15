# Package gaps

These are the places an Imran package does not yet cover what the factory needs. The application keeps an adapter in-app instead of forking the package.

## `imrandevbd/adhikar`

Not published on Packagist under `imranbru99`. Roles, permissions and approval-level permissions live in the application (`roles`, `permissions`, `RoleMatrix`, `FactoryPolicy`). Permission names follow the filament-master templates (`view-any Buyer`, `approve InternalMemo`) so a later Adhikar release can take over the same strings. `Gate::before` grants a permission the user holds and otherwise leaves the decision to the policy. Policies call `hasPermission()` and never `$user->can()`, which would recurse.

## `imrandevbd/tarikh`

`dev-main` is a date wrapper. It advertises `Tarikh\Laravel\TarikhServiceProvider`, which is not in the package, so the package is in `dont-discover`. It has no Bangla calendar, Bangla numerals, fiscal year, holiday table or working-day math, and `isWeekend()` treats Saturday and Sunday as the weekend. The factory uses `Tarikh\Tarikh` for instants and differences, and `App\Modules\Core\Support\BanglaCalendar` plus `WorkingDayCalendar` for the Bangladesh calendar. Friday is the weekly holiday.

## `imrandevbd/laravel-filament-master`

`dev-main` targets Filament v3 (`Filament\Forms\Form`, table actions under `Filament\Tables\Actions`). Its service provider registers commands that fatal on Filament 4, so the package is in `dont-discover`. Master registers are Filament 4 resources generated from each model's `fieldMap()`. The permission names still match the package's Adhikar templates.

## `imrandevbd/laravel-admin-panel`

The package registers a panel at `ADMIN_PANEL_PREFIX`, default `admin`, which collides with Filament. Set `ADMIN_PANEL_PREFIX=staff-shell`.

## `imrandevbd/laravel-unicode-pdf`

Used for register PDFs. The Bengali preset looks for Noto Sans Bengali. The app registers the system Noto fonts from `config/factory.php` when those files exist.

## `imrandevbd/drutogoti`

Migrations are copied into `database/migrations` because the package does not load them itself. It stays off (`DRUTOGOTI_ENABLED=false`) until the dashboards that need it exist.

## Redis and Horizon

The sandbox has the PHP redis extension and no running Redis server. Queues use the database driver. Production should switch `QUEUE_CONNECTION` to `redis` and run Horizon. Queue names to use then: `default`, `notifications`, `reports`, `ai`, `imports`.

## MySQL

This machine's MariaDB is running, and the unix user cannot log in (error 1698). The schema is written with portable Laravel types so MySQL utf8mb4 is the production target. SQLite is what local install and tests use.
