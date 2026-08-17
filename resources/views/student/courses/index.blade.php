<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .page-wrap {
            min-height: 100vh;
            background: #f4f6fb;
            color: #1e2435;
            padding: 2.5rem 0 4rem;
            font-family: 'Inter', sans-serif;
        }

        .page-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-top {
            margin-bottom: 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .page-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #3b5bdb;
            margin-bottom: 5px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 600;
            color: #1e2435;
        }

        .page-sub {
            font-size: 13px;
            color: #7b8399;
            margin-top: 3px;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 2rem;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 100px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .filter-tab:hover {
            border-color: #cbd5e1;
            color: #334155;
        }

        .filter-tab.active {
            background: #3b5bdb;
            color: #ffffff;
            border-color: #3b5bdb;
            box-shadow: 0 4px 12px rgba(59, 91, 219, 0.25);
        }

        .tab-count {
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 100px;
            background: rgba(0, 0, 0, 0.06);
        }

        .filter-tab.active .tab-count {
            background: rgba(255, 255, 255, 0.2);
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .course-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: all 0.25s ease;
        }

        .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }

        .badge-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .course-badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 100px;
            width: fit-content;
            letter-spacing: 0.3px;
        }

        .badge-enrolled {
            background: #edfaf4;
            color: #1a7a4a;
            border: 1px solid #a7e9c8;
        }

        .badge-available {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .badge-completed {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-progress {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .course-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e2435;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .course-description {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .course-meta {
            margin-top: auto;
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 12px;
            font-size: 11.5px;
            color: #475569;
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 12px;
        }

        .course-meta-item {
            display: flex;
            justify-content: space-between;
        }

        .progress-section {
            margin-bottom: 14px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11.5px;
            margin-bottom: 5px;
        }

        .progress-text {
            color: #64748b;
            font-weight: 500;
        }

        .progress-pct {
            font-weight: 700;
            color: #3b5bdb;
        }

        .progress-track {
            height: 6px;
            background: #e2e8f0;
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, #3b5bdb, #6366f1);
            transition: width 0.3s ease;
        }

        .progress-fill.done {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .course-actions {
            margin-top: auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            width: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
            width: 100%;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
        }

        .btn-cert {
            background: #ecfdf5;
            color: #047857;
            border: 1.5px solid #a7f3d0;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);
            width: 100%;
        }

        .btn-cert:hover {
            background: #d1fae5;
            border-color: #6ee7b7;
        }

        @media (max-width: 1024px) {
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .page-container {
                padding: 0 1rem;
            }

            .page-top {
                flex-direction: column;
            }

            .courses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-wrap">
        <div class="page-container">

            @if (session('success'))
            <div class="alert alert-success" style="padding: 12px 16px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('success') }}
            </div>
            @endif

            @if (session('info'))
            <div class="alert alert-info" style="padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('info') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-error" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('error') }}
            </div>
            @endif

            <div class="page-top">
                <div>
                    <p class="page-eyebrow">COMPRO TEKKOM · Student Course Catalog</p>
                    <h1 class="page-title">Katalog Kursus & Mata Kuliah</h1>
                    <p class="page-sub">
                        Pilih mata kuliah akademik internal maupun pelatihan sertifikasi industri dari mitra eksternal.
                    </p>
                </div>
            </div>

            <!-- FILTER BAR (SUMBER KURIKULUS & DAFTAR PENYELENGGARA / VENDOR) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <!-- Filter 1: Sumber Kursus -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        Sumber Kurikulum:
                    </label>
                    <select id="source-filter-select" onchange="applyFilters()" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 13.5px; font-weight: 600; color: #1e2435; background-color: #ffffff; cursor: pointer; outline: none;">
                        <option value="all">Semua Sumber (Internal & Eksternal)</option>
                        <option value="internal">Internal Kampus (Teknik Komputer)</option>
                        <option value="external">Eksternal Mitra Industri (Vendor)</option>
                    </select>
                </div>

                <!-- Filter 2: Dosen / Mitra Vendor Eksternal -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        Penyelenggara (Dosen / Vendor Mitra):
                    </label>
                    <select id="author-filter-select" onchange="applyFilters()" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 13.5px; font-weight: 600; color: #1e2435; background-color: #ffffff; cursor: pointer; outline: none;">
                        <option value="all">Semua Penyelenggara</option>

                        @if(($authors ?? collect())->count() > 0)
                            <optgroup label="Dosen Pengampu Kampus (Internal)">
                                @foreach($authors as $author)
                                    <option value="lecturer-{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif

                        @if(($vendors ?? collect())->count() > 0)
                            <optgroup label="Mitra Industri / Vendor (Eksternal)">
                                @foreach($vendors as $vendor)
                                    @php
                                        $instLabel = $vendor->institution ? " ({$vendor->institution->name})" : " (Praktisi)";
                                    @endphp
                                    <option value="vendor-{{ $vendor->id }}">{{ $vendor->name }}{{ $instLabel }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>
            </div>

            <!-- FILTER TABS -->
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterCourses('all', this)">
                    Semua Kursus
                    <span class="tab-count" id="count-all">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('internal', this)">
                    Internal Kampus
                    <span class="tab-count" id="count-internal">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('external', this)">
                    Eksternal Vendor
                    <span class="tab-count" id="count-external">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('available', this)">
                    Tersedia
                    <span class="tab-count" id="count-available">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('enrolled', this)">
                    Terdaftar
                    <span class="tab-count" id="count-enrolled">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('progress', this)">
                    In Progress
                    <span class="tab-count" id="count-progress">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('completed', this)">
                    Completed
                    <span class="tab-count" id="count-completed">0</span>
                </button>
            </div>

            <!-- SECTION 1: INTERNAL ACADEMIC COURSES -->
            <div id="section-internal-courses" style="margin-bottom: 2.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 1.25rem;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">
                            Mata Kuliah Internal Teknik Komputer
                        </h2>
                        <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0 0;">
                            Kurikulum akademik resmi yang dibuka oleh Program Studi.
                        </p>
                    </div>
                    <span class="tab-count" style="font-size: 12px; font-weight: 700; color: #3b5bdb; background: #eff6ff; padding: 4px 12px; border-radius: 100px;">
                        {{ $groupedCourses->count() }} Mata Kuliah
                    </span>
                </div>

                @if($groupedCourses->count() > 0)
                <div class="courses-grid">
                    @foreach($groupedCourses as $item)
                    @php
                        $master = $item->master_course;
                        $offerings = $item->offerings;
                        $isEnrolled = $item->is_enrolled;
                        $enrolledOffering = $item->enrolled_offering;
                        $activeOffering = $item->active_offering;
                        $progress = $item->progress;
                        $isCompleted = $item->is_completed;

                        if (! $isEnrolled) {
                            $status = 'available';
                        } elseif ($isCompleted) {
                            $status = 'completed';
                        } elseif ($progress > 0) {
                            $status = 'progress';
                        } else {
                            $status = 'enrolled';
                        }

                        $lecturerIdsStr = $offerings->pluck('lecturer_id')->map(fn($id) => "lecturer-{$id}")->join(',');
                    @endphp

                    <div class="course-card" data-source="internal" data-status="{{ $status }}" data-provider-id="{{ $lecturerIdsStr }}">
                        <div class="badge-row">
                            <span class="course-badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                Internal TK
                            </span>

                            @if($isEnrolled)
                                <span class="course-badge badge-enrolled">
                                    ✓ Terdaftar di {{ $enrolledOffering->section_name }}
                                </span>
                            @else
                                <span class="course-badge badge-available">
                                    Available
                                </span>
                            @endif

                            @if($isCompleted)
                                <span class="course-badge badge-completed">
                                    Completed
                                </span>
                            @elseif($isEnrolled && $progress > 0)
                                <span class="course-badge badge-progress">
                                    In Progress
                                </span>
                            @endif
                        </div>

                        <div class="course-title">
                            {{ $master->name }}
                        </div>

                        <p class="course-description">
                            {{ \Illuminate\Support\Str::limit($master->description, 110) }}
                        </p>

                        <!-- SECTION PILLS: PEMILIHAN KELAS PARAREL -->
                        <div style="margin-top: 4px; margin-bottom: 12px; padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                            <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between;">
                                <span>Rombel Kelas ({{ $offerings->count() }} Kelas):</span>
                                @if($isEnrolled && $enrolledOffering)
                                    <span style="color: #059669; font-weight: 800; font-size: 10px;">{{ $enrolledOffering->section_name }}</span>
                                @endif
                            </div>

                            <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                @foreach($offerings as $off)
                                    @php
                                        $isThisEnrolled = $enrolledOffering && $enrolledOffering->id === $off->id;
                                        $isFull = !$off->hasAvailableCapacity();
                                        $capText = is_null($off->capacity) 
                                            ? 'Kuota Unlimited' 
                                            : ($isFull ? 'PENUH' : 'Sisa: ' . max(0, $off->capacity - $off->enrollments_count));
                                    @endphp
                                    @if($isThisEnrolled)
                                        <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #d1fae5; color: #065f46; border: 1.5px solid #34d399;">
                                            {{ $off->section_name }} <span style="font-weight: 400; opacity: 0.85;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                        </div>
                                    @elseif($isFull)
                                        <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #f1f5f9; color: #94a3b8; border: 1px solid #cbd5e1;">
                                            {{ $off->section_name }} <span style="font-weight: 400;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                        </div>
                                    @else
                                        <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #ffffff; color: #334155; border: 1px solid #cbd5e1;">
                                            {{ $off->section_name }} <span style="font-weight: 400; opacity: 0.85;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="course-meta">
                            <div class="course-meta-item">
                                <strong>Level:</strong>
                                <span>{{ $master->level ?? 'Beginner' }}</span>
                            </div>

                            <div class="course-meta-item">
                                <strong>Kategori:</strong>
                                <span>{{ $master->category->name ?? 'General' }}</span>
                            </div>
                        </div>

                        @if($isEnrolled)
                        <div class="progress-section">
                            <div class="progress-header">
                                <span class="progress-text">Progress Pembelajaran</span>
                                <span class="progress-pct">{{ $progress }}%</span>
                            </div>

                            <div class="progress-track">
                                <div class="progress-fill {{ $isCompleted ? 'done' : '' }}"
                                    style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="course-actions">
                            @if($isEnrolled)
                                <a href="{{ route('student.courses.show', $enrolledOffering->id) }}"
                                    class="btn btn-primary">
                                    {{ $isCompleted ? 'Review Course' : 'Lanjut Belajar ('.$enrolledOffering->section_name.')' }}
                                </a>

                                @if($item->can_get_certificate)
                                <a href="{{ route('student.certificate.show', $enrolledOffering->id) }}"
                                    class="btn btn-cert" style="margin-top: 6px;">
                                    Klaim Sertifikat
                                </a>
                                @endif
                            @else
                                @php
                                    $availableOfferings = $offerings->filter(fn($o) => $o->hasAvailableCapacity());
                                    $firstAvailable = $availableOfferings->first() ?? $activeOffering;
                                @endphp
                                @if($availableOfferings->count() > 0)
                                    <form action="{{ route('student.courses.enroll', $firstAvailable->id) }}" method="POST" id="enroll-form-{{ $master->id }}" style="width: 100%;">
                                        @csrf
                                        <div style="margin-bottom: 8px;">
                                            <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Pilih Rombel Kelas:</label>
                                            <select onchange="document.getElementById('enroll-form-{{ $master->id }}').action = '/student/courses/' + this.value + '/enroll'" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 12px; font-weight: 600; color: #1e293b; background: #ffffff; cursor: pointer; outline: none;">
                                                @foreach($offerings as $off)
                                                    @php
                                                        $isFull = !$off->hasAvailableCapacity();
                                                        $optCapText = is_null($off->capacity) 
                                                            ? 'Kuota Unlimited' 
                                                            : ($isFull ? 'KELAS PENUH' : 'Sisa Kuota: ' . max(0, $off->capacity - $off->enrollments_count));
                                                    @endphp
                                                    <option value="{{ $off->id }}" {{ $isFull ? 'disabled style=color:#94a3b8;background:#f8fafc;' : '' }}>
                                                        {{ $off->section_name }} — {{ $off->lecturer->name ?? 'Dosen' }} ({{ $optCapText }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div style="display: flex; gap: 8px; margin-top: 8px;">
                                            <a href="{{ route('student.courses.show', $firstAvailable->id) }}" class="btn" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; width: 100%; justify-content: center;">
                                                Preview Silabus
                                            </a>

                                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                                Ambil Kelas
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div style="width: 100%;">
                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-align: center; margin-bottom: 6px;">
                                            Seluruh Rombel Kelas Penuh
                                        </div>
                                        <button type="button" class="btn" disabled style="width: 100%; background: #e2e8f0; color: #64748b; cursor: not-allowed; opacity: 0.8;">
                                            Pendaftaran Ditutup
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem; text-align: center;">
                    <div style="font-size: 14px; font-weight: 700; color: #64748b;">Belum ada mata kuliah internal yang tersedia.</div>
                </div>
                @endif
            </div>

            <!-- SECTION 2: VENDOR CERTIFICATION COURSES (EXTERNAL) -->
            @php $vendorCourses = $vendorCourses ?? collect(); @endphp
            <div id="section-vendor-courses" style="margin-top: 2rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px; margin-bottom: 1.25rem;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 800; color: #581c87; margin: 0;">
                            Pelatihan & Sertifikasi Mitra Industri (Eksternal)
                        </h2>
                        <p style="font-size: 12.5px; color: #7e22ce; margin: 2px 0 0 0;">
                            Pelatihan kompetensi langsung dari mitra industri dengan verifikasi Sertifikat Digital.
                        </p>
                    </div>
                    <span class="tab-count" style="font-size: 12px; font-weight: 700; color: #6b21a8; background: #f3e8ff; padding: 4px 12px; border-radius: 100px;">
                        {{ $vendorCourses->count() }} Kursus Industri
                    </span>
                </div>

                @if($vendorCourses->count() > 0)
                    <div class="courses-grid">
                        @foreach($vendorCourses as $vc)
                            @php
                                if (!$vc->is_enrolled) {
                                    $vcStatus = 'available';
                                } elseif ($vc->is_completed) {
                                    $vcStatus = 'completed';
                                } elseif ($vc->progress > 0) {
                                    $vcStatus = 'progress';
                                } else {
                                    $vcStatus = 'enrolled';
                                }
                            @endphp

                            <div class="course-card" data-source="external" data-status="{{ $vcStatus }}" data-provider-id="vendor-{{ $vc->user_id }}" style="border-color: #e9d5ff; background: #faf5ff;">
                                <div class="badge-row">
                                    <span style="background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 100px;">
                                        {{ $vc->user->institution->name ?? ($vc->user->name ?? 'Mitra Vendor') }}
                                    </span>
                                    <span style="background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 8px;">
                                        {{ $vc->batch_name ?? 'Batch Industri' }}
                                    </span>
                                    <span style="background: #d1fae5; color: #065f46; border: 1px solid #a7e9c8; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 100px;">
                                        Threshold: {{ $vc->certificate_threshold ?? 75 }}%
                                    </span>
                                </div>

                                <h3 class="course-title" style="color: #4c1d95;">
                                    {{ $vc->name }}
                                </h3>

                                <p class="course-description" style="color: #6b21a8;">
                                    {{ Str::limit($vc->description, 100) }}
                                </p>

                                <div style="margin-top: 0.5rem; margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 6px;">
                                    @foreach($vc->skills as $sk)
                                        <span style="font-size: 11px; font-weight: 800; color: #5b21b6; background: #ffffff; border: 1px solid #ddd6fe; padding: 3px 8px; border-radius: 6px;">
                                            {{ $sk->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid #f3e8ff;">
                                    @if($vc->is_enrolled)
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            @if($vc->can_get_certificate)
                                                <a href="{{ route('student.certificate.show', $vc->id) }}" class="btn btn-cert" style="text-align: center;">
                                                    Klaim Sertifikat Vendor ↗
                                                </a>
                                            @else
                                                <a href="{{ route('student.courses.show', $vc->id) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); text-align: center;">
                                                    Lanjut Belajar & Kuis ({{ $vc->progress }}%)
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('student.courses.show', $vc->id) }}" class="btn" style="background: #ffffff; color: #6b21a8; border: 1px solid #ddd6fe; width: 100%; justify-content: center;">
                                                Preview Detail
                                            </a>

                                            <form action="{{ route('student.courses.enroll', $vc->id) }}" method="POST" style="width: 100%;">
                                                @csrf
                                                <button type="submit" class="btn btn-success" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); width: 100%;">
                                                    Ambil Sertifikasi
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="background: #fff; border: 1px solid #e9d5ff; border-radius: 20px; padding: 2rem; text-align: center;">
                        <div style="font-size: 14px; font-weight: 700; color: #6b21a8;">Belum ada kursus sertifikasi eksternal yang tersedia.</div>
                    </div>
                @endif
            </div>

            <!-- EMPTY SEARCH / FILTER RESULT STATE -->
            <div id="no-matching-courses" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 3rem; text-align: center;">
                <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Tidak ada kursus yang sesuai dengan filter yang dipilih.
                </div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 1rem;">
                    Silakan ubah pilihan sumber kurikulum, penyelenggara, atau tab status.
                </div>
                <button onclick="resetAllFilters()" class="btn btn-primary" style="width: auto; padding: 8px 16px;">
                    Reset Semua Filter
                </button>
            </div>

        </div>
    </div>

    <script>
        let currentTabFilter = 'all';

        function filterCourses(tab, btn) {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            currentTabFilter = tab;
            applyFilters();
        }

        function resetAllFilters() {
            document.getElementById('source-filter-select').value = 'all';
            document.getElementById('author-filter-select').value = 'all';
            const allTabBtn = document.querySelector('.filter-tab');
            if (allTabBtn) {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                allTabBtn.classList.add('active');
            }
            currentTabFilter = 'all';
            applyFilters();
        }

        function applyFilters() {
            const selectedSource = document.getElementById('source-filter-select').value;
            const selectedProvider = document.getElementById('author-filter-select').value;
            const cards = document.querySelectorAll('.course-card');

            let visibleCount = 0;
            let visibleInternal = 0;
            let visibleExternal = 0;

            cards.forEach(card => {
                const cardSource = card.dataset.source; // 'internal' or 'external'
                const cardStatus = card.dataset.status; // 'available', 'enrolled', 'progress', 'completed'
                const cardProviderStr = card.dataset.providerId || ''; // e.g. 'lecturer-1,lecturer-2' or 'vendor-5'
                const providerList = cardProviderStr.split(',');

                // 1. Check Source Filter (Dropdown 1)
                let matchesSource = (selectedSource === 'all') || (cardSource === selectedSource);

                // 2. Check Provider Filter (Dropdown 2)
                let matchesProvider = (selectedProvider === 'all') || providerList.includes(selectedProvider);

                // 3. Check Tab Filter
                let matchesTab = false;
                if (currentTabFilter === 'all') {
                    matchesTab = true;
                } else if (currentTabFilter === 'internal') {
                    matchesTab = (cardSource === 'internal');
                } else if (currentTabFilter === 'external') {
                    matchesTab = (cardSource === 'external');
                } else if (currentTabFilter === 'enrolled') {
                    matchesTab = ['enrolled', 'progress', 'completed'].includes(cardStatus);
                } else {
                    matchesTab = (cardStatus === currentTabFilter);
                }

                if (matchesSource && matchesProvider && matchesTab) {
                    card.style.display = 'flex';
                    visibleCount++;
                    if (cardSource === 'internal') visibleInternal++;
                    if (cardSource === 'external') visibleExternal++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Toggle section visibility based on content
            const internalSection = document.getElementById('section-internal-courses');
            const externalSection = document.getElementById('section-vendor-courses');
            const emptyMessage = document.getElementById('no-matching-courses');

            if (internalSection) {
                internalSection.style.display = (visibleInternal > 0 || (selectedSource === 'internal' && visibleCount === 0)) ? 'block' : (selectedSource === 'external' || (currentTabFilter === 'external' && visibleInternal === 0) ? 'none' : 'block');
            }

            if (externalSection) {
                externalSection.style.display = (visibleExternal > 0 || (selectedSource === 'external' && visibleCount === 0)) ? 'block' : (selectedSource === 'internal' || (currentTabFilter === 'internal' && visibleExternal === 0) ? 'none' : 'block');
            }

            if (emptyMessage) {
                emptyMessage.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.course-card');

            const counts = {
                all: cards.length,
                internal: 0,
                external: 0,
                available: 0,
                enrolled: 0,
                progress: 0,
                completed: 0
            };

            cards.forEach(card => {
                const source = card.dataset.source;
                const status = card.dataset.status;

                if (source === 'internal') counts.internal++;
                if (source === 'external') counts.external++;

                if (status === 'available') {
                    counts.available++;
                }

                if (['enrolled', 'progress', 'completed'].includes(status)) {
                    counts.enrolled++;
                }

                if (status === 'progress') {
                    counts.progress++;
                }

                if (status === 'completed') {
                    counts.completed++;
                }
            });

            if (document.getElementById('count-all')) document.getElementById('count-all').textContent = counts.all;
            if (document.getElementById('count-internal')) document.getElementById('count-internal').textContent = counts.internal;
            if (document.getElementById('count-external')) document.getElementById('count-external').textContent = counts.external;
            if (document.getElementById('count-available')) document.getElementById('count-available').textContent = counts.available;
            if (document.getElementById('count-enrolled')) document.getElementById('count-enrolled').textContent = counts.enrolled;
            if (document.getElementById('count-progress')) document.getElementById('count-progress').textContent = counts.progress;
            if (document.getElementById('count-completed')) document.getElementById('count-completed').textContent = counts.completed;
        });
    </script>
</x-app-layout>