<nav x-data="{ open: false, notificationsOpen: false, unreadCount: 0 }" class="bg-[#FFDCDC] border-b border-[#FFD6BA]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="text-xl font-bold text-[#D4A574]">ActTogether</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'student')
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                            {{ __('Événements') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.*')">
                            {{ __('Rewards') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'partner')
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                            {{ __('Mes Événements') }}
                        </x-nav-link>
                        <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
                            {{ __('Créer un événement') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.kyc.index')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        💬
                    </x-nav-link>

                    <button @click="notificationsOpen = !notificationsOpen" class="relative inline-flex items-center px-2 py-2 text-[#D4A574] hover:bg-[#FFF2EB] rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 -mt-1 -mr-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white"></span>
                    </button>

                    <div x-show="notificationsOpen" @click.away="notificationsOpen = false" class="absolute right-4 top-16 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden" style="display: none;">
                        <div class="p-3 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Notifications</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto" id="notificationsList">
                            <div class="p-4 text-gray-500 text-center">Aucune notification</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Points Balance (Student only) -->
            @if(auth()->user()->role === 'student')
                <div class="hidden sm:flex items-center me-4">
                    <span class="bg-[#FFE8CD] text-[#D4A574] px-3 py-1 rounded-full text-sm font-semibold">
                        {{ Auth::user()->points_balance }} pts
                    </span>
                </div>
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-[#FFF2EB] hover:bg-[#FFE8CD] focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <span class="ms-2 text-xs text-gray-500 capitalize bg-[#FFD6BA] px-2 py-0.5 rounded">
                                {{ Auth::user()->grade }}
                            </span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-[#D4A574] hover:bg-[#FFF2EB] focus:outline-none focus:bg-[#FFF2EB] focus:text-[#D4A574] transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'student')
                <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                    {{ __('Événements') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.*')">
                    {{ __('Rewards') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'partner')
                <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                    {{ __('Mes Événements') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
                    {{ __('Créer un événement') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@auth
@push('scripts')
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.data('pusherNotifications', function() {
                return {
                    init() {
                        const pusher = new Pusher('{{ config('services.pusher.key') }}', {
                            cluster: '{{ config('services.pusher.cluster') }}',
                            encrypted: true
                        });

                        const channel = pusher.subscribe('notifications.{{ auth()->id() }}');
                        channel.bind('App\\Events\\EventCreated', (data) => {
                            this.showToast(data.event);
                            this.unreadCount++;
                        });
                    },
                    showToast(event) {
                        const toast = document.createElement('div');
                        toast.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 z-50 border-l-4 border-[#D4A574] animate-slide-in';
                        toast.innerHTML = `
                            <div class="flex items-start gap-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Nouvel événement</p>
                                    <p class="text-sm text-gray-600">${event.title}</p>
                                    <p class="text-xs text-gray-500 mt-1">${event.category} • ${event.points} pts • ${event.city}</p>
                                    <a href="${event.link}" class="text-sm text-[#D4A574] hover:underline mt-2 inline-block">Voir l'événement</a>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 5000);
                    }
                };
            });
        });

        (function() {
            const pusher = new Pusher('{{ config('services.pusher.key') }}', {
                cluster: '{{ config('services.pusher.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('notifications.{{ auth()->id() }}');
            channel.bind('App\\Events\\EventCreated', function(data) {
                const notificationsList = document.getElementById('notificationsList');
                if (notificationsList) {
                    const emptyMsg = notificationsList.querySelector('.text-gray-500');
                    if (emptyMsg) emptyMsg.remove();

                    const item = document.createElement('a');
                    item.href = data.event.link;
                    item.className = 'block p-3 hover:bg-gray-50 border-b border-gray-50';
                    item.innerHTML = `
                        <p class="font-medium text-gray-800">${data.event.title}</p>
                        <p class="text-sm text-gray-500">${data.event.category} • ${data.event.points} pts</p>
                        <p class="text-xs text-gray-400">${new Date(data.event.date).toLocaleDateString()}</p>
                    `;
                    notificationsList.insertBefore(item, notificationsList.firstChild);
                }

                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 z-50 border-l-4 border-[#D4A574] max-w-sm';
                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">Nouvel événement</p>
                            <p class="text-sm text-gray-600">${data.event.title}</p>
                            <p class="text-xs text-gray-500 mt-1">${data.event.category} • ${data.event.points} pts • ${data.event.city}</p>
                            <a href="${data.event.link}" class="text-sm text-[#D4A574] hover:underline mt-2 inline-block">Voir l'événement</a>
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            });
        })();
    </script>
@endpush
@endauth