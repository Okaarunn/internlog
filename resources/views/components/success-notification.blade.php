        @if (session('success'))
            <div id="toast-top-right"
                class="fixed top-4 right-4 z-50 w-full max-w-md p-4 mb-4 text-sm text-fg-success rounded-base bg-success-soft border border-success-subtle"
                role="alert">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 shrink-0 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <h3 class="font-semibold">{{ session('success') }}</h3>
                    </div>
                    <button type="button" class="cursor-pointer" data-dismiss-target="#toast-success"
                        aria-label="Close"
                        class="ms-auto -mx-1.5 -my-1.5 bg-success-soft text-fg-success-strong rounded focus:ring-2 focus:ring-success-medium p-1.5 hover:bg-success-medium inline-flex items-center justify-center h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
