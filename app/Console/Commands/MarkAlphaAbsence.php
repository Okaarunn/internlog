<?php

namespace App\Console\Commands;

use App\Models\Absence;
use App\Models\Intern;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAlphaAbsence extends Command
{
    protected $signature = 'absence:mark-alpha';
    protected $description = 'Tandai peserta magang sebagai alpha jika tidak check in';
    public function handle()
    {
        $today = now();

        if ($today->isSunday()) {
            $this->info('Hari minggu, skip.');
            return;
        }

        $interns = Intern::with('department')->get();

        foreach ($interns as $intern) {
            $endTime = Carbon::today()->setTimeFromTimeString($intern->department->end_time);

            // Skip jika belum jam pulang
            if ($today->lessThan($endTime)) {
                continue;
            }

            $alreadyAbsent = Absence::where('intern_id', $intern->id)
                ->whereDate('date', today())
                ->exists();

            if (!$alreadyAbsent) {
                Absence::create([
                    'intern_id'         => $intern->id,
                    'date'              => today(),
                    'status'            => 'alpha',
                    'validation_status' => 'disetujui',
                ]);
                $this->info("Alpha: {$intern->name}");
            } else {
                $this->info("Sudah absen: {$intern->name}");
            }
        }

        $this->info('Selesai.');
    }
}
