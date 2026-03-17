  <header class="sticky top-0 z-50 border-b border-[#E5E7EB] bg-white shadow-[0_1px_4px_rgba(0,0,0,0.04)]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

          {{-- Logo --}}
          <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-[#145EFC]">
                  {{-- Icon ClipboardCheck --}}
                  <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125V18.75m-7.5-10.5v10.5" />
                  </svg>
              </div>
              <span class="text-[#1E1E1E] text-[1.15rem] font-bold">
                  Internlog
              </span>
          </div>

          {{-- Right Side --}}
          <div class="flex items-center gap-4">
              {{-- User Info --}}
              <div class="hidden sm:flex items-center gap-2">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#E8F0FE]">
                      <svg class="w-4 h-4 text-[#145EFC]" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                      </svg>
                  </div>
                  <span class="text-[#1E1E1E] text-sm font-medium">
                      {{ Auth::user()->name }}
                  </span>
              </div>

              {{-- Logout Button --}}
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                      class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-[#E5E7EB] text-[#6B7280] text-[0.85rem] font-medium cursor-pointer transition-all hover:bg-[#FEE2E2] hover:text-[#DC2626] hover:border-[#FECACA]">
                      <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                          stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                      </svg>
                      <span class="hidden sm:inline">Logout</span>
                  </button>
              </form>
          </div>

      </div>
  </header>
