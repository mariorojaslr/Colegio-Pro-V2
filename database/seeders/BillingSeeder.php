<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use Illuminate\Support\Str;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {
            // Generar 3 facturas por colegio
            for ($i = 1; $i <= 3; $i++) {
                DB::table('payment_records')->insert([
                    'school_id' => $school->id,
                    'invoice_number' => 'FAC-' . Str::upper(Str::random(8)),
                    'amount' => 25000.00 + (rand(0, 10) * 1000),
                    'currency' => 'CLP',
                    'payment_method' => rand(0, 1) ? 'card' : 'transfer',
                    'status' => 'paid',
                    'transaction_reference' => 'TXN_' . Str::random(10),
                    'created_at' => now()->subMonths($i),
                    'updated_at' => now()->subMonths($i),
                ]);
            }
        }
    }
}
