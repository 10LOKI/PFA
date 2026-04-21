<nav x-data="{ open: false }" class="fixed w-full top-0 z-50 backdrop-blur-md bg-[rgba(9,0,20,0.9)] border-b-2 border-[var(--neon-cyan)] shadow-[0_0_20px_rgba(0,255,255,0.3)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side: Logo + Nav Links -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-heading font-black text-[var(--neon-cyan)] tracking-wider uppercase drop-shadow-[0_0_10px_#00FFFF]">
                        <span class="text-3xl">🎯</span>
                        <span class="text-glow-cyan">ACTTOGETHER</span>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    @php
                        $navItems = [
                            'dashboard' => ['label' => 'DASHBOARD', 'icon' => '📊'],
                        ];
                        if(auth()->user()->role === 'student') {
                            $navItems['events.index'] = ['label' => 'EVENTS', 'icon' => '📅'];
                            $navItems['rewards.index'] = ['label' => 'REWARDS', 'icon' => '🎁'];
                        }
                        if(auth()->user()->role === 'partner') {
                            $navItems['events.create'] = ['label' => 'CREATE EVENT', 'icon' => '➕'];
                        }
                        if(auth()->user()->role === 'admin') {
                            $navItems['admin.kyc.index'] = ['label' => 'ADMIN', 'icon' => '⚙️'];
                        }
                        $navItems['messages.index'] = ['label' => 'MSG', 'icon' => '💬'];
                    @endphp

                    @foreach($navItems as $route => $item)
                        <a href="{{ route($route) }}" 
                           class="px-4 py-2 text-sm font-mono uppercase tracking-widest transition-all duration-200 
                                  {{ request()->routeIs($route.'*') ? 'text-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)] border-l-2 border-l-[var(--neon-cyan)]' : 'text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.05)]' }}">
                            <span class="mr-1">{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Right side: Status LEDs + Points + User Menu -->
            <div class="flex items-center gap-6">
                @if(auth()->user()->role === 'student')
                    <div class="hidden sm:flex items-center">
                        <div class="border-2 border-[var(--neon-orange)] px-4 py-2 bg-[rgba(255,153,0,0.1)]">
                            <span class="text-lg font-heading font-bold text-[var(--neon-orange)] drop-shadow-[0_0_5px_#FF9900]">{{ number_format(auth()->user()->points_balance) }}</span>
                            <span class="text-xs font-mono text-[var(--neon-orange)] ml-1">PTS</span>
                        </div>
                    </div>
                @endif

                <!-- Notification LED -->
                @php
                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count();
                @endphp
                <a href="{{ route('notifications.index') }}" class="relative hidden sm:flex items-center group">
                    <div class="w-10 h-10 border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center bg-[rgba(255,0,255,0.1)] group-hover:shadow-[var(--glow-magenta)] transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--neon-magenta)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-[var(--neon-orange)] text-black font-bold text-xs shadow-[var(--glow-orange)]">{{ $unreadCount }}</span>
                    @endif
                </a>

                <!-- User Dropdown Trigger -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <button @click="open = ! open" class="flex items-center gap-2 px-3 py-2 border-2 border-[var(--neon-magenta)] bg-[rgba(255,0,255,0.1)] hover:bg-[rgba(255,0,255,0.2)] transition-all duration-300">
                        <span class="text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)]">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)] px-2 py-0.5 border border-[var(--neon-cyan)]">{{ strtoupper(Auth::user()->grade) }}</span>
                        <svg class="w-4 h-4 text-[var(--neon-cyan)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] hover:bg-[rgba(0,255,255,0.1)] transition-colors duration-200">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t-2 border-[var(--neon-cyan)] bg-[rgba(9,0,20,0.95)] backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" 
               class="block px-4 py-3 text-sm font-mono uppercase tracking-widest border-l-4 border-l-[var(--neon-cyan)] text-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)]">
                📊 DASHBOARD
            </a>

             @if(auth()->user()->role === 'student')
                 <a href="{{ route('events.index') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                     📅 EVENTS
                 </a>
                 <a href="{{ route('rewards.index') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                     🎁 REWARDS
                 </a>
             @endif

             @if(auth()->user()->role === 'partner')
                 <a href="{{ route('events.index') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                     📅 MY EVENTS
                 </a>
                 <a href="{{ route('events.create') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                     ➕ CREATE EVENT
                 </a>
             @endif

             @if(auth()->user()->role === 'admin')
                 <a href="{{ route('admin.kyc.index') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                     ⚙️ ADMIN
                 </a>
             @endif

             <a href="{{ route('messages.index') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] hover:border-l-4 hover:border-l-[var(--neon-magenta)] transition-all duration-200">
                 💬 MESSAGES
             </a>

             @if($unreadCount > 0)
             <a href="{{ route('notifications.index') }}" class="flex items-center gap-2 px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--neon-orange)] bg-[rgba(255,153,0,0.1)] border-l-4 border-l-[var(--neon-orange)]">
                 <span class="w-2 h-2 bg-[var(--neon-orange)] rounded-full animate-pulse"></span>
                 <span>NOTIFICATIONS ({{ $unreadCount }})</span>
             </a>
             @endif
        </div>

        <div class="pt-4 pb-1 border-t border-[var(--neon-cyan)]/30">
            <div class="px-4">
                <div class="font-mono text-base text-[var(--neon-cyan)]">{{ Auth::user()->name }}</div>
                <div class="font-mono text-sm text-[var(--chrome-text)]/60">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-cyan)] hover:bg-[rgba(0,255,255,0.1)] transition-all duration-200">
                    ⚙️ PROFILE SETTINGS
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.1)] transition-all duration-200">
                        🚪 LOGOUT SYSTEM
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>