<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // 1. Core Client Information
            $table->string('client_id')->unique();
            $table->string('legal_name');
            $table->string('commercial_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('rne_id')->nullable();
            $table->year('founding_year')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            $table->string('certifications')->nullable();
            $table->string('legal_status')->nullable();
            $table->enum('client_status', ['active', 'pending', 'inactive', 'blocked'])->default('pending');
            // 2. Location & Contact Details
            $table->string('hq_address')->nullable();
            $table->string('hq_city')->nullable();
            $table->string('hq_postcode')->nullable();
            $table->string('hq_country')->default('tunisia');
            $table->string('primary_phone')->nullable();
            $table->string('primary_email')->nullable();
            $table->string('website')->nullable();
            // 3. VAT Exemption
            $table->string('vat_exemption_certificate_id')->nullable();
            $table->date('vat_exemption_certificate_issue_date')->nullable();
            $table->date('vat_exemption_certificate_expiration_date')->nullable();
            // 4. Financial & Transactional Data
            $table->string('payment_terms')->nullable();
            $table->decimal('credit_limit', 15, 3)->nullable();
            $table->decimal('outstanding_balance', 15, 3)->nullable();
            $table->string('preferred_currency')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_agence')->nullable();
            $table->string('bank_rib')->nullable();
            // 5. Client Status & Relationship
            $table->string('account_manager')->nullable();
            $table->date('client_since')->nullable();
            $table->date('last_mission_date')->nullable();
            $table->enum('client_tier', ['unclassified', 'individual', 'basic', 'enterprise', 'premium', 'group'])->default('unclassified');
            $table->tinyInteger('satisfaction_score')->unsigned()->default(0);
            $table->tinyInteger('churn_risk')->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
