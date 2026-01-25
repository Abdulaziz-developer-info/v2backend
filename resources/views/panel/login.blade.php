<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in</title>
    <link href="{{ asset('assets/dist/css/tabler.css') }}" rel="stylesheet" />
    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>
</head>

<body>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <img src="{{ asset('logo01.png') }}" width="200" alt="">
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h1 text-center mb-4">Login</h2>
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ismi</label>
                            <input type="text" name="name" class="form-control" placeholder="Abdulaziz" />
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Parol</label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="password" class="form-control" placeholder="******" />
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
