<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;

class PPDBOpenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $is_ppdb_open = Settings::getValue('is_ppdb_open', 'false') === 'true';
        $ppdb_start_date = Settings::getValue('ppdb_start_date');
        $ppdb_end_date = Settings::getValue('ppdb_end_date');
        $ppdb_year = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        if (!$is_ppdb_open) {
            return response()->view('ppdb.closed', compact('ppdb_year', 'ppdb_start_date', 'ppdb_end_date'));
        }

        if ($ppdb_start_date && $ppdb_end_date) {
            $now = now();
            $start = \Carbon\Carbon::parse($ppdb_start_date);
            $end = \Carbon\Carbon::parse($ppdb_end_date);

            if ($now->lt($start)) {
                return response()->view('ppdb.closed', [
                    'message' => 'Pendaftaran PPDB belum dibuka. Pendaftaran akan dibuka pada ' . $start->format('d F Y'),
                    'ppdb_year' => $ppdb_year,
                    'ppdb_start_date' => $ppdb_start_date,
                    'ppdb_end_date' => $ppdb_end_date
                ]);
            }

            if ($now->gt($end)) {
                return response()->view('ppdb.closed', [
                    'message' => 'Pendaftaran PPDB sudah ditutup pada ' . $end->format('d F Y'),
                    'ppdb_year' => $ppdb_year,
                    'ppdb_start_date' => $ppdb_start_date,
                    'ppdb_end_date' => $ppdb_end_date
                ]);
            }
        }

        return $next($request);
    }
}
