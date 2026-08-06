<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COMPRO TEKKOM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --blue-950: #0A1929;
      --blue-900: #0F2D5A;
      --blue-800: #1A3F7A;
      --blue-700: #1E4D96;
      --blue-600: #2563EB;
      --blue-500: #3B82F6;
      --blue-400: #60A5FA;
      --blue-100: #DBEAFE;
      --blue-50: #EFF6FF;
      --gray-900: #0F172A;
      --gray-800: #1E293B;
      --gray-700: #334155;
      --gray-500: #64748B;
      --gray-400: #94A3B8;
      --gray-300: #CBD5E1;
      --gray-100: #F1F5F9;
      --gray-50: #F8FAFC;
    }

    body {
      font-family: 'Inter', sans-serif;
    }

    .font-display {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* --- NAVBAR --- */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 48px;
      height: 68px;
      background: white;
      border-bottom: 1px solid var(--gray-300);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-icon {
      width: 40px;
      height: 40px;
      background: var(--blue-900);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .nav-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: var(--blue-900);
      line-height: 1.1;
    }

    .nav-sub {
      font-size: 11px;
      color: var(--gray-500);
    }

    .btn-ghost {
      padding: 8px 18px;
      font-size: 14px;
      font-weight: 500;
      color: var(--gray-700);
      border-radius: 8px;
      text-decoration: none;
      transition: background .15s;
    }

    .btn-ghost:hover {
      background: var(--gray-100);
    }

    .btn-primary {
      padding: 9px 20px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      background: var(--blue-700);
      border-radius: 8px;
      text-decoration: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      transition: background .15s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-primary:hover {
      background: var(--blue-800);
    }

    /* --- HERO --- */
    .hero {
      background: var(--blue-900);
      position: relative;
      overflow: hidden;
      padding: 80px 48px 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .hero-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    .hero-badge {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .12);
      color: var(--blue-400);
      font-size: 12px;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 100px;
      margin-bottom: 28px;
      letter-spacing: .03em;
      text-transform: uppercase;
    }

    .hero-badge-dot {
      width: 6px;
      height: 6px;
      background: var(--blue-400);
      border-radius: 50%;
    }

    .hero h1 {
      position: relative;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 52px;
      font-weight: 800;
      color: white;
      line-height: 1.1;
      letter-spacing: -.02em;
      max-width: 680px;
      margin-bottom: 20px;
    }

    .hero h1 span {
      color: var(--blue-400);
    }

    .hero-desc {
      position: relative;
      font-size: 16px;
      color: rgba(255, 255, 255, .55);
      max-width: 500px;
      line-height: 1.7;
      margin-bottom: 36px;
    }

    .hero-cta {
      position: relative;
      display: flex;
      gap: 12px;
      margin-bottom: 40px;
    }

    .btn-hero-primary {
      padding: 13px 28px;
      font-size: 15px;
      font-weight: 600;
      color: white;
      background: var(--blue-600);
      border-radius: 10px;
      text-decoration: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      transition: background .15s;
    }

    .btn-hero-primary:hover {
      background: var(--blue-500);
    }

    .btn-hero-ghost {
      padding: 13px 28px;
      font-size: 15px;
      font-weight: 500;
      color: rgba(255, 255, 255, .7);
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .14);
      border-radius: 10px;
      text-decoration: none;
      transition: all .15s;
    }

    .btn-hero-ghost:hover {
      background: rgba(255, 255, 255, .14);
      color: white;
    }

    .hero-checklist {
      position: relative;
      display: flex;
      gap: 24px;
      font-size: 13px;
      color: rgba(255, 255, 255, .5);
      margin-bottom: 56px;
    }

    .check-item {
      display: flex;
      align-items: center;
      gap: 7px;
    }

    .check-dot {
      width: 16px;
      height: 16px;
      background: var(--blue-600);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .check-dot::after {
      content: '';
      width: 6px;
      height: 4px;
      border-left: 1.5px solid white;
      border-bottom: 1.5px solid white;
      transform: rotate(-45deg) translateY(-1px);
    }

    .hero-strip {
      position: relative;
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .1);
      border-bottom: none;
      border-radius: 12px 12px 0 0;
      width: 100%;
      max-width: 780px;
      padding: 28px 24px;
      display: flex;
      justify-content: space-around;
    }

    .strip-num {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 30px;
      font-weight: 800;
      color: white;
    }

    .strip-label {
      font-size: 12px;
      color: rgba(255, 255, 255, .4);
      margin-top: 4px;
    }

    /* --- FEATURES --- */
    .features {
      padding: 72px 48px;
      background: var(--gray-50);
    }

    .eyebrow {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--blue-600);
      margin-bottom: 10px;
    }

    .section-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 30px;
      font-weight: 800;
      color: var(--gray-900);
      margin-bottom: 8px;
    }

    .section-sub {
      font-size: 15px;
      color: var(--gray-500);
      line-height: 1.6;
      margin-bottom: 40px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .feature-card {
      background: white;
      border: 1px solid var(--gray-300);
      border-radius: 12px;
      padding: 24px;
    }

    .feature-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: var(--blue-50);
      border: 1px solid var(--blue-100);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }

    .feature-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 6px;
    }

    .feature-desc {
      font-size: 13px;
      color: var(--gray-500);
      line-height: 1.6;
    }

    /* --- ROLES --- */
    .roles {
      padding: 72px 48px;
      background: white;
      border-top: 1px solid var(--gray-300);
    }

    .roles-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
    }

    .role-card {
      border: 1.5px solid var(--gray-300);
      border-radius: 12px;
      padding: 28px 24px;
      text-decoration: none;
      color: inherit;
      display: block;
      transition: all .2s;
      position: relative;
      overflow: hidden;
    }

    .role-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--blue-700);
      opacity: 0;
      transition: opacity .2s;
    }

    .role-card:hover {
      border-color: var(--blue-400);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(37, 99, 235, .1);
    }

    .role-card:hover::before {
      opacity: 1;
    }

    .role-num {
      font-size: 11px;
      font-weight: 700;
      color: var(--gray-400);
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 16px;
    }

    .role-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      background: var(--blue-50);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
    }

    .role-name {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 18px;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 6px;
    }

    .role-desc {
      font-size: 13px;
      color: var(--gray-500);
      line-height: 1.6;
    }

    .role-arrow {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 600;
      color: var(--blue-600);
      margin-top: 20px;
    }

    /* --- FOOTER --- */
    footer {
      background: var(--blue-950);
      padding: 28px 48px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    footer p {
      font-size: 13px;
      color: rgba(255, 255, 255, .3);
    }

    footer span {
      font-size: 13px;
      color: rgba(255, 255, 255, .2);
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 0 20px;
      }

      .hero {
        padding: 60px 20px 0;
      }

      .hero h1 {
        font-size: 34px;
      }

      .hero-checklist {
        flex-direction: column;
        gap: 10px;
      }

      .features {
        padding: 48px 20px;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }

      .roles {
        padding: 48px 20px;
      }

      .roles-grid {
        grid-template-columns: 1fr;
      }

      .hero-strip {
        flex-wrap: wrap;
        gap: 16px;
      }

      footer {
        flex-direction: column;
        gap: 8px;
        padding: 24px 20px;
        text-align: center;
      }
    }
  </style>
