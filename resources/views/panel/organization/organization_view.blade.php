@extends('layout.main')

@section('content')
    <div class="page-body d-print-none">
        <div class="container-xl">
            @include('layout.header', [
                'pretitle' => 'Sozlamalar',
                'title' => "Tashkilot ma'lumotlari",
                'subtitle' => 'Bushbu tashkilotni boshqarish',
                'back_url' => route('organizations.index'),
            ])
            <div class="row row-cards">

                {{-- LEFT SIDE – ORGANIZATION EDIT --}}
                <div class="col-lg-9">
                    <form action="{{ route('organizations.update', $organization) }}" method="POST"
                        enctype="multipart/form-data" class="card">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h3 class="card-title">Organization settings</h3>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <a href="{{ url('api/test') }}">sdfsdfsdf</a>
                                {{-- Logo --}}
                                <div class="col-md-12">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-6">
                                            <div class="avatar avatar-xl border-dashed bg-white shadow-sm rounded-3"
                                                style="background-image: url({{ asset('storage/' . $organization->logo) }}); background-size: contain; cursor: pointer;"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Kattalashtirish"
                                                data-bs-toggle="modal" data-bs-target="#modal-logo-view">
                                                @if (!$organization->logo)
                                                    <span
                                                        class="text-muted fw-bold">{{ substr($organization->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h2 class="mb-2 fw-bold text-dark">{{ $organization->name }}</h2>

                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="small fw-medium me-2 text-uppercase"
                                                        style="min-width: 50px;">ID:</span>
                                                    <span
                                                        class="badge badge-outline text-dark fw-mono border-0 bg-light px-2"
                                                        id="id-text">
                                                        {{ $organization->id }}
                                                    </span>
                                                    <a href="javascript:void(0)" class="ms-2 text-muted"
                                                        onclick="copyValue('{{ $organization->id }}', this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none">
                                                            <path
                                                                d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                            <path
                                                                d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                                        </svg>
                                                    </a>
                                                </div>

                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="small fw-medium me-2 text-uppercase"
                                                        style="min-width: 50px;">Auth:</span>
                                                    <span
                                                        class="badge badge-outline text-dark fw-mono border-0 bg-light px-2">
                                                        {{ Str::limit($organization->auth, 16) }}
                                                    </span>
                                                    <a href="javascript:void(0)" class="ms-2 text-muted"
                                                        onclick="copyValue('{{ $organization->auth }}', this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none">
                                                            <path
                                                                d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                            <path
                                                                d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal modal-blur fade" id="modal-logo-view" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-body p-1 text-center bg-white rounded">
                                                    <img src="{{ asset('storage/' . $organization->logo) }}"
                                                        class="img-fluid rounded shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal modal-blur fade" id="modal-logo-view" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                            <div class="modal-content shadow-lg">
                                                <div class="modal-header border-0 pb-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center pt-0">
                                                    <img src="{{ asset('storage/' . $organization->logo) }}"
                                                        class="img-fluid rounded border shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        .cursor-pointer {
                                            cursor: zoom-in;
                                        }

                                        .input-group-flat:hover {
                                            border-color: var(--tblr-primary) !important;
                                            transition: 0.3s;
                                        }
                                    </style>

                                    <br>
                                    <div class="mb-3">
                                        <input type="hidden" name="block" value="0">

                                        <div class="form-check form-switch m-0 me-3">
                                            <input class="form-check-input" type="checkbox" name="block" value="1"
                                                {{ $organization->block ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger fw-bold">Bloklash</label>
                                        </div>
                                    </div>
                                    <label class="form-label">Logo</label>
                                    <input type="file" name="logo" class="form-control">
                                </div>



                                {{-- Organization name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Organization name</label>
                                    <input type="text" name="org_name" class="form-control"
                                        value="{{ old('org_name', $organization->org_name) }}">
                                </div>

                                {{-- staff --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin biriktirish</label>
                                    <select name="admin_id" class="form-select">
                                        <option value="">— Tanlang —</option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}"
                                                {{ $organization->admin_id == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }} ({{ $admin->role }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $organization->phone) }}">
                                </div>

                                {{-- Address --}}
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address', $organization->address) }}">
                                </div>

                                {{-- Currency --}}
                                <div class="col-md-6">
                                    <label class="form-label">Currency</label>
                                    <input type="text" name="currency" class="form-control"
                                        value="{{ old('currency', $organization->currency) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Organizatsiya turi</label>
                                    <select name="org_type" class="form-select">
                                        <option value="" {{ $organization->org_type == null ? 'selected' : '' }}>--
                                            Tanlanmagan --</option>
                                        <option value="small_cafe"
                                            {{ $organization->org_type == 'small_cafe' ? 'selected' : '' }}>
                                            Kichik Cafe
                                        </option>
                                        <option value="multi_branch_small_cafe"
                                            {{ $organization->org_type == 'multi_branch_small_cafe' ? 'selected' : '' }}>
                                            Ko'p filialli kichik Cafe
                                        </option>
                                        <option value="fast_food_cafe"
                                            {{ $organization->org_type == 'fast_food_cafe' ? 'selected' : '' }}>
                                            Kafe Fast Food
                                        </option>
                                        <option value="multi_branch_fast_food"
                                            {{ $organization->org_type == 'multi_branch_fast_food' ? 'selected' : '' }}>
                                            Ko'p filialli Kafe Fast Food
                                        </option>
                                        <option value="restaurant"
                                            {{ $organization->org_type == 'restaurant' ? 'selected' : '' }}>
                                            Restaran
                                        </option>
                                        <option value="multi_branch_restaurant"
                                            {{ $organization->org_type == 'multi_branch_restaurant' ? 'selected' : '' }}>
                                            Ko'p filialli Restaran
                                        </option>
                                        <option value="restaurant_supplier"
                                            {{ $organization->org_type == 'restaurant_supplier' ? 'selected' : '' }}>
                                            Restaranga mahsulot yetkazib beruvchi do'kon
                                        </option>
                                    </select>
                                </div>

                                {{-- Location --}}
                                <div class="col-md-6">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control"
                                        value="{{ old('location', $organization->location) }}">
                                </div>

                                {{-- Timezone --}}
                                <div class="col-md-6">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-select">
                                        <option value="">— Tanlang —</option>

                                        @php
                                            $timezones = [
                                                'Asia/Tashkent' => '🇺🇿 Uzbekistan (Asia/Tashkent)',
                                                'Asia/Almaty' => '🇰🇿 Kazakhstan (Asia/Almaty)',
                                                'Asia/Bishkek' => '🇰🇬 Kyrgyzstan (Asia/Bishkek)',
                                                'Asia/Dushanbe' => '🇹🇯 Tajikistan (Asia/Dushanbe)',
                                                'Asia/Ashgabat' => '🇹🇲 Turkmenistan (Asia/Ashgabat)',
                                                'Asia/Karachi' => '🇵🇰 Pakistan (Asia/Karachi)',
                                                'Asia/Kolkata' => '🇮🇳 India (Asia/Kolkata)',
                                                'Asia/Dhaka' => '🇧🇩 Bangladesh (Asia/Dhaka)',
                                                'Asia/Kathmandu' => '🇳🇵 Nepal (Asia/Kathmandu)',
                                                'Asia/Colombo' => '🇱🇰 Sri Lanka (Asia/Colombo)',
                                                'Asia/Dubai' => '🇦🇪 UAE (Asia/Dubai)',
                                                'Asia/Riyadh' => '🇸🇦 Saudi Arabia (Asia/Riyadh)',
                                                'Asia/Tehran' => '🇮🇷 Iran (Asia/Tehran)',
                                                'Asia/Baghdad' => '🇮🇶 Iraq (Asia/Baghdad)',
                                                'Asia/Jakarta' => '🇮🇩 Indonesia (Asia/Jakarta)',
                                                'Asia/Bangkok' => '🇹🇭 Thailand (Asia/Bangkok)',
                                                'Asia/Ho_Chi_Minh' => '🇻🇳 Vietnam (Asia/Ho Chi Minh)',
                                                'Asia/Shanghai' => '🇨🇳 China (Asia/Shanghai)',
                                                'Asia/Hong_Kong' => '🇭🇰 Hong Kong (Asia/Hong_Kong)',
                                                'Asia/Seoul' => '🇰🇷 South Korea (Asia/Seoul)',
                                                'Asia/Tokyo' => '🇯🇵 Japan (Asia/Tokyo)',
                                                'Asia/Singapore' => '🇸🇬 Singapore (Asia/Singapore)',
                                                'Asia/Manila' => '🇵🇭 Philippines (Asia/Manila)',
                                            ];
                                        @endphp

                                        @foreach ($timezones as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('timezone', $organization->timezone) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Start --}}
                                <div class="col-md-6">
                                    <label class="form-label">Start time</label>
                                    <input type="datetime-local" name="start" class="form-control"
                                        value="{{ $organization->start ? \Carbon\Carbon::parse($organization->start)->format('Y-m-d\TH:i') : '' }}">
                                </div>

                                {{-- End --}}
                                <div class="col-md-6">
                                    <label class="form-label">End time</label>
                                    <input type="datetime-local" name="end" class="form-control"
                                        value="{{ $organization->end ? \Carbon\Carbon::parse($organization->end)->format('Y-m-d\TH:i') : '' }}">
                                </div>

                                {{-- Product count --}}
                                <div class="col-md-6">
                                    <label class="form-label">Product count</label>
                                    <input type="number" name="product_count" class="form-control"
                                        value="{{ old('product_count', $organization->product_count) }}">
                                </div>

                                {{-- Payment --}}
                                <div class="col-md-6">
                                    <label class="form-label">Payment</label>
                                    <input type="text" name="payment" class="form-control"
                                        value="{{ old('payment', $organization->payment) }}">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="new"
                                            {{ old('status', $organization->status) == 'new' ? 'selected' : '' }}>Yangi
                                        </option>
                                        <option value="testing"
                                            {{ old('status', $organization->status) == 'testing' ? 'selected' : '' }}>
                                            Sinovda</option>
                                        <option value="client"
                                            {{ old('status', $organization->status) == 'client' ? 'selected' : '' }}>Klient
                                        </option>
                                        <option value="cancelled"
                                            {{ old('status', $organization->status) == 'cancelled' ? 'selected' : '' }}>
                                            Bekor qilingan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Branch Id --}}
                                <div class="col-md-6">
                                    <label class="form-label">Branch Id (New
                                        {{ $organization->id . rand(100, 999) }})</label>
                                    <input type="text" name="branch_id" class="form-control"
                                        value="{{ old('branch_id', $organization->branch_id) }}">
                                </div>

                                {{-- Message --}}
                                <div class="col-md-12">
                                    <label class="form-label">Message auth: {{ $organization->auth }}</label>
                                    <textarea name="message" class="form-control" rows="3">{{ old('message', $organization->message) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-center">

                                <div class="dropdown">
                                    <a href="#" class="btn-link text-muted" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="5" cy="12" r="1" />
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="19" cy="12" r="1" />
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modal-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon dropdown-item-icon text-danger" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="4" y1="7" x2="20" y2="7" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                            Profilni o'chirish
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary px-4">💾 Saqlash</button>
                        </div>
                    </form>
                </div>

                {{-- RIGHT SIDE – ACTION MENU --}}
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Actions</h3>
                        </div>
                        {{-- {{ dd($organization->id) }} --}}
                        <div class="list-group list-group-flush">
                            <a href="{{ route('organization_staff.org_staff', $organization->id) }}" class="list-group-item">Xodimlar</a>
                            <a href="{{ route('organization_menu.menu', $organization->id) }}" class="list-group-item">Menu</a>
                            <a href="#" class="list-group-item">Xodimlar</a>
                            <a href="#" class="list-group-item">Hisobotlar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function copyValue(text, element) {
            navigator.clipboard.writeText(text);

            // Ikonkani vaqtinchalik o'zgartirish (faqat tasdiq uchun)
            const originalContent = element.innerHTML;
            element.innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 12l5 5l10 -10" /></svg>';

            setTimeout(() => {
                element.innerHTML = originalContent;
            }, 1500);
        }
    </script>
@endsection
