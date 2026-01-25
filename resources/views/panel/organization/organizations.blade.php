@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                @include('layout.header', [
                    'pretitle' => 'Sozlamalar',
                    'title' => "Tashkilot ma'lumotlari",
                    'subtitle' => 'Bushbu tashkilotni boshqarish',
                    'back_url' => route('admin.dashboard'),
                ])
                <div class="row g-2 align-items-center">
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('organizations.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Yangi restoran qo'shish
                            </a>
                            <a href="{{ route('organizations.create') }}" class="btn btn-primary d-sm-none btn-icon"
                                aria-label="Create new report">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-muted">
                                Jami: <span class="fw-bold">{{ $organizations->count() }} ta</span>
                            </div>
                            <div class="ms-auto text-muted">
                                Qidiruv:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" id="searchInput" class="form-control form-control-sm"
                                        placeholder="Nomi, telefon yoki manzil..." aria-label="Search invoice">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped" id="restaurantsTable">
                            <thead>
                                <tr>
                                    <th class="w-1">#</th>
                                    <th>Restoran nomi</th>
                                    <th>Aloqa</th>
                                    <th>Manzil</th>
                                    <th>Valyuta / Vaqt</th>
                                    <th>Ish vaqti</th>
                                    <th class="w-1">Amallar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($organizations as $org)
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $org->org_name }}</td>
                                        <td>
                                            <div class="text-nowrap">{{ $org->phone }}</div>
                                        </td>
                                        <td class="text-muted small">
                                            {{ Str::limit($org->address, 30) }}
                                        </td>
                                        <td>
                                            <div class="badge bg-azure-lt">{{ $org->currency }}</div>
                                            <div class="text-muted small mt-1">{{ $org->timezone }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline text-green">
                                                {{ \Carbon\Carbon::parse($org->start) }} -
                                                {{ \Carbon\Carbon::parse($org->end) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="{{ route('organizations.show', $org->id) }}"
                                                    class="btn btn-success btn-sm">
                                                    Ko'rish
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <circle cx="12" cy="12" r="9" />
                                                        <line x1="9" y1="10" x2="9.01" y2="10" />
                                                        <line x1="15" y1="10" x2="15.01" y2="10" />
                                                        <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
                                                    </svg>
                                                </div>
                                                <p class="empty-title">Restoranlar topilmadi</p>
                                                <p class="empty-subtitle text-muted">
                                                    Hozircha tizimda hech qanday restoran mavjud emas.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#restaurantsTable tbody tr');

            const translitMap = {
                'а': 'a',
                'б': 'b',
                'в': 'v',
                'г': 'g',
                'д': 'd',
                'е': 'e',
                'ё': 'e',
                'ж': 'zh',
                'з': 'z',
                'и': 'i',
                'й': 'i',
                'к': 'k',
                'л': 'l',
                'м': 'm',
                'н': 'n',
                'о': 'o',
                'п': 'p',
                'р': 'r',
                'с': 's',
                'т': 't',
                'у': 'u',
                'ф': 'f',
                'х': 'h',
                'ц': 'c',
                'ч': 'ch',
                'ш': 'sh',
                'щ': 'shch',
                'ъ': '',
                'ы': 'y',
                'ь': '',
                'э': 'e',
                'ю': 'yu',
                'я': 'ya'
            };

            function transliterate(str) {
                return str.toLowerCase().split('').map(char => translitMap[char] || char).join('');
            }

            searchInput.addEventListener('input', function() {
                const filter = transliterate(this.value.trim());

                tableRows.forEach(row => {
                    // Faqat ma'lumot bor qatorlarni qidirish (empty qatorni hisobga olmaslik)
                    if (row.cells.length > 1) {
                        const text = transliterate(row.innerText);
                        row.style.display = text.includes(filter) ? '' : 'none';
                    }
                });
            });
        });
    </script>
@endsection
