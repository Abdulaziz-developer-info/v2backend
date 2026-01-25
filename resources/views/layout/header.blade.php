<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row g-2 align-items-center">

            <div class="col">
                <div class="page-pretitle text-uppercase d-flex align-items-center gap-1">
                    <a href="{{ $back_url ?? url()->previous() }}"
                        class="text-muted d-flex align-items-center decoration-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l4 4"></path>
                            <path d="M5 12l4 -4"></path>
                        </svg>
                        {{ $pretitle ?? 'Orqaga' }}
                    </a>
                </div>

                <h2 class="page-title fw-bold mt-1">
                    {{ $title ?? 'Sahifa nomi' }}
                </h2>

                @if (isset($subtitle))
                    <div class="text-muted small mt-1">
                        {{ $subtitle }}
                    </div>
                @endif
            </div>

            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex align-items-center gap-2">

                    @isset($actions)
                        {{ $actions }}
                    @endisset

                    <a href="{{ $back_url ?? url()->previous() }}"
                        class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 14l-4 -4l4 -4"></path>
                            <path d="M5 10h11a4 4 0 1 1 0 8h-1"></path>
                        </svg>
                        Orqaga qaytish
                    </a>

                    <a href="{{ $back_url ?? url()->previous() }}" class="btn btn-outline-secondary btn-icon d-sm-none"
                        aria-label="Orqaga">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                            <path d="M9 14l-4 -4l4 -4"></path>
                            <path d="M5 10h11a4 4 0 1 1 0 8h-1"></path>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
