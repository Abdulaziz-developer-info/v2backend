@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                @include('layout.header', [
                    'pretitle' => 'Bosh sahifa',
                    'title' => 'Barcha adminlar',
                    'subtitle' => 'Tizim adminlarini boshqarish, ish vaqtlari va aloqa ma’lumotlarini tahrirlash',
                    'back_url' => route('admin.dashboard'),
                ])

                <div class="row g-2 align-items-center mt-2">
                    <div class="col-auto ms-auto d-print-none">
                        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Yangi admin qo'shish
                        </button>
                    </div>
                </div>

                @if (session('admin_credentials'))
                    <div class="alert alert-important alert-success alert-dismissible mt-3" role="alert">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                            </div>
                            <div>
                                <strong>Muvaffaqiyatli!</strong> Admin ma'lumotlari yangilandi.<br>
                                <b>Login:</b> <span id="copyLogin">{{ session('admin_credentials.login') }}</span> |
                                <b>Parol:</b> <span
                                    id="copyPass">{{ session('admin_credentials.password') ?? "O'zgartirilmadi" }}</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-white ms-3 mt-2" onclick="copyCreds()">Nusxa olish</button>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>F.I.O</th>
                                    <th>Rol</th>
                                    <th>Ijtimoiy tarmoqlar</th>
                                    <th>Ish vaqti</th>
                                    <th>Holat</th>
                                    <th class="w-1">Amallar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                    <tr>
                                        <td>
                                            <span class="avatar rounded-3 shadow-sm border"
                                                style="background-image: url({{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('logo01.png') }})"></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $admin->name }}</div>
                                            <div class="text-muted small">{{ Str::limit($admin->message, 30) }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $roleNames = [1 => 'Main Admin', 2 => 'Sub Admin', 3 => 'Mini Admin'];
                                                $roleColors = [1 => 'bg-purple', 2 => 'bg-azure', 3 => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $roleColors[$admin->role] ?? 'bg-gray' }} text-white">
                                                {{ $roleNames[$admin->role] ?? 'Noma\'lum' }}
                                            </span>
                                        </td>
                                        <td class="small">
                                            <div class="d-flex flex-column gap-1">
                                                @if ($admin->phone)
                                                    <span><i
                                                            class="ti ti-phone text-muted me-1"></i>{{ $admin->phone }}</span>
                                                @endif
                                                @if ($admin->telegram)
                                                    <span class="text-azure"><i
                                                            class="ti ti-brand-telegram me-1"></i>{{ $admin->telegram }}</span>
                                                @endif
                                                @if ($admin->instagram)
                                                    <span class="text-pink"><i
                                                            class="ti ti-brand-instagram me-1"></i>{{ $admin->instagram }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            <div class="fw-medium">{{ $admin->work_start ?? '--:--' }} -
                                                {{ $admin->work_end ?? '--:--' }}</div>
                                            <div style="font-size: 10px;" class="text-uppercase">Kun: {{ $admin->work_days }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($admin->block == 0)
                                                <span class="badge bg-green-lt">Faol</span>
                                            @elseif($admin->block == 1)
                                                <span class="badge bg-red-lt">Bloklangan</span>
                                            @else
                                                <span class="badge bg-dark-lt">Banned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="d-flex align-items-center">
                                                    <div class="dropdown">
                                                        <a href="#" class="btn-link text-muted"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="5" cy="12" r="1" />
                                                                <circle cx="12" cy="12" r="1" />
                                                                <circle cx="19" cy="12" r="1" />
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-start">
                                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal"
                                                                data-id="{{ $admin->id }}">
                                                                Xafli o'chirish &nbsp;&nbsp;
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor"
                                                                    fill="none">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M4 7l16 0" />
                                                                    <path d="M10 11l0 6" />
                                                                    <path d="M14 11l0 6" />
                                                                    <path
                                                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-white px-3" data-bs-toggle="modal"
                                                    data-bs-target="#editModal" data-id="{{ $admin->id }}"
                                                    data-name="{{ $admin->name }}" data-role="{{ $admin->role }}"
                                                    data-block="{{ $admin->block }}"
                                                    data-message="{{ $admin->message }}"
                                                    data-avatar="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('logo01.png') }}"
                                                    data-phone="{{ $admin->phone }}"
                                                    data-telegram="{{ $admin->telegram }}"
                                                    data-instagram="{{ $admin->instagram }}"
                                                    data-whatsapp="{{ $admin->whatsapp }}"
                                                    data-work_start="{{ $admin->work_start }}"
                                                    data-work_end="{{ $admin->work_end }}"
                                                    data-work_days="{{ $admin->work_days }}">
                                                    Tahrirlash
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALLAR --}}

    {{-- ADD MODAL --}}
    <div class="modal modal-blur fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admins.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yangi admin qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4 text-center border-end">
                            <label class="form-label">Profil rasmi</label>
                            <img src="{{ asset('logo01.png') }}" id="addPreview"
                                style="width:120px;height:120px;object-fit:cover"
                                class="rounded-circle mb-2 border shadow-sm">
                            <input type="file" name="avatar" class="form-control form-control-sm"
                                onchange="previewImage(this,'addPreview')">
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label required">F.I.O</label>
                                    <input class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Parol</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Rol</label>
                                    <select class="form-select" name="role" required>
                                        <option value="1">Main Admin</option>
                                        <option value="2">Sub Admin</option>
                                        <option value="3">Mini Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Telefon</label>
                                    <input class="form-control" name="phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row g-2">
                        <div class="col-md-4"><label
                                class="form-label small text-muted text-uppercase">Telegram</label><input
                                class="form-control" name="telegram"></div>
                        <div class="col-md-4"><label
                                class="form-label small text-muted text-uppercase">Instagram</label><input
                                class="form-control" name="instagram"></div>
                        <div class="col-md-4"><label
                                class="form-label small text-muted text-uppercase">WhatsApp</label><input
                                class="form-control" name="whatsapp"></div>
                        <div class="col-md-4"><label class="form-label small text-muted text-uppercase">Ish
                                boshlash</label><input type="time" class="form-control" name="work_start"></div>
                        <div class="col-md-4"><label class="form-label small text-muted text-uppercase">Ish
                                tugash</label><input type="time" class="form-control" name="work_end"></div>
                        <div class="col-md-4"><label class="form-label small text-muted text-uppercase">Ish
                                kunlari</label><input class="form-control" name="work_days" placeholder="Mon,Tue,Wed">
                        </div>
                        <div class="col-12"><label class="form-label small text-muted text-uppercase">Tavsif</label>
                            <textarea class="form-control" name="message" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Bekor
                        qilish</button>
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL (Add Modal bilan bir xil tuzilmada) --}}
    <div class="modal modal-blur fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" method="POST" id="editForm" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ma'lumotlarni tahrirlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Chap qism: Rasm --}}
                        <div class="col-md-4 text-center border-end">
                            <label class="form-label">Profil rasmi</label>
                            <img id="editPreview" style="width:120px;height:120px;object-fit:cover"
                                class="rounded-circle mb-2 border shadow-sm">
                            <input type="file" name="avatar" class="form-control form-control-sm"
                                onchange="previewImage(this,'editPreview')">
                        </div>
                        {{-- O'ng qism: Asosiy ma'lumotlar --}}
                        <div class="col-md-8">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">F.I.O</label>
                                    <input class="form-control" name="name" id="editName" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Yangi parol</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="O'zgartirmaslik uchun bo'sh">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <select class="form-select" name="role" id="editRole">
                                        <option value="1">Main Admin</option>
                                        <option value="2">Sub Admin</option>
                                        <option value="3">Mini Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Holat</label>
                                    <select class="form-select" name="block" id="editBlock">
                                        <option value="0">Faol</option>
                                        <option value="1">Bloklangan</option>
                                        <option value="2">Banned</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    {{-- Pastki qism: Ijtimoiy tarmoqlar va ish vaqti --}}
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase">Telefon</label>
                            <input class="form-control" name="phone" id="editPhone">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase text-azure">Telegram</label>
                            <input class="form-control" name="telegram" id="editTelegram">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase text-pink">Instagram</label>
                            <input class="form-control" name="instagram" id="editInstagram">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase">WhatsApp</label>
                            <input class="form-control" name="whatsapp" id="editWhatsapp">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase">Ish boshlash</label>
                            <input type="time" class="form-control" name="work_start" id="editWorkStart">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase">Ish tugash</label>
                            <input type="time" class="form-control" name="work_end" id="editWorkEnd">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-muted text-uppercase">Ish kunlari</label>
                            <input class="form-control" name="work_days" id="editWorkDays">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted text-uppercase">Tavsif</label>
                            <textarea class="form-control" name="message" id="editMessage" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Bekor
                        qilish</button>
                    <button type="submit" class="btn btn-warning">Yangilash</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal modal-blur fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form class="modal-content" method="POST" id="deleteForm">
                @csrf @method('DELETE')
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                    <h3>Ishonchingiz komilmi?</h3>
                    <div class="text-muted">Ushbu adminni o'chirish barcha bog'liq ma'lumotlarni yo'q qilishi mumkin.</div>
                </div>
                <div class="modal-footer border-0">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><button type="button" class="btn btn-white w-100"
                                    data-bs-dismiss="modal">Yo'q</button></div>
                            <div class="col"><button type="submit" class="btn btn-danger w-100">Ha,
                                    o'chirilsin</button></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input, targetId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById(targetId).src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        function copyCreds() {
            const login = document.getElementById('copyLogin').innerText;
            const pass = document.getElementById('copyPass').innerText;
            navigator.clipboard.writeText(`Login: ${login}\nParol: ${pass}`).then(() => alert("Ma'lumotlar nusxalandi!"));
        }

        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const form = document.getElementById('editForm');
            form.action = `/admins/${btn.dataset.id}`;

            document.getElementById('editName').value = btn.dataset.name;
            document.getElementById('editRole').value = btn.dataset.role;
            document.getElementById('editBlock').value = btn.dataset.block;
            document.getElementById('editPhone').value = btn.dataset.phone || '';
            document.getElementById('editTelegram').value = btn.dataset.telegram || '';
            document.getElementById('editInstagram').value = btn.dataset.instagram || '';
            document.getElementById('editWhatsapp').value = btn.dataset.whatsapp || '';
            document.getElementById('editWorkStart').value = btn.dataset.work_start || '';
            document.getElementById('editWorkEnd').value = btn.dataset.work_end || '';
            document.getElementById('editWorkDays').value = btn.dataset.work_days || '';
            document.getElementById('editMessage').value = btn.dataset.message || '';
            document.getElementById('editPreview').src = btn.dataset.avatar;
        });

        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(e) {
            document.getElementById('deleteForm').action = `/admins/${e.relatedTarget.dataset.id}`;
        });
    </script>
@endsection
