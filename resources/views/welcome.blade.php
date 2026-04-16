<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Sanbe | Welcome</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(27, 130, 159, 0.20), transparent 32%),
                radial-gradient(circle at 85% 20%, rgba(255, 255, 255, 0.08), transparent 22%),
                linear-gradient(180deg, #06121a 0%, #0b1d29 38%, #f6f8fb 38%, #f6f8fb 100%);
        }

        .hero-bg {
            background-image:
                linear-gradient(180deg, rgba(3, 10, 15, 0.45), rgba(3, 10, 15, 0.72)),
                url("{{ asset('img/welcome_background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .glass {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.08);
        }

        .section-card {
            background: white;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 18px 60px rgba(15, 23, 42, 0.08);
        }

        .grid-accent {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="text-slate-900 antialiased">

    <header class="relative hero-bg text-white">
        <div class="absolute inset-0 grid-accent"></div>

        <nav class="relative mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-6 lg:px-10">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15">
                    <img src="{{ asset('img/logo.png') }}" alt="Sanbe" class="h-7 w-7 object-contain">
                </div>
                <div>
                    <div class="text-sm font-semibold tracking-[0.2em] text-white/65 uppercase">SIS</div>
                    <div class="text-sm text-white/90">Sistem Informasi Sanbe</div>
                </div>
            </div>

            <div class="hidden items-center gap-8 text-sm text-white/70 md:flex">
                <a href="#layanan" class="hover:text-white transition">Layanan</a>
                <a href="#alur" class="hover:text-white transition">Alur</a>
                {{-- <a href="#info" class="hover:text-white transition">Informasi</a> --}}
                <a href="#kontak" class="hover:text-white transition">Kontak</a>
            </div>
        </nav>

        <section class="relative mx-auto flex min-h-screen w-full max-w-7xl items-center px-6 pb-24 pt-10 lg:px-10">
            <div class="grid w-full gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                <div class="max-w-3xl">
                    <div
                        class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold tracking-[0.2em] text-white/75 uppercase glass">
                        Internal Service Portal
                    </div>

                    <h1 class="text-5xl font-extrabold leading-none tracking-tight text-white md:text-7xl">
                        Satu pintu pengajuan Barang IT.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-white/78 md:text-xl">
                        Kelola pengajuan BPPB, Booking Order, layanan email, internet, service, dan kebutuhan
                        operasional lain secara lebih terstruktur, cepat, dan terdokumentasi.
                    </p>

                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ url('/sis') }}"
                            class="inline-flex items-center justify-center rounded-full bg-white px-8 py-4 text-sm font-bold text-slate-900 shadow-2xl transition hover:-translate-y-0.5 hover:bg-slate-100">
                            Masuk ke SIS
                        </a>

                        <a href="#layanan"
                            class="inline-flex items-center justify-center rounded-full border border-white/20 px-8 py-4 text-sm font-semibold text-white/90 transition hover:bg-white/10">
                            Lihat Layanan
                        </a>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/10 p-6 glass">
                    <div class="rounded-[1.5rem] bg-white/95 p-6 text-slate-900 shadow-2xl">
                        <div class="text-sm font-semibold tracking-[0.18em] text-sky-700 uppercase">Highlight</div>
                        <h2 class="mt-3 text-2xl font-extrabold leading-tight">
                            Portal internal untuk kebutuhan pengajuan harian.
                        </h2>

                        <div class="mt-6 grid gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-sm font-semibold text-slate-900">BPPB Barang, Tinta, dan Software</div>
                                <div class="mt-1 text-sm text-slate-600">Pengajuan kebutuhan operasional yang tercatat
                                    rapi dan mudah ditelusuri.</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-sm font-semibold text-slate-900">Layanan IT</div>
                                <div class="mt-1 text-sm text-slate-600">Pengajuan email, internet, dan service
                                    elektronik dalam satu sistem.</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-sm font-semibold text-slate-900">Booking Order</div>
                                <div class="mt-1 text-sm text-slate-600">Booking fasilitas internal dengan kuota unit
                                    yang terkontrol.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <main class="relative z-10 -mt-16 pb-20">
        <section id="alur" class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="section-card rounded-[2rem] p-6 md:p-8">
                <div class="mb-8">
                    <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Alur Singkat</div>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Pengajuan dibuat, divalidasi,
                        lalu diproses</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">01</div>
                        <div class="mt-3 text-lg font-bold">Buat Pengajuan</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">User membuat pengajuan sesuai kebutuhan
                            melalui modul yang tersedia.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">02</div>
                        <div class="mt-3 text-lg font-bold">Validasi dan Peninjauan</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Pengajuan diperiksa oleh pihak terkait sesuai
                            alur proses dan kewenangan.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">03</div>
                        <div class="mt-3 text-lg font-bold">Proses dan Penyelesaian</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Data diproses lebih lanjut sampai kebutuhan
                            selesai atau fasilitas siap digunakan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="layanan" class="mx-auto mt-16 max-w-7xl px-6 lg:px-10">
            <div class="mb-8">
                <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Layanan</div>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Apa saja yang bisa diajukan di
                    SIS</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">BPPB</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Pengajuan barang, tinta, dan software dengan alur
                        pemrosesan yang terdokumentasi.</p>
                </article>

                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">Booking Order</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Pemesanan fasilitas internal seperti Zoom,
                        proyektor, dan unit lain per hari.</p>
                </article>

                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">Email</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Permohonan pembuatan email dan konfigurasi email
                        karyawan.</p>
                </article>

                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">Internet</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Pengajuan akses internet sesuai kebutuhan kerja dan
                        kebijakan perusahaan.</p>
                </article>

                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">Service Elektronik</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Pelaporan dan pengajuan service perangkat
                        elektronik yang digunakan operasional.</p>
                </article>

                <article class="section-card rounded-[1.75rem] p-6">
                    <h3 class="text-xl font-extrabold">Tracking Proses</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Memantau progres pengajuan, validasi, pembelian,
                        hingga penyelesaian secara lebih transparan.</p>
                </article>
            </div>
        </section>

        {{-- <section id="alur" class="mx-auto mt-16 max-w-7xl px-6 lg:px-10">
            <div class="section-card rounded-[2rem] p-6 md:p-8">
                <div class="mb-8">
                    <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Alur Singkat</div>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Pengajuan dibuat,
                        divalidasi,
                        lalu diproses</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">01</div>
                        <div class="mt-3 text-lg font-bold">Buat Pengajuan</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">User membuat pengajuan sesuai kebutuhan
                            melalui modul yang tersedia.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">02</div>
                        <div class="mt-3 text-lg font-bold">Validasi dan Peninjauan</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Pengajuan diperiksa oleh pihak terkait sesuai
                            alur proses dan kewenangan.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <div class="text-sm font-bold text-sky-700">03</div>
                        <div class="mt-3 text-lg font-bold">Proses dan Penyelesaian</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Data diproses lebih lanjut sampai kebutuhan
                            selesai atau fasilitas siap digunakan.</p>
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- <section id="info" class="mx-auto mt-16 max-w-7xl px-6 lg:px-10">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="section-card rounded-[2rem] p-6 md:p-8">
                    <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Pengumuman</div>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Bisa dipakai untuk info
                        internal</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Misalnya maintenance sistem, perubahan kebijakan pengajuan, informasi jadwal libur, atau update
                        layanan yang perlu diketahui user.
                    </p>
                </div>

                <div class="section-card rounded-[2rem] p-6 md:p-8">
                    <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Ucapan Musiman</div>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Siap untuk banner event
                        tertentu</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Section ini nanti bisa dibuat dinamis berdasarkan tanggal atau data admin, jadi ucapan seperti
                        Idul Fitri, Natal, Tahun Baru, dan event perusahaan bisa tampil otomatis.
                    </p>
                </div>
            </div>
        </section> --}}

        <section id="kontak" class="mx-auto mt-16 max-w-7xl px-6 lg:px-10">
            <div class="section-card rounded-[2rem] p-6 md:p-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <div class="text-sm font-bold tracking-[0.18em] text-sky-700 uppercase">Kontak</div>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Butuh bantuan?</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            Untuk kendala akses atau penggunaan sistem, silakan hubungi tim IT sesuai ext internal yang
                            berlaku.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-900 px-6 py-5 text-white">
                        <div class="text-xs font-semibold tracking-[0.18em] text-white/60 uppercase">IT Tamansari</div>
                        <div class="mt-2 text-lg font-bold">Ext. 1313 / 1294</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white/80">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-8 text-sm text-slate-500 md:flex-row md:items-center md:justify-between lg:px-10">
            <p>&copy; {{ date('Y') }} Sanbe Farma. All Rights Reserved.</p>
            <a href="{{ url('/sis') }}" class="font-semibold text-sky-700 hover:text-sky-900">Masuk ke SIS</a>
        </div>
    </footer>

</body>

</html>
