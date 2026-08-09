<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- GERBANG UTAMA MASTER COURSE BANNER CARD matching TampilanAdmin.jpeg -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 300px;">
                <div style="width: 58px; height: 58px; border-radius: 14px; background: #F3E8FF; color: #7E22CE; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800; flex-shrink: 0;">
                    MC-{{ $masterCourse->id }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="font-size: 11px; font-weight: 700; color: #2563EB; background: #EFF6FF; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Gerbang Utama Matkul</span>
                        <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $masterCourse->name }}</h1>
                        <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">{{ $masterCourse->level }}</span>
                    </div>
                    <p style="font-size: 13px; color: #64748B; margin: 6px 0 0 0; line-height: 1.5; max-width: 650px;">
                        {{ $masterCourse->description ?: 'Pusat kendali administrasi mata kuliah induk, penentuan semester, penawaran kelas, serta bank materi & kuis.' }}
                    </p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 1.5rem; border-left: 1px solid #F1F5F9; padding-left: 1.5rem; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Semester</div>
                    <div style="font-size: 20px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $totalSemesters }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Kelas</div>
                    <div style="font-size: 20px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $totalOfferings }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Materi</div>
                    <div style="font-size: 20px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $materials->count() }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Kuis</div>
                    <div style="font-size: 20px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $quizzes->count() }}</div>
                </div>

                <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'settings']) }}" 
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 10px; color: #334155; font-size: 12.5px; font-weight: 600; text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Edit Matkul
                </a>
            </div>
        </div>

        <!-- TAB NAVIGATION GATEWAY CENTER -->
        <div style="display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #E2E8F0; padding-bottom: 2px; overflow-x: auto;">
            <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'hierarchy']) }}" 
               style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'hierarchy' ? '#2563EB' : 'transparent' }}; color: {{ $activeTab === 'hierarchy' ? '#2563EB' : '#64748B' }}; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                Semester & Penawaran Kelas (3NF)
            </a>

            <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'materials']) }}" 
               style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'materials' ? '#2563EB' : 'transparent' }}; color: {{ $activeTab === 'materials' ? '#2563EB' : '#64748B' }}; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Bank Materi Pembelajaran ({{ $materials->count() }})
            </a>

            <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'quizzes']) }}" 
               style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'quizzes' ? '#2563EB' : 'transparent' }}; color: {{ $activeTab === 'quizzes' ? '#2563EB' : '#64748B' }}; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Bank Soal & Kuis ({{ $quizzes->count() }})
            </a>

            <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'settings']) }}" 
               style="padding: 10px 16px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'settings' ? '#2563EB' : 'transparent' }}; color: {{ $activeTab === 'settings' ? '#2563EB' : '#64748B' }}; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Pengaturan Matkul
            </a>
        </div>

        <!-- TAB CONTENT RENDER -->

        @if($activeTab === 'hierarchy')
            <!-- TAB 1: SEMESTER & PENAWARAN KELAS 3NF matching TampilanAdmin.jpeg -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
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
                            <tbody>
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
                                                <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'term_id' => $term->id, 'tab' => 'hierarchy']) }}" 
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
                                <a href="{{ route('admin.master-courses.show', [$masterCourse->id, 'tab' => 'hierarchy']) }}" style="color: #2563EB; text-decoration: none;">Kembali ke Daftar Semester</a>
                            </div>
                            <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                                Penawaran Kelas - {{ $selectedTerm->name ?? '2026/2027 Ganjil Cert' }}
                            </h2>
                            <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Kelas paralel yang ditawarkan pada semester ini.</p>
                        </div>
                        <a href="{{ route('admin.course-offerings.create', ['master_course_id' => $masterCourse->id, 'academic_term_id' => $selectedTerm->id ?? '']) }}" 
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
                            <tbody>
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
        @elseif($activeTab === 'materials')
            <!-- TAB 2: BANK MATERI PEMBELAJARAN -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Bank Materi Pembelajaran - {{ $masterCourse->name }}</h2>
                        <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Seluruh modul dan bahan ajar yang terhubung dengan mata kuliah induk ini.</p>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                        <thead>
                            <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase;">
                                <th style="padding: 12px 20px;">Judul Materi</th>
                                <th style="padding: 12px 20px;">Deskripsi</th>
                                <th style="padding: 12px 20px;">Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materials as $mat)
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 14px 20px; font-weight: 700; color: #0F172A;">
                                        {{ $mat->title }}
                                    </td>
                                    <td style="padding: 14px 20px; color: #64748B;">
                                        {{ Str::limit($mat->description ?? '-', 80) }}
                                    </td>
                                    <td style="padding: 14px 20px; color: #475569;">
                                        {{ $mat->created_at ? $mat->created_at->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 2rem; text-align: center; color: #94A3B8;">Belum ada materi pembelajaran yang dibuat untuk matkul ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($activeTab === 'quizzes')
            <!-- TAB 3: BANK SOAL & KUIS -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Bank Soal & Kuis - {{ $masterCourse->name }}</h2>
                        <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Evaluasi kuis dan ujian yang terikat pada mata kuliah induk ini.</p>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                        <thead>
                            <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase;">
                                <th style="padding: 12px 20px;">Judul Kuis</th>
                                <th style="padding: 12px 20px;">Waktu Durasi</th>
                                <th style="padding: 12px 20px;">Pass Grade</th>
                                <th style="padding: 12px 20px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quizzes as $quiz)
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 14px 20px; font-weight: 700; color: #0F172A;">
                                        {{ $quiz->title }}
                                    </td>
                                    <td style="padding: 14px 20px; color: #475569;">
                                        {{ $quiz->time_limit_minutes ?? 60 }} Menit
                                    </td>
                                    <td style="padding: 14px 20px; color: #475569; font-weight: 600;">
                                        {{ $quiz->passing_score ?? 70 }}
                                    </td>
                                    <td style="padding: 14px 20px;">
                                        <span style="background: #DCFCE7; color: #15803D; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                            Aktif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 2rem; text-align: center; color: #94A3B8;">Belum ada kuis/evaluasi yang dibuat untuk matkul ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($activeTab === 'settings')
            <!-- TAB 4: EDIT ADMINISTRASI MASTER COURSE -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Edit Administrasi Master Course</h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Perbarui data induk mata kuliah langsung dari gerbang administrasi.</p>
                </div>

                <form action="{{ route('admin.master-courses.update', $masterCourse) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Kode Matkul</label>
                            <input type="text" name="code" value="{{ old('code', $masterCourse->code) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nama Mata Kuliah</label>
                            <input type="text" name="name" value="{{ old('name', $masterCourse->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Level Kesulitan</label>
                            <select name="level" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none; background: #FFF;">
                                <option value="Beginner" {{ old('level', $masterCourse->level) === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ old('level', $masterCourse->level) === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ old('level', $masterCourse->level) === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Kategori Matkul</label>
                            <select name="category_id" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none; background: #FFF;">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $masterCourse->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Deskripsi Mata Kuliah</label>
                        <textarea name="description" rows="4" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none;">{{ old('description', $masterCourse->description) }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" style="padding: 10px 24px; background: #2563EB; color: #FFF; font-weight: 700; font-size: 13.5px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                            Simpan Perubahan Matkul
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
