<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/global_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Homepage</title>
</head>

<body>
    <nav class="navbar navbar-light bg-primary">
        <div class=" d-flex w-100 justify-content-between align-items-center container">
            <div class="header-prfile d-flex">
                {{-- <div class="header-nav">
                    <img src="https://i.pinimg.com/736x/fa/d5/e7/fad5e79954583ad50ccb3f16ee64f66d.jpg"
                        alt="Header Image" style="max-height: 50px;max-width:50px">
                </div> --}}
                <div class="info-profile d-flex flex-column ps-3">
                    <h1>Welcome </h1>
                    <h6> {{ $userEmail }}</h6>
                </div>
            </div>
            <form action="{{ route('logoutAdmin') }}" method="POST">

                @csrf

                <button class="btn btn-danger ">
                    <div class="logout d-flex  align-items-center">
                        <span class="logou-text">
                            Logout
                        </span>
                        <i class="fa-solid fa-right-from-bracket ps-2"></i>
                    </div>
                </button>

            </form>



        </div>
    </nav>

    <div class="container mt-3 d-flex justify-content-between align-items-center">
        <div class="dw-info">
            <div class="dw">
                Download & Export
            </div>
            <span>
                You can download and export from folders below.
            </span>
        </div>
        {{-- <button class="btn btn-primary">
            <i class="fa-solid fa-download me-1"></i>
            Download with Export
        </button> --}}
    </div>
    <div class="container">
        @if(Request::is('home') || Request::is('home-page'))
            @include('home.homepage')
        @endif
        @yield('content')
    </div>
</body>

</html>
