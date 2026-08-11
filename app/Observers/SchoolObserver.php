<?php

namespace App\Observers;

use App\Models\School;

class SchoolObserver
{
    /**
     * Handle the School "created" event.
     */
    public function created(School $school): void
    {
        // Sembrar las 4 billeteras por defecto
        $school->wallets()->createMany([
            ['name' => 'Mercado Pago', 'type' => 'mercadopago', 'balance' => 0, 'is_active' => true],
            ['name' => 'Tarjeta Naranja', 'type' => 'bank', 'balance' => 0, 'is_active' => true],
            ['name' => 'Banco Santander', 'type' => 'bank', 'balance' => 0, 'is_active' => true],
            ['name' => 'Banco Macro', 'type' => 'bank', 'balance' => 0, 'is_active' => true],
        ]);
    }

    /**
     * Handle the School "updated" event.
     */
    public function updated(School $school): void
    {
        //
    }

    /**
     * Handle the School "deleted" event.
     */
    public function deleted(School $school): void
    {
        //
    }

    /**
     * Handle the School "restored" event.
     */
    public function restored(School $school): void
    {
        //
    }

    /**
     * Handle the School "force deleted" event.
     */
    public function forceDeleted(School $school): void
    {
        //
    }
}
