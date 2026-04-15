@props([
    'id' => 'delete-modal',
    'title' => 'Hapus Data',
    'action' => '#',
    'maxWidth' => 'sm',
])

@php
    $widthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        default => 'max-w-md',
    };
@endphp

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

    <div class="relative p-4 w-full {{ $widthClass }} max-h-full">
        <div class="relative bg-white border border-default rounded-base shadow-sm p-4 md:p-6 text-center">

            {{-- Tombol Close --}}
            <button type="button"
                class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                data-modal-hide="{{ $id }}">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6""" ) />>
                </svg>
                <span class="sr-only">Tutup</span>
            </button>

            <div class="p-4 md:p-5 text-center">
                {{-- Icon Peringatan --}}
                <svg class="mx-auto mb-4 text-red-600 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z""" ) />>
                </svg>

                <h3 class="mb-5 text-lg font-semibold text-heading">
                    {{ $title }}
                </h3>

                {{-- Body slot untuk pesan kustom (Contoh: "Apakah anda yakin?") --}}
                <div class="mb-6 text-sm text-body">
                    {{ $body ?? 'Tindakan ini tidak dapat dibatalkan. Semua data yang terkait akan terhapus.' }}
                </div>

                {{-- Form Aksi --}}
                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" data-modal-hide="{{ $id }}"
                            class="py-2.5 px-5 text-sm font-medium text-body bg-white rounded-base border border-default hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none transition-colors">
                            Batal
                        </button>

                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-base text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
