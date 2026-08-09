<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
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

        <!-- TOP BANNER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 300px;">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: #F3E8FF; color: #7E22CE; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800; flex-shrink: 0;">
                    MC-{{ $masterCourse->id }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $masterCourse->name }}</h1>
                        <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">{{ $masterCourse->level }}</span>
                    </div>
                    <p style="font-size: 13px; color: #64748B; margin: 6px 0 0 0; line-height: 1.5; max-width: 650px;">
                        {{ $masterCourse->description ?: 'Pusat kendali administrasi mata kuliah induk, penentuan semester, penawaran kelas paralel, dan kompetensi target.' }}
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

                <a href="#edit-master-course" 
                   onclick="document.getElementById('edit-master-course-section').scrollIntoView({behavior: 'smooth'}); return false;"
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 10px; color: #334155; font-size: 13px; font-weight: 600; text-decoration: none;">
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
            <span style="color: #94A3B8;">&rsaquo;</span>
            <span style="color: #64748B;">Skill & Tag Target</span>
        </div>

        <!-- SECTION 1: DAFTAR SEMESTER TABLE CARD -->
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
        </div>

        <!-- SECTION 2: PENAWARAN KELAS TABLE CARD -->
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
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Buka dan kelola kelas paralel (Plotting Dosen, Kuota Mhs, & Threshold Awal Sertifikat).</p>
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
                            <th style="padding: 12px 20px;">Threshold Sertifikat</th>
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
                                <td style="padding: 14px 20px;">
                                    <span style="background: #EEF2FF; color: #4338CA; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 6px;">
                                        Min Score: {{ $offering->certificate_threshold ?? 75 }}
                                    </span>
                                    <span style="display: block; font-size: 11px; color: #64748B; margin-top: 2px;">(Dapat dikelola oleh Dosen)</span>
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
                                <td colspan="6" style="padding: 2rem; text-align: center; color: #94A3B8;">Belum ada penawaran kelas untuk semester ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 3: ⚡ TARGET SKILL & TAG KOMPETENSI CARD (GROUPING TERPISAH PER SKILL INDUK) -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">⚡ Skill & Tag Target Kompetensi Matkul</h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Centang Skill Induk di sebelah kiri. Daftar Tag Sub-Topik akan ditampilkan secara <strong>terpisah dan terkelompok per Skill</strong> di sebelah kanan.</p>
                </div>
            </div>

            <!-- ACTIVE SKILL & TAG PILLS DISPLAY -->
            <div style="margin-bottom: 1.5rem; background: #FAFAFA; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem;">
                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 8px;">Target Skill & Tag Aktif Saat Ini:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @forelse($masterCourse->skills as $s)
                        <span style="background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 100px;">
                            ⚡ {{ $s->name }}
                        </span>
                    @empty
                    @endforelse

                    @forelse($masterCourse->tags as $t)
                        <span style="background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 100px;">
                            🏷️ {{ $t->name }}
                        </span>
                    @empty
                    @endforelse

                    @if($masterCourse->skills->isEmpty() && $masterCourse->tags->isEmpty())
                        <span style="font-size: 12.5px; color: #94A3B8; italic;">Belum ada Skill atau Tag yang dihubungkan ke Master Course ini. Gunakan form interaktif di bawah untuk menentukan target kompetensi.</span>
                    @endif
                </div>
            </div>

            <!-- FORM SYNC SKILL & TAG DINAMIS DI-GROUP PER SKILL -->
            <form action="{{ route('admin.master-courses.competencies.sync', $masterCourse) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: minmax(280px, 1fr) minmax(320px, 1.5fr); gap: 1.5rem; margin-bottom: 1.25rem;">
                    
                    <!-- LANGKAH 1: PILIH SKILL INDUK (BISA PILIH MULTIPLE SKILL) -->
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">1. Pilih Skill Induk (Bisa Multiple):</label>
                            <span id="skill-count-badge" style="font-size: 11px; font-weight: 700; color: #2563EB; background: #EFF6FF; padding: 2px 8px; border-radius: 100px;">0 Terpilih</span>
                        </div>
                        <div style="max-height: 380px; overflow-y: auto; border: 1px solid #CBD5E1; border-radius: 10px; padding: 10px; background: #FFF; display: flex; flex-direction: column; gap: 8px;">
                            @foreach($allSkills as $sk)
                                <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #0F172A; cursor: pointer; padding: 6px 8px; border-radius: 8px; border: 1px solid #F1F5F9; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                                    <input type="checkbox" 
                                           name="skill_ids[]" 
                                           value="{{ $sk->id }}" 
                                           class="skill-dynamic-checkbox" 
                                           data-skill-id="{{ $sk->id }}"
                                           {{ $masterCourse->skills->contains($sk->id) ? 'checked' : '' }}>
                                    <span style="font-weight: 700;">⚡ {{ $sk->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- LANGKAH 2: DAFTAR TAG SUB-TOPIK DI-GROUP SECARA TERPISAH PER SKILL INDUK -->
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">2. Tag Sub-Topik (Terpisah per Kelompok Skill):</label>
                            <span id="tag-count-badge" style="font-size: 11px; font-weight: 700; color: #4338CA; background: #EEF2FF; padding: 2px 8px; border-radius: 100px;">0 Terpilih</span>
                        </div>
                        
                        <!-- PESAN JIKA BELUM ADA SKILL DIPILIH -->
                        <div id="no-skill-selected-notice" style="display: none; padding: 2.5rem 1rem; text-align: center; background: #FAFAFA; border: 2px dashed #CBD5E1; border-radius: 10px; color: #64748B; font-size: 13px;">
                            📌 Centang minimal 1 Skill Induk di sebelah kiri untuk menampilkan kelompok Tag Sub-Topik yang sesuai.
                        </div>

                        <!-- CONTAINER KELOMPOK TAG TERPISAH PER SKILL INDUK -->
                        <div id="tags-grouped-wrapper" style="max-height: 380px; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($allSkills as $sk)
                                @php
                                    $skillTags = $allTags->where('skill_id', $sk->id);
                                @endphp
                                <div class="skill-tag-group-card" 
                                     data-parent-skill-id="{{ $sk->id }}"
                                     style="background: #FAFAFA; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; display: none;">
                                    
                                    <!-- HEADER SKILL GROUP -->
                                    <div style="background: #F1F5F9; padding: 8px 14px; border-bottom: 1px solid #E2E8F0; font-size: 12.5px; font-weight: 800; color: #1E293B; display: flex; align-items: center; justify-content: space-between;">
                                        <span>⚡ KELOMPOK TAG: {{ strtoupper($sk->name) }}</span>
                                        <span style="font-size: 11px; font-weight: 600; color: #64748B;">({{ $skillTags->count() }} Tag)</span>
                                    </div>

                                    <!-- GRID TAG CHECKBOXES UNTUK SKILL INI -->
                                    <div style="padding: 10px 14px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; background: #FFF;">
                                        @forelse($skillTags as $tg)
                                            <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; cursor: pointer; padding: 4px 6px; border-radius: 6px; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                                <input type="checkbox" 
                                                       name="tag_ids[]" 
                                                       value="{{ $tg->id }}" 
                                                       class="tag-dynamic-checkbox"
                                                       data-parent-skill-id="{{ $sk->id }}"
                                                       {{ $masterCourse->tags->contains($tg->id) ? 'checked' : '' }}>
                                                <span style="font-weight: 600;">🏷️ {{ $tg->name }}</span>
                                            </label>
                                        @empty
                                            <span style="font-size: 12px; color: #94A3B8; italic; grid-column: 1 / -1;">Belum ada tag terdaftar di bawah skill ini.</span>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                <button type="submit" style="padding: 9px 22px; background: #2563EB; color: #FFF; font-weight: 700; font-size: 13px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    Simpan Skill & Tag Kompetensi
                </button>
            </form>
        </div>

        <!-- SECTION 4: EDIT MASTER COURSE FORM INLINE -->
        <div id="edit-master-course-section" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem;">
                <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Edit Metadata Master Course</h2>
                <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Perbarui informasi dasar mata kuliah induk ini secara langsung.</p>
            </div>

            <form action="{{ route('admin.master-courses.update', $masterCourse) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Kode Matkul</label>
                        <input type="text" name="code" value="{{ old('code', $masterCourse->code) }}" required style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nama Mata Kuliah</label>
                        <input type="text" name="name" value="{{ old('name', $masterCourse->name) }}" required style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Level Kesulitan</label>
                        <select name="level" required style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; background: #FFF;">
                            <option value="Beginner" {{ old('level', $masterCourse->level) === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('level', $masterCourse->level) === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('level', $masterCourse->level) === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Kategori Matkul</label>
                        <select name="category_id" style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; background: #FFF;">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $masterCourse->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Deskripsi Mata Kuliah</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none;">{{ old('description', $masterCourse->description) }}</textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="padding: 9px 22px; background: #2563EB; color: #FFF; font-weight: 700; font-size: 13px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                        Simpan Perubahan Matkul
                    </button>
                </div>
            </form>
        </div>

        <!-- BOTTOM CALLOUT INFO BOX -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 12px;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #2563EB; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; flex-shrink: 0;">
                i
            </div>
            <div>
                <strong style="color: #1E40AF; font-size: 13.5px;">Struktur: Master Course &rarr; Semester &rarr; Penawaran Kelas</strong>
                <p style="color: #1E3A8A; font-size: 12.5px; margin: 2px 0 0 0;">Setiap Skill Induk yang dicentang akan menampilkan kelompok Tag Sub-Topik terkait secara terpisah.</p>
            </div>
        </div>

    </div>

    <!-- SCRIPT FILTERING & GROUPING DINAMIS SKILL TO TAGS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skillCheckboxes = document.querySelectorAll('.skill-dynamic-checkbox');
            const tagGroupCards = document.querySelectorAll('.skill-tag-group-card');
            const tagsWrapper = document.getElementById('tags-grouped-wrapper');
            const noSkillNotice = document.getElementById('no-skill-selected-notice');
            const skillBadge = document.getElementById('skill-count-badge');
            const tagBadge = document.getElementById('tag-count-badge');

            function updateGroupedDynamicTags() {
                const selectedSkillIds = Array.from(skillCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.getAttribute('data-skill-id'));

                skillBadge.textContent = selectedSkillIds.length + ' Skill Terpilih';

                if (selectedSkillIds.length === 0) {
                    noSkillNotice.style.display = 'block';
                    tagsWrapper.style.display = 'none';
                    tagBadge.textContent = '0 Tag Terpilih';
                } else {
                    noSkillNotice.style.display = 'none';
                    tagsWrapper.style.display = 'flex';

                    let selectedTagCount = 0;

                    tagGroupCards.forEach(card => {
                        const parentSkillId = card.getAttribute('data-parent-skill-id');
                        const tagCbs = card.querySelectorAll('.tag-dynamic-checkbox');

                        if (selectedSkillIds.includes(parentSkillId)) {
                            card.style.display = 'block';
                            tagCbs.forEach(cb => {
                                if (cb.checked) selectedTagCount++;
                            });
                        } else {
                            card.style.display = 'none';
                            // Uncheck hidden tags
                            tagCbs.forEach(cb => {
                                cb.checked = false;
                            });
                        }
                    });

                    tagBadge.textContent = selectedTagCount + ' Tag Terpilih';
                }
            }

            // Bind change listeners to skill checkboxes
            skillCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateGroupedDynamicTags);
            });

            // Bind change listeners to tag checkboxes
            document.querySelectorAll('.tag-dynamic-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const checkedTags = document.querySelectorAll('.tag-dynamic-checkbox:checked');
                    tagBadge.textContent = checkedTags.length + ' Tag Terpilih';
                });
            });

            // Run initial update on page load
            updateGroupedDynamicTags();
        });
    </script>
</x-app-layout>
