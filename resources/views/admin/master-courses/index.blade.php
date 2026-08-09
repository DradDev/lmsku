<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

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
                    <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Pusat Katalis Akademik</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Katalog Master Course Induk</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Pilih mata kuliah di bawah untuk membuka <strong>Gerbang Administrasi 3NF</strong> (Semester, Penawaran Kelas, & Pengaturan Matkul).
                </p>
            </div>

            <a href="{{ route('admin.master-courses.create') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Master Course Baru
            </a>
        </div>

        <!-- MASTER COURSE CATALOG TABLE CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 14px 20px;">Kode Matkul</th>
                            <th style="padding: 14px 20px;">Nama Mata Kuliah</th>
                            <th style="padding: 14px 20px;">Level</th>
                            <th style="padding: 14px 20px;">Kategori</th>
                            <th style="padding: 14px 20px;">Total Kelas</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi Utama</th>
                        </tr>
                    </thead>

                    <tbody style="divide-y: 1px solid #F1F5F9;">
                        @forelse ($masterCourses as $mc)
                            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
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
                                            {{ Str::limit($mc->description, 70) }}
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
                                        <!-- TOMBOL TUNGGAL UTAMA: BUKA GERBANG MATKUL -->
                                        <a href="{{ route('admin.master-courses.show', $mc) }}" 
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #2563EB; color: #FFFFFF; font-size: 12.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                            Buka Gerbang Matkul
                                        </a>

                                        <!-- HAPUS HANYA JIKA BELUM ADA KELAS PENAWARAN -->
                                        <form action="{{ route('admin.master-courses.destroy', $mc) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin hapus Master Course ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Master Course"
                                                    style="all: unset; cursor: pointer; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #FCA5A5; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 3rem; text-align: center;">
                                    <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">Belum Ada Master Course</h3>
                                    <p style="font-size: 13px; color: #64748B; margin: 4px 0 1.25rem 0;">Tambahkan katalog mata kuliah induk untuk mulai menentukan semester dan penawaran kelas.</p>
                                    <a href="{{ route('admin.master-courses.create') }}" 
                                       style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                                        Tambah Master Course
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
