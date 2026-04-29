<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ActTogether - Gamification du Bénévolat</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#090014] text-[#E0E0E0] font-mono min-h-screen relative">
        <!-- Global Perspective Grid -->
        <div class="perspective-grid"></div>
        <!-- Floating Sun Glow -->
        <div class="sun-glow"></div>
        <!-- Terminal Window Header -->
        <header class="fixed w-full top-0 z-50">
            <div class="border-2 border-[#00FFFF] glass-panel shadow-[0_0_20px_rgba(0,255,255,0.2)]">
                <!-- Window Title Bar -->
                <div class="bg-[#00FFFF]/10 border-b-2 border-[#00FFFF] px-4 py-2 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Window Control Dots -->
                        <div class="flex gap-2">
                            <div class="h-3 w-3 rounded-full bg-[#FF00FF]"></div>
                            <div class="h-3 w-3 rounded-full bg-[#00FFFF]"></div>
                            <div class="h-3 w-3 rounded-full bg-[#FF9900]"></div>
                        </div>
                        <span class="font-mono text-sm text-[#00FFFF]">ActTogether.exe - Terminal Session</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-skew border-2 border-[#00FFFF] bg-transparent text-[#00FFFF] px-4 py-2 font-mono uppercase tracking-wider hover:bg-[#00FFFF] hover:text-black"><span>Dashboard</span></a>
                        @else
                            <a href="{{ route('login') }}" class="text-[#00FFFF] hover:text-[#FF00FF] font-mono transition">Connexion</a>
                            <a href="{{ route('register') }}" class="btn-skew border-2 border-[#FF00FF] bg-[#FF00FF] text-black px-4 py-2 font-mono uppercase tracking-wider hover:opacity-80"><span>Inscription</span></a>
                        @endauth
                    </div>
                </div>
                <!-- Nav Content -->
                <nav class="px-6 py-4 flex items-center justify-between">
                    <a href="/" class="font-heading text-2xl font-bold text-[#FF00FF] flex items-center gap-2">
                        <span class="text-3xl">🎯</span>
                        <span>ActTogether</span>
                    </a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="pt-48 pb-32 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10">
                <div class="animate-slide-up">
                    <div class="inline-flex items-center gap-2 glass-panel px-4 py-2 mb-6 border-t-2 border-t-[#00FFFF]">
                        <span class="w-2 h-2 bg-[#00FFFF] rounded-full animate-pulse"></span>
                        <span class="text-sm text-[#E0E0E0]/70 font-mono">> 500 étudiants connectés</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-heading font-black text-gradient-sunset text-glow-magenta mb-6 leading-tight uppercase tracking-wider">
                        Transformez votre<br>
                        <span class="text-[#FF00FF]">engagement</span><br>
                        en récompenses
                    </h1>
                    <p class="text-xl text-[#E0E0E0]/80 mb-8 max-w-lg font-mono leading-relaxed">
                        La plateforme de gamification du bénévolat<br>
                        Accumulez des points, gravissez les grades<br>
                        Échangez contre des rewards exclusives
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn-skew border-2 border-[#FF00FF] bg-[#FF00FF] text-black px-6 py-3 font-mono uppercase tracking-wider hover:opacity-80 hover:shadow-[0_0_20px_#FF00FF] flex items-center gap-2">
                            <span>Commencer maintenant</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#features" class="btn-skew border-2 border-[#00FFFF] bg-transparent text-[#00FFFF] px-6 py-3 font-mono uppercase tracking-wider hover:bg-[#00FFFF] hover:text-black flex items-center gap-2">
                            <span>En savoir plus</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <span class="inline-block px-6 py-2 glass-panel border-t-2 border-t-[#00FFFF] text-[#00FFFF] text-sm font-mono uppercase tracking-wider mb-6">Fonctionnalités</span>
                    <h2 class="text-5xl md:text-6xl font-heading font-black text-[#00FFFF] text-glow-cyan mb-6 uppercase tracking-wider">Comment ça marche ?</h2>
                    <p class="text-xl text-[#E0E0E0]/70 max-w-2xl mx-auto font-mono">> Un système de points infalsifiable basé sur vos vraies actions</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="glass-panel border-2 border-[#00FFFF] p-8 hover:shadow-[0_0_20px_rgba(0,255,255,0.3)] transition-all duration-300 transform hover:-translate-y-2 group">
                        <div class="w-16 h-16 bg-[#00FFFF]/20 border border-[#00FFFF] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform rotate-45">
                            <svg class="w-8 h-8 text-[#00FFFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-[#00FFFF] mb-4 uppercase">Check-in QR</h3>
                        <p class="text-[#E0E0E0]/70 font-mono">Présence validée uniquement via scan QR physique<br>Pas de triche possible</p>
                        <div class="mt-6 flex items-center gap-2 text-[#00FFFF] font-mono text-sm group-hover:text-[#FF00FF] transition">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-panel border-2 border-[#FF00FF] p-8 hover:shadow-[0_0_20px_rgba(255,0,255,0.3)] transition-all duration-300 transform hover:-translate-y-2 group">
                        <div class="w-16 h-16 bg-[#FF00FF]/20 border border-[#FF00FF] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform rotate-45">
                            <svg class="w-8 h-8 text-[#FF00FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-[#FF00FF] mb-4 uppercase">Points Garantis</h3>
                        <p class="text-[#E0E0E0]/70 font-mono">Chaque heure de bénévolat = points<br>Multiplicateurs pour missions urgentes</p>
                        <div class="mt-6 flex items-center gap-2 text-[#FF00FF] font-mono text-sm group-hover:text-[#00FFFF] transition">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-panel border-2 border-[#FF9900] p-8 hover:shadow-[0_0_20px_rgba(255,153,0,0.3)] transition-all duration-300 transform hover:-translate-y-2 group">
                        <div class="w-16 h-16 bg-[#FF9900]/20 border border-[#FF9900] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform rotate-45">
                            <svg class="w-8 h-8 text-[#FF9900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-[#FF9900] mb-4 uppercase">Marketplace</h3>
                        <p class="text-[#E0E0E0]/70 font-mono">Échangez vos points contre des rewards<br>Grade plus élevé = plus de choix</p>
                        <div class="mt-6 flex items-center gap-2 text-[#FF9900] font-mono text-sm group-hover:text-[#FF00FF] transition">
                            <span>En savoir plus</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <span class="inline-block px-6 py-2 glass-panel border-t-2 border-t-[#FF00FF] text-[#FF00FF] text-sm font-mono uppercase tracking-wider mb-6">Guide</span>
                    <h2 class="text-5xl md:text-6xl font-heading font-black text-[#FF00FF] text-glow-magenta mb-6 uppercase tracking-wider">Étapes pour commencer</h2>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#00FFFF]/20 border-2 border-[#00FFFF] flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#00FFFF] font-heading rotate-45 hover:rotate-90 transition-transform">1</div>
                        <h3 class="font-heading font-bold text-[#00FFFF] text-xl mb-3 uppercase">Créez un compte</h3>
                        <p class="text-[#E0E0E0]/70 font-mono text-sm">Inscrivez-vous gratuitement<br>En quelques secondes</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#FF00FF]/20 border-2 border-[#FF00FF] flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#FF00FF] font-heading rotate-45 hover:rotate-90 transition-transform">2</div>
                        <h3 class="font-heading font-bold text-[#FF00FF] text-xl mb-3 uppercase">Trouvez un événement</h3>
                        <p class="text-[#E0E0E0]/70 font-mono text-sm">Parcourez les événements<br>Près de chez vous</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#FF9900]/20 border-2 border-[#FF9900] flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#FF9900] font-heading rotate-45 hover:rotate-90 transition-transform">3</div>
                        <h3 class="font-heading font-bold text-[#FF9900] text-xl mb-3 uppercase">Participez</h3>
                        <p class="text-[#E0E0E0]/70 font-mono text-sm">Check-in via QR code<br>Accumulez vos heures</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-[#FF9900] via-[#FF00FF] to-[#00FFFF] border-2 border-[#00FFFF] flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-black font-heading rotate-45 hover:rotate-90 transition-transform">4</div>
                        <h3 class="font-heading font-bold text-gradient-sunset text-xl mb-3 uppercase">Échangez</h3>
                        <p class="text-[#E0E0E0]/70 font-mono text-sm">Convertissez vos points<br>En rewards</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-32 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="glass-panel border-2 border-[#00FFFF] p-6 hover:shadow-[0_0_20px_rgba(0,255,255,0.3)] hover:scale-105 transition-all duration-300">
                        <div class="text-5xl md:text-6xl font-heading font-black text-[#00FFFF] text-glow-cyan">500+</div>
                        <div class="text-[#E0E0E0]/70 mt-3 font-mono uppercase tracking-wider">Étudiants actifs</div>
                    </div>
                    <div class="glass-panel border-2 border-[#FF00FF] p-6 hover:shadow-[0_0_20px_rgba(255,0,255,0.3)] hover:scale-105 transition-all duration-300">
                        <div class="text-5xl md:text-6xl font-heading font-black text-[#FF00FF] text-glow-magenta">50+</div>
                        <div class="text-[#E0E0E0]/70 mt-3 font-mono uppercase tracking-wider">Partenaires</div>
                    </div>
                    <div class="glass-panel border-2 border-[#FF9900] p-6 hover:shadow-[0_0_20px_rgba(255,153,0,0.3)] hover:scale-105 transition-all duration-300">
                        <div class="text-5xl md:text-6xl font-heading font-black text-[#FF9900]">1000+</div>
                        <div class="text-[#E0E0E0]/70 mt-3 font-mono uppercase tracking-wider">Heures de bénévolat</div>
                    </div>
                    <div class="glass-panel border-2 border-[#00FFFF] p-6 hover:shadow-[0_0_20px_rgba(0,255,255,0.3)] hover:scale-105 transition-all duration-300">
                        <div class="text-5xl md:text-6xl font-heading font-black text-[#00FFFF] text-glow-cyan">200+</div>
                        <div class="text-[#E0E0E0]/70 mt-3 font-mono uppercase tracking-wider">Événements</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <span class="inline-block px-6 py-2 glass-panel border-t-2 border-t-[#FF9900] text-[#FF9900] text-sm font-mono uppercase tracking-wider mb-6">Témoignages</span>
                    <h2 class="text-5xl md:text-6xl font-heading font-black text-[#FF9900] mb-6 uppercase tracking-wider">Ce qu'ils en disent</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-panel border-2 border-[#00FFFF] p-6">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#FF9900] text-lg">★★★★★</span>
                        </div>
                        <p class="text-[#E0E0E0]/80 mb-4 font-mono"><span class="text-[#00FFFF]">&lt;Marie&gt;</span> Super plateforme ! J'ai déjà accumulé 500 points et je prépare à échanger contre un livre.</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#00FFFF]/20 border border-[#00FFFF] rounded-none flex items-center justify-center text-[#00FFFF] font-heading font-bold rotate-45">M</div>
                            <div>
                                <div class="font-heading font-bold text-[#00FFFF] text-glow-cyan">Marie</div>
                                <div class="text-sm text-[#E0E0E0]/50 font-mono">Étudiante</div>
                            </div>
                        </div>
                    </div>
                    <div class="glass-panel border-2 border-[#FF00FF] p-6">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#FF9900] text-lg">★★★★★</span>
                        </div>
                        <p class="text-[#E0E0E0]/80 mb-4 font-mono"><span class="text-[#FF00FF]">&lt;Thomas&gt;</span> Le système de check-in QR est vraiment sécurisé. Ça motive à participer!</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FF00FF]/20 border border-[#FF00FF] rounded-none flex items-center justify-center text-[#FF00FF] font-heading font-bold rotate-45">T</div>
                            <div>
                                <div class="font-heading font-bold text-[#FF00FF] text-glow-magenta">Thomas</div>
                                <div class="text-sm text-[#E0E0E0]/50 font-mono">Étudiant</div>
                            </div>
                        </div>
                    </div>
                    <div class="glass-panel border-2 border-[#FF9900] p-6">
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-[#FF9900] text-lg">★★★★★</span>
                        </div>
                        <p class="text-[#E0E0E0]/80 mb-4 font-mono"><span class="text-[#00FFFF]">&lt;Sarah&gt;</span> J'adore la progression par grades. bronze, argent, or... ça donne de vraies objectifs!</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FF9900]/20 border border-[#FF9900] rounded-none flex items-center justify-center text-[#FF9900] font-heading font-bold rotate-45">S</div>
                            <div>
                                <div class="font-heading font-bold text-[#FF9900]">Sarah</div>
                                <div class="text-sm text-[#E0E0E0]/50 font-mono">Étudiante</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="py-32 bg-[#090014] relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <span class="inline-block px-6 py-2 glass-panel border-t-2 border-t-[#00FFFF] text-[#00FFFF] text-sm font-mono uppercase tracking-wider mb-6">Tutoriel</span>
                    <h2 class="text-5xl md:text-6xl font-heading font-black text-[#00FFFF] text-glow-cyan mb-6 uppercase tracking-wider">Guide vidéo</h2>
                </div>
                <div class="max-w-5xl mx-auto animate-slide-up">
                    <div class="glass-panel border-4 border-[#00FFFF] shadow-[0_0_30px_rgba(0,255,255,0.2)] overflow-hidden">
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
        <section class="py-32 relative overflow-hidden">
            <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
                <h2 class="text-6xl md:text-7xl font-heading font-black text-gradient-sunset text-glow-magenta mb-8 uppercase tracking-wider">Prêt à commencer<br>votre aventure ?</h2>
                <p class="text-2xl text-[#E0E0E0]/80 mb-12 max-w-3xl mx-auto font-mono">Rejoignez la communauté ActTogether<br>Transformez votre engagement en rewards incroyable</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('register') }}" class="btn-skew border-4 border-[#FF00FF] bg-[#FF00FF] text-black px-10 py-5 font-mono text-xl uppercase tracking-wider hover:opacity-80 hover:shadow-[0_0_30px_#FF00FF] flex items-center gap-3">
                        <span>Créer mon compte</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="mt-12 flex items-center justify-center gap-4 text-[#E0E0E0]/60 font-mono text-lg">
                    <span class="text-[#00FFFF]">✓</span>
                    <span>Gratuit</span>
                    <span class="text-[#00FFFF]">•</span>
                    <span>Sans engagement</span>
                    <span class="text-[#00FFFF]">•</span>
                    <span>Sécurisé</span>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-20 border-t-2 border-[#00FFFF] relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-12 mb-12">
                    <div>
                        <div class="flex items-center gap-3 text-2xl font-heading font-bold text-[#FF00FF] mb-6">
                            <span class="text-3xl">🎯</span>
                            <span>ActTogether</span>
                        </div>
                        <p class="text-[#E0E0E0]/70 font-mono text-sm">> La plateforme de gamification du bénévolat étudiant</p>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-[#00FFFF] text-lg mb-6 uppercase">Liens rapides</h4>
                        <ul class="space-y-3 text-sm font-mono text-[#E0E0E0]/70">
                            <li><a href="#" class="hover:text-[#00FFFF] transition">> Accueil</a></li>
                            <li><a href="#features" class="hover:text-[#00FFFF] transition">> Fonctionnalités</a></li>
                            <li><a href="#" class="hover:text-[#00FFFF] transition">> Événements</a></li>
                            <li><a href="#" class="hover:text-[#00FFFF] transition">> Rewards</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-[#FF00FF] text-lg mb-6 uppercase">Légal</h4>
                        <ul class="space-y-3 text-sm font-mono text-[#E0E0E0]/70">
                            <li><a href="#" class="hover:text-[#FF00FF] transition">> Conditions</a></li>
                            <li><a href="#" class="hover:text-[#FF00FF] transition">> Confidentialité</a></li>
                            <li><a href="#" class="hover:text-[#FF00FF] transition">> FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-[#FF9900] text-lg mb-6 uppercase">Contact</h4>
                        <ul class="space-y-3 text-sm font-mono text-[#E0E0E0]/70">
                            <li>> contact@acttogether.fr</li>
                            <li>> Paris, France</li>
                        </ul>
                        <div class="flex gap-4 mt-6">
                            <a href="#" class="w-12 h-12 glass-panel border border-[#00FFFF] rounded-none flex items-center justify-center text-[#00FFFF] hover:bg-[#00FFFF] hover:text-black transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </a>
                            <a href="#" class="w-12 h-12 glass-panel border border-[#FF00FF] rounded-none flex items-center justify-center text-[#FF00FF] hover:bg-[#FF00FF] hover:text-black transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="#" class="w-12 h-12 glass-panel border border-[#FF9900] rounded-none flex items-center justify-center text-[#FF9900] hover:bg-[#FF9900] hover:text-black transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t-2 border-[#00FFFF] pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-[#E0E0E0]/50 font-mono">© 2026 ActTogether. Tous droits réservés.</div>
                    <div class="flex gap-6 font-mono text-sm">
                        <a href="#" class="text-[#E0E0E0]/70 hover:text-[#00FFFF] transition">À propos</a>
                        <a href="#" class="text-[#E0E0E0]/70 hover:text-[#FF00FF] transition">Contact</a>
                        <a href="#" class="text-[#E0E0E0]/70 hover:text-[#FF9900] transition">Confidentialité</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>