<x-admin-layout>
    <div class="p-6 sm:ml-64 bg-gray-50 mt-16">

        {{-- header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Monitoring kehadiran</h1>
            <p class="text-sm text-gray-400">Melacak dan memantau catatan kehadiran peserta magang.</p>
        </div>

        {{-- filter --}}
        <div class="bg-white rounded-xl w-full shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex items-start gap-2 text-gray-600 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="none" stroke="#145EFC" stroke-width="1.5"
                        d="M19 3H5c-1.414 0-2.121 0-2.56.412S2 4.488 2 5.815v.69c0 1.037 0 1.556.26 1.986s.733.698 1.682 1.232l2.913 1.64c.636.358.955.537 1.183.735c.474.411.766.895.898 1.49c.064.284.064.618.064 1.285v2.67c0 .909 0 1.364.252 1.718c.252.355.7.53 1.594.88c1.879.734 2.818 1.101 3.486.683S15 19.452 15 17.542v-2.67c0-.666 0-1 .064-1.285a2.68 2.68 0 0 1 .899-1.49c.227-.197.546-.376 1.182-.735l2.913-1.64c.948-.533 1.423-.8 1.682-1.23c.26-.43.26-.95.26-1.988v-.69c0-1.326 0-1.99-.44-2.402C21.122 3 20.415 3 19 3Z" />
                </svg>
                <span class="text-sm font-semibold">Filter Absensi</span>

                {{-- Logika Reset Filter --}}
                @if (request('search') || request('department_id') || request('date'))
                    <a href="{{ route('admin.absence') }}"
                        class="ml-auto text-xs text-red-500 hover:text-red-700 font-medium">
                        Reset Filter
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.absence') }}" method="GET" class="flex flex-col lg:flex-row gap-3">

                {{-- Search Input (Berdasarkan Nama/NIN) --}}
                <div class="relative w-full ">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Cari nama peserta...">
                </div>

                {{-- Filter Tanggal --}}
                <div class="w-full lg:w-32">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                </div>

                {{-- Filter Departemen --}}
                <div class="w-full lg:w-36">
                    <select name="department_id" onchange="this.form.submit()"
                        class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Cari (Mobile) --}}
                <button type="submit"
                    class="lg:hidden bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Terapkan Filter
                </button>

            </form>
        </div>


        {{-- table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs table-fixed">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Dept</th>
                        <th class="px-6 py-3">Masuk</th>
                        <th class="px-6 py-3">Keluar</th>
                        <th class="px-6 py-3">Durasi</th>
                        <th class="px-6 py-3">Catatan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Validasi</th>
                        <th class="px-6 py-3">Dicatat oleh</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700 divide-y">

                    @foreach ($absences as $absence)
                        @php
                            $statusClass = match ($absence->status) {
                                'hadir' => 'bg-green-100 text-green-600',
                                'terlambat' => 'bg-yellow-100 text-yellow-600',
                                'izin' => 'bg-blue-100 text-blue-600',
                                'sakit' => 'bg-purple-100 text-purple-600',
                                'alpha' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            $validationClass = match ($absence->validation_status) {
                                'menunggu' => 'bg-orange-100 text-orange-600',
                                'disetujui' => 'bg-green-100 text-green-600',
                                'ditolak' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <tr>
                            <td class="px-6 py-4">{{ $absence->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $absence->intern->name }}</td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $absence->intern->department->name }}
                            </td>
                            <td class="px-6 py-4">{{ $absence->check_in }}</td>
                            <td class="px-6 py-4">{{ $absence->check_out ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if ($absence->duration)
                                    {{ intdiv($absence->duration, 60) }} jam {{ $absence->duration % 60 }} menit
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $absence->notes_out ?? '-' }} </td>

                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $statusClass }}">
                                    {{ $absence->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $validationClass }}">
                                    {{ $absence->validation_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $absence->admin->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-blue-500 cursor-pointer">
                                <button type="button" data-modal-target="edit-absence-modal-{{ $absence->id }}"
                                    data-modal-toggle="edit-absence-modal-{{ $absence->id }}">Lihat</button>
                            </td>
                        </tr>

                        <x-edit-modal id="edit-absence-modal-{{ $absence->id }}" title="Detail Kehadiran"
                            method="PUT" :action="route('admin.absence.update', $absence->id)">
                            <x-slot:body>
                                {{-- detail absence --}}
                                <div class="grid grid-cols-2 gap-24">
                                    <div class="">
                                        <div>
                                            <label for="name-{{ $absence->id }}"
                                                class="block mb-1.5 text-xs font-medium text-gray-400">
                                                Nama
                                            </label>
                                            <p>{{ $absence->intern->name }}</p>
                                        </div>
                                        <div class="mt-5">
                                            <label for="checkin-{{ $absence->id }}"
                                                class="block mb-1.5 text-xs font-medium text-gray-400">
                                                Jam Masuk
                                            </label>
                                            <p>{{ $absence->check_in }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <div>
                                            <label for="date-{{ $absence->id }}"
                                                class="block mb-1.5 text-xs font-medium text-gray-400">
                                                Tanggal
                                            </label>
                                            <p>{{ $absence->date->format('d M Y') }}</p>
                                        </div>

                                        <div class="mt-5">
                                            <label for="checkout-{{ $absence->id }}"
                                                class="block mb-1.5 text-xs font-medium text-gray-400">
                                                Jam Pulang
                                            </label>
                                            <p>{{ $absence->check_out ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <div class="h-px bg-default"></div>

                                {{-- Status Kehadiran --}}
                                <div class="mt-4">
                                    <label for="status-{{ $absence->id }}"
                                        class="block mb-1.5 text-2xs uppercase tracking-wider font-bold text-gray-500">
                                        Status Kehadiran
                                    </label>
                                    <select id="status-{{ $absence->id }}" name="status"
                                        class="w-full px-3 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer shadow-sm">

                                        @foreach (['hadir', 'terlambat', 'izin', 'sakit', 'alpha'] as $status)
                                            <option value="{{ $status }}"
                                                {{ $absence->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- validation status --}}
                                <div class="mt-4">
                                    <label for="validation_status-{{ $absence->id }}"
                                        class="block mb-1.5 text-2xs uppercase tracking-wider font-bold text-gray-500">
                                        validasi status kehadiran
                                    </label>
                                    <select id="validation_status-{{ $absence->id }}" name="validation_status"
                                        class="w-full px-3 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer shadow-sm">

                                        @foreach (['menunggu', 'disetujui', 'ditolak'] as $validation_status)
                                            <option value="{{ $validation_status }}"
                                                {{ $absence->validation_status == $validation_status ? 'selected' : '' }}>
                                                {{ ucfirst($validation_status) }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </x-slot:body>
                        </x-edit-modal>
                    @endforeach
                </tbody>
            </table>

            {{-- footer --}}
            <div class="flex items-center justify-between px-6 py-3 text-sm text-gray-500">
                {{-- Menampilkan total record absensi --}}
                <span>Menampilkan {{ $absences->firstItem() ?? 0 }}-{{ $absences->lastItem() ?? 0 }} dari
                    {{ $absences->total() }} data absensi</span>

                <div class="flex gap-1">
                    {{-- Button Previous --}}
                    @if ($absences->onFirstPage())
                        <span
                            class="px-3 py-1 rounded-md border bg-gray-50 text-gray-300 cursor-not-allowed">&lt;</span>
                    @else
                        <a href="{{ $absences->previousPageUrl() }}"
                            class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50 transition-colors">&lt;</a>
                    @endif

                    {{-- Number Page (Logika sliding window) --}}
                    @foreach ($absences->getUrlRange(max(1, $absences->currentPage() - 1), min($absences->lastPage(), $absences->currentPage() + 1)) as $page => $url)
                        @if ($page == $absences->currentPage())
                            <span
                                class="px-3 py-1 rounded-md bg-blue-600 text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Button Next --}}
                    @if ($absences->hasMorePages())
                        <a href="{{ $absences->nextPageUrl() }}"
                            class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50 transition-colors">&gt;</a>
                    @else
                        <span
                            class="px-3 py-1 rounded-md border bg-gray-50 text-gray-300 cursor-not-allowed">&gt;</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
