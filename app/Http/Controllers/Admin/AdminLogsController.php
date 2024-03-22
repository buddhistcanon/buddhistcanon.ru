<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;

class AdminLogsController extends Controller
{
    public function index()
    {
        $logs = Log::query()
            ->where('action', 'update_content')
            ->with(['user', 'sutta', 'content.translator'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return inertia('Admin/Logs/AdminLogsPage', [
            'logs' => $logs->toArray(),
        ]);
    }
}
