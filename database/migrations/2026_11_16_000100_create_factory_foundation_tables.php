<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type')->index();
            $table->foreignId('parent_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('admin')->index();
            $table->string('phone')->nullable();
            $table->string('locale', 5)->default('en');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('employee_code')->nullable()->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->softDeletes();
        });

        Schema::create('branch_user', function (Blueprint $table) {
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_default')->default(false);
            $table->primary(['branch_id', 'user_id']);
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_core')->default(false)->index();
            $table->boolean('is_enabled')->default(true)->index();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('panel')->index();
            $table->string('user_type')->index();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('sees_all_branches')->default(false);
            $table->boolean('requires_two_factor')->default(false);
            $table->unsignedSmallInteger('level')->default(10);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('module_key')->index();
            $table->string('group')->nullable()->index();
            $table->string('action')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->string('key');
            $table->json('value')->nullable();
            $table->string('scope')->default('global');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->unique(['group', 'key', 'scope']);
        });

        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->string('event')->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('url')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['auditable_type', 'auditable_id']);
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('collection')->default('default')->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('prefix', 12);
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_number')->default(0);
            $table->unsignedTinyInteger('padding')->default(5);
            $table->timestamps();
            $table->unique(['branch_id', 'document_type', 'year']);
        });

        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->index();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_flow_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('step_order');
            $table->string('name');
            $table->string('permission')->nullable();
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('escalate_after_hours')->nullable();
            $table->foreignId('escalate_to_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->timestamps();
            $table->unique(['approval_flow_id', 'step_order']);
        });

        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable');
            $table->foreignId('approval_flow_id')->constrained();
            $table->string('document_type')->index();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->index();
            $table->unsignedSmallInteger('current_step')->default(1);
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('step_started_at')->nullable()->index();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('approval_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('step_order');
            $table->foreignId('actor_id')->constrained('users');
            $table->string('decision');
            $table->text('comment')->nullable();
            $table->foreignId('delegated_from_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('acted_at');
            $table->timestamps();
        });

        Schema::create('approval_delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('document_type')->nullable()->index();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('internal_memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('document_type')->default('internal_memo')->index();
            $table->string('subject');
            $table->text('body');
            $table->decimal('amount', 18, 4)->nullable();
            $table->string('currency_code', 3)->default('BDT');
            $table->string('status')->default('draft')->index();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('channel');
            $table->string('locale', 5)->default('en');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['key', 'channel', 'locale']);
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('channel');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'channel']);
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_type')->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->string('channel')->index();
            $table->string('template_key')->nullable();
            $table->string('status')->index();
            $table->text('payload')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('internal_memos');
        Schema::dropIfExists('approval_delegations');
        Schema::dropIfExists('approval_actions');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_flows');
        Schema::dropIfExists('number_sequences');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('audits');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('branch_user');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn([
                'user_type', 'phone', 'locale', 'employee_code', 'is_active',
                'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at',
                'quiet_hours_start', 'quiet_hours_end', 'deleted_at',
            ]);
        });

        Schema::dropIfExists('branches');
    }
};
