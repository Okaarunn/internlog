@props([
    'name',
    'label',
    'value',
    'options', // array of ['label' => '', 'value' => '']
])

<div class="relative flex-1 sm:flex-none" x-data="{ open: false }" @click.outside="open = false">
    <button type="button" @click="open = !open"
        :class="open ? 'border-[#145EFC] shadow-[0_0_0_3px_rgba(20,94,252,0.1)]' : 'border-[#E5E7EB] hover:border-[#C5D2E0]'"
        class="w-full sm:w-[180px] flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl border bg-white text-[0.875rem] font-medium text-[#1E1E1E] cursor-pointer transition-all">
        <span class="truncate">
            @foreach ($options as $option)
                @if ($option['value'] == $value)
                    {{ $option['label'] }}
                @endif
            @endforeach
        </span>
        <svg class="w-4 h-4 shrink-0 text-[#9CA3AF] transition-transform duration-200"
            :class="open ? 'rotate-180' : 'rotate-0'" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="absolute z-50 top-full left-0 right-0 sm:w-[180px] mt-1.5 rounded-xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_rgba(0,0,0,0.08)] overflow-hidden">
        <div class="max-h-[220px] overflow-y-auto py-1">
            @foreach ($options as $option)
                <button type="button"
                    onclick="selectOption('{{ $name }}', '{{ $option['value'] }}'); open = false"
                    @click="open = false"
                    class="w-full px-4 py-2.5 text-left text-[0.85rem] cursor-pointer transition-colors flex items-center justify-between
                        {{ $option['value'] == $value ? 'bg-[#F0F4FF] text-[#145EFC] font-semibold' : 'text-[#1E1E1E] font-normal hover:bg-[#F9FAFB]' }}">
                    <span>{{ $option['label'] }}</span>
                    @if ($option['value'] == $value)
                        <svg class="w-3.5 h-3.5 text-[#145EFC]" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <input type="hidden" name="{{ $name }}" id="input-{{ $name }}" value="{{ $value }}">
</div>
