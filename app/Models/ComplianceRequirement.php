<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceRequirement extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'type',
        'is_mandatory',
        'delivery_format',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(CollegiateDocument::class);
    }
}
