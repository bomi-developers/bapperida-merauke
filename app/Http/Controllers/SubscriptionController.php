<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        Subscription::create([
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil berlangganan',
        ], 200);
    }
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subscription berhasil dihapus'
        ], 200);
    }
}