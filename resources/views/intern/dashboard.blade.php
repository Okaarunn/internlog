<x-intern-layout title="Dashboard - Internlog">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-summary-card label="Work Days" :value="$summary['work_days']" color="#145EFC" bgColor="#E8F0FE"
            icon='<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>' />
        <x-summary-card label="Hadir" :value="$summary['hadir']" color="#02A740" bgColor="#E8F5EE"
            icon='<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>' />
        <x-summary-card label="Menunggu" :value="$summary['menunggu']" color="#F59E0B" bgColor="#FEF3C7"
            icon='<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>' />
        <x-summary-card label="Alpha" :value="$summary['alpha']" color="#F54900" bgColor="#FFF3ED"
            icon='<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>' />
    </div>

    {{-- Today's Attendance --}}
    <div class="rounded-2xl p-6 sm:p-8 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-6">
            <div>
                <h2 class="text-[#1E1E1E] text-[1.1rem] font-semibold">Today's Attendance</h2>
                <p class="mt-1 text-[#6B7280] text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-1 font-mono">
                <span
                    class="inline-block rounded-lg px-2.5 py-1.5 bg-[#F0F4FF] text-[#145EFC] text-[1.75rem] font-bold min-w-[3rem] text-center"
                    id="clock-h">00</span>
                <span class="text-[#145EFC] text-[1.5rem] font-bold mx-1">:</span>
                <span
                    class="inline-block rounded-lg px-2.5 py-1.5 bg-[#F0F4FF] text-[#145EFC] text-[1.75rem] font-bold min-w-[3rem] text-center"
                    id="clock-m">00</span>
                <span class="text-[#145EFC] text-[1.5rem] font-bold mx-1">:</span>
                <span
                    class="inline-block rounded-lg px-2.5 py-1.5 bg-[#F0F4FF] text-[#145EFC] text-[1.75rem] font-bold min-w-[3rem] text-center"
                    id="clock-s">00</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            {{-- Check In --}}
            @if ($todayAbsence)
                <button type="button" disabled
                    class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl text-white text-[0.9rem] font-semibold bg-[#145EFC] opacity-40 cursor-not-allowed">
                @else
                    <button type="button" onclick="document.getElementById('checkin-modal').classList.remove('hidden')"
                        class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl text-white text-[0.9rem] font-semibold bg-[#145EFC] hover:bg-[#0F4FDB] shadow-[0_2px_8px_rgba(20,94,252,0.25)] transition-all cursor-pointer">
            @endif
            <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
            </svg>
            {{ $todayAbsence ? 'Sudah Check In' : 'Check In' }}
            </button>

            {{-- Check Out --}}
            @if (!$todayAbsence || $todayAbsence->check_out || in_array($todayAbsence->status, ['alpha', 'izin', 'sakit']))
                <button type="button" disabled
                    class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl text-white text-[0.9rem] font-semibold bg-[#02A740] opacity-40 cursor-not-allowed">
                @else
                    <button type="button"
                        onclick="document.getElementById('checkout-modal').classList.remove('hidden')"
                        class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl text-white text-[0.9rem] font-semibold bg-[#02A740] hover:bg-[#028A35] shadow-[0_2px_8px_rgba(2,167,64,0.25)] transition-all cursor-pointer">
            @endif
            <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
            </svg>
            @if ($todayAbsence?->status === 'alpha')
                Alpha
            @elseif(in_array($todayAbsence?->status, ['izin', 'sakit']))
                {{ ucfirst($todayAbsence->status) }}
            @elseif($todayAbsence?->check_out)
                Sudah Check Out
            @else
                Check Out
            @endif
            </button>

            {{-- Leave / Permission --}}
            <button type="button" onclick="document.getElementById('leave-modal').classList.remove('hidden')"
                class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl text-white text-[0.9rem] font-semibold bg-[#F54900] hover:bg-[#D94000] shadow-[0_2px_8px_rgba(245,73,0,0.25)] transition-all cursor-pointer">
                <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Leave / Permission
            </button>

        </div>
    </div>

    {{-- Period Filter --}}
    <form method="GET" action="{{ route('intern.dashboard') }}"
        class="rounded-2xl p-5 sm:p-6 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] mb-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-[#F0F4FF]">
                <svg class="w-3.5 h-3.5 text-[#145EFC]" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                </svg>
            </div>
            <span class="text-[#1E1E1E] text-sm font-semibold">Filter by period</span>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <x-filter-dropdown name="month" label="Select Month" :value="request('month', now()->month)" :options="collect(range(1, 12))
                ->map(
                    fn($m) => [
                        'label' => \Carbon\Carbon::create()->month($m)->translatedFormat('F'),
                        'value' => (string) $m,
                    ],
                )
                ->toArray()" />
            <x-filter-dropdown name="year" label="Select Year" :value="request('year', now()->year)" :options="collect([2024, 2025, 2026, 2027, 2028])
                ->map(fn($y) => ['label' => (string) $y, 'value' => (string) $y])
                ->toArray()" />
            {{-- Pertahankan tab aktif saat filter --}}
            <input type="hidden" name="tab" value="{{ request('tab', 'absence') }}">
            <button type="submit"
                class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-white text-[0.85rem] font-semibold bg-[#145EFC] hover:bg-[#0F4FDB] shadow-[0_2px_8px_rgba(20,94,252,0.25)] transition-all cursor-pointer sm:ml-1">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                Apply Filter
            </button>
        </div>
    </form>

    {{-- Attendance & Permission Table --}}
    @php
        $activeTab = request('tab', 'absence');
        $statusMap = [
            'hadir' => ['bg' => '#E8F5EE', 'text' => '#02A740', 'label' => 'Hadir'],
            'terlambat' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'label' => 'Terlambat'],
            'izin' => ['bg' => '#FFF3ED', 'text' => '#F54900', 'label' => 'Izin'],
            'sakit' => ['bg' => '#EEF2FF', 'text' => '#4F46E5', 'label' => 'Sakit'],
            'alpha' => ['bg' => '#FEE2E2', 'text' => '#DC2626', 'label' => 'Alpha'],
        ];
        $validationMap = [
            'menunggu' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'label' => 'Menunggu'],
            'disetujui' => ['bg' => '#E8F5EE', 'text' => '#02A740', 'label' => 'Disetujui'],
            'ditolak' => ['bg' => '#FEE2E2', 'text' => '#DC2626', 'label' => 'Ditolak'],
        ];
        $typeMap = [
            'izin' => ['bg' => '#FFF3ED', 'text' => '#F54900', 'label' => 'Izin'],
            'sakit' => ['bg' => '#EEF2FF', 'text' => '#4F46E5', 'label' => 'Sakit'],
        ];
        $permStatusMap = [
            'pending' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'label' => 'Menunggu'],
            'approved' => ['bg' => '#E8F5EE', 'text' => '#02A740', 'label' => 'Disetujui'],
            'rejected' => ['bg' => '#FEE2E2', 'text' => '#DC2626', 'label' => 'Ditolak'],
        ];
    @endphp

    <div class="rounded-2xl overflow-hidden bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] mb-6">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-[#F0F0F0]">
            <h2 class="text-[#1E1E1E] text-[1.1rem] font-semibold">Attendance Recap</h2>
            <p class="mt-0.5 text-[#6B7280] text-[0.8rem]">
                {{ now()->translatedFormat('F Y') }}
            </p>
        </div>

        {{-- Tabs --}}
        <ul class="flex text-sm font-medium px-6 border-b border-[#F0F0F0]">
            <li class="me-2">
                <a href="?tab=absence&month={{ request('month', now()->month) }}&year={{ request('year', now()->year) }}"
                    class="inline-block px-4 py-2.5 transition-all {{ $activeTab === 'absence' ? 'text-[#145EFC] border-b-2 border-[#145EFC] font-semibold' : 'text-[#6B7280] hover:text-[#1E1E1E]' }}">
                    Absence
                </a>
            </li>
            <li>
                <a href="?tab=permission&month={{ request('month', now()->month) }}&year={{ request('year', now()->year) }}"
                    class="inline-block px-4 py-2.5 transition-all {{ $activeTab === 'permission' ? 'text-[#145EFC] border-b-2 border-[#145EFC] font-semibold' : 'text-[#6B7280] hover:text-[#1E1E1E]' }}">
                    Permission
                </a>
            </li>
        </ul>

        {{-- ===== ABSENCE TAB ===== --}}
        <div class="{{ $activeTab === 'absence' ? 'block' : 'hidden' }}">

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#FAFBFC]">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Date</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Status</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Check In</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Check Out</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Duration</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Description</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                            @php
                                $s = $statusMap[$absence->status] ?? [
                                    'bg' => '#F3F4F6',
                                    'text' => '#6B7280',
                                    'label' => $absence->status,
                                ];
                                $v = $validationMap[$absence->validation_status] ?? null;
                            @endphp
                            <tr class="{{ !$loop->last ? 'border-b border-[#F0F0F0]' : '' }} hover:bg-[#FAFBFC]">
                                <td class="px-6 py-4 text-[#1E1E1E] text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($absence->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-[0.78rem] font-semibold"
                                        style="background-color:{{ $s['bg'] }};color:{{ $s['text'] }}">
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[#374151] text-sm">{{ $absence->check_in ?? '-' }}</td>
                                <td class="px-6 py-4 text-[#374151] text-sm">{{ $absence->check_out ?? '-' }}</td>
                                <td class="px-6 py-4 text-[#374151] text-sm">
                                    @if ($absence->duration)
                                        {{ intdiv($absence->duration, 60) }} jam {{ $absence->duration % 60 }} menit
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-[#6B7280] text-[0.85rem]">{{ $absence->notes_out ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($v)
                                        <span class="inline-block px-3 py-1 rounded-full text-[0.78rem] font-semibold"
                                            style="background-color:{{ $v['bg'] }};color:{{ $v['text'] }}">
                                            {{ $v['label'] }}
                                        </span>
                                    @else
                                        <span class="text-[#9CA3AF] text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-[#9CA3AF] text-sm">
                                    Belum ada data absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-[#F0F0F0]">
                @forelse($absences as $absence)
                    @php
                        $s = $statusMap[$absence->status] ?? [
                            'bg' => '#F3F4F6',
                            'text' => '#6B7280',
                            'label' => $absence->status,
                        ];
                        $v = $validationMap[$absence->validation_status] ?? null;
                    @endphp
                    <div class="px-5 py-4 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[#1E1E1E] text-sm font-semibold">
                                {{ \Carbon\Carbon::parse($absence->date)->translatedFormat('d M Y') }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[0.75rem] font-semibold"
                                style="background:{{ $s['bg'] }};color:{{ $s['text'] }}">
                                {{ $s['label'] }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <p class="text-[#9CA3AF] text-[0.7rem]">Check In</p>
                                <p class="text-[#374151] text-[0.85rem] font-medium">{{ $absence->check_in ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[#9CA3AF] text-[0.7rem]">Check Out</p>
                                <p class="text-[#374151] text-[0.85rem] font-medium">{{ $absence->check_out ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[#9CA3AF] text-[0.7rem]">Duration</p>
                                <p class="text-[#374151] text-[0.85rem] font-medium">
                                    @if ($absence->duration)
                                        {{ intdiv($absence->duration, 60) }}j {{ $absence->duration % 60 }}m
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[#6B7280] text-[0.8rem]">{{ $absence->notes_out ?? '-' }}</p>
                            @if ($v)
                                <span class="px-3 py-1 rounded-full text-[0.72rem] font-semibold"
                                    style="background:{{ $v['bg'] }};color:{{ $v['text'] }}">
                                    {{ $v['label'] }}
                                </span>
                            @else
                                <span class="text-[#9CA3AF] text-[0.8rem]">-</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-[#9CA3AF] text-sm">Belum ada data absensi.</div>
                @endforelse
            </div>

            {{-- Pagination Absence --}}
            <div class="px-6 py-4 flex items-center justify-between border-t border-[#F0F0F0]">
                <p class="text-[#9CA3AF] text-[0.8rem]">Page {{ $absences->currentPage() }} of
                    {{ $absences->lastPage() }}</p>
                <div class="flex gap-2">
                    <a href="{{ $absences->previousPageUrl() ?? '#' }}"
                        class="w-9 h-9 rounded-lg flex items-center justify-center border border-[#E5E7EB] text-[#6B7280] transition-all {{ $absences->onFirstPage() ? 'opacity-40 pointer-events-none' : 'hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                    <a href="{{ $absences->nextPageUrl() ?? '#' }}"
                        class="w-9 h-9 rounded-lg flex items-center justify-center border border-[#E5E7EB] text-[#6B7280] transition-all {{ !$absences->hasMorePages() ? 'opacity-40 pointer-events-none' : 'hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- ===== PERMISSION TAB ===== --}}
        <div class="{{ $activeTab === 'permission' ? 'block' : 'hidden' }}">

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#FAFBFC]">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Start Date</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">End
                                Date</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Type</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Reason</th>
                            <th class="px-6 py-3.5 text-left text-[#6B7280] text-[0.78rem] font-semibold uppercase">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            @php
                                $t = $typeMap[$permission->type] ?? [
                                    'bg' => '#F3F4F6',
                                    'text' => '#6B7280',
                                    'label' => $permission->type,
                                ];
                                $ps = $permStatusMap[$permission->status] ?? [
                                    'bg' => '#F3F4F6',
                                    'text' => '#6B7280',
                                    'label' => $permission->status,
                                ];
                            @endphp
                            <tr class="{{ !$loop->last ? 'border-b border-[#F0F0F0]' : '' }} hover:bg-[#FAFBFC]">
                                <td class="px-6 py-4 text-[#1E1E1E] text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($permission->start_date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-[#1E1E1E] text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($permission->end_date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-[0.78rem] font-semibold"
                                        style="background-color:{{ $t['bg'] }};color:{{ $t['text'] }}">
                                        {{ $t['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[#6B7280] text-[0.85rem]">{{ $permission->reason }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-[0.78rem] font-semibold"
                                        style="background-color:{{ $ps['bg'] }};color:{{ $ps['text'] }}">
                                        {{ $ps['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#9CA3AF] text-sm">
                                    Belum ada data permission.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-[#F0F0F0]">
                @forelse($permissions as $permission)
                    @php
                        $t = $typeMap[$permission->type] ?? [
                            'bg' => '#F3F4F6',
                            'text' => '#6B7280',
                            'label' => $permission->type,
                        ];
                        $ps = $permStatusMap[$permission->status] ?? [
                            'bg' => '#F3F4F6',
                            'text' => '#6B7280',
                            'label' => $permission->status,
                        ];
                    @endphp
                    <div class="px-5 py-4 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#9CA3AF] text-[0.7rem]">Periode</p>
                                <p class="text-[#1E1E1E] text-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($permission->start_date)->translatedFormat('d M Y') }}
                                    @if ($permission->start_date !== $permission->end_date)
                                        — {{ \Carbon\Carbon::parse($permission->end_date)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[0.75rem] font-semibold"
                                style="background:{{ $t['bg'] }};color:{{ $t['text'] }}">
                                {{ $t['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[#6B7280] text-[0.8rem]">{{ $permission->reason }}</p>
                            <span class="px-3 py-1 rounded-full text-[0.72rem] font-semibold"
                                style="background:{{ $ps['bg'] }};color:{{ $ps['text'] }}">
                                {{ $ps['label'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-[#9CA3AF] text-sm">Belum ada data permission.</div>
                @endforelse
            </div>

            {{-- Pagination Permission --}}
            <div class="px-6 py-4 flex items-center justify-between border-t border-[#F0F0F0]">
                <p class="text-[#9CA3AF] text-[0.8rem]">Page {{ $permissions->currentPage() }} of
                    {{ $permissions->lastPage() }}</p>
                <div class="flex gap-2">
                    <a href="{{ $permissions->previousPageUrl() ?? '#' }}"
                        class="w-9 h-9 rounded-lg flex items-center justify-center border border-[#E5E7EB] text-[#6B7280] transition-all {{ $permissions->onFirstPage() ? 'opacity-40 pointer-events-none' : 'hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                    <a href="{{ $permissions->nextPageUrl() ?? '#' }}"
                        class="w-9 h-9 rounded-lg flex items-center justify-center border border-[#E5E7EB] text-[#6B7280] transition-all {{ !$permissions->hasMorePages() ? 'opacity-40 pointer-events-none' : 'hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- Modals --}}
    <x-modal-leave />
    <x-modal-checkin />
    <x-modal-checkout />

    <footer class="py-6 text-center text-[#9CA3AF] text-[0.78rem]">
        &copy; {{ date('Y') }} Internlog. All rights reserved.
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            document.getElementById('clock-h').textContent = pad(now.getHours());
            document.getElementById('clock-m').textContent = pad(now.getMinutes());
            document.getElementById('clock-s').textContent = pad(now.getSeconds());
        }
        updateClock();
        setInterval(updateClock, 1000);

        function selectOption(name, value) {
            document.getElementById('input-' + name).value = value;
        }
    </script>

</x-intern-layout>
`
