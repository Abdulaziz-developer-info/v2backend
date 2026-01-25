@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">Boshqaruv paneli</div>
                        <h2 class="page-title">Default Menular</h2>
                    </div>
                    <div class="col-auto ms-auto">
                        <div class="btn-list">
                            <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                data-bs-target="#menuCreateModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Menu qo'shish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">

                {{-- Alert --}}
                @if (session('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div><svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l5 5l10 -10" />
                                </svg></div>
                            <div>{{ session('success') }}</div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- KATEGORIYALAR QISMI --}}
                <div class="card mb-3">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Kategoriyalar</h3>
                        <button class="btn btn-sm btn-outline-primary text-uppercase fw-bold" data-bs-toggle="modal"
                            data-bs-target="#categoryCreateModal">
                            + Kategoriya
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 overflow-auto pb-2" style="white-space: nowrap;">
                            <a href="{{ route('default-menus.index') }}"
                                class="btn {{ empty($categoryId) ? 'btn-primary' : 'btn-outline-primary' }} btn-pill">
                                Hammasi
                            </a>
                            @foreach ($categories as $cat)
                                <div class="btn-group">
                                    <a href="{{ route('default-menus.index', ['category_id' => $cat->id]) }}"
                                        class="btn {{ (string) $categoryId === (string) $cat->id ? 'btn-primary' : 'btn-outline-primary' }} btn-pill">
                                        {{ $cat->name }}
                                    </a>
                                    <button type="button"
                                        class="btn {{ (string) $categoryId === (string) $cat->id ? 'btn-primary' : 'btn-outline-primary' }} dropdown-toggle dropdown-toggle-split btn-pill"
                                        data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <button class="dropdown-item"
                                            onclick="openCategoryEdit({{ $cat->id }}, '{{ $cat->name }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                <path d="M13.5 6.5l4 4" />
                                            </svg>
                                            Tahrirlash
                                        </button>
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST"
                                            onsubmit="return confirm('Kategoriyani o\'chirasizmi?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon dropdown-item-icon text-danger" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                O'chirish
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- JADVAL QISMI --}}
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Rasm</th>
                                    <th>Nomi</th>
                                    <th>Narxi</th>
                                    <th class="w-1">Amallar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td><span class="text-muted">{{ $menu->id }}</span></td>
                                        <td>
                                            @if ($menu->image)
                                                <span
                                                    class="avatar avatar-md cursor-pointer border shadow-sm menu-img-trigger"
                                                    data-src="{{ asset('storage/' . $menu->image) }}"
                                                    style="background-image: url({{ asset('storage/' . $menu->image) }})"></span>
                                            @else
                                                <span class="avatar avatar-md border shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M15 8h.01" />
                                                        <path
                                                            d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                        <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $menu->name }}</td>
                                        <td class="text-nowrap text-primary fw-bold">
                                            {{ number_format($menu->price, 0, '.', ' ') }} so'm
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <button class="btn btn-white btn-sm"
                                                    onclick="openMenuEdit({{ $menu->id }}, @js($menu->name), @js($menu->price), @js($menu->description), @js($menu->image ? asset('storage/' . $menu->image) : ''), @js($menu->category_id))">
                                                    Edit
                                                </button>
                                                <form action="{{ route('default-menus.destroy', $menu->id) }}"
                                                    method="POST" onsubmit="return confirm('Ochirasizmi?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted small italic">
                                            Menu mavjud emas
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        {{ $menus->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODALLAR ================= --}}

    {{-- MENU CREATE --}}
    <div class="modal modal-blur fade" id="menuCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form method="POST" action="{{ route('default-menus.store') }}" enctype="multipart/form-data"
                class="modal-content text-start">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yangi menu qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label required">Kategoriya</label>
                        <select name="category_id" class="form-select" required>
                            <option value="" hidden>— Kategoriya tanlang —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label required">Mahsulot nomi</label>
                        <input type="text" name="name" class="form-control" placeholder="Masalan: Klassik Burger"
                            required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label required">Narxi</label>
                        <input type="number" name="price" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Tavsif</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Rasm</label>
                        <input type="file" name="image" class="form-control" accept="image/*"
                            onchange="previewImage(this, 'createImgPreview')">
                        <div class="mt-2 text-center">
                            <img id="createImgPreview" class="img-fluid rounded border shadow-sm d-none"
                                style="max-height: 150px;">
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

    {{-- MENU EDIT --}}
    <div class="modal modal-blur fade" id="menuEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="menuEditForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Menuni tahrirlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="editImgPreview" class="img-fluid rounded border shadow-sm"
                            style="max-height: 150px; display: none;">
                    </div>
                    <div class="col-lg-12 text-start mb-3">
                        <label class="form-label">Rasm (almashtirish uchun yuklang)</label>
                        <input type="file" name="image" class="form-control" accept="image/*"
                            onchange="previewImage(this, 'editImgPreview')">
                    </div>
                    <div class="row">

                        <div class="col-lg-6 mb-3 text-start">
                            <label class="form-label">Kategoriya</label>
                            <select name="category_id" id="editMenuCategory" class="form-select" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3 text-start">
                            <label class="form-label">Mahsulot nomi</label>
                            <input type="text" name="name" id="editMenuName" class="form-control" required>
                        </div>
                        <div class="col-lg-12 mb-3 text-start">
                            <label class="form-label">Narxi</label>
                            <input type="number" name="price" id="editMenuPrice" class="form-control" required>
                        </div>
                        <div class="col-lg-12 mb-3 text-start">
                            <label class="form-label">Tavsif</label>
                            <textarea name="description" id="editMenuDesc" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Bekor
                        qilish</button>
                    <button type="submit" class="btn btn-primary">Yangilash</button>
                </div>
            </form>
        </div>
    </div>

    {{-- KATEGORIYA CREATE --}}
    <div class="modal modal-blur fade" id="categoryCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <form method="POST" action="{{ route('categories.store') }}" class="modal-content text-start">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yangi kategoriya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 text-start">
                    <div class="mb-3">
                        <label class="form-label">Nomi</label>
                        <input type="text" name="name" class="form-control" placeholder="Ichimliklar, Ovqatlar..."
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Saqlash</button>
                </div>
            </form>
        </div>
    </div>

    {{-- KATEGORIYA EDIT --}}
    <div class="modal modal-blur fade" id="categoryEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <form id="categoryEditForm" method="POST" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Tahrirlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 text-start">
                    <div class="mb-3 text-start">
                        <label class="form-label">Kategoriya nomi</label>
                        <input type="text" name="name" id="editCatInput" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer text-start">
                    <button type="submit" class="btn btn-primary w-100">Yangilash</button>
                </div>
            </form>
        </div>
    </div>

    {{-- IMAGE PREVIEW MODAL --}}
    <div class="modal modal-blur fade" id="fullImageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0 shadow-none text-center">
                <img id="fullImageDisplay" src="" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>

    {{-- ================= JS LOGIKA ================= --}}
    <script>
        // Rasmni modalda ko'rsatish
        document.querySelectorAll('.menu-img-trigger').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('fullImageDisplay').src = this.dataset.src;
                var myModal = new bootstrap.Modal(document.getElementById('fullImageModal'));
                myModal.show();
            });
        });

        // Rasm preview funksiyasi (input o'zgarganda)
        function previewImage(input, targetId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var target = document.getElementById(targetId);
                    target.src = e.target.result;
                    target.classList.remove('d-none');
                    target.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Kategoriyani tahrirlash modalini ochish
        function openCategoryEdit(id, name) {
            const form = document.getElementById('categoryEditForm');
            form.action = `/default-menus-categories/${id}`; // URLni loyihangizga qarab tekshiring
            document.getElementById('editCatInput').value = name;

            var editCatModal = new bootstrap.Modal(document.getElementById('categoryEditModal'));
            editCatModal.show();
        }

        // Menu tahrirlash modalini ochish
        function openMenuEdit(id, name, price, description, image, category_id) {
            const form = document.getElementById('menuEditForm');
            form.action = `/default-menus/${id}`;

            document.getElementById('editMenuName').value = name;
            document.getElementById('editMenuPrice').value = price;
            document.getElementById('editMenuDesc').value = description || '';
            document.getElementById('editMenuCategory').value = category_id;

            const imgPreview = document.getElementById('editImgPreview');
            if (image) {
                imgPreview.src = image;
                imgPreview.style.display = 'block';
            } else {
                imgPreview.style.display = 'none';
            }

            var editModal = new bootstrap.Modal(document.getElementById('menuEditModal'));
            editModal.show();
        }
    </script>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .btn-pill {
            border-radius: 50px;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(32, 107, 196, 0.03);
        }
    </style>
@endsection
