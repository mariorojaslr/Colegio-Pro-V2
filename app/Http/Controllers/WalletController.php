<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;

        // Billeteras del colegio actual, con sus movimientos ordenados por fecha
        $wallets = $school->wallets()->with(['movements' => function($query) {
            $query->latest()->limit(50); // Mostramos los últimos 50 movimientos
        }])->get();

        return view('admin.wallets.index', compact('wallets', 'school'));
    }
}
