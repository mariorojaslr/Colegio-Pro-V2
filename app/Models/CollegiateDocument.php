<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegiateDocument extends Model
{
    protected $fillable = [
        'collegiate_id',
        'compliance_requirement_id',
        'file_path',
        'status',
        'expiry_date',
        'admin_notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requirement()
    {
        return $this->belongsTo(ComplianceRequirement::class, 'compliance_requirement_id');
    }
}
