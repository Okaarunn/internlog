<div class="min-h-screen flex items-center justify-center px-4 py-8 bg-gray-200"
    style="font-family: 'Inter', sans-serif;">
    <div
        class="w-full max-w-240 rounded-2xl overflow-hidden flex flex-col lg:flex-row bg-white shadow-[0_4px_24px_rgba(0,0,0,0.06)]">

        <!-- Left Image Panel -->
        <div class="hidden lg:flex relative w-120 shrink-0 flex-col justify-end p-8">
            <img src="https://plus.unsplash.com/premium_photo-1678139620960-5d00a6d7bb81?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Office" class="absolute inset-0 w-full h-full object-cover">
        </div>

        <!-- Right Form Panel -->
        <div class="flex-1 p-8 sm:p-10 flex flex-col justify-center">

            <!-- Logo Mobile -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 lg:hidden bg-[#145EFC]">
                    <x-logo></x-logo>
                </div>

                <h1 class="tracking-tight text-[#1E1E1E] text-[1.75rem] font-bold">
                    <span class="hidden lg:inline">Welcome Back Admin</span>
                    <span class="lg:hidden">Internlog</span>
                </h1>

                <p class="mt-1 text-[#6B7280] text-[0.875rem]">
                    <span class="hidden lg:inline">Sign in to your account to continue</span>
                    <span class="lg:hidden">Intern Attendance System Login</span>
                </p>
            </div>



            <!-- Form action to /login-->
            <form method="POST" action="{{ route('admin-login.submit') }}" class="space-y-5 relative">
                @csrf

                @if (session('failed'))
                    <div
                        class="absolute -top-14 left-0 right-0 flex items-center p-3 text-sm text-red-700 bg-red-100 rounded-md shadow">
                        <svg class="w-4 h-4 mr-2 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-width="2"
                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>{{ session('failed') }}</span>
                    </div>
                @endif

                <!-- Username -->
                <div>
                    <label class="block mb-1.5 text-[#1E1E1E] text-[0.875rem] font-medium">
                        Username
                    </label>
                    <input type="text" name="username" placeholder="Enter your username" required
                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-[#1E1E1E] text-[0.9rem] focus:border-[#145EFC] outline-none transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-1.5 text-[#1E1E1E] text-[0.875rem] font-medium">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required
                            class="w-full px-4 py-3 pr-12 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-[#1E1E1E] text-[0.9rem] focus:border-[#145EFC] outline-none transition-all">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg id="eye-open" class="h-4 w-4 text-black cursor-pointer" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2"
                                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            <svg id="eye-closed" class="h-4 w-4 text-black hidden cursor-pointer" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                        </button>
                    </div>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full cursor-pointer py-3 rounded-xl text-white bg-[#145EFC] hover:bg-[#0F4FDB] transition-all text-[0.95rem] font-semibold shadow-[0_2px_8px_rgba(20,94,252,0.3)]">
                    Sign In
                </button>
            </form>

            <p class="text-center mt-6 text-gray-400 text-[0.8rem]">
                &copy; 2026 Internlog. All rights reserved.
            </p>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        input.type = input.type === 'password' ? 'text' : 'password';

        // Toggle icon visibility
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    }
</script>
