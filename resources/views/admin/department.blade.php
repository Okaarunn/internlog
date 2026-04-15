<x-admin-layout>
    <div class="p-4 sm:ml-64 mt-14">
        <div class="flex items-center justify-between my-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Manajemen Departemen</h1>
                <p class="text-sm text-gray-400">3 departemen - 1 peserta magang</p>
            </div>

            <div>
                <button data-modal-target="create-department-modal" data-modal-toggle="create-department-modal"
                    type="button"
                    class="text-white bg-blue-600 box-border border border-transparent hover:bg-blue-700 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none flex items-center justify-center gap-1 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                    </svg>
                    <span>Tambah Departemen</span>
                </button>
            </div>
        </div>

        {{-- summary cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                $palettes = [
                    ['color' => '#7C3AED', 'bg' => '#F3E8FF'],
                    ['color' => '#2563EB', 'bg' => '#DBEAFE'],
                    ['color' => '#059669', 'bg' => '#D1FAE5'],
                    ['color' => '#D97706', 'bg' => '#FEF3C7'],
                    ['color' => '#DB2777', 'bg' => '#FCE7F3'],
                ];
            @endphp

            @foreach ($departments as $department)
                @php
                    $palette = $palettes[$loop->index % count($palettes)];
                @endphp

                <div class="rounded-2xl p-5 flex flex-col gap-3 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] border-2">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                            style="background-color: {{ $palette['bg'] }}">
                            <span style="color: {{ $palette['color'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </span>
                        </div>

                        <div class="flex items-center gap-1">
                            {{-- edit button --}}
                            <button type="button" data-modal-target="edit-department-modal-{{ $department->id }}"
                                data-modal-toggle="edit-department-modal-{{ $department->id }}"
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
                            <button type="button" data-modal-target="delete-department-modal-{{ $department->id }}"
                                data-modal-toggle="delete-department-modal-{{ $department->id }}"
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
                    </div>
                    <div>
                        <p class="text-[#1E1E1E] text-2xl font-bold">{{ $department->name }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 640 640">
                                <path fill="#6B7280"
                                    d="M96 192c0-61.9 50.1-112 112-112s112 50.1 112 112s-50.1 112-112 112S96 253.9 96 192M32 528c0-97.2 78.8-176 176-176s176 78.8 176 176v6c0 23.2-18.8 42-42 42H74c-23.2 0-42-18.8-42-42zm432-400c53 0 96 43 96 96s-43 96-96 96s-96-43-96-96s43-96 96-96m0 240c79.5 0 144 64.5 144 144v22.4c0 23-18.6 41.6-41.6 41.6H421.6c6.6-12.5 10.4-26.8 10.4-42v-6c0-51.5-17.4-98.9-46.5-136.7c22.6-14.7 49.6-23.3 78.5-23.3" />
                            </svg>
                            <p class="text-[#6B7280] text-[0.9rem] font-medium">{{ $department->interns_count }} Peserta
                                Magang</p>
                        </div>
                    </div>
                </div>


                {{-- edit modal --}}
                <x-edit-modal id="edit-department-modal-{{ $department->id }}" title="Edit Departemen" method="PUT"
                    :action="route('admin.department.update', $department->id)">
                    <x-slot:body>
                        {{-- Nama Departemen --}}
                        <div>
                            <label for="name-{{ $department->id }}"
                                class="block mb-1.5 text-sm font-medium text-heading">
                                Nama Departemen
                            </label>
                            <div class="relative">
                                <input type="text" id="name-{{ $department->id }}" name="name"
                                    value="{{ old('name', $department->name) }}"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2.5 shadow-xs placeholder:text-body transition-colors"
                                    placeholder="Contoh: Engineering" required />
                            </div>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-default"></div>
                            <span class="text-xs text-body font-medium">Jam Kerja</span>
                            <div class="flex-1 h-px bg-default"></div>
                        </div>

                        {{-- Jam Mulai & Selesai --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="start_time-{{ $department->id }}"
                                    class="block mb-1.5 text-sm font-medium text-heading">
                                    Jam mulai
                                </label>
                                <input type="time" id="start_time-{{ $department->id }}" name="start_time"
                                    value="{{ old('start_time', \Carbon\Carbon::parse($department->start_time)->format('H:i')) }}"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2.5 shadow-xs transition-colors"
                                    required />
                            </div>

                            <div>
                                <label for="end_time-{{ $department->id }}"
                                    class="block mb-1.5 text-sm font-medium text-heading">
                                    Jam selesai
                                </label>
                                <input type="time" id="end_time-{{ $department->id }}" name="end_time"
                                    value="{{ old('end_time', \Carbon\Carbon::parse($department->end_time)->format('H:i')) }}"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2.5 shadow-xs transition-colors"
                                    required />
                            </div>
                        </div>
                    </x-slot:body>

                </x-edit-modal>

                {{-- delete modal --}}
                <x-delete-modal id="delete-department-modal-{{ $department->id }}"
                    title="Hapus Departemen {{ $department->name }}?" :action="route('admin.department.destroy', $department->id)">
                    <x-slot:body>
                        <p>Apakah Anda yakin ingin menghapus departemen <strong>{{ $department->name }}</strong>?
                            Pastikan tidak ada peserta magang yang terdaftar.</p>
                    </x-slot:body>
                </x-delete-modal>
            @endforeach
        </div>
    </div>

    {{-- create modal --}}
    <x-create-modal id="create-department-modal" title="Tambah Departemen" method="POST" :action="route('admin.department.store')">
        <x-slot:body>

            {{-- Nama Departemen --}}
            <div>
                <label for="name" class="block mb-1.5 text-sm font-medium text-heading">
                    Nama Departemen
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                    </div>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-3 py-2.5 shadow-xs placeholder:text-body transition-colors"
                        placeholder="Contoh: Engineering" required />
                </div>
                @error('name')
                    <div class="flex items-center gap-1 mt-1.5">
                        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            {{-- Divider --}}
            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-default"></div>
                <span class="text-xs text-body font-medium">Jam Kerja</span>
                <div class="flex-1 h-px bg-default"></div>
            </div>

            {{-- Jam Mulai & Selesai --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_time" class="block mb-1.5 text-sm font-medium text-heading">
                        Jam mulai
                    </label>
                    <div class="relative">
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-10 py-2.5 shadow-xs transition-colors appearance-none"
                            required />
                        <div class="absolute  inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    @error('start_time')
                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block mb-1.5 text-sm font-medium text-heading">
                        Jam selesai
                    </label>
                    <div class="relative">
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-10 py-2.5 shadow-xs transition-colors appearance-none"
                            required />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    @error('end_time')
                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Info hint --}}
            <p class="text-xs text-body flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022zM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5z"
                        clip-rule="evenodd" />
                </svg>
                Jam selesai harus lebih dari jam mulai
            </p>

        </x-slot:body>

    </x-create-modal>
</x-admin-layout>
