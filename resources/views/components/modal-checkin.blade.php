{{-- Check In Modal --}}
<div id="checkin-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onclick="document.getElementById('checkin-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[#1E1E1E] text-[1.05rem] font-semibold">Check In</h3>
            <button onclick="document.getElementById('checkin-modal').classList.add('hidden')"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-all">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form class="space-y-4" method="POST" action="{{ route('checkin') }}">
            @csrf
            <div class="text-center mb-6">
                <p class="text-[#6B7280] text-sm mb-2">Waktu Saat Ini</p>
                <div class="flex items-center justify-center gap-1 font-mono">
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#145EFC] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="modal-clock-h">00</span>
                    <span class="text-[#145EFC] text-2xl font-bold mx-1">:</span>
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#145EFC] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="modal-clock-m">00</span>
                    <span class="text-[#145EFC] text-2xl font-bold mx-1">:</span>
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#145EFC] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="modal-clock-s">00</span>
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button" onclick="document.getElementById('checkin-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-[#E5E7EB] text-[#6B7280] text-sm font-medium hover:bg-[#F9FAFB] transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-[#145EFC] hover:bg-[#0F4FDB] text-white text-sm font-semibold transition-all shadow-[0_2px_8px_rgba(20,94,252,0.25)]">
                    Check In
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateModalClock() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('modal-clock-h').textContent = pad(now.getHours());
        document.getElementById('modal-clock-m').textContent = pad(now.getMinutes());
        document.getElementById('modal-clock-s').textContent = pad(now.getSeconds());
    }
    updateModalClock();
    setInterval(updateModalClock, 1000);
</script>
