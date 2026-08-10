<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
        @if (session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- HERO HEADER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Pengawasan & Moderasi Global</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Monitoring Course Sertifikasi & Modul System</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Pantau seluruh course sertifikasi yang diterbitkan oleh <strong>Mitra Vendor Industri</strong> dan <strong>Dosen Akademik</strong> secara real-time.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <div style="background: #FAFAFA; border: 1px solid #E2E8F0; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #64748B; text-transform: uppercase;">Total Course</div>
                    <div style="font-size: 16px; font-weight: 800; color: #0F172A;">{{ $totalCourses }}</div>
                </div>

                <div style="background: #F3E8FF; border: 1px solid #E9D5FF; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #6B21A8; text-transform: uppercase;">🏢 Mitra Vendor</div>
                    <div style="font-size: 16px; font-weight: 800; color: #581C87;">{{ $vendorCourses }}</div>
                </div>

                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #1D4ED8; text-transform: uppercase;">🎓 Dosen Akademik</div>
                    <div style="font-size: 16px; font-weight: 800; color: #1E40AF;">{{ $lecturerCourses }}</div>
                </div>

                <div style="background: #DCFCE7; border: 1px solid #BBF7D0; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #15803D; text-transform: uppercase;">Aktif</div>
                    <div style="font-size: 16px; font-weight: 800; color: #166534;">{{ $activeCourses }}</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <form method="GET" action="{{ route('admin.courses.index') }}" style="display: flex; flex-wrap: wrap; items-center; justify-content: space-between; gap: 1rem;">
                <div style="display: flex; flex-wrap: wrap; items-center; gap: 0.75rem; flex: 1;">
                    <!-- Search Input -->
                    <div style="position: relative; min-width: 260px; flex: 1;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama course, deskripsi, atau nama pengampu..."
                               style="width: 100%; padding: 8px 14px 8px 36px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="position: absolute; left: 12px; top: 10px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>

                    <!-- Provider Type Filter -->
                    <select name="provider_type" onchange="this.form.submit()" style="padding: 8px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Semua Peran Pengampu --</option>
                        <option value="vendor" {{ request('provider_type') === 'vendor' ? 'selected' : '' }}>🏢 Mitra Vendor Industri</option>
                        <option value="lecturer" {{ request('provider_type') === 'lecturer' ? 'selected' : '' }}>🎓 Dosen Akademik</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()" style="padding: 8px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Aktif</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>🔴 Archived / Draft Bank</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" style="padding: 8px 16px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer;">
                        Filter Data
                    </button>

                    @if(request()->anyFilled(['search', 'provider_type', 'status']))
                    <a href="{{ route('admin.courses.index') }}" style="padding: 8px 14px; background: #F1F5F9; color: #475569; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                        Reset Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- COURSES TABLE CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 14px 20px;">Course & Sertifikasi</th>
                            <th style="padding: 14px 20px;">Pengampu (Provider)</th>
                            <th style="padding: 14px 20px;">Level & Kategori</th>
                            <th style="padding: 14px 20px;">Status Publikasi</th>
                            <th style="padding: 14px 20px;">Materi & Mahasiswa</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi Audit Admin</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($courses as $course)
                            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                                <!-- COURSE NAME -->
                                <td style="padding: 16px 20px;">
                                    <div style="font-size: 14px; font-weight: 800; color: #0F172A;">{{ $course->name }}</div>
                                    <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;">Threshold Kuis: {{ $course->certificate_threshold }} | ID: #{{ $course->id }}</div>
                                </td>

                                <!-- PROVIDER USER -->
                                <td style="padding: 16px 20px;">
                                    @php
                                        $isVendor = optional($course->user)->role === 'vendor';
                                        $badgeBg = $isVendor ? '#F3E8FF' : '#EFF6FF';
                                        $badgeColor = $isVendor ? '#6B21A8' : '#2563EB';
                                        $providerLabel = $isVendor ? '🏢 Mitra Vendor' : '🎓 Dosen Akademik';
                                    @endphp
                                    <div style="font-size: 13.5px; font-weight: 700; color: #1E293B;">{{ optional($course->user)->name ?? 'Sistem Admin' }}</div>
                                    <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 6px; display: inline-block; margin-top: 3px;">
                                        {{ $providerLabel }}
                                    </span>
                                </td>

                                <!-- LEVEL & CATEGORY -->
                                <td style="padding: 16px 20px;">
                                    <div style="font-weight: 700; color: #334155;">{{ $course->level }}</div>
                                    <div style="font-size: 11.5px; color: #64748B;">Kategori: {{ optional($course->category)->name ?? 'General' }}</div>
                                </td>

                                <!-- STATUS -->
                                <td style="padding: 16px 20px;">
                                    @if($course->is_archived)
                                        <span style="background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                            🔴 Archived / Draft Bank
                                        </span>
                                    @else
                                        <span style="background: #ECFDF5; color: #047857; border: 1px solid #6EE7B7; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                            🟢 Aktif Dipublikasikan
                                        </span>
                                    @endif
                                </td>

                                <!-- METRICS -->
                                <td style="padding: 16px 20px;">
                                    <div style="font-weight: 600; color: #334155;">📚 {{ $course->materials_count }} Modul | ❓ {{ $course->quizzes_count }} Kuis</div>
                                    <div style="font-size: 11.5px; color: #64748B;">👥 {{ $course->students_count }} Mahasiswa Enrolled</div>
                                </td>

                                <!-- ACTIONS -->
                                <td style="padding: 16px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <a href="{{ route('admin.courses.show', $course) }}" style="padding: 6px 12px; background: #F1F5F9; color: #334155; font-size: 12px; font-weight: 700; border-radius: 8px; text-decoration: none;">
                                            Detail & Audit
                                        </a>

                                        <form action="{{ route('admin.courses.toggle-archive', $course) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @if($course->is_archived)
                                                <button type="submit" style="padding: 6px 10px; background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">
                                                    🚀 Aktifkan
                                                </button>
                                            @else
                                                <button type="submit" style="padding: 6px 10px; background: #FEF3C7; color: #B45309; font-size: 11.5px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">
                                                    📦 Arsipkan
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus course ini dari sistem?')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding: 6px 10px; background: #FEF2F2; color: #B91C1C; font-size: 11.5px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: #64748B;">
                                    Tidak ada data course sertifikasi ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
