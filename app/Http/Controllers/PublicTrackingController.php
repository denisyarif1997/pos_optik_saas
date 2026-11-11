<?php

namespace App\Http\Controllers;

use App\Models\OpticalOrder;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    public function form(Request $request)
    {
        // Jika ada kode di query string, langsung redirect ke halaman tracking
        if ($request->filled('code')) {
            return redirect()->route('track.show', $request->query('code'));
        }

        return view('tracking.form');
    }

    public function show(string $code)
    {
        $order = OpticalOrder::where('code', $code)
            ->with(['customer', 'items', 'payments', 'prescription', 'store'])
            ->first();

        if (! $order) {
            return redirect()->route('track.form')
                ->withErrors(['code' => 'Kode pesanan tidak ditemukan.']);
        }

        return view('tracking.order', compact('order'));
    }
}
