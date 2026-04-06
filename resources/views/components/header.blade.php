  <header class="sticky top-0 z-50 border-b border-[#E5E7EB] bg-white shadow-[0_1px_4px_rgba(0,0,0,0.04)]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

          {{-- Logo --}}
          <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-[#145EFC]">
                  {{-- Icon ClipboardCheck --}}
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path fill="#fff"
                          d="M8 3.5A1.5 1.5 0 0 1 9.5 2h5A1.5 1.5 0 0 1 16 3.5v1A1.5 1.5 0 0 1 14.5 6h-5A1.5 1.5 0 0 1 8 4.5z" />
                      <path fill="#fff" fill-rule="evenodd"
                          d="M6.5 4.037c-1.258.07-2.052.27-2.621.84C3 5.756 3 7.17 3 9.998v6c0 2.829 0 4.243.879 5.122c.878.878 2.293.878 5.121.878h6c2.828 0 4.243 0 5.121-.878c.879-.88.879-2.293.879-5.122v-6c0-2.828 0-4.242-.879-5.121c-.569-.57-1.363-.77-2.621-.84V4.5a3 3 0 0 1-3 3h-5a3 3 0 0 1-3-3zM6.25 10.5A.75.75 0 0 1 7 9.75h10a.75.75 0 0 1 0 1.5H7a.75.75 0 0 1-.75-.75m1 3.5a.75.75 0 0 1 .75-.75h8a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1-.75-.75m1 3.5a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75"
                          clip-rule="evenodd" />
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
