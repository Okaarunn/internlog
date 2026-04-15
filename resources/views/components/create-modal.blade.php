{{--
|--------------------------------------------------------------------------
| modal component
|--------------------------------------------------------------------------
|
| Props:
|   - id          : string  — ID unik modal (wajib)
|   - title       : string  — Judul modal
|   - action      : string  — Action URL form
|   - method      : string  — HTTP method (default: POST)
|   - maxWidth    : string  — Lebar modal: sm | md | lg | xl (default: md)
|
| Slots:
|   - $trigger    : Tombol/elemen yang membuka modal
|   - $body       : Isi form (field-field input)
|   - $footer     : Tombol aksi (default: Batal + Simpan)
|
| Contoh penggunaan:
|
|   <x-create-modal id="create-department-modal" title="Tambah Departemen" :action="route('admin.department.store')">
|
|       <x-slot:trigger>
|           <button data-modal-target="create-department-modal"
|                   data-modal-toggle="create-department-modal"
|                   type="button"
|                   class="text-white bg-brand ...">
|               + Tambah Departemen
|           </button>
|       </x-slot:trigger>
|
|       <x-slot:body>
|           <div class="mb-4">
|               <label class="block mb-2.5 text-sm font-medium text-heading">Nama Departemen</label>
|               <input type="text" name="nama" class="bg-neutral-secondary-medium border ..." required />
|           </div>
|       </x-slot:body>
|
|   </x-create-modal>
|
--}}

@props([
    'id' => 'create-modal',
    'title' => 'Tambah Data',
    'action' => '#',
    'method' => 'POST',
    'maxWidth' => 'md',
])

@php
    $widthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

{{-- Trigger slot --}}
{{ $trigger ?? '' }}

{{-- Modal --}}
<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

    <div class="relative p-4 w-full {{ $widthClass }} max-h-full">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <h3 class="text-lg font-medium text-heading">
                    {{ $title }}
                </h3>
                <button type="button"
                    class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="{{ $id }}">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Tutup</span>
                </button>
            </div>

            {{-- Form --}}
            <form action="{{ $action }}" method="POST" class="pt-4 md:pt-5" enctype="multipart/form-data">
                @csrf

                @if (strtoupper($method) !== 'POST')
                    @method($method)
                @endif

                {{-- Body slot --}}
                <div class="space-y-4">
                    {{ $body ?? '' }}
                </div>

                {{-- Footer slot atau default --}}
                <div class="flex items-center justify-end gap-2 pt-5 mt-5 border-t border-default">
                    {{ $footer ?? '' }}

                    @if (!isset($footer))
                        <button type="button" data-modal-hide="{{ $id }}"
                            class="px-4 py-2.5 text-sm font-medium text-body bg-transparent border border-default rounded-base hover:bg-neutral-tertiary hover:text-heading focus:outline-none">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-base hover:bg-blue-700 shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium">
                            Simpan
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>
