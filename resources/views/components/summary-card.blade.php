@props(['icon', 'label', 'value', 'color', 'bgColor', 'editIcon' => null, 'detail' => null])

<div class="rounded-2xl p-5 flex flex-col gap-3 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)]">

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

        <p class="text-[#6B7280] text-[0.8rem] font-normal">{{ $label }}</p>
        <p class="text-[#1E1E1E] text-2xl font-bold">{{ $value }}</p>

        <p class="text-[#6B7280] text-[0.8rem] font-normal">{{ $detail }}</p>
    </div>

</div>
