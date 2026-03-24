{{-- Leave Modal --}}
<div id="leave-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onclick="document.getElementById('leave-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[#1E1E1E] text-[1.05rem] font-semibold">Leave / Permission Request</h3>
            <button onclick="document.getElementById('leave-modal').classList.add('hidden')"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-all">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('permission.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[#374151] text-sm font-medium mb-1.5">Date</label>
                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-[#1E1E1E] text-sm focus:outline-none focus:border-[#145EFC] focus:ring-2 focus:ring-[#145EFC]/10 transition-all">
            </div>
            <div>
                <label class="block text-[#374151] text-sm font-medium mb-1.5">Reason</label>
                <textarea name="description" rows="3" placeholder="Describe your reason..."
                    class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-[#1E1E1E] text-sm resize-none focus:outline-none focus:border-[#145EFC] focus:ring-2 focus:ring-[#145EFC]/10 transition-all"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="document.getElementById('leave-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-[#E5E7EB] text-[#6B7280] text-sm font-medium hover:bg-[#F9FAFB] transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-[#F54900] hover:bg-[#D94000] text-white text-sm font-semibold transition-all shadow-[0_2px_8px_rgba(245,73,0,0.25)]">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
