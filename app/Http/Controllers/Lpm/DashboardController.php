<?php

namespace App\Http\Controllers\Lpm;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pending' => FinalDraft::whereIn('status', ['approved_by_reviewer', 'pending_lpm'])->count(),
            'total_approved' => FinalDraft::where('status', 'approved')->count(),
            'total_rejected' => FinalDraft::where('status', 'rejected')->count(),
            'total_validated_by_lpm' => User::where('is_penyusun', true)->count(),
        ];

        return view('lpm.dashboard', compact('stats'));
    }
}
