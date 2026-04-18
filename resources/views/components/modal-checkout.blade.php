{{-- Check Out Modal --}}
<div id="checkout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onclick="document.getElementById('checkout-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[#1E1E1E] text-[1.05rem] font-semibold">Check Out</h3>
            <button onclick="document.getElementById('checkout-modal').classList.add('hidden')"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-all">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form class="space-y-4" method="POST" action="{{ route('checkout') }}">
            @csrf
            <div class="text-center mb-6">
                <p class="text-[#6B7280] text-sm mb-2">Waktu Saat Ini</p>
                <div class="flex items-center justify-center gap-1 font-mono">
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#02A740] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="checkout-clock-h">00</span>
                    <span class="text-[#02A740] text-2xl font-bold mx-1">:</span>
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#02A740] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="checkout-clock-m">00</span>
                    <span class="text-[#02A740] text-2xl font-bold mx-1">:</span>
                    <span
                        class="inline-block rounded-lg px-3 py-2 bg-[#F0F4FF] text-[#02A740] text-2xl font-bold min-w-[3.5rem] text-center"
                        id="checkout-clock-s">00</span>
                </div>
            </div>

            <div>
                <label class="block text-[#374151] text-sm font-medium mb-1.5">Catatan</label>
                <textarea name="notes_out" rows="2" placeholder="Tambahkan catatan..."
                    class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-[#1E1E1E] text-sm resize-none focus:outline-none focus:border-[#02A740] focus:ring-2 focus:ring-[#02A740]/10 transition-all"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="document.getElementById('checkout-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-[#E5E7EB] text-[#6B7280] text-sm font-medium hover:bg-[#F9FAFB] transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-[#02A740] hover:bg-[#028A35] text-white text-sm font-semibold transition-all shadow-[0_2px_8px_rgba(2,167,64,0.25)]">
                    Check Out
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCheckoutClock() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('checkout-clock-h').textContent = pad(now.getHours());
        document.getElementById('checkout-clock-m').textContent = pad(now.getMinutes());
        document.getElementById('checkout-clock-s').textContent = pad(now.getSeconds());
    }
    updateCheckoutClock();
    setInterval(updateCheckoutClock, 1000);
</script>
