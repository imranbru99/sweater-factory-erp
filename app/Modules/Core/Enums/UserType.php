<?php

namespace App\Modules\Core\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserType: string implements HasLabel
{
    case Admin = 'admin';
    case Director = 'director';
    case Merchandiser = 'merchandiser';
    case MerchandisingManager = 'merchandising_manager';
    case SamplingMaster = 'sampling_master';
    case Programmer = 'programmer';
    case YarnStoreKeeper = 'yarn_store_keeper';
    case KnittingMaster = 'knitting_master';
    case LinkingSupervisor = 'linking_supervisor';
    case QcInspector = 'qc_inspector';
    case FinishingIncharge = 'finishing_incharge';
    case PackingIncharge = 'packing_incharge';
    case CommercialOfficer = 'commercial_officer';
    case Accounts = 'accounts';
    case HrAdmin = 'hr_admin';
    case MaintenanceEngineer = 'maintenance_engineer';
    case ComplianceOfficer = 'compliance_officer';
    case Buyer = 'buyer';
    case Supplier = 'supplier';
    case Subcontractor = 'subcontractor';
    case PublicVisitor = 'public_visitor';

    public function getLabel(): string
    {
        return __('factory.user_types.'.$this->value);
    }

    public function panel(): string
    {
        return match ($this) {
            self::Buyer => 'buyer',
            self::Supplier => 'supplier',
            self::Subcontractor => 'subcontractor',
            self::PublicVisitor => 'public',
            default => 'admin',
        };
    }

    /**
     * @return list<string>
     */
    public static function staff(): array
    {
        return array_values(array_map(
            fn (self $type): string => $type->value,
            array_filter(self::cases(), fn (self $type): bool => $type->panel() === 'admin'),
        ));
    }
}
