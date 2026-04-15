<x-admin-layout>
    <div class="p-6 sm:ml-64 mt-14 bg-gray-50 min-h-screen">

        {{-- header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Monitoring kehadiran</h1>
            <p class="text-sm text-gray-400">Melacak dan memantau catatan kehadiran peserta magang.</p>
        </div>

        {{-- filter --}}
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex items-center gap-2 text-gray-600 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="#145EFC" stroke-width="1.5"
                        d="M19 3H5c-1.414 0-2.121 0-2.56.412S2 4.488 2 5.815v.69c0 1.037 0 1.556.26 1.986s.733.698 1.682 1.232l2.913 1.64c.636.358.955.537 1.183.735c.474.411.766.895.898 1.49c.064.284.064.618.064 1.285v2.67c0 .909 0 1.364.252 1.718c.252.355.7.53 1.594.88c1.879.734 2.818 1.101 3.486.683S15 19.452 15 17.542v-2.67c0-.666 0-1 .064-1.285a2.68 2.68 0 0 1 .899-1.49c.227-.197.546-.376 1.182-.735l2.913-1.64c.948-.533 1.423-.8 1.682-1.23c.26-.43.26-.95.26-1.988v-.69c0-1.326 0-1.99-.44-2.402C21.122 3 20.415 3 19 3Z" />
                </svg>
                <span class="text-sm font-medium">Filter</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-3 lg:items-center justify-between">

                {{-- search --}}
                <input type="text"
                    class="w-full lg:w-1/3 px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                    placeholder="Cari nama peserta magang...">

                {{-- filter kanan --}}
                <div class="flex gap-3">
                    <input type="date"
                        class="px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500">

                    <select
                        class="px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>Semua Departemen</option>
                    </select>
                </div>
            </div>
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
                                <span class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $statusClass }}">
                                    {{ $absence->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $validationClass }}">
                                    {{ $absence->validation_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-blue-500 cursor-pointer">View</td>
                        </tr>
                    @endforeach




                </tbody>
            </table>

            {{-- footer --}}
            <div class="flex items-center justify-between px-6 py-3 text-sm text-gray-500">
                <span>Showing 1–8 of 12</span>

                <div class="flex gap-1">
                    <button class="px-3 py-1 rounded-md border bg-white">&lt;</button>
                    <button class="px-3 py-1 rounded-md bg-blue-500 text-white">1</button>
                    <button class="px-3 py-1 rounded-md border bg-white">2</button>
                    <button class="px-3 py-1 rounded-md border bg-white">&gt;</button>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
