<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
        @if(session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 14px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div style="padding: 1rem 1.25rem; background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; border-radius: 14px; font-size: 13.5px; font-weight: 600;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TOP NAVIGATION & HEADER -->
        <div>
            <a href="{{ route('admin.skills.index') }}" 
               style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 700; color: #64748B; text-decoration: none; margin-bottom: 0.75rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Daftar Main Skill</span>
            </a>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 300px;">
                    <div style="width: 52px; height: 52px; border-radius: 14px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #DBEAFE;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <h1 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $skill->name }}</h1>
                            <span style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 100px; text-transform: uppercase;">
                                Main Skill
                            </span>
                        </div>
                        <p style="font-size: 13px; color: #64748B; margin: 6px 0 0 0; line-height: 1.5;">
                            {{ $skill->description ?: 'Belum ada deskripsi untuk main skill ini.' }}
                        </p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1.5rem; border-left: 1px solid #F1F5F9; padding-left: 1.5rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Specialty Tags</div>
                        <div style="font-size: 24px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $skill->tags->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">

            <!-- PANEL 1: EDIT METADATA MAIN SKILL -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem;">
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Informasi Main Skill</h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Perbarui nama dan ruang lingkup keahlian utama ini.</p>
                </div>

                <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;">
                            Nama Main Skill <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $skill->name) }}" required
                               placeholder="Contoh: Software Engineering, Data Science..."
                               style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 12px; font-size: 13px; font-weight: 600; color: #0F172A; outline: none;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;">
                            Deskripsi / Ruang Lingkup Keahlian
                        </label>
                        <textarea name="description" rows="4"
                                  placeholder="Jelaskan cakupan topik dan kompetensi yang bernaung di bawah keahlian ini..."
                                  style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 12px; font-size: 13px; color: #334155; line-height: 1.5; outline: none;">{{ old('description', $skill->description) }}</textarea>
                    </div>

                    <button type="submit" 
                            style="padding: 10px 20px; background: #0F172A; color: #FFFFFF; font-weight: 700; font-size: 13px; border-radius: 12px; border: none; cursor: pointer; transition: background 0.2s;">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- PANEL 2: MANAJEMEN SPECIALTY TAGS -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Specialty Tags (Sub-Topik)</h2>
                            <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Tag spesifik yang otomatis terhubung ke {{ $skill->name }}.</p>
                        </div>
                        <span style="font-size: 11px; font-weight: 700; color: #4338CA; background: #EEF2FF; padding: 3px 10px; border-radius: 100px;">
                            {{ $skill->tags->count() }} Terdaftar
                        </span>
                    </div>
                </div>

                <!-- FORM TAMBAH TAG BARU -->
                <form action="{{ route('admin.tags.store') }}" method="POST" style="display: flex; gap: 8px; margin-bottom: 1.25rem;">
                    @csrf
                    <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                    <input type="text" name="name" placeholder="Tambah Tag baru (contoh: REST API, Docker)..." required 
                           style="flex: 1; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 12px; font-size: 13px; outline: none;">
                    <button type="submit" 
                            style="padding: 9px 18px; background: #2563EB; color: #FFF; font-size: 13px; font-weight: 700; border-radius: 12px; border: none; cursor: pointer; white-space: nowrap;">
                        + Tambah
                    </button>
                </form>

                <!-- DAFTAR TAG PILLS -->
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @forelse($skill->tags as $tag)
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; color: #334155; font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 8px;">
                            <span>#{{ $tag->name }}</span>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Hapus tag {{ $tag->name }}?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Tag" 
                                        style="all: unset; cursor: pointer; color: #94A3B8; font-size: 14px; font-weight: 800; line-height: 1; transition: color 0.15s;" 
                                        onmouseover="this.style.color='#EF4444'" 
                                        onmouseout="this.style.color='#94A3B8'">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @empty
                        <div style="padding: 2rem 1.5rem; text-align: center; color: #94A3B8; width: 100%; border: 2px dashed #E2E8F0; border-radius: 14px; font-size: 13px;">
                            Belum ada specialty tag di bawah Main Skill ini. Gunakan form di atas untuk menambahkan tag baru.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
