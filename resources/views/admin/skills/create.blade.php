<x-app-layout>
    <div style="max-width: 720px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- BACK LINK -->
        <div>
            <a href="{{ route('admin.skills.index') }}" 
               style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 700; color: #64748B; text-decoration: none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Daftar Main Skill</span>
            </a>
        </div>

        <!-- FORM CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
                <div style="display: inline-flex; align-items: center; gap: 6px; background: #EFF6FF; color: #1D4ED8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase; margin-bottom: 6px;">
                    Kompetensi Baru
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0;">Tambah Main Skill Baru</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Daftarkan kategori keahlian utama. Setelah disimpan, Anda dapat langsung menambahkan specialty tags di bawahnya.
                </p>
            </div>

            <form action="{{ route('admin.skills.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;">
                        Nama Main Skill <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Software Engineering, Cloud Architecture, Data Analytics..."
                           style="width: 100%; padding: 11px 14px; border: 1px solid #CBD5E1; border-radius: 12px; font-size: 13.5px; font-weight: 600; color: #0F172A; outline: none;">
                    @error('name')
                        <p style="color: #EF4444; font-size: 12px; font-weight: 600; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;">
                        Deskripsi / Ruang Lingkup Keahlian
                    </label>
                    <textarea name="description" rows="4"
                              placeholder="Jelaskan cakupan topik dan kompetensi yang bernaung di bawah keahlian ini..."
                              style="width: 100%; padding: 11px 14px; border: 1px solid #CBD5E1; border-radius: 12px; font-size: 13px; color: #334155; line-height: 1.5; outline: none;">{{ old('description') }}</textarea>
                    @error('description')
                        <p style="color: #EF4444; font-size: 12px; font-weight: 600; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #F1F5F9; padding-top: 1.25rem;">
                    <a href="{{ route('admin.skills.index') }}" 
                       style="padding: 10px 18px; background: #FFFFFF; border: 1px solid #CBD5E1; color: #475569; font-size: 13px; font-weight: 700; border-radius: 12px; text-decoration: none;">
                        Batal
                    </a>

                    <button type="submit" 
                            style="padding: 10px 22px; background: #0F172A; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 12px; border: none; cursor: pointer;">
                        Simpan & Kelola Tag →
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>