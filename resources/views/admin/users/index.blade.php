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
                    <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Pengelolaan Hak Akses & Peran</span>
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
                    <div style="font-size: 10px; font-weight: 700; color: #6B21A8; text-transform: uppercase;">Mitra Vendor</div>
                    <div style="font-size: 16px; font-weight: 800; color: #581C87;">{{ $users->where('role', 'vendor')->count() }}</div>
                </div>

                <div style="background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 12px; padding: 8px 14px; text-align: center;">
                    <div style="font-size: 10px; font-weight: 700; color: #B45309; text-transform: uppercase;">Pending</div>
                    <div style="font-size: 16px; font-weight: 800; color: #92400E;">{{ $users->where('registration_status', 'pending')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & ROLE FILTER CONTROLS -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                <!-- ROLE CATEGORY TABS -->
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;" id="role-tabs-container">
                    <button type="button" 
                            data-role-target="all"
                            onclick="setRoleFilter('all')"
                            class="role-filter-tab active-role-btn"
                            style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; background: #0F172A; color: #FFFFFF; transition: all 0.15s ease;">
                        <span>Semua</span>
                        <span class="role-tab-badge" style="background: rgba(255,255,255,0.25); color: #FFFFFF; padding: 1px 8px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                            {{ $users->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            data-role-target="student"
                            onclick="setRoleFilter('student')"
                            class="role-filter-tab"
                            style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                        <span>Mahasiswa</span>
                        <span class="role-tab-badge" style="background: #DCFCE7; color: #166534; padding: 1px 8px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                            {{ $users->where('role', 'student')->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            data-role-target="lecturer"
                            onclick="setRoleFilter('lecturer')"
                            class="role-filter-tab"
                            style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                        <span>Dosen Pengampu</span>
                        <span class="role-tab-badge" style="background: #EFF6FF; color: #1D4ED8; padding: 1px 8px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                            {{ $users->where('role', 'lecturer')->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            data-role-target="vendor"
                            onclick="setRoleFilter('vendor')"
                            class="role-filter-tab"
                            style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                        <span>Mitra Vendor</span>
                        <span class="role-tab-badge" style="background: #F3E8FF; color: #6B21A8; padding: 1px 8px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                            {{ $users->where('role', 'vendor')->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            data-role-target="admin"
                            onclick="setRoleFilter('admin')"
                            class="role-filter-tab"
                            style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                        <span>Administrator</span>
                        <span class="role-tab-badge" style="background: #E2E8F0; color: #334155; padding: 1px 8px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                            {{ $users->where('role', 'admin')->count() }}
                        </span>
                    </button>
                </div>

                <!-- SEARCH BAR INPUT -->
                <div style="position: relative; min-width: 280px; flex-grow: 1; max-width: 440px;">
                    <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; pointer-events: none; display: flex; align-items: center;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <input type="text" 
                           id="user-search-input"
                           autocomplete="off"
                           placeholder="Cari nama, email, ID, atau institusi..."
                           style="width: 100%; box-sizing: border-box; padding: 9px 38px 9px 40px; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 13px; color: #0F172A; outline: none; background: #F8FAFC; transition: all 0.15s ease;">
                    <button type="button" 
                            id="clear-search-btn"
                            title="Hapus pencarian"
                            onclick="clearSearchInput()"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); all: unset; cursor: pointer; color: #94A3B8; display: none; padding: 2px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- RESULT COUNT STATUS BADGE -->
            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #64748B; padding-top: 4px; border-top: 1px dashed #F1F5F9;">
                <div>
                    <span id="user-count-display">Menampilkan <strong>{{ $users->count() }}</strong> pengguna</span>
                </div>
                <div id="active-filter-indicator" style="display: none;">
                    <span style="background: #EFF6FF; color: #1D4ED8; padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 11px;">Filter Aktif</span>
                </div>
            </div>
        </div>

        <!-- USERS TABLE CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;" id="users-data-table">
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
                            @php
                                $roleKeyword = match($user->role) {
                                    'student' => 'student mahasiswa',
                                    'lecturer' => 'lecturer dosen pengampu',
                                    'vendor' => 'vendor mitra industri',
                                    'admin' => 'admin administrator',
                                    default => $user->role,
                                };
                                $statusKeyword = match($user->registration_status) {
                                    'approved' => 'approved disetujui aktif',
                                    'rejected' => 'rejected ditolak',
                                    default => 'pending menunggu approval',
                                };
                                $searchContent = strtolower(
                                    $user->name . ' ' . 
                                    $user->email . ' ' . 
                                    $user->id . ' ' . 
                                    '#' . $user->id . ' ' . 
                                    $roleKeyword . ' ' . 
                                    $statusKeyword . ' ' . 
                                    ($user->institution->name ?? '') . ' ' . 
                                    ($user->institution->code ?? '')
                                );
                            @endphp
                            <tr class="user-row" 
                                data-role="{{ $user->role }}"
                                data-search="{{ $searchContent }}"
                                style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" 
                                onmouseover="this.style.background='#F8FAFC'" 
                                onmouseout="this.style.background='#FFFFFF'">
                                
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
                                            'vendor' => ['bg' => '#F3E8FF', 'color' => '#6B21A8', 'label' => 'Mitra Vendor'],
                                            default => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Mahasiswa'],
                                        };
                                    @endphp
                                    <span style="background: {{ $roleBadges['bg'] }}; color: {{ $roleBadges['color'] }}; font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;">
                                        {{ $roleBadges['label'] }}
                                    </span>
                                    @if($user->role === 'vendor')
                                        @if($user->institution)
                                            <div style="font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <span>{{ $user->institution->name }}</span> <span style="background: #F3E8FF; color: #7E22CE; padding: 1px 6px; border-radius: 4px; font-size: 10px;">{{ $user->institution->code }}</span>
                                            </div>
                                        @elseif($user->institution_type === 'individual')
                                            <div style="font-size: 11px; font-weight: 600; color: #64748B; margin-top: 4px;">
                                                Praktisi Perorangan
                                            </div>
                                        @endif
                                    @endif
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
                            <tr id="empty-initial-row">
                                <td colspan="6" style="padding: 3rem; text-align: center; color: #94A3B8;">
                                    Belum ada pengguna terdaftar dalam sistem.
                                </td>
                            </tr>
                        @endforelse

                        <!-- DYNAMIC EMPTY STATE ROW (FOR SEARCH/FILTER NO MATCHES) -->
                        <tr id="no-matching-users-row" style="display: none;">
                            <td colspan="6" style="padding: 3.5rem 1.5rem; text-align: center;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: #64748B;">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #94A3B8; margin-bottom: 4px;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    </div>
                                    <div style="font-size: 14px; font-weight: 700; color: #1E293B;">Pengguna Tidak Ditemukan</div>
                                    <div style="font-size: 12.5px; color: #64748B; max-width: 380px;">
                                        Tidak ada akun pengguna yang cocok dengan kriteria kata kunci atau kategori peran yang dipilih.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- JAVASCRIPT REAL-TIME FILTER & SEARCH ENGINE -->
    <script>
        let currentActiveRole = 'all';

        function setRoleFilter(role) {
            currentActiveRole = role;

            // Update Tab UI styles
            document.querySelectorAll('.role-filter-tab').forEach(btn => {
                const target = btn.getAttribute('data-role-target');
                const badge = btn.querySelector('.role-tab-badge');

                if (target === role) {
                    btn.style.background = '#0F172A';
                    btn.style.color = '#FFFFFF';
                    btn.style.fontWeight = '700';
                    if (badge) {
                        badge.style.background = 'rgba(255,255,255,0.25)';
                        badge.style.color = '#FFFFFF';
                    }
                } else {
                    btn.style.background = '#F1F5F9';
                    btn.style.color = '#475569';
                    btn.style.fontWeight = '600';
                    if (badge) {
                        if (target === 'student') {
                            badge.style.background = '#DCFCE7';
                            badge.style.color = '#166534';
                        } else if (target === 'lecturer') {
                            badge.style.background = '#EFF6FF';
                            badge.style.color = '#1D4ED8';
                        } else if (target === 'vendor') {
                            badge.style.background = '#F3E8FF';
                            badge.style.color = '#6B21A8';
                        } else {
                            badge.style.background = '#E2E8F0';
                            badge.style.color = '#334155';
                        }
                    }
                }
            });

            applyUserFilters();
        }

        function clearSearchInput() {
            const searchInput = document.getElementById('user-search-input');
            if (searchInput) {
                searchInput.value = '';
                document.getElementById('clear-search-btn').style.display = 'none';
                applyUserFilters();
                searchInput.focus();
            }
        }

        function applyUserFilters() {
            const searchInput = document.getElementById('user-search-input');
            const rawSearch = (searchInput ? searchInput.value : '') || '';
            const searchTokens = rawSearch.trim().toLowerCase().split(/\s+/).filter(Boolean);
            const clearBtn = document.getElementById('clear-search-btn');

            if (clearBtn) {
                clearBtn.style.display = rawSearch.length > 0 ? 'block' : 'none';
            }

            const rows = document.querySelectorAll('.user-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const userRole = (row.getAttribute('data-role') || '').toLowerCase();
                const searchData = (row.getAttribute('data-search') || '').toLowerCase();

                const matchesRole = (currentActiveRole === 'all' || userRole === currentActiveRole);
                const matchesSearch = searchTokens.length === 0 || searchTokens.every(token => searchData.includes(token));

                if (matchesRole && matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update result counter display
            const countDisplay = document.getElementById('user-count-display');
            if (countDisplay) {
                countDisplay.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari ${rows.length} pengguna`;
            }

            // Update active filter indicator
            const activeFilterBadge = document.getElementById('active-filter-indicator');
            if (activeFilterBadge) {
                activeFilterBadge.style.display = (currentActiveRole !== 'all' || searchTokens.length > 0) ? 'block' : 'none';
            }

            // Empty state row display
            const noMatchRow = document.getElementById('no-matching-users-row');
            if (noMatchRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noMatchRow.style.display = '';
                } else {
                    noMatchRow.style.display = 'none';
                }
            }
        }

        // Attach event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('user-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', applyUserFilters);
                searchInput.addEventListener('keyup', applyUserFilters);
                searchInput.addEventListener('change', applyUserFilters);
            }
        });
    </script>
</x-app-layout>
