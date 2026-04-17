<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ActTogether - Gamification du Bénévolat</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            @keyframes pulse-soft {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            @keyframes slide-up {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes scale-in {
                from { opacity: 0; transform: scale(0.9); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-float { animation: float 3s ease-in-out infinite; }
            .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
            .animate-slide-up { animation: slide-up 0.6s ease-out forwards; }
            .animate-scale-in { animation: scale-in 0.5s ease-out forwards; }
            .animation-delay-100 { animation-delay: 100ms; }
            .animation-delay-200 { animation-delay: 200ms; }
            .animation-delay-300 { animation-delay: 300ms; }
            .animation-delay-400 { animation-delay: 400ms; }
            .animation-delay-500 { animation-delay: 500ms; }
        </style>
    </head>
    <body class="bg-[#FFF2EB] dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans transition-colors duration-300">
        <!-- Header -->
        <header class="bg-[#FFDCDC] dark:bg-gray-800 shadow-sm fixed w-full top-0 z-50 backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95 transition-colors duration-300">
            <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="text-2xl font-bold text-[#D4A574] flex items-center gap-2">
                    <span class="text-3xl">🎯</span>
                    <span>ActTogether</span>
                </a>
                <div class="flex items-center gap-4">
                    <button id="theme-toggle" class="p-2 rounded-lg bg-[#FFD6BA] dark:bg-gray-700 hover:bg-[#FFE8CD] dark:hover:bg-gray-600 transition transform hover:scale-105" aria-label="Toggle dark mode">
                        <svg id="sun-icon" class="w-5 h-5 text-[#D4A574] hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg id="moon-icon" class="w-5 h-5 text-[#D4A574] block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-[#FFD6BA] dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg font-medium hover:bg-[#FFE8CD] dark:hover:bg-gray-600 transition transform hover:scale-105">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 dark:text-gray-200 hover:text-[#D4A574] font-medium transition hover:scale-105">Connexion</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition transform hover:scale-105 hover:shadow-lg">Inscription</a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="bg-[#FFDCDC] dark:bg-gray-800 pt-32 pb-24 relative overflow-hidden transition-colors duration-300">
            <div class="absolute inset-0 opacity-10 dark:opacity-5">
                <div class="absolute top-20 left-10 w-64 h-64 bg-[#D4A574] rounded-full filter blur-3xl animate-pulse-soft dark:bg-[#D4A574]"></div>
                <div class="absolute bottom-10 right-20 w-96 h-96 bg-[#FFE8CD] rounded-full filter blur-3xl animate-pulse-soft animation-delay-200 dark:bg-[#FFD6BA]"></div>
            </div>
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative">
                <div class="animate-slide-up">
                    <div class="inline-flex items-center gap-2 bg-white dark:bg-gray-700 px-4 py-2 rounded-full shadow mb-6">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Plus de 500 étudiants déjà engagés</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 dark:text-white mb-4 leading-tight">
                        Transformez votre <span class="text-[#D4A574] relative">
                            engagement
                            <span class="absolute -bottom-2 left-0 w-full h-1 bg-[#D4A574] rounded-full"></span>
                        </span> en récompenses
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg">
                        La plateforme de gamification du bénévolat. Accumulez des points, gravissez les grades et échangez contre des rewards exclusives.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-[#D4A574] text-white rounded-lg font-semibold hover:bg-[#c49560] transition transform hover:scale-105 hover:shadow-xl flex items-center gap-2">
                            <span>Commencer maintenant</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#features" class="px-6 py-3 border-2 border-[#D4A574] text-[#D4A574] dark:text-[#D4A574] rounded-lg font-semibold hover:bg-[#FFD6BA] dark:hover:bg-gray-700 transition transform hover:scale-105 flex items-center gap-2">
                            <span>En savoir plus</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>
                </div>
                <div class="hidden md:block animate-scale-in">
                    <div class="bg-[#FFE8CD] dark:bg-gray-700 rounded-2xl p-8 shadow-xl relative">
                        <div class="absolute -top-4 -right-4 text-4xl animate-float">🌟</div>
                        <div class="absolute -bottom-6 -left-6 text-3xl animate-float animation-delay-200">✨</div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-white dark:bg-gray-600 rounded-xl p-4 shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                                <div class="text-4xl font-bold text-[#D4A574]">1,234</div>
                                <div class="text-sm text-gray-600 dark:text-gray-200 mt-1 font-medium">Points</div>
                                <div class="text-xs text-[#D4A574] mt-1">+10/jour</div>
                            </div>
                            <div class="bg-white dark:bg-gray-600 rounded-xl p-4 shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                                <div class="text-4xl font-bold text-[#D4A574]">⭐⭐⭐</div>
                                <div class="text-sm text-gray-600 dark:text-gray-200 mt-1 font-medium">Grades</div>
                                <div class="text-xs text-green-600 mt-1">Prochain: Or</div>
                            </div>
                            <div class="bg-white dark:bg-gray-600 rounded-xl p-4 shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                                <div class="text-4xl font-bold text-[#D4A574]">5</div>
                                <div class="text-sm text-gray-600 dark:text-gray-200 mt-1 font-medium">Rewards</div>
                                <div class="text-xs text-[#D4A574] mt-1">à échanger</div>
                            </div>
                        </div>
                        <div class="mt-6 bg-white dark:bg-gray-600 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200">Progression</span>
                                <span class="text-sm text-[#D4A574] font-semibold">75%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-500 rounded-full h-3 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#D4A574] to-[#FFD6BA] rounded-full transition-all duration-1000" style="width: 75%"></div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-300 mt-2">150 points pour le prochain grade</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-[#FFF2EB] dark:bg-gray-800 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-[#FFDCDC] text-[#D4A574] rounded-full text-sm font-medium mb-4">Fonctionnalités</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Comment ça marche ?</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Un système de points infalsifiable basé sur vos vraies actions</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-[#FFDCDC] group">
                        <div class="w-16 h-16 bg-[#FFDCDC] dark:bg-gray-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Check-in QR</h3>
                        <p class="text-gray-600 dark:text-gray-300">Présence validée uniquement via scan QR physique. Pas de triche possible.</p>
                        <div class="mt-4 flex items-center gap-2 text-[#D4A574] font-medium text-sm">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-[#FFE8CD] group">
                        <div class="w-16 h-16 bg-[#FFE8CD] dark:bg-gray-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Points Garantis</h3>
                        <p class="text-gray-600 dark:text-gray-300">Chaque heure de bénévolat = points. Multiplicateurs pour missions urgentes.</p>
                        <div class="mt-4 flex items-center gap-2 text-[#D4A574] font-medium text-sm">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-[#FFD6BA] group">
                        <div class="w-16 h-16 bg-[#FFD6BA] dark:bg-gray-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Marketplace</h3>
                        <p class="text-gray-600 dark:text-gray-300">Échangez vos points contre des rewards. Grade plus élevé = plus de choix.</p>
                        <div class="mt-4 flex items-center gap-2 text-[#D4A574] font-medium text-sm">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-24 bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-[#D4A574] text-white rounded-full text-sm font-medium mb-4">Guide</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Étapes pour commencer</h2>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#FFDCDC] dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-[#D4A574]">1</div>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Créez un compte</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Inscrivez-vous gratuitement en quelques secondes</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#FFE8CD] dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-[#D4A574]">2</div>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Trouvez un événement</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Parcourez les événements près de chez vous</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#FFD6BA] dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-[#D4A574]">3</div>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Participez</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Check-in via QR code et累计 vos heures</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#D4A574] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white">4</div>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">ÉCHANGEZ</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Convertissez vos points en rewards</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-[#FFD6BA] dark:bg-gray-700 relative overflow-hidden transition-colors duration-300">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-10 left-1/4 w-48 h-48 bg-[#D4A574] rounded-full filter blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto px-6 relative">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white">500+</div>
                        <div class="text-gray-600 dark:text-gray-200 mt-2 font-medium">Étudiants actifs</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white">50+</div>
                        <div class="text-gray-600 dark:text-gray-200 mt-2 font-medium">Partenaires</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white">1000+</div>
                        <div class="text-gray-600 dark:text-gray-200 mt-2 font-medium">Heures de bénévolat</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white">200+</div>
                        <div class="text-gray-600 dark:text-gray-200 mt-2 font-medium">Événements</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-24 bg-[#FFF2EB] dark:bg-gray-800 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-[#FFDCDC] text-[#D4A574] rounded-full text-sm font-medium mb-4">Témoignages</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Ce qu'ils en disent</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#D4A574]">⭐⭐⭐⭐⭐</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"Super plateforme ! J'ai déjà accumulé 500 points et je準備 échange contre un livre."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FFDCDC] dark:bg-gray-600 rounded-full flex items-center justify-center text-[#D4A574] font-bold">M</div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">Marie</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Étudiante</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#D4A574]">⭐⭐⭐⭐⭐</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"Le système de check-in QR est vraiment sécurisé. Ça motive à participer!"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FFE8CD] dark:bg-gray-600 rounded-full flex items-center justify-center text-[#D4A574] font-bold">T</div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">Thomas</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Étudiant</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#D4A574]">⭐⭐⭐⭐⭐</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"J'adore la progression par grades. bronze, argent, or... ça donne un vraies objectif!"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FFD6BA] dark:bg-gray-600 rounded-full flex items-center justify-center text-[#D4A574] font-bold">S</div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">Sarah</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Étudiante</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="py-24 bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-[#FFDCDC] text-[#D4A574] rounded-full text-sm font-medium mb-4">Tutoriel</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Guide vidéo</h2>
                </div>
                <div class="max-w-4xl mx-auto animate-slide-up">
                    <div class="bg-white dark:bg-gray-700 rounded-2xl shadow-2xl overflow-hidden border-4 border-[#FFDCDC] dark:border-gray-600">
                        <div class="aspect-video">
                            <iframe
                                src="https://www.youtube.com/embed/tYmvsrkN8po?autoplay=0&rel=0"
                                class="w-full h-full"
                                loading="lazy"
                                allowfullscreen
                                title="ActTogether Tutorial"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-[#FFDCDC] dark:bg-gray-800 relative overflow-hidden transition-colors duration-300">
            <div class="absolute inset-0 opacity-10 dark:opacity-5">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#D4A574] rounded-full filter blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#FFE8CD] rounded-full filter blur-3xl dark:bg-[#FFD6BA]"></div>
            </div>
            <div class="max-w-4xl mx-auto px-6 text-center relative">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 dark:text-white mb-6">Prêt à commencer votre aventure ?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">Rejoignez la communauté ActTogether et transformez votre engagement en rewards incroyable</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-[#D4A574] text-white text-lg font-semibold rounded-lg hover:bg-[#c49560] transition transform hover:scale-105 hover:shadow-xl">
                        <span>Créer mon compte</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="mt-8 flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Gratuit • Sans engagement • Sécurisé</span>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#FFF2EB] dark:bg-gray-900 py-12 border-t border-[#FFD6BA] dark:border-gray-700 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-2 text-xl font-bold text-[#D4A574] mb-4">
                            <span class="text-2xl">🎯</span>
                            <span>ActTogether</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">La plateforme de gamification du bénévolat étudiant.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Liens rapides</h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li><a href="#" class="hover:text-[#D4A574] transition">Accueil</a></li>
                            <li><a href="#features" class="hover:text-[#D4A574] transition">Fonctionnalités</a></li>
                            <li><a href="#" class="hover:text-[#D4A574] transition">Événements</a></li>
                            <li><a href="#" class="hover:text-[#D4A574] transition">Rewards</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Légal</h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li><a href="#" class="hover:text-[#D4A574] transition">Conditions</a></li>
                            <li><a href="#" class="hover:text-[#D4A574] transition">Confidentialité</a></li>
                            <li><a href="#" class="hover:text-[#D4A574] transition">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li>contact@acttogether.fr</li>
                            <li>Paris, France</li>
                        </ul>
                        <div class="flex gap-3 mt-4">
                            <a href="#" class="w-8 h-8 bg-[#FFDCDC] dark:bg-gray-700 rounded-full flex items-center justify-center text-[#D4A574] hover:bg-[#D4A574] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-[#FFDCDC] dark:bg-gray-700 rounded-full flex items-center justify-center text-[#D4A574] hover:bg-[#D4A574] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-[#FFDCDC] dark:bg-gray-700 rounded-full flex items-center justify-center text-[#D4A574] hover:bg-[#D4A574] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-[#FFD6BA] dark:border-gray-700 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-gray-600 dark:text-gray-300 text-sm">© 2026 ActTogether. Tous droits réservés.</div>
                    <div class="flex gap-4 text-sm">
                        <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-[#D4A574] transition">À propos</a>
                        <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-[#D4A574] transition">Contact</a>
                        <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-[#D4A574] transition">Confidentialité</a>
                    </div>
                </div>
            </div>
        </footer>
        <script>
            const themeToggle = document.getElementById('theme-toggle');
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        </script>
    </body>
</html>