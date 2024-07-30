<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{env('SITE_NAME')}}</title>

    <link rel="stylesheet" type="text/css" href="{{asset('/css-1/bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('/css-1/fontawesome-all.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('/css-1/iofrm-style.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('/css-1/iofrm-theme4.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
@php
$success = Session::get('success');
$error = Session::get('error');
@endphp


<body>

    <div class="form-body">

        <div class="website-logo">

            <a href="/">

                <div class="logo">

                    <img class="logo-size" src="{{asset('/')}}images/logo2" alt="">

                </div>

            </a>

        </div>

        <div class="row">

            <div class="img-holder">

                <div class="bg"></div>

                <div class="info-holder">

                    <img src="{{asset('/images/graphic1.svg')}}" alt="">

                </div>

            </div>

            <div class="form-holder">

                <div class="form-content">

                    <div class="form-items">

                        <h3>Media Server</h3>

                        <p>Please fill the details to register.</p>

                        <div class="page-links">

                            <a href="/login">Login</a><a href="/register" class="active">Register</a>

                        </div>

                        <form method="POST" action="/createUser">

                            @csrf

                            <input class="form-control" type="text" name="name" placeholder="Name" required>

                            <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>

                            <input class="form-control" type="password" name="password" placeholder="Password" required>

                            <div class="form-button">

                                <button id="submit" type="submit" class="ibtn">Register</button>

                            </div>

                        </form>

                        

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="{{asset('/js-1/jquery.min.js')}}"></script>

    <script src="{{asset('/js-1/popper.min.js')}}"></script>

    <script src="{{asset('/js-1/bootstrap.min.js')}}"></script>

    <script src="{{asset('/js-1/main.js')}}"></script>

</body>

<script>
    var success = "{{!empty($success)?$success:'NA'}}";
    var error = "{{!empty($error)?$error:'NA'}}";
    console.log(success);
    if (success != 'NA') {
        Swal.fire({
            title: 'Registered Successfully',
            text: success,
            icon: 'success',
            confirmButtonText: 'Okay',

        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/';
            }
        })
    }
    if (error != 'NA') {
        Swal.fire({
            title: 'Failed!',
            text: error,
            icon: 'error',
            confirmButtonText: 'Okay',

        });
    }
</script>

</html>