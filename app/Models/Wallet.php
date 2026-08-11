<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'type',
        'balance',
        'currency',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function movements()
    {
        return $this->hasMany(WalletMovement::class);
    }

    public function addFunds($amount, $description, $referenceId = null, $referenceType = null)
    {
        $this->balance += $amount;
        $this->save();

        return $this->movements()->create([
            'type' => 'income',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    public function deductFunds($amount, $description, $referenceId = null, $referenceType = null)
    {
        $this->balance -= $amount;
        $this->save();

        return $this->movements()->create([
            'type' => 'expense',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }
}
