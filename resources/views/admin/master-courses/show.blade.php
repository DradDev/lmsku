<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- TOP BANNER CARD matching TampilanAdmin.jpeg -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 300px;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: #F3E8FF; color: #7E22CE; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800; flex-shrink: 0;">
                    MC-{{ $masterCourse->id }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $masterCourse->name }}</h1>
                        <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">{{ $masterCourse->level }}</span>
                    </div>
                    <p style="font-size: 13px; color: #64748B; margin: 6px 0 0 0; line-height: 1.5; max-width: 650px;">
                        {{ $masterCourse->description ?: 'Kursus membangun antarmuka Laravel Blade yang rapi, reusable, dan mudah di-maintain dengan komponen.' }}
                    </p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 2rem; border-left: 1px solid #F1F5F9; padding-left: 1.5rem;">
                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Total Semester</div>
                    <div style="font-size: 22px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $totalSemesters }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Total Kelas</div>
                    <div style="font-size: 22px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $totalOfferings }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Level</div>
                    <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-top: 5px;">{{ $masterCourse->level }}</div>
                </div>

                <a href="{{ route('admin.master-courses.edit', $masterCourse) }}" 
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 10px; color: #334155; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Edit Master Course
                </a>
            </div>
        </div>

        <!-- NAVIGATION SUB-TABS -->
        <div style="display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 600; padding: 0 4px;">
            <span style="color: #2563EB; border-bottom: 2px solid #2563EB; padding-bottom: 4px;">Semester ({{ $totalSemesters }})</span>
            <span style="color: #94A3B8;">&rsaquo;</span>
            <span style="color: #64748B;">Penawaran Kelas</span>
        </div>

        <!-- SECTION 1: DAFTAR SEMESTER TABLE CARD matching TampilanAdmin.jpeg -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9;">
                <div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Daftar Semester - {{ $masterCourse->name }}</h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Kelola semester untuk mata kuliah induk ini.</p>
                </div>
                <a href="{{ route('admin.academic-terms.create') }}" 
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    + Tambah Semester
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 12px 20px;">Semester</th>
                            <th style="padding: 12px 20px;">Tahun Ajaran</th>
                            <th style="padding: 12px 20px;">Tipe</th>
                            <th style="padding: 12px 20px;">Periode</th>
                            <th style="padding: 12px 20px;">Status</th>
                            <th style="padding: 12px 20px;">Jumlah Kelas</th>
                            <th style="padding: 12px 20px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid #F1F5F9;">
                        @forelse($academicTerms as $term)
                            @php
                                $termOfferingCount = $courseOfferings->where('academic_term_id', $term->id)->count();
                                $isCurrentSelected = ($selectedTerm && $selectedTerm->id === $term->id);
                            @endphp
                            <tr style="border-bottom: 1px solid #F1F5F9; background: {{ $isCurrentSelected ? '#EFF6FF' : '#FFFFFF' }};">
                                <td style="padding: 14px 20px; font-weight: 700; color: #0F172A;">
                                    {{ $term->name }}
                                </td>
                                <td style="padding: 14px 20px; color: #475569;">
                                    {{ $term->academic_year ?? '2026/2027' }}
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if(strtolower($term->term_type ?? '') === 'ganjil')
                                        <span style="background: #E0F2FE; color: #0369A1; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">Ganjil</span>
                                    @else
                                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">Genap</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; color: #475569;">
                                    {{ $term->start_date ? \Carbon\Carbon::parse($term->start_date)->format('d/m/Y') : '01/09/2026' }} - {{ $term->end_date ? \Carbon\Carbon::parse($term->end_date)->format('d/m/Y') : '31/01/2027' }}
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if($term->is_active)
                                        <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #15803D;"></span> Aktif
                                        </span>
                                    @else
                                        <span style="background: #F1F5F9; color: #475569; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #94A3B8;"></span> Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; font-weight: 700; color: #0F172A;">
                                    {{ $termOfferingCount }}
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                        <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'term_id' => $term->id]) }}" 
                                           title="Lihat Kelas Semester Ini"
                                           style="width: 30px; height: 30px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFF; display: flex; align-items: center; justify-content: center; color: #475569; text-decoration: none;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <a href="{{ route('admin.academic-terms.edit', $term) }}" 
                                           title="Edit Semester"
                                           style="width: 30px; height: 30px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFF; display: flex; align-items: center; justify-content: center; color: #475569; text-decoration: none;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #94A3B8;">Belum ada semester terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 0.75rem; text-align: center; background: #FAFAFA; border-top: 1px solid #F1F5F9;">
                <span style="font-size: 12px; font-weight: 600; color: #64748B; background: #FFF; border: 1px solid #CBD5E1; padding: 5px 14px; border-radius: 100px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    Tampilkan Semester Non-Aktif
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            </div>
        </div>

        <!-- SECTION 2: PENAWARAN KELAS TABLE CARD matching TampilanAdmin.jpeg -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #2563EB; margin-bottom: 4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                        <a href="{{ route('admin.master-courses.show', $masterCourse->id) }}" style="color: #2563EB; text-decoration: none;">Kembali ke Daftar Semester</a>
                    </div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                        Penawaran Kelas - {{ $selectedTerm->name ?? '2026/2027 Ganjil Cert' }}
                    </h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Kelas paralel yang ditawarkan pada semester ini.</p>
                </div>
                <a href="{{ route('admin.course-offerings.create', ['master_course_id' => $masterCourse->id]) }}" 
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    + Tambah Penawaran Kelas
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 12px 20px;">Nama Kelas</th>
                            <th style="padding: 12px 20px;">Dosen Pengampu</th>
                            <th style="padding: 12px 20px;">Kuota</th>
                            <th style="padding: 12px 20px;">Threshold</th>
                            <th style="padding: 12px 20px;">Periode Kelas</th>
                            <th style="padding: 12px 20px;">Status</th>
                            <th style="padding: 12px 20px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid #F1F5F9;">
                        @forelse($selectedOfferings as $offering)
                            <tr style="border-bottom: 1px solid #F1F5F9;">
                                <td style="padding: 14px 20px; font-weight: 700; color: #0F172A;">
                                    {{ $offering->section_name ?? 'Kelas Cert A' }}
                                </td>
                                <td style="padding: 14px 20px; color: #334155; font-weight: 500;">
                                    {{ $offering->lecturer->name ?? 'Dr. Budi Santoso' }}
                                </td>
                                <td style="padding: 14px 20px; color: #475569;">
                                    {{ $offering->capacity ?? 30 }} Mhs
                                </td>
                                <td style="padding: 14px 20px; color: #475569; font-weight: 600;">
                                    {{ $offering->certificate_threshold ?? 75 }}
                                </td>
                                <td style="padding: 14px 20px; color: #64748B; font-size: 12px;">
                                    {{ $selectedTerm->start_date ? \Carbon\Carbon::parse($selectedTerm->start_date)->format('d/m/Y') : '08/09/2026' }} - {{ $selectedTerm->end_date ? \Carbon\Carbon::parse($selectedTerm->end_date)->format('d/m/Y') : '22/12/2026' }}
                                </td>
                                <td style="padding: 14px 20px;">
                                    <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                        {{ ucfirst($offering->status ?? 'published') }}
                                    </span>
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                        <a href="{{ route('admin.course-offerings.edit', $offering) }}" 
                                           title="Edit Kelas"
                                           style="width: 30px; height: 30px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFF; display: flex; align-items: center; justify-content: center; color: #475569; text-decoration: none;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.course-offerings.destroy', $offering) }}" method="POST" onsubmit="return confirm('Hapus penawaran kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Kelas"
                                                    style="all: unset; cursor: pointer; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #FCA5A5; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #94A3B8;">Belum ada penawaran kelas untuk semester ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BOTTOM CALLOUT INFO BOX matching TampilanAdmin.jpeg -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 12px;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #2563EB; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; flex-shrink: 0;">
                i
            </div>
            <div>
                <strong style="color: #1E40AF; font-size: 13.5px;">Struktur: Master Course &rarr; Semester &rarr; Penawaran Kelas</strong>
                <p style="color: #1E3A8A; font-size: 12.5px; margin: 2px 0 0 0;">Pastikan semester aktif sebelum membuat penawaran kelas.</p>
            </div>
        </div>

    </div>
</x-app-layout>
