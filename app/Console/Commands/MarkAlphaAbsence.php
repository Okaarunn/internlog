<?php

namespace App\Console\Commands;

use App\Models\Absence;
use App\Models\Intern;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAlphaAbsence extends Command
{
    protected $signature = 'absence:mark-alpha {--debug : Show detailed debug output}';
    protected $description = 'Tandai peserta magang sebagai alpha jika tidak check in';
    
    public function handle()
    {
        $today = now();
        $debug = $this->option('debug');
        
        if ($debug) {
            $this->info("=== MARK ALPHA ABSENCE ===");
            $this->info("Current time: {$today->format('Y-m-d H:i:s')}");
            $this->info("Is Sunday: " . ($today->isSunday() ? 'Yes' : 'No'));
        }

        if ($today->isSunday()) {
            if ($debug) $this->info('Hari minggu, skip.');
            \Log::info('MarkAlphaAbsence: Skipped - Sunday');
            return;
        }

        $interns = Intern::with('department')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();

        if ($debug) $this->info("Found {$interns->count()} active interns");

        if ($interns->isEmpty()) {
            if ($debug) $this->info('No active interns found for today.');
            \Log::info('MarkAlphaAbsence: No active interns found');
            return;
        }

        $processedCount = 0;
        $alphaCount = 0;
        $alreadyAbsentCount = 0;
        $skippedCount = 0;

        foreach ($interns as $intern) {
            if ($debug) {
                $this->info("");
                $this->info("Processing: {$intern->name} (Department: {$intern->department->name})");
            }
            
            $endTime = Carbon::today()->setTimeFromTimeString($intern->department->end_time);
            
            if ($debug) {
                $this->info("Department end time: {$endTime->format('H:i')}");
                $this->info("Current time: {$today->format('H:i')}");
            }

            // Cek apakah sudah lewat jam pulang
            if ($today->lessThan($endTime)) {
                if ($debug) $this->info("⏰ Belum jam pulang, skip {$intern->name}");
                $skippedCount++;
                continue;
            }

            // Cek apakah sudah ada absen hari ini
            $alreadyAbsent = Absence::where('intern_id', $intern->id)
                ->whereDate('date', today())
                ->exists();

            if ($alreadyAbsent) {
                if ($debug) $this->info("✅ {$intern->name} sudah absen hari ini");
                $alreadyAbsentCount++;
            } else {
                Absence::create([
                    'intern_id'         => $intern->id,
                    'date'              => today(),
                    'status'            => 'alpha',
                    'validation_status' => 'disetujui',
                ]);
                if ($debug) $this->info("🚫 ALPHA: {$intern->name} - marked as alpha");
                $alphaCount++;
            }
            $processedCount++;
        }

        // Log summary for production monitoring
        \Log::info('MarkAlphaAbsence completed', [
            'total_interns' => $interns->count(),
            'processed' => $processedCount,
            'marked_alpha' => $alphaCount,
            'already_absent' => $alreadyAbsentCount,
            'skipped' => $skippedCount,
            'date' => today()->format('Y-m-d')
        ]);

        if ($debug) {
            $this->info("");
            $this->info("=== SUMMARY ===");
            $this->info("Total interns: {$interns->count()}");
            $this->info("Processed: {$processedCount}");
            $this->info("Marked as alpha: {$alphaCount}");
            $this->info("Already absent: {$alreadyAbsentCount}");
            $this->info("Skipped (not yet end time): {$skippedCount}");
            $this->info('=== SELESAI ===');
        } else {
            $this->info("Processed {$processedCount} interns, marked {$alphaCount} as alpha");
        }
    }
}
