<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
        @if (session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 14px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- HERO HEADER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #EFF6FF; color: #1D4ED8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">
                        Kompetensi & Portfolio Talent
                    </span>
                </div>
                <h1 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Pusat Main Skill & Specialty Tags</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Kelola kategori keahlian utama (Main Skill) dan specialty tags sub-topik penunjang di bawahnya.
                </p>
            </div>

            <a href="{{ route('admin.skills.create') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #0F172A; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 12px; text-decoration: none; transition: background 0.2s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                <span>Tambah Main Skill</span>
            </a>
        </div>

        <!-- SKILLS LIST CARDS -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse ($skills as $skill)
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; flex: 1; min-width: 280px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; flex-shrink: 0; border: 1px solid #DBEAFE;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('admin.skills.show', $skill) }}" style="font-size: 16px; font-weight: 800; color: #0F172A; text-decoration: none;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#0F172A'">
                                        {{ $skill->name }}
                                    </a>
                                    <span style="background: #F1F5F9; color: #475569; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 100px; text-transform: uppercase;">
                                        Main Skill
                                    </span>
                                </div>
                                <p style="font-size: 12.5px; color: #64748B; margin: 4px 0 0 0; line-height: 1.4;">
                                    {{ $skill->description ?: 'Belum ada deskripsi untuk main skill ini.' }}
                                </p>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('admin.skills.show', $skill) }}" 
                               style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #0F172A; color: #FFFFFF; font-size: 12.5px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                <span>Kelola Tag & Detail</span>
                            </a>

                            <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Hapus Main Skill {{ $skill->name }} beserta seluruh tag di bawahnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Hapus Main Skill"
                                        style="all: unset; cursor: pointer; width: 34px; height: 34px; border-radius: 10px; border: 1px solid #FECACA; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TAG PILLS ATTACHED TO THIS SKILL -->
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 12px 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">
                                Specialty Tags Terdaftar ({{ $skill->tags->count() }})
                            </div>
                            <a href="{{ route('admin.skills.show', $skill) }}" style="font-size: 11px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                + Tambah Tag →
                            </a>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @forelse($skill->tags as $tag)
                                <span style="background: #FFFFFF; color: #334155; border: 1px solid #CBD5E1; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                    #{{ $tag->name }}
                                </span>
                            @empty
                                <span style="font-size: 12px; color: #94A3B8; font-style: italic;">Belum ada tag. Klik <strong>Kelola Tag & Detail</strong> untuk menambahkan tag.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 3rem; text-align: center;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">Belum Ada Main Skill</h3>
                    <p style="font-size: 13px; color: #64748B; margin: 4px 0 1.25rem 0;">Tambahkan Main Skill pertama untuk menentukan kompetensi dan specialty tag sub-topik.</p>
                    <a href="{{ route('admin.skills.create') }}" 
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #0F172A; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 12px; text-decoration: none;">
                        Tambah Main Skill
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>