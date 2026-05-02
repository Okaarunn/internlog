<x-admin-layout>
    <div class="p-6 sm:ml-64 mt-14 bg-gray-50">

        {{-- header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Manajemen peserta</h1>
                <p class="text-sm text-gray-400">Mengelola data dan akun magang peserta</p>
            </div>

            <button data-modal-target="create-intern-modal" data-modal-toggle="create-intern-modal" type="button"
                class="text-white bg-blue-600 box-border border border-transparent hover:bg-blue-700 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none flex items-center justify-center gap-1 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                </svg>
                <span>Tambah Peserta</span>
            </button>


        </div>

        {{-- filter --}}
        <div class="bg-white rounded-xl w-full shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex items-center gap-2 text-gray-600 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="#145EFC" stroke-width="1.5"
                        d="M19 3H5c-1.414 0-2.121 0-2.56.412S2 4.488 2 5.815v.69c0 1.037 0 1.556.26 1.986s.733.698 1.682 1.232l2.913 1.64c.636.358.955.537 1.183.735c.474.411.766.895.898 1.49c.064.284.064.618.064 1.285v2.67c0 .909 0 1.364.252 1.718c.252.355.7.53 1.594.88c1.879.734 2.818 1.101 3.486.683S15 19.452 15 17.542v-2.67c0-.666 0-1 .064-1.285a2.68 2.68 0 0 1 .899-1.49c.227-.197.546-.376 1.182-.735l2.913-1.64c.948-.533 1.423-.8 1.682-1.23c.26-.43.26-.95.26-1.988v-.69c0-1.326 0-1.99-.44-2.402C21.122 3 20.415 3 19 3Z" />
                </svg>
                <span class="text-sm font-medium">Filters</span>

                @if (request('search') || request('department_id'))
                    <a href="{{ route('admin.intern') }}" class="ml-auto text-xs text-red-500 hover:underline">Reset
                        Filter</a>
                @endif
            </div>

            <form action="{{ route('admin.intern') }}" method="GET" class="flex flex-col lg:flex-row gap-4">

                {{-- Search Input --}}
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                        placeholder="Cari nama, username, atau NIK peserta...">
                </div>

                {{-- Filter Departemen --}}
                <div class="w-56">
                    <select name="department_id" onchange="this.form.submit()"
                        class="block w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm
                   focus:ring-2 focus:ring-blue-500 transition-all bg-white">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 w-[20%]">Nama</th>
                        <th class="px-6 py-3 w-[15%]">Username</th>
                        <th class="px-6 py-3 w-[20%]">Departemen</th>
                        <th class="px-6 py-3 w-[25%]">Periode Magang</th>
                        <th class="px-6 py-3 w-[15%] text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700 divide-y">

                    @foreach ($interns as $intern)
                        {{-- row --}}
                        <tr>
                            <td class="px-6 py-4 truncate font-medium text-gray-900">{{ $intern->name }}</td>
                            <td class="px-6 py-4 truncate">{{ $intern->username }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-600 inline-block truncate max-w-full">
                                    {{ $intern->department->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $intern->start_date->format('d/m/Y') }} — {{ $intern->end_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    {{-- edit button --}}
                                    <button type="button" data-modal-target="edit-intern-modal-{{ $intern->id }}"
                                        data-modal-toggle="edit-intern-modal-{{ $intern->id }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition group">
                                        <span class="transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <path fill="none" stroke="#145EFC" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497zM15 5l4 4" />
                                            </svg>
                                        </span>
                                    </button>

                                    {{-- delete button --}}
                                    <button type="button" data-modal-target="delete-intern-modal-{{ $intern->id }}"
                                        data-modal-toggle="delete-intern-modal-{{ $intern->id }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition group">
                                        <span class="transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <g fill="#DC2626">
                                                    <path fill-rule="evenodd"
                                                        d="M17 5V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V7h1a1 1 0 1 0 0-2zm-2-1H9v1h6zm2 3H7v11a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1z"
                                                        clip-rule="evenodd" />
                                                    <path d="M9 9h2v8H9zm4 0h2v8h-2z" />
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </td>


                        </tr>


                        {{-- edit modal --}}

                        <x-edit-modal id="edit-intern-modal-{{ $intern->id }}" title="Edit Peserta Magang"
                            method="PUT" :action="route('admin.intern.update', $intern->id)">
                            <x-slot:body>
                                {{-- Kontainer dengan scroll jika layar pendek --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3 overflow-y-auto px-1 mb-3">

                                    {{-- Nama & NIN --}}
                                    <div class="md:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-heading">Nama
                                            Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $intern->name) }}"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2 focus:ring-1 focus:ring-blue-500"
                                            placeholder="Nama sesuai KTP" required>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">NIK (16
                                            Digit)</label>
                                        <input type="text" name="nin" value="{{ old('nin', $intern->nin) }}"
                                            inputmode="numeric" maxlength="16"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            placeholder="Nomor Induk" required>
                                    </div>


                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Jenis
                                            Kelamin</label>
                                        <select name="gender"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            required>
                                            <option value="laki-laki"
                                                {{ old('gender', $intern->gender) == 'laki-laki' ? 'selected' : '' }}>
                                                Laki-laki
                                            </option>
                                            <option value="perempuan"
                                                {{ old('gender', $intern->gender) == 'perempuan' ? 'selected' : '' }}>
                                                Perempuan
                                            </option>
                                        </select>
                                    </div>


                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Departemen</label>
                                        <select name="department_id"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            required>
                                            <option value="" disabled hidden>Pilih...</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ old('department_id', $intern->department_id) == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">No.
                                            Telepon</label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', $intern->phone) }}"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            placeholder="08..." required>
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="md:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-heading">Alamat</label>
                                        <textarea name="address"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            placeholder="Alamat domisili" required>{{ old('address', $intern->address) }}</textarea>
                                    </div>


                                    {{-- Periode Magang --}}
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Tgl Mulai</label>
                                        <input type="date" name="start_date"
                                            value="{{ old('start_date', $intern->start_date->format('Y-m-d')) }}"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Tgl
                                            Selesai</label>
                                        <input type="date" name="end_date"
                                            value="{{ old('end_date', $intern->end_date->format('Y-m-d')) }}"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            required>
                                    </div>


                                    {{-- Akun --}}
                                    <div class="md:col-span-2 border-t border-dashed border-default mt-2 pt-3">
                                        <p class="text-2xs uppercase tracking-wider font-bold text-gray-400 mb-2">
                                            Informasi Login</p>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Username</label>
                                        <input type="text" name="username"
                                            value="{{ old('username', $intern->username) }}"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            required>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-heading">Password</label>
                                        <input type="password" name="password"
                                            class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                                            placeholder="••••••••">
                                        <span class="text-xs text-gray-400">*Kosongkan jika tidak dirubah</span>
                                    </div>

                                </div>
                            </x-slot:body>
                        </x-edit-modal>

                        {{-- delete modal --}}
                        <x-delete-modal id="delete-intern-modal-{{ $intern->id }}"
                            title="Hapus akun {{ $intern->name }}?" :action="route('admin.intern.destroy', $intern->id)">
                            <x-slot:body>
                                <p>Apakah Anda yakin ingin menghapus data peserta
                                    <strong>{{ $intern->name }}</strong>?
                                </p>
                            </x-slot:body>
                        </x-delete-modal>
                    @endforeach
                </tbody>
            </table>

            {{-- footer --}}
            <div class="flex items-center justify-between px-6 py-3 text-sm text-gray-500">
                <span>{{ $interns->total() }} peserta</span>

                <div class="flex gap-1">
                    {{-- button next --}}
                    @if ($interns->onFirstPage())
                        <span
                            class="px-3 py-1 rounded-md border bg-gray-50 text-gray-300 cursor-not-allowed">&lt;</span>
                    @else
                        <a href="{{ $interns->previousPageUrl() }}"
                            class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50">&lt;</a>
                    @endif

                    {{-- number page --}}
                    @foreach ($interns->getUrlRange(max(1, $interns->currentPage() - 1), min($interns->lastPage(), $interns->currentPage() + 1)) as $page => $url)
                        @if ($page == $interns->currentPage())
                            <span class="px-3 py-1 rounded-md bg-blue-600 text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- button next --}}
                    @if ($interns->hasMorePages())
                        <a href="{{ $interns->nextPageUrl() }}"
                            class="px-3 py-1 rounded-md border bg-white hover:bg-gray-50">&gt;</a>
                    @else
                        <span
                            class="px-3 py-1 rounded-md border bg-gray-50 text-gray-300 cursor-not-allowed">&gt;</span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- modal create --}}
    <x-create-modal id="create-intern-modal" title="Tambah Peserta Magang" method="POST" :action="route('admin.intern.store')"
        maxWidth="lg">
        <x-slot:body>
            {{-- Kontainer dengan scroll jika layar pendek --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3 overflow-y-auto px-1 mb-3">

                {{-- Nama & NIN --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-xs font-semibold text-heading">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2 focus:ring-1 focus:ring-blue-500"
                        placeholder="Nama sesuai KTP" required>
                </div>

                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">NIK (16 Digit)</label>
                    <input type="text" name="nin" value="{{ old('nin') }}" inputmode="numeric"
                        maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        placeholder="Nomor Induk" required>
                </div>


                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Jenis Kelamin</label>
                    <select name="gender"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        required>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                {{-- Departemen & Telepon --}}
                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Departemen</label>
                    <select name="department_id"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        required>
                        <option value="" disabled selected hidden>Pilih...</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        placeholder="08..." required>
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-xs font-semibold text-heading">Alamat</label>
                    <textarea type="text" name="address" value="{{ old('address') }}"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        placeholder="Alamat domisili" required> </textarea>
                </div>

                {{-- Periode Magang --}}
                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Tgl Mulai</label>
                    <input type="date" name="start_date"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        required>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Tgl Selesai</label>
                    <input type="date" name="end_date"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        required>
                </div>

                {{-- Akun --}}
                <div class="md:col-span-2 border-t border-dashed border-default mt-2 pt-3">
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400 mb-2">Informasi Login</p>
                </div>

                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Username</label>
                    <input type="text" name="username"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        required>
                </div>

                <div>
                    <label class="block mb-1 text-xs font-semibold text-heading">Password</label>
                    <input type="password" name="password"
                        class="w-full bg-neutral-secondary-medium border border-default-medium rounded-md text-sm px-3 py-2"
                        placeholder="Min. 6 Karakter" required>
                </div>
            </div>
        </x-slot:body>
    </x-create-modal>
</x-admin-layout>
