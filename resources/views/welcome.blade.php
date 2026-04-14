<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ActTogether - Gamification du Bénévolat</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FFF2EB] text-gray-800 font-sans">
        <!-- Header -->
        <header class="bg-[#FFDCDC] shadow-sm">
            <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="text-2xl font-bold text-[#D4A574]">ActTogether</a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-[#FFD6BA] text-gray-800 rounded-lg font-medium hover:bg-[#FFE8CD] transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-[#D4A574] font-medium transition">Connexion</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition">Inscription</a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="bg-[#FFDCDC] py-20">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                        Transformez votre <span class="text-[#D4A574]">engagement</span> en récompenses
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        La plateforme de gamification du bénévolat. Accumulez des points, gravissez les grades et échangez contre des rewards exclusives.
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-[#D4A574] text-white rounded-lg font-semibold hover:bg-[#c49560] transition">
                            Commencer maintenant
                        </a>
                        <a href="#features" class="px-6 py-3 border-2 border-[#D4A574] text-[#D4A574] rounded-lg font-semibold hover:bg-[#FFD6BA] transition">
                            En savoir plus
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="bg-[#FFE8CD] rounded-2xl p-8 shadow-lg">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-white rounded-xl p-4 shadow">
                                <div class="text-3xl font-bold text-[#D4A574]">🎯</div>
                                <div class="text-sm text-gray-600 mt-1">Points</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 shadow">
                                <div class="text-3xl font-bold text-[#D4A574]">⭐</div>
                                <div class="text-sm text-gray-600 mt-1">Grades</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 shadow">
                                <div class="text-3xl font-bold text-[#D4A574]">🎁</div>
                                <div class="text-sm text-gray-600 mt-1">Rewards</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-[#FFF2EB]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Comment ça marche ?</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Un système de points infalsifiable basé sur vos vraies actions</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition border-t-4 border-[#FFDCDC]">
                        <div class="w-14 h-14 bg-[#FFDCDC] rounded-full flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Check-in QR</h3>
                        <p class="text-gray-600">Présence validée uniquement via scan QR physique. Pas de triche possible.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition border-t-4 border-[#FFE8CD]">
                        <div class="w-14 h-14 bg-[#FFE8CD] rounded-full flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Points Garantis</h3>
                        <p class="text-gray-600">Chaque heure de bénévolat = points. Multiplicateurs pour missions urgentes.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition border-t-4 border-[#FFD6BA]">
                        <div class="w-14 h-14 bg-[#FFD6BA] rounded-full flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Marketplace</h3>
                        <p class="text-gray-600">Échangez vos points contre des rewards. Grade plus élevé = plus de choix.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-[#FFD6BA]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-gray-800">500+</div>
                        <div class="text-gray-600 mt-1">Étudiants actifs</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gray-800">50+</div>
                        <div class="text-gray-600 mt-1">Partenaires</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gray-800">1000+</div>
                        <div class="text-gray-600 mt-1">Heures de bénévolat</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gray-800">200+</div>
                        <div class="text-gray-600 mt-1">Événements</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-[#FFDCDC]">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Prêt à commencer ?</h2>
                <p class="text-lg text-gray-600 mb-8">Rejoignez la communauté ActTogether et transformez votre engagement en rewards</p>
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-[#D4A574] text-white text-lg font-semibold rounded-lg hover:bg-[#c49560] transition shadow-lg">
                    Créer mon compte
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#FFF2EB] py-8 border-t border-[#FFD6BA]">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-xl font-bold text-[#D4A574]">ActTogether</div>
                <div class="text-gray-600 text-sm">© 2026 ActTogether. Tous droits réservés.</div>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-600 hover:text-[#D4A574] transition">À propos</a>
                    <a href="#" class="text-gray-600 hover:text-[#D4A574] transition">Contact</a>
                    <a href="#" class="text-gray-600 hover:text-[#D4A574] transition">Confidentialité</a>
                </div>
            </div>
        </footer>
    </body>
</html>