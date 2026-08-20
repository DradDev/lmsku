<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 900px; margin: 0 auto; padding-bottom: 2rem;">

        <!-- BACK LINK & BREADCRUMB -->
        <div>
            <a href="{{ route('admin.users.index') }}"
               style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #64748B; text-decoration: none; margin-bottom: 0.5rem; transition: color 0.15s ease;"
               onmouseover="this.style.color='#0F172A'" onmouseout="this.style.color='#64748B'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Manajemen Pengguna
            </a>
        </div>

        <!-- USER PROFILE HERO CARD -->
        @php
            $avatarBg = match($user->role) {
                'admin' => 'linear-gradient(135deg, #7E22CE, #A855F7)',
                'lecturer' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                'vendor' => 'linear-gradient(135deg, #6B21A8, #C084FC)',
                default => 'linear-gradient(135deg, #047857, #10B981)',
            };
            $roleBadges = match($user->role) {
                'admin' => ['bg' => '#F3E8FF', 'color' => '#7E22CE', 'label' => 'Administrator System'],
                'lecturer' => ['bg' => '#EFF6FF', 'color' => '#2563EB', 'label' => 'Dosen Pengampu'],
                'vendor' => ['bg' => '#F3E8FF', 'color' => '#6B21A8', 'label' => 'Mitra Industri / Vendor'],
                default => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Mahasiswa'],
            };
            $statusBadges = match($user->registration_status) {
                'approved' => ['bg' => '#ECFDF5', 'color' => '#047857', 'dot' => '#059669', 'label' => 'Disetujui (Aktif)'],
                'rejected' => ['bg' => '#FFE4E6', 'color' => '#BE123C', 'dot' => '#E11D48', 'label' => 'Registrasi Ditolak'],
                default => ['bg' => '#FEF3C7', 'color' => '#B45309', 'dot' => '#D97706', 'label' => 'Menunggu Approval Admin'],
            };
        @endphp

        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; padding-bottom: 1.75rem; border-bottom: 1px solid #F1F5F9;">
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #E2E8F0;">
                    @else
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: {{ $avatarBg }}; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                            <h1 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">{{ $user->name }}</h1>
                            <span style="background: {{ $roleBadges['bg'] }}; color: {{ $roleBadges['color'] }}; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                {{ $roleBadges['label'] }}
                            </span>
                        </div>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0;">
                            {{ $user->email }} &bull; User ID: <span style="font-family: monospace; font-weight: 700; color: #334155;">#{{ $user->id }}</span>
                        </p>
                    </div>
                </div>

                <div>
                    <span style="background: {{ $statusBadges['bg'] }}; color: {{ $statusBadges['color'] }}; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 100px; display: inline-flex; align-items: center; gap: 7px; border: 1px solid rgba(0,0,0,0.05);">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $statusBadges['dot'] }};"></span>
                        {{ $statusBadges['label'] }}
                    </span>
                </div>
            </div>

            <!-- DETAIL INFORMATION GRID -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-top: 1.75rem;">
                <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px; margin-bottom: 4px;">Nama Lengkap</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $user->name }}</div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px; margin-bottom: 4px;">Alamat Email</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $user->email }}</div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px; margin-bottom: 4px;">Peran Akses (*Role*)</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ ucfirst($user->role) }}</div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px; margin-bottom: 4px;">Tanggal Registrasi</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ optional($user->created_at)->format('d M Y, H:i') ?? '-' }}</div>
                </div>

                @if($user->peminatan)
                    <div style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 1rem; grid-column: 1 / -1;">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px; margin-bottom: 4px;">Peminatan / Minat Bidang</div>
                        <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $user->peminatan }}</div>
                    </div>
                @endif

                @if($user->registration_note)
                    <div style="background: #FFFBEB; border: 1px solid #FEF3C7; border-radius: 12px; padding: 1rem; grid-column: 1 / -1;">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #B45309; letter-spacing: 0.5px; margin-bottom: 4px;">Catatan Verifikasi Admin</div>
                        <div style="font-size: 13.5px; color: #92400E;">{{ $user->registration_note }}</div>
                    </div>
                @endif
            </div>

            <!-- VENDOR INSTITUTION PROFILE SECTION -->
            @if($user->role === 'vendor')
                <div style="margin-top: 1.5rem; background: #FAF5FF; border: 1px solid #E9D5FF; border-radius: 14px; padding: 1.25rem;">
                    <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #7E22CE; letter-spacing: 0.5px; margin-bottom: 8px;">
                        Profil Kemitraan Vendor / Industri
                    </div>
                    @if($user->institution)
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <div style="font-size: 15px; font-weight: 800; color: #0F172A;">{{ $user->institution->name }}</div>
                                <div style="font-size: 12.5px; color: #64748B; margin-top: 2px;">
                                    Tipe: Badan Usaha (PT / CV / Lembaga Mitra) &bull; Slug: {{ $user->institution->slug }}
                                </div>
                            </div>
                            <span style="background: #7E22CE; color: #FFFFFF; font-size: 12px; font-family: monospace; font-weight: 700; padding: 4px 12px; border-radius: 8px;">
                                Kode Instansi: {{ $user->institution->code }}
                            </span>
                        </div>
                    @elseif($user->institution_type === 'individual')
                        <div>
                            <div style="font-size: 14px; font-weight: 700; color: #0F172A;">Praktisi Perorangan / Independen</div>
                            <div style="font-size: 12.5px; color: #64748B; margin-top: 2px;">
                                Kredensial sertifikat diterbitkan dalam format IND (Independent Specialist).
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- ACTION BUTTONS TOOLBAR -->
            <div style="margin-top: 2rem; pt-4; border-top: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ route('admin.users.index') }}"
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none; transition: background 0.15s ease;"
                       onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                        Daftar Pengguna
                    </a>

                    @if($user->registration_status === 'pending')
                        <!-- APPROVE ACTION -->
                        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" 
                                    style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: #10B981; border: none; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; cursor: pointer; box-shadow: 0 2px 6px rgba(16,185,129,0.25);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                Setujui Akun
                            </button>
                        </form>

                        <!-- REJECT ACTION -->
                        <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" 
                                    style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: #F59E0B; border: none; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; cursor: pointer; box-shadow: 0 2px 6px rgba(245,158,11,0.25);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Tolak Registrasi
                            </button>
                        </form>
                    @endif
                </div>

                @if($user->id !== auth()->id())
                    <!-- DELETE USER ACTION -->
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; font-size: 13px; font-weight: 700; border-radius: 10px; cursor: pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            Hapus Akun
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
