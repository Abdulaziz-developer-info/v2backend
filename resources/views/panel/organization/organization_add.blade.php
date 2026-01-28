@extends('layout.main')

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle text-muted">Sozlamalar</div>
                        <h2 class="page-title">Yangi restoran qo'shish</h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <a href="{{ route('organizations.index') }}" class="btn btn-ghost-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                            </svg>
                            Ro'yxatga qaytish
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <form action="{{ route('organizations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row row-cards">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Umumiy ma'lumotlar</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label required">Restoran nomi</label>
                                            <input type="text" name="org_name"
                                                class="form-control @error('org_name') is-invalid @enderror"
                                                value="{{ old('org_name') }}" placeholder="Masalan: Rayhon Milliy Taomlari"
                                                required>
                                            @error('org_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone">
                                                Telefon raqami
                                            </label>
                                            <div class="input-icon">
                                                <input type="text" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone') }}" placeholder="+998 90 123 45 67"
                                                    id="phone" required>
                                            </div>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Valyuta</label>
                                            <select name="currency"
                                                class="form-select @error('currency') is-invalid @enderror">
                                                <option value="UZS" {{ old('currency') == 'UZS' ? 'selected' : '' }}>UZS
                                                    (So'm)</option>
                                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD
                                                    ($)</option>
                                            </select>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Organizatsiya turi</label>
                                            <select name="org_type"
                                                class="form-select @error('org_type') is-invalid @enderror" required>
                                                <option value="">-- Tanlanmagan --</option>

                                                <optgroup label="Umumiy ovqatlanish (Cafe & Restoran)">
                                                    <option value="small_cafe">Kichik Cafe</option>
                                                    <option value="multi_branch_small_cafe">Ko'p filialli kichik Cafe
                                                    </option>
                                                    <option value="fast_food_cafe">Kafe Fast Food</option>
                                                    <option value="multi_branch_fast_food">Ko'p filialli Kafe Fast Food
                                                    </option>
                                                    <option value="restaurant">Restaran</option>
                                                    <option value="multi_branch_restaurant">Ko'p filialli Restaran</option>
                                                </optgroup>

                                                <optgroup label="Savdo va Do'konlar">
                                                    <option value="small_shop">Kichik dukon</option>
                                                    <option value="multi_branch_small_shop">Ko'p filialli kichik dukon
                                                    </option>
                                                    <option value="big_shop">Katta dukon</option>
                                                    <option value="multi_branch_big_shop">Ko'p filialli katta dukon</option>
                                                    <option value="market">Market</option>
                                                    <option value="multi_branch_market">Ko'p filialli Market</option>
                                                </optgroup>

                                                <optgroup label="Xizmat ko'rsatish">
                                                    <option value="restaurant_supplier">Restaranga mahsulot yetkazib
                                                        beruvchi do'kon</option>
                                                </optgroup>
                                            </select>

                                            @error('org_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Manzil</label>
                                            <input type="text" name="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                value="{{ old('address') }}" placeholder="Shahar, tuman, ko'cha nomi...">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Qisqacha ma'lumot (Message)</label>
                                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4"
                                                placeholder="Mijozlar uchun qo'shimcha ma'lumot...">{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Logo va Brend</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-label">Restoran logotipi</div>
                                        <input type="file" name="logo"
                                            class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                        <small class="form-hint">Tavsiya etilgan o'lcham: 500x500px (PNG, JPG)</small>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Ish vaqti va Hudud</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Vaqt zonasi (Timezone)</label>
                                        <input type="text" name="timezone" class="form-control"
                                            value="{{ old('timezone', 'Asia/Tashkent') }}">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label">Ochilish vaqti</label>
                                            <input type="datetime-local" name="start"
                                                class="form-control @error('start') is-invalid @enderror"
                                                value="{{ old('start') }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Yopilish vaqti</label>
                                            <input type="datetime-local" name="end"
                                                class="form-control @error('end') is-invalid @enderror"
                                                value="{{ old('end') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M14 4l0 4l-4 0l0 -4" />
                                        </svg>
                                        Restoranni saqlash
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
