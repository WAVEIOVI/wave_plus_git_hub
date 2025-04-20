<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 1. Core Supplier Information
        'supplier_id',
        'legal_name',
        'commercial_name',
        'tax_id',
        'rne_id',
        'company_size',
        'industry',
        'founding_year',
        'website',
        'certifications',
        'legal_status',
        'supplier_status',
        // 2. Location & Contact Details
        'hq_address',
        'hq_city',
        'hq_postcode',
        'hq_country',
        'primary_phone',
        'primary_email',
        // 3. VAT Exemption
        'vat_exemption_certificate_id',
        'vat_exemption_certificate_issue_date',
        'vat_exemption_certificate_expiration_date',
        // 4. Financial & Transactional Data
        'payment_terms',
        'credit_limit',
        'preferred_currency',
        'last_mission_date',
        'outstanding_balance',
        'bank',
        'bank_agence',
        'bank_rib',
        // 5. Supplier Status & Relationship
        'account_manager',
        'supplier_since',
        'supplier_tier',
        'satisfaction_score',
        'churn_risk',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    /**
     * Booted method to attach model event listeners.
     */
    protected static function booted()
    {
        static::creating(function (Supplier $supplier) {
            $supplier->supplier_id = self::generateSupplierId();
        });
    }

    /**
     * Generate a unique supplier ID with prefix.
     *
     * @return string
     */
    protected static function generateSupplierId(): string
    {
        $prefix = 'CL25'; // Adjust the prefix as needed.
        $lastSupplier = self::withTrashed()
            ->where('supplier_id', 'like', $prefix . '%')
            ->orderByDesc('supplier_id')
            ->first();

        $nextNumber = 1;
        if ($lastSupplier) {
            // Extract numeric part after the prefix and increment it
            $lastNumber = (int) substr($lastSupplier->supplier_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        // Return supplier id formatted with leading zeros (4 digits)
        return $prefix . sprintf('%04d', $nextNumber);
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(BpContact::class, 'contactable');
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(BpAddress::class, 'addressable');
    }
}
