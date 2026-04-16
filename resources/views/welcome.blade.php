<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Sanbe | Welcome</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Background Image Logic */
        .hero-bg {
            background-image: url("{{ asset('img/welcome_background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Overlay untuk memastikan teks terbaca */
        .overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
        }

        .fade-in {
            animation: fadeIn 1.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="antialiased selection:bg-white selection:text-black">

    <div class="relative min-h-screen flex flex-col items-center justify-center px-6 hero-bg">

        <div class="absolute inset-0 overlay"></div>

        <nav class="absolute top-0 w-full p-8 flex justify-between items-center max-w-7xl z-10">
            <div class="text-2xl font-black tracking-tighter italic text-white">SIS</div>
            <div class="text-[10px] uppercase tracking-[0.3em] text-white/60">Sanbe Farma</div>
        </nav>

        <main class="max-w-4xl w-full text-center fade-in z-10">

            <h1 class="text-5xl md:text-8xl font-extrabold tracking-tighter leading-none mb-8 text-white">
                Sistem Informasi <br> SANBE.
            </h1>

            {{-- <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto mb-12 font-light leading-relaxed">
                Pusat pengajuan layanan operasional dan fasilitas IT:
                <span class="font-medium text-white">BPPB Barang & Software, Service Elektronik, Akses Internet, Email
                    Karyawan,</span> hingga <span class="font-medium text-white">Serah Terima Asset</span> dalam satu
                pintu.
            </p> --}}
            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto mb-12 font-light leading-relaxed">
                Pusat pengajuan layanan operasional dan fasilitas IT:
                <span class="font-medium text-white">BPPB Barang & Software, Service Elektronik, Akses Internet dan
                    Email Karyawan.
            </p>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="{{ url('/sis') }}"
                    class="group relative inline-flex items-center justify-center px-12 py-4 font-bold text-black transition-all duration-300 bg-white rounded-full hover:bg-neutral-200 hover:scale-105 active:scale-95 shadow-2xl">
                    Masuk ke SIS
                    <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

        </main>

        <footer
            class="absolute bottom-0 w-full p-8 flex flex-col md:flex-row justify-between items-center text-[10px] uppercase tracking-[0.2em] text-white/50 z-10">
            <p>&copy; {{ date('Y') }} Sanbe Farma. All Rights Reserved.</p>
            {{-- <div class="flex gap-8 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition-colors">Security</a>
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Status</a>
            </div> --}}
        </footer>

    </div>

</body>

</html>
