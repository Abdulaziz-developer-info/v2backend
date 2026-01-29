@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none py-3">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title fw-bold text-uppercase">Katalog Boshqaruvi</h2>
                    </div>
                    <div class="col-auto ms-auto d-flex gap-2">
                        <a href="{{ route('organization_menu.default_menu_org', [$org_id, $active_cat_id ?? 0]) }}"
                            class="btn btn-outline-primary border-2 shadow-sm fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 9l5 -5l5 5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            Import (Tayyor menyu)
                        </a>

                        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Kategoriya qo'shish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 sticky-top"  style="top: 1rem;">
                            <div class="card-header">
                                <h3 class="card-title fw-bold">Bo'limlar</h3>
                            </div>
                            <div class="list-group list-group-flush custom-scroll"
                                style="max-height: 250px; overflow: auto;">
                                @foreach ($categories as $cat)
                                    <div
                                        class="list-group-item list-group-item-action d-flex align-items-center py-2 {{ $active_cat_id == $cat->id ? 'active' : '' }}">
                                        <a href="?category_id={{ $cat->id }}"
                                            class="flex-fill text-decoration-none text-reset">
                                            {{ $cat->name }} <small
                                                class="text-muted">({{ $cat->products_count }})</small>
                                        </a>
                                        <div class="dropdown">
                                            <a href="#" class="link-secondary p-1" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline"
                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none">
                                                    <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <button class="dropdown-item"
                                                    onclick='editCategory(@json($cat))'>Tahrirlash</button>
                                                <button class="dropdown-item text-danger"
                                                    onclick="confirmDeleteCategory({{ $cat->id }})">O'chirish</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card border-0 shadow-sm">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th class="w-1">Rasm</th>
                                            <th>Mahsulot</th>
                                            <th>Narxi</th>
                                            <th class="text-center">Holati</th>
                                            <th class="text-end">Amallar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                            <tr>
                                                <td>
                                                    <span class="avatar avatar-md rounded-2"
                                                        style="background-image: url('{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('static/no-image.png') }}')"></span>
                                                </td>
                                                <td class="fw-bold">{{ $product->name }}</td>
                                                <td>{{ number_format($product->price, 0) }} so'm</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}-lt">
                                                        {{ $product->is_active ? 'Faol' : 'Stop' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-icon btn-ghost-primary"
                                                        onclick='editProduct(@json($product))'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none">
                                                            <path
                                                                d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                            <path d="M13.5 6.5l4 4" />
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-icon btn-ghost-danger"
                                                        onclick="confirmDeleteProduct({{ $product->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none">
                                                            <path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" />
                                                            <path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Mahsulotlar topilmadi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Mahsulot tahrirlash</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mahsulot nomi</label>
                                        <input type="text" name="name" id="edit_name" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kategoriya</label>
                                        <select name="category_id" id="edit_category_id" class="form-select">
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Narxi</label>
                                        <input type="number" name="price" id="edit_price" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tannarxi</label>
                                        <input type="number" name="cost_price" id="edit_cost_price"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Sort (Tartib)</label>
                                        <input type="number" name="sort" id="edit_sort" class="form-control">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Tavsif</label>
                                    <textarea name="message" id="edit_message" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-dashed">
                                    <div class="card-body text-center">
                                        <label class="form-label fw-bold">Mahsulot rasmi</label>
                                        <div class="mb-3">
                                            <img id="edit_image_preview" src=""
                                                class="rounded-2 border shadow-sm"
                                                style="max-width: 100%; height: 150px; object-fit: cover;">
                                        </div>
                                        <input type="file" name="image" class="form-control form-control-sm"
                                            onchange="previewImage(this)">
                                        <input type="hidden" name="image_url" id="edit_image_url_hidden">
                                        <small class="text-muted mt-2 d-block">Yangi rasm tanlash uchun bosing</small>
                                    </div>
                                </div>
                                <div class="mt-3 p-2 border rounded">
                                    <label class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="edit_is_active">
                                        <span class="form-check-label">Sotuvda faol</span>
                                    </label>
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_free"
                                            id="edit_is_free">
                                        <span class="form-check-label">Bepul mahsulot</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">O'zgarishlarni saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <form id="editCategoryForm" method="POST" action="">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Bo'limni tahrirlash</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="cat_edit_name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Yangilash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <h3>Ishonchingiz komilmi?</h3>
                    <div class="text-muted">Bu amalni ortga qaytarib bo'lmaydi.</div>
                </div>
                <div class="modal-footer">
                    <form id="universalDeleteForm" method="POST" action="" class="w-100">
                        @csrf @method('DELETE')
                        <div class="row">
                            <div class="col"><button type="button" class="btn btn-white w-100"
                                    data-bs-dismiss="modal">Bekor qilish</button></div>
                            <div class="col"><button type="submit" class="btn btn-danger w-100">Ha,
                                    o'chirilsin</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <form action="{{ route('organization_menu.category_store', $org_id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Yangi bo'lim</h5>
                    </div>
                    <div class="modal-body"><input type="text" name="name" class="form-control"
                            placeholder="Nomi" required></div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Qo'shish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Product Edit
        function editProduct(product) {
            const form = document.getElementById('editProductForm');
            form.action = `/organization/menu/product/update/${product.id}`;

            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_category_id').value = product.category_id;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_cost_price').value = product.cost_price;
            document.getElementById('edit_sort').value = product.sort;
            document.getElementById('edit_message').value = product.message;
            document.getElementById('edit_is_active').checked = (product.is_active == 1);
            document.getElementById('edit_is_free').checked = (product.is_free == 'true');

            // Rasm preview
            const preview = document.getElementById('edit_image_preview');
            preview.src = product.image_url ? `/storage/${product.image_url}` : '/static/no-image.png';

            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }

        // Category Edit
        function editCategory(cat) {
            const form = document.getElementById('editCategoryForm');
            form.action = `/organization/menu/category/update/${cat.id}`; // Route yozilgan deb hisoblaymiz
            document.getElementById('cat_edit_name').value = cat.name;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }

        // Rasm yuklash preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit_image_preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Delete mahsulot
        function confirmDeleteProduct(id) {
            const form = document.getElementById('universalDeleteForm');
            form.action = `/organization/menu/product/delete/${id}`;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }

        // Delete kategoriya
        function confirmDeleteCategory(id) {
            const form = document.getElementById('universalDeleteForm');
            form.action = `/organization/menu/category/delete/${id}`; // Route yozilgan bo'lishi kerak
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }
    </script>
@endsection
