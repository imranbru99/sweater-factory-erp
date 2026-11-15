<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_of_measures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('dimension')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_unit_id')->constrained('unit_of_measures')->cascadeOnDelete();
            $table->foreignId('to_unit_id')->constrained('unit_of_measures')->cascadeOnDelete();
            $table->decimal('factor', 18, 8);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['from_unit_id', 'to_unit_id']);
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('symbol', 8);
            $table->unsignedTinyInteger('decimal_places')->default(2);
            $table->boolean('is_base')->default(false)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3)->index();
            $table->string('to_currency', 3)->index();
            $table->decimal('rate', 18, 8);
            $table->date('effective_date')->index();
            $table->string('source')->default('manual');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['from_currency', 'to_currency', 'effective_date']);
        });

        $this->lookup('gauges', function (Blueprint $table) {
            $table->decimal('gauge_number', 5, 2);
            $table->text('description')->nullable();
        });
        $this->lookup('plies', function (Blueprint $table) {
            $table->unsignedTinyInteger('ply_count');
        });
        $this->lookup('yarn_counts', function (Blueprint $table) {
            $table->string('system', 12)->index();
            $table->decimal('count_value', 8, 2);
        });
        $this->lookup('yarn_types', function (Blueprint $table) {
            $table->string('fibre')->nullable();
        });
        $this->lookup('stitch_types');
        $this->lookup('product_types');
        $this->lookup('departments');
        $this->lookup('seasons', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->index();
        });
        $this->lookup('colors', function (Blueprint $table) {
            $table->string('pantone')->nullable()->index();
            $table->string('hex', 7)->nullable();
        });
        $this->lookup('size_groups');

        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_group_id')->constrained()->cascadeOnDelete();
            $table->string('code', 16);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['size_group_id', 'code']);
        });

        $this->lookup('defect_codes', function (Blueprint $table) {
            $table->string('category')->index();
            $table->string('severity')->index();
        });
        $this->lookup('operations', function (Blueprint $table) {
            $table->string('stage')->index();
            $table->decimal('smv', 8, 4);
        });
        $this->lookup('taxes', function (Blueprint $table) {
            $table->string('tax_type')->index();
            $table->decimal('rate', 8, 4);
        });
        $this->lookup('hs_codes', function (Blueprint $table) {
            $table->text('description')->nullable();
        });
        $this->lookup('shifts', function (Blueprint $table) {
            $table->time('starts_at');
            $table->time('ends_at');
            $table->unsignedSmallInteger('break_minutes')->default(60);
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date')->index();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['holiday_date', 'branch_id']);
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 16);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type')->index();
            $table->boolean('is_bonded')->default(false)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('rack');
            $table->string('bin')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['warehouse_id', 'rack']);
        });

        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->string('code', 16);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('brand')->nullable()->index();
            $table->string('country', 2)->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('incoterm', 8)->nullable();
            $table->string('currency_code', 3)->default('USD');
            $table->unsignedSmallInteger('credit_days')->default(0);
            $table->decimal('commission_percent', 8, 4)->default(0);
            $table->text('compliance_requirements')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('buyer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buying_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->decimal('commission_percent', 8, 4)->default(0);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->string('code', 16);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type')->index();
            $table->string('country', 2)->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('currency_code', 3)->default('BDT');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('supplier_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->string('code', 16);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->json('processes');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gauge_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('stitch_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('yarn_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 32);
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->text('description')->nullable();
            $table->decimal('target_weight_grams', 8, 2)->nullable();
            $table->string('status')->default('development')->index();
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('gauge_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 16);
            $table->string('brand')->index();
            $table->string('model')->nullable();
            $table->decimal('width_inches', 6, 2)->nullable();
            $table->unsignedSmallInteger('year_made')->nullable();
            $table->string('serial_number')->nullable()->index();
            $table->string('status')->default('idle')->index();
            $table->unsignedSmallInteger('needles')->nullable();
            $table->date('installed_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });
    }

    public function down(): void
    {
        foreach ([
            'machines', 'styles', 'subcontractors', 'supplier_contacts', 'suppliers',
            'buying_agents', 'buyer_contacts', 'buyers', 'locations', 'warehouses',
            'holidays', 'shifts', 'hs_codes', 'taxes', 'operations', 'defect_codes',
            'sizes', 'size_groups', 'colors', 'seasons', 'departments', 'product_types',
            'stitch_types', 'yarn_types', 'yarn_counts', 'plies', 'gauges',
            'exchange_rates', 'currencies', 'unit_conversions', 'unit_of_measures',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    private function lookup(string $table, ?callable $extra = null): void
    {
        Schema::create($table, function (Blueprint $blueprint) use ($extra) {
            $blueprint->id();
            $blueprint->string('code', 32)->unique();
            $blueprint->string('name');
            $blueprint->string('name_bn')->nullable();
            if ($extra !== null) {
                $extra($blueprint);
            }
            $blueprint->boolean('is_active')->default(true)->index();
            $blueprint->unsignedSmallInteger('sort')->default(0);
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }
};
