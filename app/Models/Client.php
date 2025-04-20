<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 1. Core Client Information
        'client_id',
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
        'client_status',
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
        // 5. Client Status & Relationship
        'account_manager',
        'client_since',
        'client_tier',
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
        static::creating(function (Client $client) {
            $client->client_id = self::generateClientId();
        });
    }

    /**
     * Generate a unique client ID with prefix.
     *
     * @return string
     */
    protected static function generateClientId(): string
    {
        $prefix = 'CL25'; // Adjust the prefix as needed.
        $lastClient = self::withTrashed()
            ->where('client_id', 'like', $prefix . '%')
            ->orderByDesc('client_id')
            ->first();

        $nextNumber = 1;
        if ($lastClient) {
            // Extract numeric part after the prefix and increment it
            $lastNumber = (int) substr($lastClient->client_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        // Return client id formatted with leading zeros (4 digits)
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
