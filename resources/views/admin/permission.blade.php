<x-admin-layout>
    <div class="p-6 sm:ml-64 bg-gray-50 mt-16">

        {{-- header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Manajemen Perizinan</h1>
            <p class="text-sm text-gray-400">Meninjau dan menyetujui permintaan perizinan peserta magang..</p>
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
                        class="ml-auto e-xs text-red-500 hover:text-red-700 font-medium">
                        Reset Filter
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.permission') }}" method="GET" class="flex flex-col lg:flex-row gap-3">

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


                {{-- Filter Departemen --}}
                <div class="lg:w-96">
                    <select name="department_id"
                        class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                        <option disabled selected>Semua status</option>
                        <option value="">Menunggu</option>
                        <option value="">Disetujui</option>
                        <option value="">Ditolak</option>
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
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">tipe izin</th>
                        <th class="px-6 py-3">tanggal mulai</th>
                        <th class="px-6 py-3">tanggal selesai</th>
                        <th class="px-6 py-3">keterangan</th>
                        <th class="px-6 py-3">status</th>
                        <th class="px-6 py-3">Dicatat oleh</th>
                        <th class="px-6 py-3">aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700 divide-y">

                    @foreach ($permissions as $permission)
                        {{-- label --}}
                        @php
                            $typeClass = match ($permission->type) {
                                'izin' => 'bg-blue-100 text-blue-600',
                                'sakit' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            $statusClass = match ($permission->status) {
                                'pending' => 'bg-orange-100 text-orange-600',
                                'approved' => 'bg-green-100 text-green-600',
                                'rejected' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp


                        <tr>
                            {{-- intern name --}}
                            <td class="px-6 py-4">{{ $permission->intern->name }} <br>
                                <span class="text-gray-400 text-xs">{{ $permission->intern->department->name }}</span>
                            </td>
                            {{-- permission type --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $typeClass }}">
                                    {{ $permission->type }}
                                </span>
                            </td>
                            {{-- start date --}}
                            <td class="px-6 py-4">{{ $permission->start_date->format('d M Y') }}</td>
                            {{-- end date --}}
                            <td class="px-6 py-4">{{ $permission->end_date->format('d M Y') }}</td>

                            {{-- notes --}}
                            <td class="px-6 py-4">{{ $permission->reason ?? '-' }} </td>

                            {{-- status --}}
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $statusClass }}">
                                    {{ $permission->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $permission->approvedBy->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-blue-500 cursor-pointer">
                                <button type="button" data-modal-target="edit-permission-modal-{{ $permission->id }}"
                                    data-modal-toggle="edit-permission-modal-{{ $permission->id }}">Lihat</button>
                            </td>
                        </tr>

                        {{-- permission detail --}}
                        <x-edit-modal id="edit-permission-modal-{{ $permission->id }}" title="Detail Permohonan Cuti"
                            method="PUT" :action="route('admin.permission.update', $permission->id)">
                            <x-slot:body>
                                {{-- detail absence --}}

                                {{-- intern data --}}
                                <div>
                                    <div class="flex items-center justify-between gap-24">
                                        <p>{{ $permission->intern->name }}</p>
                                        <span
                                            class="px-3 py-1 text-xs capitalize rounded-full font-medium {{ $statusClass }}">
                                            {{ $permission->status }}
                                        </span>
                                    </div>

                                    <span class="text-xs text-gray-400">
                                        {{ $permission->intern->department->name }}
                                    </span>
                                </div>




                                {{-- permission detail --}}
                                <div class="grid grid-cols-2 gap-24 bg-gray-100 rounded-xl p-5">
                                    {{-- permission type --}}
                                    <div>
                                        <div>
                                            <p class="block text-xs font-normal text-gray-500">
                                                Tipe Izin
                                            </p>
                                            <p class="text-[14px] font-semibold text-gray-800 capitalize">
                                                {{ $permission->type }}
                                            </p>
                                        </div>

                                        <div class="mt-5">
                                            <p class="block text-xs font-normal text-gray-500">
                                                Dibuat Pada
                                            </p>
                                            {{-- H = Jam (24 jam), i = Menit --}}
                                            <p class="text-[14px] font-semibold text-gray-800 capitalize">
                                                {{ $permission->created_at->format('d M Y') }}
                                            </p>
                                        </div>

                                    </div>

                                    {{-- start date end date --}}
                                    <div>
                                        <div>
                                            <p class="block text-xs font-normal text-gray-500">
                                                Tanggal Mulai
                                            </p>
                                            <p class="text-[14px] font-semibold text-gray-800 capitalize">
                                                {{ $permission->start_date->format('d M Y') }}
                                            </p>
                                        </div>

                                        <div class="mt-5">
                                            <p class="block text-xs font-normal text-gray-500">
                                                Tanggal Selesai
                                            </p>
                                            <p class="text-[14px] font-semibold text-gray-800 capitalize">
                                                {{ $permission->end_date->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                {{-- reason --}}
                                <div>
                                    <p class="block text-[14px] font-normal text-black mb-3">
                                        Keterangan
                                    </p>

                                    <div class="w-full bg-gray-100 rounded-xl p-3">
                                        <p class="text-[14px] text-justify normal-case">
                                            {{ $permission->reason }}
                                        </p>
                                    </div>
                                </div>
                            </x-slot:body>
                            <x-slot:footer>
                                <div class="flex justify-end items-center gap-3 w-full">
                                    {{-- Tombol Tutup --}}
                                    <button type="button"
                                        data-modal-hide="edit-permission-modal-{{ $permission->id }}"
                                        class="px-6 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                                        Tutup
                                    </button>

                                    {{-- Tombol Tolak --}}
                                    <button type="submit" name="status" value="rejected"
                                        class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-red-500 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Ditolak
                                    </button>

                                    {{-- Tombol Setujui --}}
                                    <button type="submit" name="status" value="approved"
                                        class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-green-500 rounded-xl hover:bg-green-600 shadow-lg shadow-green-100 transition-all active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Disetujui
                                    </button>
                                </div>
                            </x-slot:footer>

                        </x-edit-modal>
                    @endforeach
                </tbody>
            </table>

            {{-- footer --}}


        </div>
    </div>
</x-admin-layout>
