<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show premium subscription page
     */
    public function index()
    {
        $user = Auth::user();
        $isPremium = $user->is_premium_active;
        $premiumExpiresAt = $user->premium_expires_at;
        
        return view('premium.index', compact('isPremium', 'premiumExpiresAt'));
    }
    
    /**
     * Process subscription (simulasi tanpa payment gateway dulu)
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        
        // Simulasi pembayaran berhasil
        $user->activatePremium(30);
        
        // Simpan transaksi
        Transaction::create([
            'user_id' => $user->id,
            'order_id' => 'ORDER-' . time() . '-' . $user->id,
            'amount' => 20000,
            'status' => 'success',
            'payment_type' => 'simulated',
            'paid_at' => now(),
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Premium berhasil diaktifkan! Selamat belajar!');
    }
    
    /**
     * Payment success callback
     */
    public function success(Request $request)
    {
        // Untuk integrasi payment gateway nanti
        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
    }
    
    /**
     * Payment cancelled
     */
    public function cancel(Request $request)
    {
        return redirect()->route('premium.index')->with('error', 'Pembayaran dibatalkan.');
    }
    
    /**
     * Webhook for payment gateway
     */
    public function webhook(Request $request)
    {
        // Untuk integrasi payment gateway nanti
        Log::info('Payment webhook received', $request->all());
        
        return response()->json(['status' => 'ok']);
    }
}