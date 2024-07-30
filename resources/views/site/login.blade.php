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

</head>



<body>

    <div class="form-body">

        <div class="website-logo">

            <a href="index.html">

                <div class="logo">

                    <img class="logo-size" src="{{asset('/images/logo.svg')}}" alt="">

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

                        <h3>Media Server.</h3>

                        <p>Please login to continue.</p>

                        <div class="page-links">

                            <a href="/login" class="active">Login</a><a href="/register">Register</a>

                        </div>

                        <form method="POST" action="/loginAuthentication">

                            @csrf

                            <input class="form-control" type="text" name="email" placeholder="E-mail Address" required>

                            <input class="form-control" type="password" name="password" placeholder="Password" required>

                            <div class="form-button">

                                <button id="submit" type="submit" class="ibtn">Login</button> 
                                <!-- <a href="/forgotPassword">Forget password?</a> -->

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



</html>