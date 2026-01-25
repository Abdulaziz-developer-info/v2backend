@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle text-uppercase mb-1">Foydalanuvchi profili</div>
                        <h2 class="page-title d-flex align-items-center">
                            <span class="avatar avatar-lg rounded me-2" id="avatarPreview"
                                style="background-image: url({{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}); cursor:pointer;">
                            </span>
                            {{ $user->name }}
                        </h2>

                        <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0 text-center">
                                        <img id="modalAvatarImage" src="" class="img-fluid rounded" alt="Avatar">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            const avatar = document.getElementById('avatarPreview');
                            const modalAvatarImage = document.getElementById('modalAvatarImage');

                            avatar.addEventListener('click', function() {
                                const bg = this.style.backgroundImage;
                                const url = bg.slice(5, -2).replace(/"/g, "");
                                modalAvatarImage.src = url;
                                const modal = new bootstrap.Modal(document.getElementById('avatarModal'));
                                modal.show();
                            });
                        </script>
                    </div>
                    <div class="col-auto ms-auto">
                        <a href="{{ route('organization_staff.org_staff', $organization->id) }}"
                            class="btn btn-outline-secondary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 14l-4 -4l4 -4" />
                                <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                            </svg>
                            Orqaga
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">

                    {{-- Chap tomon: Tahrirlash Formasi --}}
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-lt">
                                <h3 class="card-title fw-bold text-primary">Asosiy ma'lumotlar</h3>
                            </div>
                            <form action="{{ route('organization_staff.update', [$organization->id, $user->id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="form-check form-switch m-0 me-3">
                                                <input class="form-check-input" type="checkbox" name="block"
                                                    value="1" {{ $user->block ? 'checked' : '' }}>
                                                <label class="form-check-label text-danger fw-bold">Bloklash</label>
                                            </div>
                                            <br>
                                            <div class="form-check form-switch m-0 me-3">
                                                <input class="form-check-input" type="checkbox" name="active"
                                                    value="1" {{ $user->active ? 'checked' : '' }}>
                                                <label class="form-check-label text-success fw-bold">Avtive qilish</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label required">To'liq ismi</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $user->name }}" required placeholder="F.I.SH">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Tizimdagi roli</label>
                                            <select name="role" class="form-select" required>
                                                @foreach (['admin', 'manager', 'cashier', 'waiter', 'cook', 'mijoz', 'kuryer', 'yetkazuvchi'] as $role)
                                                    <option value="{{ $role }}"
                                                        {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Telefon raqami</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon" width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                                    </svg></span>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ $user->phone }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email manzil</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email }}" placeholder="example@mail.com">
                                        </div>

                                        <div class="hr-text text-blue mt-4 mb-2">Ijtimoiy tarmoqlar</div>

                                        <div class="col-md-4">
                                            <label class="form-label text-muted small">Telegram</label>
                                            <input type="text" name="telegram" class="form-control"
                                                value="{{ $user->telegram }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small">Instagram</label>
                                            <input type="text" name="instagram" class="form-control"
                                                value="{{ $user->instagram }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small">Facebook</label>
                                            <input type="text" name="facebook" class="form-control"
                                                value="{{ $user->facebook }}">
                                        </div>

                                        <div class="hr-text text-blue mt-4 mb-2">Xavfsizlik va Profil</div>

                                        <div class="col-md-6">
                                            <label class="form-label">Avatar yuklash</label>
                                            <input type="file" name="avatar" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-warning">Yangi Parol (ixtiyoriy)</label>
                                            <input type="text" name="password" class="form-control"
                                                placeholder="O'zgartirish uchun yozing" value="{{ $user->password }}">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Mijoz uchun eslatma</label>
                                            <textarea name="note" class="form-control" rows="2">{{ $user->note }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Ichki tizim eslatmasi (faqat admin ko'radi)</label>
                                            <textarea name="message" class="form-control" rows="2">{{ $user->message }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div class="dropdown">
                                            <a href="#" class="btn-link text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <circle cx="5" cy="12" r="1" />
                                                    <circle cx="12" cy="12" r="1" />
                                                    <circle cx="19" cy="12" r="1" />
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-start">
                                                <button type="button" class="dropdown-item text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#modal-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon dropdown-item-icon text-danger" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="4" y1="7" x2="20"
                                                            y2="7" />
                                                        <line x1="10" y1="11" x2="10"
                                                            y2="17" />
                                                        <line x1="14" y1="11" x2="14"
                                                            y2="17" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                    Profilni o'chirish
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                            <circle cx="12" cy="14" r="2" />
                                            <polyline points="14 4 14 8 8 8 8 4" />
                                        </svg>
                                        Saqlash
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Sessiyalar bo'limi --}}
                        <div class="card mt-4 shadow-lg border-0">
                            <div
                                class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="ti ti-devices me-2"></i> Sessiyalarni Professional Boshqarish
                                </h3>
                                <span class="badge bg-blue">{{ $account_sessions->count() }} ta faol qurilma</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-hover border-bottom">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3">Qurilma Ma'lumotlari</th>
                                            <th class="py-3">IP va Tarmoq</th>
                                            <th class="py-3">Joylashuv (GPS)</th>
                                            <th class="py-3">Oxirgi faollik</th>
                                            <th class="py-3 text-center" style="width: 250px;">Statusni Boshqarish</th>
                                            <th class="pe-4 py-3 text-end">Amallar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($account_sessions as $session)
                                            <tr class="align-middle">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        @if ($session->device_type == 'web')
                                                            <div class="avatar bg-blue-lt shadow-sm me-3 position-relative"
                                                                title="Desktop/Web">
                                                                @if ($session->is_active == 1)
                                                                    <span
                                                                        class="badge bg-lime badge-notification badge-blink"></span>
                                                                @endif
                                                                <i class="ti ti-world-www"></i>
                                                                
                                                            </div>
                                                            
                                                        @else
                                                            <div class="avatar bg-purple-lt shadow-sm me-3"
                                                                title="Mobile/App">
                                                                @if ($session->is_active == 1)
                                                                    <span
                                                                        class="badge bg-lime badge-notification badge-blink"></span>
                                                                @endif
                                                                <i class="ti ti-device-mobile"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ $session->device_name }}</div>
                                                            <div class="text-muted small font-monospace">
                                                                {{ $session->device_id }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="badge badge-outline text-grey font-monospace px-2 py-1">
                                                        {{ $session->ip_address }}
                                                    </span>
                                                </td>

                                                <td>
                                                    @if ($session->location)
                                                        <a href="https://www.google.com/maps?q={{ $session->location }}"
                                                            target="_blank"
                                                            class="btn btn-sm btn-pill btn-outline-danger d-inline-flex align-items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-inline me-1" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none">
                                                                <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                            </svg>
                                                            Google Maps
                                                        </a>
                                                    @else
                                                        <span class="text-muted small italic">Aniqlanmagan</span>
                                                    @endif
                                                </td>

                                                <td class="text-muted">
                                                    <div class="small">
                                                        {{ \Carbon\Carbon::parse($session->last_activity_at)->format('d.m.Y H:i') }}
                                                    </div>
                                                    <div class="small fw-bold">
                                                        {{ \Carbon\Carbon::parse($session->last_activity_at)->diffForHumans() }}
                                                    </div>
                                                    <a href="{{ url("organization/staff/notlification/$session->id") }}" class="btn btn-danger">Notlification</a>
                                                </td>

                                                <td style="min-width: 300px;">
                                                    <form
                                                        action="{{ route('organization_staff.sessions.editSession', $session->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="input-group input-group-sm"> <select name="status"
                                                                class="form-select fw-bold shadow-none border-primary"
                                                                style="cursor: pointer;">
                                                                <option value="pending"
                                                                    {{ $session->status == 'pending' ? 'selected' : '' }}>
                                                                    Tekshiruvda
                                                                </option>
                                                                <option value="accepted"
                                                                    {{ $session->status == 'accepted' ? 'selected' : '' }}>
                                                                    Ruxsat berilgan
                                                                </option>
                                                                <option value="blocked"
                                                                    {{ $session->status == 'blocked' ? 'selected' : '' }}>
                                                                    Blocklangan
                                                                </option>
                                                                <option value="banned"
                                                                    {{ $session->status == 'banned' ? 'selected' : '' }}>
                                                                    Kirish yuq
                                                                </option>
                                                            </select>
                                                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                                                <i class="ti ti-device-floppy me-1"></i> Saqlash
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>

                                                <td class="pe-4 text-end">
                                                    <form
                                                        action="{{ route('organization_staff.sessions.destroySession', $session->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('DIQQAT! Ushbu sessiya o\'chirilsa, foydalanuvchi tizimdan majburiy chiqarib yuboriladi. Rozimisiz?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm shadow-sm d-inline-flex align-items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none">
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                            O'chirish
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- O'ng tomon: QR Kod --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center p-4">
                                <h3 class="mb-3">Kirish QR Kodi</h3>
                                <div class="d-inline-block p-2 rounded-3 bg-white shadow-sm mb-3">
                                    <div id="qrcode"></div>
                                </div>

                                <div class="rounded-2 p-2 mb-3">
                                    <span class="text-muted d-block small mb-1">Xavfsiz ID (Auth)</span>
                                    <code class="fw-bold">{{ $user->auth }}</code>
                                </div>

                                <p class="text-muted small lh-base px-2">
                                    Xodim ushbu QR kodni skanerlash orqali o'z ishchi paneliga avtomatik kirishi mumkin.
                                </p>

                                <div class="row g-2 mt-3">
                                    <div class="col-12">
                                        <button onclick="downloadQR()" class="btn btn-success w-100 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <polyline points="7 11 12 16 17 11" />
                                                <line x1="12" y1="4" x2="12" y2="16" />
                                            </svg>
                                            Yuklab olish
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ route('organization_staff.auth_edit', [$organization->id, $user->id]) }}"
                                            class="btn btn-outline-warning w-100"
                                            onclick="return confirm('QR kodni yangilasangiz, eski kod ishlamaydi. Tasdiqlaysizmi?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                            </svg>
                                            Kodni yangilash
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- O'chirish Modali --}}
    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                    <h3>Ishonchingiz komilmi?</h3>
                    <div class="text-muted">Siz haqiqatan ham ushbu foydalanuvchini o'chirib tashlamoqchimisiz? Bu amalni
                        ortga qaytarib bo'lmaydi.</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">Bekor
                                    qilish</a></div>
                            <div class="col">
                                <form action="{{ route('organization_staff.destroy', [$organization->id, $user->id]) }}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">O'chirish</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $secret = 't_a';
        $payload = $organization->auth . '|' . $user->auth . '|' . $user->role . '|' . $secret;
        $finalQR = Crypt::encryptString($payload);
    @endphp

    {{-- QR kod JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // const qrContent = "{{ Crypt::encryptString($organization->auth . '|' . $user->auth . '|' . $user->role) }}";
        const qrContent = "{{ $finalQR }}";
        const qrContainer = document.getElementById("qrcode");
        const qrcode = new QRCode(qrContainer, {
            text: qrContent,
            width: 250,
            height: 250,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.L
        });

        function downloadQR() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = 600;
            canvas.height = 800;

            ctx.fillStyle = "#ffffff";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.fillStyle = "#206bc4";
            ctx.fillRect(0, 0, canvas.width, 110);

            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 32px sans-serif";
            ctx.textAlign = "center";
            ctx.fillText("{{ $organization->org_name }}", canvas.width / 2, 65);

            ctx.fillStyle = "#1d273b";
            ctx.font = "bold 44px sans-serif";
            ctx.fillText("{{ $user->name }}", canvas.width / 2, 190);

            ctx.fillStyle = "#656d77";
            ctx.font = "bold 26px sans-serif";
            ctx.fillText("{{ strtoupper($user->role) }}", canvas.width / 2, 235);

            const qrImg = qrContainer.querySelector('img');
            if (qrImg && qrImg.src) {
                const img = new Image();
                img.src = qrImg.src;
                img.onload = function() {
                    ctx.strokeStyle = "#e6e7e9";
                    ctx.lineWidth = 3;
                    ctx.strokeRect(95, 295, 410, 410);

                    ctx.drawImage(img, 100, 300, 400, 400);

                    ctx.fillStyle = "#656d77";
                    ctx.font = "bold 20px monospace";
                    ctx.fillText("Tizimga kirish uchun QrCode", canvas.width / 2, 755);

                    const link = document.createElement('a');
                    link.href = canvas.toDataURL("image/png");
                    link.download = 'QR_ID_{{ Str::slug($user->name) }}.png';
                    link.click();
                };
            } else {
                alert("QR kod tayyor emas.");
            }
        }
    </script>
@endsection
