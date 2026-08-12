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
                    <span style="background: #FEF3C7; color: #D97706; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Kompetensi & Portfolio Talent</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Pusat Skill & Tag Sub-Topik</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Kelola kategori keahlian induk dan kelola tag sub-topik penunjang langsung dari satu tempat.
                </p>
            </div>

            <a href="{{ route('admin.skills.create') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Skill Baru
            </a>
        </div>

        <!-- SKILLS LIST CARDS -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse ($mainSkills as $mainSkill)
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; flex: 1; min-width: 280px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; flex-shrink: 0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('admin.skills.show', $mainSkill) }}" style="font-size: 16px; font-weight: 800; color: #0F172A; text-decoration: none;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#0F172A'">
                                        {{ $mainSkill->name }}
                                    </a>
                                    <span style="background: #DCFCE7; color: #15803D; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 100px;">Skill Induk</span>
                                </div>
                                <p style="font-size: 12.5px; color: #64748B; margin: 4px 0 0 0; line-height: 1.4;">
                                    {{ $mainSkill->description ?: 'Belum ada deskripsi untuk skill ini.' }}
                                </p>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('admin.skills.show', $mainSkill) }}" 
                               style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #2563EB; color: #FFFFFF; font-size: 12.5px; font-weight: 700; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                Kelola Skill & Tag
                            </a>

                            <a href="{{ route('admin.skills.edit', $mainSkill) }}" 
                               title="Edit Skill"
                               style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; display: flex; align-items: center; justify-content: center; color: #475569; text-decoration: none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                            </a>

                            <form action="{{ route('admin.skills.destroy', $mainSkill) }}" method="POST" onsubmit="return confirm('Hapus skill {{ $mainSkill->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Hapus Skill"
                                        style="all: unset; cursor: pointer; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #FCA5A5; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TAG PILLS ATTACHED TO THIS SKILL -->
                    <div style="background: #FAFAFA; border: 1px solid #F1F5F9; border-radius: 12px; padding: 10px 14px;">
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">
                            Tag Sub-Topik ({{ $mainSkill->tags->count() }})
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @forelse($mainSkill->tags as $tag)
                                <span style="background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                    {{ $tag->name }}
                                </span>
                            @empty
                                <span style="font-size: 12px; color: #94A3B8; italic;">Belum ada tag. Klik <strong>Kelola Skill & Tag</strong> untuk menambah tag.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 3rem; text-align: center;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">Belum Ada Skill</h3>
                    <p style="font-size: 13px; color: #64748B; margin: 4px 0 1.25rem 0;">Tambahkan skill utama untuk menentukan kompetensi dan tag sub-topik.</p>
                    <a href="{{ route('admin.skills.create') }}" 
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                        Tambah Skill Baru
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>