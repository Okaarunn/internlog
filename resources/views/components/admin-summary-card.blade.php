@props(['icon', 'label', 'value', 'color', 'bgColor', 'editIcon' => null])

<div class="rounded-2xl p-5 flex flex-col gap-3 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] border-2">
    <div class="flex items-center justify-between">
        {{-- icon kiri --}}
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $bgColor }}">
            <span style="color: {{ $color }}">
                {!! $icon !!}
            </span>
        </div>

        {{-- edit icon kanan --}}
        @if ($editIcon)
            <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition group">
                <span class="text-gray-400 group-hover:text-[#145EFC] transition">
                    {!! $editIcon !!}
                </span>
            </button>
        @endif

    </div>
    <div>
        <p class="text-[#1E1E1E] text-2xl font-bold">{{ $label }}</p>
        <div class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 640 640">
                <path fill="#6B7280"
                    d="M96 192c0-61.9 50.1-112 112-112s112 50.1 112 112s-50.1 112-112 112S96 253.9 96 192M32 528c0-97.2 78.8-176 176-176s176 78.8 176 176v6c0 23.2-18.8 42-42 42H74c-23.2 0-42-18.8-42-42zm432-400c53 0 96 43 96 96s-43 96-96 96s-96-43-96-96s43-96 96-96m0 240c79.5 0 144 64.5 144 144v22.4c0 23-18.6 41.6-41.6 41.6H421.6c6.6-12.5 10.4-26.8 10.4-42v-6c0-51.5-17.4-98.9-46.5-136.7c22.6-14.7 49.6-23.3 78.5-23.3" />
            </svg>
            <p class="text-[#6B7280] text-[0.8rem] font-normal">{{ $value }}</p>
        </div>
    </div>
</div>
