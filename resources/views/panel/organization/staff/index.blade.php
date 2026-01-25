@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                            @include('layout.header', [
                'pretitle' => 'Sozlamalar',
                'title' => "Tashkilot ma'lumotlari",
                'subtitle' => 'Bushbu tashkilotni boshqarish',
                'back_url' => route('organizations.index'),
            ])
            <br>
                <div class="row g-2 align-items-center">
                    <div class="col">
                    <div class="col-auto ms-auto">
                        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-staff-add">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Yangi xodim qo'shish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Xodim</th>
                                    <th>Rol</th>
                                    <th>Aloqa</th>
                                    <th>Holat</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffs as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span
                                                    class="avatar me-2 rounded-circle bg-blue-lt">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ $item->name }}</div>
                                                    <div class="text-muted small">ID: {{ $item->auth }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $roleBadge = [
                                                    'admin' => 'bg-red-lt',
                                                    'manager' => 'bg-azure-lt',
                                                    'cashier' => 'bg-green-lt',
                                                    'waiter' => 'bg-orange-lt',
                                                    'cook' => 'bg-purple-lt',
                                                ];
                                            @endphp
                                            <span
                                                class="badge {{ $roleBadge[$item->role] ?? 'bg-secondary-lt' }} text-uppercase">
                                                {{ $item->role }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">{{ $item->phone }}</div>
                                            <div class="text-muted small">Telegram: {{ $item->telegram ?? '-' }}</div>
                                            <div class="text-muted small">Instagram: {{ $item->instagram ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="status-dot {{ $item->block ? 'bg-danger' : 'bg-success' }}"></span>
                                            <span class="{{ $item->block ? 'text-danger' : 'text-success' }} small">
                                                {{ $item->block ? 'Bloklangan' : 'Faol' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('organization_staff.view', [$organization->id, $item->id]) }}"
                                                class="btn btn-white btn-sm px-3 shadow-sm">Boshqarish</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Xodimlar mavjud emas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Yangi xodim qo'shish modal --}}
    <div class="modal modal-blur fade" id="modal-staff-add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('organization_staff.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="org_id" value="{{ $organization->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold text-primary">Yangi xodim qo'shish</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required">Xodim F.I.SH</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Lavozimi</label>
                                <select class="form-select" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Menejer</option>
                                    <option value="cashier">Kassir</option>
                                    <option value="waiter">Ofitsiant</option>
                                    <option value="cook">Oshpaz</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Telefon</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-info">Telegram</label>
                                <input type="text" class="form-control" name="telegram" value="{{ old('telegram') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-pink">Instagram</label>
                                <input type="text" class="form-control" name="instagram" value="{{ old('instagram') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ichki eslatma (note)</label>
                                <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tashqi xabar (message)</label>
                                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Bekor
                            qilish</button>
                        <button type="submit" class="btn btn-primary ms-auto">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
