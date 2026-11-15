<?php

namespace App\Modules\Core\Support;

/**
 * Who may do what on the factory registers.
 * Admin may do everything. A director may do everything except permanently
 * delete a user. Shop-floor roles see the registers they actually keep.
 */
final class RoleMatrix
{
    /** @var list<string> */
    private const READ = ['view-any', 'view', 'export'];

    /** @var list<string> */
    private const WRITE = ['view-any', 'view', 'create', 'update', 'export'];

    /** @var list<string> */
    private const FULL = ['view-any', 'view', 'create', 'update', 'delete', 'restore', 'export'];

    public static function allows(string $role, string $permission): bool
    {
        if ($role === 'admin') {
            return true;
        }

        if ($role === 'director') {
            return $permission !== 'force-delete User';
        }

        $grants = self::matrix()[$role] ?? [];

        if (! str_contains($permission, ' ')) {
            return in_array($permission, $grants['_extra'] ?? [], true);
        }

        [$action, $subject] = explode(' ', $permission, 2);

        return in_array($action, $grants[$subject] ?? [], true);
    }

    /**
     * @return array<string, array<string, list<string>>>
     */
    private static function matrix(): array
    {
        $merchandising = ['Buyer', 'BuyerContact', 'BuyingAgent', 'Style', 'Color', 'Gauge', 'Season', 'Department', 'ProductType', 'YarnType', 'YarnCount', 'Ply', 'StitchType', 'Size', 'SizeGroup', 'InternalMemo'];
        $floorRead = ['Style', 'Operation', 'DefectCode', 'Shift', 'Machine', 'Gauge'];

        $matrix = [
            'merchandiser' => self::subjects($merchandising, self::WRITE),
            'merchandising-manager' => array_replace(self::subjects($merchandising, self::FULL), [
                'ApprovalFlow' => self::READ,
                'InternalMemo' => [...self::FULL, 'approve'],
            ]),
            'sampling-master' => self::subjects(['Style', 'Color', 'Gauge', 'YarnType', 'StitchType', 'Machine', 'Operation'], self::READ),
            'programmer' => self::subjects(['Style', 'Gauge', 'Machine', 'Operation', 'StitchType'], self::READ),
            'yarn-store' => self::subjects(['Warehouse', 'Location', 'Supplier', 'SupplierContact', 'UnitOfMeasure', 'UnitConversion', 'Color'], self::WRITE),
            'knitting-master' => self::subjects($floorRead, self::READ),
            'linking-supervisor' => self::subjects(['Style', 'Operation', 'DefectCode', 'Shift'], self::READ),
            'qc-inspector' => self::subjects(['DefectCode'], self::WRITE) + self::subjects(['Style', 'Color', 'Operation', 'Buyer'], self::READ),
            'finishing-incharge' => self::subjects(['Style', 'Operation', 'DefectCode', 'Shift', 'Color'], self::READ),
            'packing-incharge' => self::subjects(['Style', 'Operation', 'DefectCode', 'Shift'], self::READ),
            'commercial-officer' => self::subjects(['Buyer', 'BuyingAgent', 'Currency', 'HsCode', 'Tax'], self::READ) + self::subjects(['ExchangeRate'], self::WRITE),
            'accounts' => self::subjects(['Currency', 'ExchangeRate', 'Tax', 'HsCode'], self::FULL) + self::subjects(['Buyer', 'Supplier', 'InternalMemo', 'Audit'], self::READ) + [
                '_extra' => ['core.settings.manage'],
            ],
            'hr-admin' => self::subjects(['Shift', 'Holiday'], self::FULL) + [
                'User' => ['view-any', 'view', 'create', 'update', 'export'],
            ],
            'maintenance' => self::subjects(['Machine'], self::WRITE) + self::subjects(['Shift', 'Operation', 'Gauge'], self::READ),
            'compliance' => self::subjects(['Holiday'], self::WRITE) + self::subjects(['Buyer', 'Audit'], self::READ),
        ];

        foreach (array_keys($matrix) as $role) {
            $matrix[$role]['Branch'] = array_values(array_unique(array_merge(
                $matrix[$role]['Branch'] ?? [],
                ['view-any', 'view'],
            )));
        }

        return $matrix;
    }

    /**
     * @param  list<string>  $subjects
     * @param  list<string>  $actions
     * @return array<string, list<string>>
     */
    private static function subjects(array $subjects, array $actions): array
    {
        $map = [];

        foreach ($subjects as $subject) {
            $map[$subject] = $actions;
        }

        return $map;
    }
}
