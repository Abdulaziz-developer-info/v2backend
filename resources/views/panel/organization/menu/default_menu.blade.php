@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <div class="page-header d-print-none mt-3">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Menyu Boshqaruvi <span class="badge bg-azure-lt ms-2">{{ $defaultMenus->total() }} ta taom</span>
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-lg-none">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasCategories">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Kategoriyalar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row g-4">
                    <aside class="col-lg-3 d-none d-lg-block">
                        <div class="card shadow-sm border-0">
                            <div class="card-header fw-bold">Kategoriya Filtrlari</div>
                            <div class="p-2">
                                <input type="text" id="catSearch"
                                    class="form-control form-control-flush border-bottom mb-2"
                                    placeholder="Kategoriyani tezkor topish...">
                            </div>
                            <div class="list-group list-group-flush overflow-auto" style="max-height: 70vh;"
                                id="categoryList">
                                <a href="{{ url()->current() }}"
                                    class="list-group-item list-group-item-action {{ !request('category_id') ? 'active' : '' }}">Barchasi</a>
                                @foreach ($defaultCategories as $cat)
                                    <a href="?category_id={{ $cat->id }}"
                                        class="list-group-item list-group-item-action cat-item"
                                        data-name="{{ strtolower($cat->name) }}">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <div class="col-lg-9">
                        <div class="card mb-3 shadow-none border">
                            <div class="card-body p-2">
                                <form action="{{ url()->current() }}" method="GET" class="input-group input-group-flat">
                                    <input type="text" name="search" class="form-control ps-3"
                                        value="{{ request('search') }}"
                                        placeholder="Baza bo'yicha global qidiruv (Nom, tarkib)...">
                                    <span class="input-group-text pe-2">
                                        <button type="submit" class="btn btn-ghost-primary btn-sm">Qidirish</button>
                                    </span>
                                </form>
                            </div>
                        </div>

                        <div class="row row-cards">
                            @foreach ($defaultMenus as $menu)
                                <div class="col-6 col-md-4 col-xl-4">
                                    <div class="card card-sm card-link-pop border-0 shadow-sm">
                                        <div class="img-responsive img-responsive-16x9 card-img-top"
                                            style="background-image: url({{ $menu->image ? asset('storage/' . $menu->image) : asset('static/no-image.png') }}); cursor: pointer;"
                                            onclick="openLightbox('{{ $menu->image ? asset('storage/' . $menu->image) : asset('static/no-image.png') }}', '{{ $menu->name }}')">
                                        </div>

                                        <div class="card-body p-2 p-md-3">
                                            <div class="text-truncate fw-bold mb-1" style="font-size: 1rem;">
                                                {{ $menu->name }}</div>
                                            <div class="text-success fw-bold mb-2">
                                                {{ number_format($menu->price, 0, ',', ' ') }} so'm</div>

                                            <div class="d-flex justify-content-between gap-1">
                                                <button class="btn btn-outline-secondary btn-icon d-none d-md-inline-flex"
                                                    onclick="viewDetails({{ json_encode($menu) }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none">
                                                        <circle cx="12" cy="12" r="9" />
                                                        <line x1="12" y1="8" x2="12.01" y2="8" />
                                                        <polyline points="11 12 12 12 12 16 13 16" />
                                                    </svg>
                                                </button>
                                                <form
                                                    action="{{ route('organization_menu.default_menu_add_org_store', [$org_id, $category_id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="image" value="{{ $menu->image }}">
                                                    <input type="hidden" name="name" value="{{ $menu->name }}">
                                                    <input type="hidden" name="message" value="{{ $menu->description }}">
                                                    <input type="hidden" name="price" value="{{ $menu->price }}">

                                                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none">
                                                            <path d="M12 5l0 14" />
                                                            <path d="M5 12l14 0" />
                                                        </svg>
                                                        <span class="d-none d-md-inline">Qo'shish</span>
                                                        <span class="d-md-none">+</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $defaultMenus->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCategories">
        <div class="offcanvas-header bg-light">
            <h2 class="offcanvas-title">Kategoriyalar</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3">
                <input type="text" id="catSearchMobile" class="form-control" placeholder="Qidirish...">
            </div>
            <div class="list-group list-group-flush overflow-auto">
                @foreach ($defaultCategories as $cat)
                    <a href="?category_id={{ $cat->id }}"
                        class="list-group-item list-group-item-action cat-item-mobile"
                        data-name="{{ strtolower($cat->name) }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="lightboxModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-index-10"
                        data-bs-dismiss="modal"></button>
                    <img id="lbImage" src="" class="img-fluid rounded shadow-lg">
                    <div id="lbTitle" class="text-white text-center mt-2 fw-bold h3"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Kategoriya Search (Desktop & Mobile)
        function setupCatSearch(inputId, itemClass) {
            document.getElementById(inputId).addEventListener('input', function() {
                let val = this.value.toLowerCase();
                document.querySelectorAll('.' + itemClass).forEach(item => {
                    item.style.display = item.getAttribute('data-name').includes(val) ? 'block' : 'none';
                });
            });
        }
        setupCatSearch('catSearch', 'cat-item');
        setupCatSearch('catSearchMobile', 'cat-item-mobile');

        // 2. Lightbox (Rasm kattalashishi)
        function openLightbox(src, title) {
            document.getElementById('lbImage').src = src;
            document.getElementById('lbTitle').innerText = title;
            new bootstrap.Modal(document.getElementById('lightboxModal')).show();
        }

        // 3. Mahsulot qo'shish funksiyasi
        function addToClient(menu) {
            // Bu yerga mijozga qo'shish modalini chaqirish kodi keladi
            console.log("Qo'shilmoqda:", menu.name);
        }
    </script>
@endsection