</head>

<body style="background: var(--gray-50);">

  {{-- NAVBAR --}}
  {{-- NAVBAR --}}
  <nav class="navbar">
    <div class="nav-brand">
      <div class="nav-icon">
        <svg width="22" height="22" fill="white" viewBox="0 0 24 24">
          <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
        </svg>
      </div>
      <div>
        <div class="nav-title">COMPRO</div>
        <div class="nav-sub">TEKKOM</div>
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:8px;">
      <a href="{{ route('blockchain.verify.index') }}" class="btn-ghost">
        Validasi Certificate
      </a>

      <a href="{{ route('login') }}" class="btn-ghost">
        Login
      </a>

      <a href="{{ route('register') }}" class="btn-primary">
        Mulai Sekarang
        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </nav>

  {{-- HERO --}}
  <section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-badge">
      <span class="hero-badge-dot"></span>
      COMPRO • Computer Engineering Digital Platform
    </div>
    <h1>COMPRO<br><span>Competency & Talent Platform</span></h1>
    <p class="hero-desc">
      Official Computer Engineering platform for competency courses, evaluation quizzes, real-world industry projects, and verified digital credentials.
    </p>
    <div class="hero-cta">
      <a href="{{ route('login') }}" class="btn-hero-primary">Start Learning Now</a>
      <a href="{{ route('login') }}" class="btn-hero-ghost">Explore Platform</a>
    </div>
    <div class="hero-checklist">
      <span class="check-item"><span class="check-dot"></span>Personalized skill pathways</span>
      <span class="check-item"><span class="check-dot"></span>Real-time progress analytics</span>
      <span class="check-item"><span class="check-dot"></span>Interactive skill assessments</span>
    </div>
    <div class="hero-strip">
      <div style="text-align:center">
        <div class="strip-num">500+</div>
        <div class="strip-label">Active Students</div>
      </div>
      <div style="text-align:center">
        <div class="strip-num">50+</div>
        <div class="strip-label">Competency Courses</div>
      </div>
      <div style="text-align:center">
        <div class="strip-num">30+</div>
        <div class="strip-label">Authors / Instructors</div>
      </div>
      <div style="text-align:center">
        <div class="strip-num">95%</div>
        <div class="strip-label">Completion Rate</div>
      </div>
    </div>
  </section>

  {{-- FITUR --}}
  <section class="features">
    <div class="eyebrow">Fitur Unggulan</div>
    <div class="section-title">Semua yang Anda butuhkan</div>
    <p class="section-sub">Dirancang khusus untuk ekosistem akademik Teknik Komputer UNDIP.</p>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="22" height="22" fill="none" stroke="#1E4D96" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="feature-title">Manajemen Tugas</div>
        <div class="feature-desc">Pengumpulan tugas digital dengan sistem tenggat waktu otomatis dan notifikasi pengingat.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="22" height="22" fill="none" stroke="#1E4D96" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <div class="feature-title">Pantau Progres</div>
        <div class="feature-desc">Lacak perkembangan belajar mahasiswa secara real-time dengan laporan analitik lengkap.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg width="22" height="22" fill="none" stroke="#1E4D96" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <div class="feature-title">Materi Digital</div>
        <div class="feature-desc">Unggah dan akses materi kuliah dalam berbagai format: video, PDF, slide, dan dokumen.</div>
      </div>
    </div>
  </section>

  {{-- ROLE --}}
  <section class="roles">
    <div style="text-align:center; margin-bottom:48px;">
      <div class="eyebrow" style="text-align:center;">Akses Portal</div>
      <div class="section-title">Masuk sebagai</div>
    </div>
    <div class="roles-grid">
      <a href="{{ route('login') }}" class="role-card">
        <div class="role-num">01 — Student</div>
        <div class="role-icon">
          <svg width="26" height="26" fill="#1E4D96" viewBox="0 0 24 24">
            <path d="M12 14l9-5-9-5-9 5 9 5z" />
            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
          </svg>
        </div>
        <div class="role-name">Student</div>
        <div class="role-desc">Akses materi kuliah, kumpulkan tugas, ikuti project industri, dan pantau sertifikat Anda.</div>
        <div class="role-arrow">
          Masuk Portal
          <svg width="14" height="14" fill="none" stroke="#2563EB" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </div>
      </a>


      <a href="{{ route('blockchain.verify.index') }}"
        class="group inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-600 hover:text-white hover:shadow-md">

        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-white transition duration-200 group-hover:bg-white group-hover:text-indigo-600">
          <svg width="15"
            height="15"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z" />
            <path d="M12 8v8" />
            <path d="M8 10.5l4 2.5 4-2.5" />
          </svg>
        </span>

        <span>
          Validasi Certificate
        </span>

        <svg class="transition duration-200 group-hover:translate-x-0.5"
          width="14"
          height="14"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>


      <a href="{{ route('login') }}" class="role-card">
        <div class="role-num">02 — Author / Instructor</div>
        <div class="role-icon">
          <svg width="26" height="26" fill="none" stroke="#1E4D96" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <div class="role-name">Author / Instructor</div>
        <div class="role-desc">Kelola course, susun kuis evaluasi, buat project industri, dan berikan penilaian.</div>
        <div class="role-arrow">
          Masuk Portal
          <svg width="14" height="14" fill="none" stroke="#2563EB" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </div>
      </a>

      <a href="{{ route('login') }}" class="role-card">
        <div class="role-num">03 — Admin</div>
        <div class="role-icon">
          <svg width="26" height="26" fill="none" stroke="#1E4D96" stroke-width="2" viewBox="0 0 24 24">
            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <div class="role-name">Admin</div>
        <div class="role-desc">Kelola pengguna, verifikasi sertifikat, dan konfigurasi sistem secara menyeluruh.</div>
        <div class="role-arrow">
          Masuk Portal
          <svg width="14" height="14" fill="none" stroke="#2563EB" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </div>
      </a>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer>
    <p>© {{ date('Y') }} COMPRO — Teknik Komputer, Universitas Diponegoro</p>
    <span>v2.0.0</span>
  </footer>

</body>

</html>