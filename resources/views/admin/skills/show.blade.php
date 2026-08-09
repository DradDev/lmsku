<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
        @if(session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- TOP BANNER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 300px;">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; flex-shrink: 0;">
                    ⚡
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $skill->name }}</h1>
                        @if($skill->parent)
                            <span style="background: #EEF2FF; color: #4338CA; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">Sub-skill dari {{ $skill->parent->name }}</span>
                        @else
                            <span style="background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">Skill Induk</span>
                        @endif
                    </div>
                    <p style="font-size: 13px; color: #64748B; margin: 6px 0 0 0; line-height: 1.5; max-width: 650px;">
                        {{ $skill->description ?: 'Pusat pengelolaan kategori keahlian dan tag sub-topik penunjang talent matching.' }}
                    </p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 2rem; border-left: 1px solid #F1F5F9; padding-left: 1.5rem;">
                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Total Tags</div>
                    <div style="font-size: 22px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $skill->tags->count() }}</div>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Sub-Skills</div>
                    <div style="font-size: 22px; font-weight: 800; color: #0F172A; margin-top: 2px;">{{ $skill->children->count() }}</div>
                </div>

                <a href="#edit-skill-section" 
                   onclick="document.getElementById('edit-skill-section').scrollIntoView({behavior: 'smooth'}); return false;"
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 10px; color: #334155; font-size: 13px; font-weight: 600; text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Edit Skill
                </a>
            </div>
        </div>

        <!-- SECTION 1: MANAJEMEN TAG SUB-TOPIK CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                <div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Tag Sub-Topik (Milik Skill Ini)</h2>
                    <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Tag spesifik yang dinaungi oleh skill {{ $skill->name }}.</p>
                </div>

                <!-- FORM TAMBAH TAG BARU -->
                <form action="{{ route('admin.tags.store') }}" method="POST" style="display: flex; align-items: center; gap: 8px;">
                    @csrf
                    <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                    <input type="text" name="name" placeholder="Nama Tag baru (contoh: REST API)..." required style="padding: 8px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; min-width: 240px;">
                    <button type="submit" style="padding: 8px 16px; background: #2563EB; color: #FFF; font-size: 13px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                        + Tambah Tag
                    </button>
                </form>
            </div>

            <!-- DAFTAR TAG PILLS -->
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @forelse($skill->tags as $tag)
                    <div style="background: #EEF2FF; border: 1px solid #C7D2FE; color: #3730A3; font-size: 13px; font-weight: 700; padding: 6px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 8px;">
                        <span>🏷️ {{ $tag->name }}</span>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Hapus tag {{ $tag->name }}?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Tag" style="all: unset; cursor: pointer; color: #991B1B; font-size: 14px; font-weight: 800; line-height: 1; opacity: 0.7;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                                &times;
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="padding: 1.5rem; text-align: center; color: #94A3B8; width: 100%; border: 2px dashed #E2E8F0; border-radius: 12px;">
                        Belum ada Tag terdaftar di bawah Skill ini. Gunakan form di atas untuk menambahkan Tag.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SECTION 2: EDIT SKILL METADATA -->
        <div id="edit-skill-section" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem;">
                <h2 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Edit Data Skill</h2>
                <p style="font-size: 12.5px; color: #64748B; margin: 3px 0 0 0;">Perbarui nama, hirarki induk, atau deskripsi skill ini.</p>
            </div>

            <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nama Skill</label>
                        <input type="text" name="name" value="{{ old('name', $skill->name) }}" required style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Parent Skill (Opsional)</label>
                        <select name="parent_id" style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; background: #FFF;">
                            <option value="">-- Tanpa Parent (Skill Induk) --</option>
                            @foreach(\App\Models\Skill::whereNull('parent_id')->where('id', '!=', $skill->id)->get() as $pSkill)
                                <option value="{{ $pSkill->id }}" {{ old('parent_id', $skill->parent_id) == $pSkill->id ? 'selected' : '' }}>{{ $pSkill->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Deskripsi Skill</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 9px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none;">{{ old('description', $skill->description) }}</textarea>
                </div>

                <button type="submit" style="padding: 9px 22px; background: #2563EB; color: #FFF; font-weight: 700; font-size: 13px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    Simpan Perubahan Skill
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
