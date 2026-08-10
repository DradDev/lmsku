<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- ALERTS -->
        @if(session('success'))
            <div style="padding: 1rem 1.25rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div style="padding: 1rem 1.25rem; background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- HERO HEADER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-text-transform: uppercase;">Pengelolaan Hak Akses & Peran</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">Manajemen Pengguna System</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Kelola akun Dosen, Mahasiswa, status verifikasi pendaftaran, dan hak akses aplikasi.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <div style="background: #FAFAFA; border: 1px solid #E2E8F0; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #64748B; text-transform: uppercase;">Total User</div>
                    <div style="font-size: 16px; font-weight: 800; color: #0F172A;">{{ $users->count() }}</div>
                </div>

                <div style="background: #DCFCE7; border: 1px solid #BBF7D0; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #15803D; text-transform: uppercase;">Mahasiswa</div>
                    <div style="font-size: 16px; font-weight: 800; color: #166534;">{{ $users->where('role', 'student')->count() }}</div>
                </div>

                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #1D4ED8; text-transform: uppercase;">Dosen Pengampu</div>
                    <div style="font-size: 16px; font-weight: 800; color: #1E40AF;">{{ $users->where('role', 'lecturer')->count() }}</div>
                </div>

                <div style="background: #F3E8FF; border: 1px solid #E9D5FF; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #6B21A8; text-transform: uppercase;">🏢 Mitra Vendor</div>
                    <div style="font-size: 16px; font-weight: 800; color: #581C87;">{{ $users->where('role', 'vendor')->count() }}</div>
                </div>

                <div style="background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #B45309; text-transform: uppercase;">Pending</div>
                    <div style="font-size: 16px; font-weight: 800; color: #92400E;">{{ $users->where('registration_status', 'pending')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- USERS TABLE CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background: #FAFAFA; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 14px 20px;">Pengguna</th>
                            <th style="padding: 14px 20px;">Email</th>
                            <th style="padding: 14px 20px;">Peran (Role)</th>
                            <th style="padding: 14px 20px;">Status Verifikasi</th>
                            <th style="padding: 14px 20px;">Tanggal Daftar</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi Management</th>
                        </tr>
                    </thead>

                    <tbody style="divide-y: 1px solid #F1F5F9;">
                        @forelse($users as $user)
                            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                                <!-- PENGGUNA AVATAR & NAME -->
                                <td style="padding: 16px 20px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        @php
                                            $avatarBg = match($user->role) {
                                                'admin' => 'linear-gradient(135deg, #7E22CE, #A855F7)',
                                                'lecturer' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                                                'vendor' => 'linear-gradient(135deg, #6B21A8, #C084FC)',
                                                default => 'linear-gradient(135deg, #047857, #10B981)',
                                            };
                                        @endphp
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background: {{ $avatarBg }}; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; flex-shrink: 0;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 800; color: #0F172A;">{{ $user->name }}</div>
                                            <div style="font-size: 11.5px; color: #64748B;">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- EMAIL -->
                                <td style="padding: 16px 20px; color: #334155; font-weight: 500;">
                                    {{ $user->email }}
                                </td>

                                <!-- ROLE BADGE -->
                                <td style="padding: 16px 20px;">
                                    @php
                                        $roleBadges = match($user->role) {
                                            'admin' => ['bg' => '#F3E8FF', 'color' => '#7E22CE', 'label' => 'Administrator'],
                                            'lecturer' => ['bg' => '#EFF6FF', 'color' => '#2563EB', 'label' => 'Dosen Pengampu'],
                                            'vendor' => ['bg' => '#F3E8FF', 'color' => '#6B21A8', 'label' => '🏢 Mitra Vendor'],
                                            default => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Mahasiswa'],
                                        };
                                    @endphp
                                    <span style="background: {{ $roleBadges['bg'] }}; color: {{ $roleBadges['color'] }}; font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        {{ $roleBadges['label'] }}
                                    </span>
                                </td>

                                <!-- STATUS BADGE -->
                                <td style="padding: 16px 20px;">
                                    @php
                                        $statusBadges = match($user->registration_status) {
                                            'approved' => ['bg' => '#ECFDF5', 'color' => '#047857', 'dot' => '#059669', 'label' => 'Disetujui'],
                                            'rejected' => ['bg' => '#FFE4E6', 'color' => '#BE123C', 'dot' => '#E11D48', 'label' => 'Ditolak'],
                                            default => ['bg' => '#FEF3C7', 'color' => '#B45309', 'dot' => '#D97706', 'label' => 'Menunggu Approval'],
                                        };
                                    @endphp
                                    <span style="background: {{ $statusBadges['bg'] }}; color: {{ $statusBadges['color'] }}; font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 6px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $statusBadges['dot'] }};"></span>
                                        {{ $statusBadges['label'] }}
                                    </span>
                                    @if($user->registration_note)
                                        <p style="font-size: 11px; color: #94A3B8; margin: 3px 0 0 0;">{{ Str::limit($user->registration_note, 35) }}</p>
                                    @endif
                                </td>

                                <!-- CREATED DATE -->
                                <td style="padding: 16px 20px; color: #64748B; font-size: 12.5px;">
                                    {{ optional($user->created_at)->format('d M Y') ?? '-' }}
                                </td>

                                <!-- ACTIONS -->
                                <td style="padding: 16px 20px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        @if($user->registration_status === 'pending')
                                            <!-- APPROVE BUTTON -->
                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #10B981; color: #FFF; font-size: 12px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                                    Setujui
                                                </button>
                                            </form>

                                            <!-- REJECT BUTTON -->
                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #F59E0B; color: #FFF; font-size: 12px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        <!-- VIEW DETAIL BUTTON -->
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #2563EB; color: #FFFFFF; font-size: 12px; font-weight: 700; border-radius: 8px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Detail
                                        </a>

                                        <!-- CHANGE ROLE BUTTON -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           title="Ubah Peran (Role)"
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #475569; text-decoration: none;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>

                                        <!-- DELETE BUTTON -->
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        title="Hapus User"
                                                        style="all: unset; cursor: pointer; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #FCA5A5; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 3rem; text-align: center; color: #94A3B8;">
                                    Belum ada pengguna terdaftar dalam sistem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
