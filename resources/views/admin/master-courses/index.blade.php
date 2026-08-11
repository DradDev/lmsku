<x-app-layout>
    <div x-data="{ tab: 'all' }" style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- SUCCESS / ERROR ALERTS -->
        @if (session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div style="padding: 1rem 1.25rem; background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- HERO HEADER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Pusat Katalog Terpadu</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Katalog Master Course & Sertifikasi (Internal & Vendor)</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Kelola seluruh katalog mata kuliah kurikulum akademik Dosen dan course sertifikasi bootcamp milik Mitra Vendor Industri.
                </p>
            </div>

            <a href="{{ route('admin.master-courses.create') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Master Course Akademik
            </a>
        </div>

        <!-- 4 EXECUTIVE STAT CARDS -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; tracking-wider">Total Katalog Master Course</span>
                <p style="font-size: 26px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">
                    {{ $masterCourses->count() + $vendorCourses->count() }}
                </p>
                <span style="font-size: 12px; color: #475569; font-weight: 500;">Internal Kampus & Vendor</span>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <span style="font-size: 11px; font-weight: 700; color: #2563EB; text-transform: uppercase; tracking-wider">🏛️ Internal Kampus (Dosen)</span>
                <p style="font-size: 26px; font-weight: 800; color: #2563EB; margin: 6px 0 2px 0;">
                    {{ $masterCourses->count() }}
                </p>
                <span style="font-size: 12px; color: #64748B; font-weight: 500;">Matkul Kurikulum Akademik</span>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <span style="font-size: 11px; font-weight: 700; color: #7C3AED; text-transform: uppercase; tracking-wider">🏢 Mitra Vendor (Sertifikasi)</span>
                <p style="font-size: 26px; font-weight: 800; color: #7C3AED; margin: 6px 0 2px 0;">
                    {{ $vendorCourses->count() }}
                </p>
                <span style="font-size: 12px; color: #64748B; font-weight: 500;">Course Sertifikasi Industri</span>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <span style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; tracking-wider">🎯 Total Class Offerings & Batches</span>
                <p style="font-size: 26px; font-weight: 800; color: #059669; margin: 6px 0 2px 0;">
                    {{ $masterCourses->sum('offerings_count') + $vendorCourses->count() }}
                </p>
                <span style="font-size: 12px; color: #64748B; font-weight: 500;">Kelas Pararel & Angkatan Batch</span>
            </div>
        </div>

        <!-- TAB NAVIGATION (ALL / INTERNAL / VENDOR) -->
        <div style="display: flex; gap: 8px; border-bottom: 2px solid #E2E8F0; padding-bottom: 4px;">
            <button type="button" 
                    @click="tab = 'all'" 
                    :style="tab === 'all' ? 'border-color: #2563EB; color: #2563EB; font-weight: 800;' : 'border-color: transparent; color: #64748B; font-weight: 600;'"
                    style="padding: 10px 18px; border-bottom: 3px solid; font-size: 13.5px; background: none; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 6px;">
                <span>🌐 Semua Master Course ({{ $masterCourses->count() + $vendorCourses->count() }})</span>
            </button>

            <button type="button" 
                    @click="tab = 'internal'" 
                    :style="tab === 'internal' ? 'border-color: #2563EB; color: #2563EB; font-weight: 800;' : 'border-color: transparent; color: #64748B; font-weight: 600;'"
                    style="padding: 10px 18px; border-bottom: 3px solid; font-size: 13.5px; background: none; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 6px;">
                <span>🏛️ Internal Kampus ({{ $masterCourses->count() }})</span>
            </button>

            <button type="button" 
                    @click="tab = 'vendor'" 
                    :style="tab === 'vendor' ? 'border-color: #7C3AED; color: #7C3AED; font-weight: 800;' : 'border-color: transparent; color: #64748B; font-weight: 600;'"
                    style="padding: 10px 18px; border-bottom: 3px solid; font-size: 13.5px; background: none; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 6px;">
                <span>🏢 Sertifikasi Vendor ({{ $vendorCourses->count() }})</span>
            </button>
        </div>

        <!-- MASTER COURSE CATALOG TABLE CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 14px 20px;">Tipe Provider</th>
                            <th style="padding: 14px 20px;">Kode / Batch</th>
                            <th style="padding: 14px 20px;">Nama Master Course / Sertifikasi</th>
                            <th style="padding: 14px 20px;">Level</th>
                            <th style="padding: 14px 20px;">Kategori</th>
                            <th style="padding: 14px 20px;">Kelas / Batches</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi Utama</th>
                        </tr>
                    </thead>

                    <tbody style="divide-y: 1px solid #F1F5F9;">

                        <!-- 1. ACADEMIC INTERNAL MASTER COURSES -->
                        @foreach ($masterCourses as $mc)
                            <tr x-show="tab === 'all' || tab === 'internal'"
                                style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                                <td style="padding: 16px 20px;">
                                    <span style="background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        🏛️ Internal Kampus
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #F1F5F9; color: #334155; font-size: 12px; font-weight: 700; font-family: monospace; padding: 4px 10px; border-radius: 8px; border: 1px solid #E2E8F0;">
                                        {{ $mc->code ?? 'MC-' . $mc->id }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <a href="{{ route('admin.master-courses.show', $mc) }}" style="font-size: 14.5px; font-weight: 800; color: #0F172A; text-decoration: none; display: block;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#0F172A'">
                                        {{ $mc->name }}
                                    </a>
                                    @if($mc->description)
                                        <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0; line-height: 1.4;">
                                            {{ Str::limit($mc->description, 75) }}
                                        </p>
                                    @endif
                                </td>

                                <td style="padding: 16px 20px;">
                                    @php
                                        $levelBadges = [
                                            'Beginner' => ['bg' => '#DCFCE7', 'color' => '#15803D'],
                                            'Intermediate' => ['bg' => '#FEF3C7', 'color' => '#B45309'],
                                            'Advanced' => ['bg' => '#FFE4E6', 'color' => '#BE123C'],
                                        ];
                                        $badge = $levelBadges[$mc->level] ?? ['bg' => '#F1F5F9', 'color' => '#475569'];
                                    @endphp
                                    <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        {{ $mc->level }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #EEF2FF; color: #4338CA; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 8px;">
                                        {{ $mc->category->name ?? 'Umum' }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #E0F2FE; color: #0369A1; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 6px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                        {{ $mc->offerings_count }} Kelas
                                    </span>
                                </td>

                                <td style="padding: 16px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <a href="{{ route('admin.master-courses.show', $mc) }}" 
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #2563EB; color: #FFFFFF; font-size: 12.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                            Buka Gerbang Matkul
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- 2. VENDOR CERTIFICATION MASTER COURSES -->
                        @foreach ($vendorCourses as $vc)
                            <tr x-show="tab === 'all' || tab === 'vendor'"
                                style="border-bottom: 1px solid #F1F5F9; background: #FAF5FF; transition: background 0.15s ease;" onmouseover="this.style.background='#F3E8FF'" onmouseout="this.style.background='#FAF5FF'">
                                <td style="padding: 16px 20px;">
                                    <span style="background: #F3E8FF; color: #6B21A8; border: 1px solid #E9D5FF; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        🏢 Mitra Vendor ({{ $vc->user->name ?? 'External' }})
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #EDE9FE; color: #5B21B6; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 8px; border: 1px solid #DDD6FE;">
                                        {{ $vc->batch_name ?? 'Batch 1 - 2026' }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <a href="{{ route('vendor.courses.show', $vc) }}" style="font-size: 14.5px; font-weight: 800; color: #4C1D95; text-decoration: none; display: block;" onmouseover="this.style.color='#7C3AED'" onmouseout="this.style.color='#4C1D95'">
                                        {{ $vc->name }}
                                    </a>
                                    @if($vc->description)
                                        <p style="font-size: 12.5px; color: #6B21A8; margin: 3px 0 0 0; line-height: 1.4;">
                                            {{ Str::limit($vc->description, 75) }}
                                        </p>
                                    @endif
                                </td>

                                <td style="padding: 16px 20px;">
                                    @php
                                        $levelBadges = [
                                            'Beginner' => ['bg' => '#DCFCE7', 'color' => '#15803D'],
                                            'Intermediate' => ['bg' => '#FEF3C7', 'color' => '#B45309'],
                                            'Advanced' => ['bg' => '#FFE4E6', 'color' => '#BE123C'],
                                        ];
                                        $badge = $levelBadges[$vc->level] ?? ['bg' => '#F1F5F9', 'color' => '#475569'];
                                    @endphp
                                    <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        {{ $vc->level }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #F3E8FF; color: #6B21A8; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 8px;">
                                        {{ $vc->category->name ?? 'Sertifikasi Vendor' }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px;">
                                    <span style="background: #D1FAE5; color: #065F46; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 6px;">
                                        Threshold: {{ $vc->certificate_threshold ?? 75 }}%
                                    </span>
                                </td>

                                <td style="padding: 16px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <a href="{{ route('vendor.courses.show', $vc) }}" 
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #7C3AED; color: #FFFFFF; font-size: 12.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(124,58,237,0.2);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m10 15 5-3-5-3v6z"/></svg>
                                            Inspeksi Vendor
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($masterCourses->isEmpty() && $vendorCourses->isEmpty())
                            <tr>
                                <td colspan="7" style="padding: 40px; text-align: center; color: #94A3B8; font-weight: 600;">
                                    Belum ada Master Course atau Course Sertifikasi Vendor terdaftar.
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
