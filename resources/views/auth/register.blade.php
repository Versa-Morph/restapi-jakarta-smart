<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="description" content="Smart Jakarta Application" />
    <meta name="author" content="SartJKT Dev" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="Smart , SMARTJKT, Smart Jakarta, 2024">
    <title>Register - Smart Jakarta</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset('assets/img/rki_icon.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('assets/img/rki_icon.png') }}" />
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('assets/img/rki_icon.png') }}" />
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('assets/img/rki_icon.png') }}" />
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('assets/img/rki_icon.png') }}" />

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Caveat|Poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/vendor/feather/css/feather.css') }}">

    <!-- animation css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/vendor/animate.css/animate.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/css/jquery.mCustomScrollbar.css') }}">

    <script type="text/javascript" src="{{ asset('assets/auth/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/auth/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <style>
        a:focus, a:hover {
            color: #D99022;
        }
    </style>
</head>

<body class="fix-menu">
    <section id="login_background" class="login-block auth-more">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row style-padding">
                <div class="col-12 col-md-6 text-left">
                    <img class="mb-5" width="80%" src="{{ asset('assets/img/logo-smartJKT.png') }}" alt="Company Logo">
                    <p class="description">Jakarta <span class="color-smartj">Smart</span> is a location-based mobile <span class="color-smartj">application</span> designed to <span class="color-smartj">improve</span> the <span class="color-smartj">quality</span> of life for Jakartans by <span class="color-smartj">providing</span> a range of essential <span class="color-smartj">services</span> that can be accessed <span class="color-smartj">easily</span> and <span class="color-smartj">quickly</span>. The app offers <span class="color-smartj">comprehensive solutions</span> for a wide range of <span class="color-smartj">daily needs</span> and <span class="color-smartj">emergency situations</span>, which makes it a very useful tool for citizens of a <span class="color-smartj">dense</span> and <span class="color-smartj">dynamic</span> city like Jakarta.</p>
                </div>
                <div class="col-0 col-md-1"></div>
                <div class="col-12 col-md-5 mt-5">
                    <div class="text-center login">
                        Welcome aboard!
                    </div>
                    <div class="text-center" style="color: #6C6C6C;">
                        Please sign up for your account
                    </div>
                    <!-- Registration form start -->
                    <form class="md-float-material form-material mt-5" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 px-1">
                                <div class="form-group formlogin mb-3">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" name="name" id="name" class="style-form-input" required maxlength="255" placeholder="Input Your Name..." title="maximum 255 characters" autofocus>
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 px-1">
                                <div class="form-group formlogin mb-3">
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" name="email" id="email" class="style-form-input" required maxlength="255" placeholder="Input Your Email..." title="maximum 255 characters">
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 px-1">
                                <div class="form-group formlogin mb-3 mt-2">
                                    <div class="input-icon">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" name="password" id="password" class="style-form-input" required autocomplete="on" title="minimum 8 characters" minlength="8" placeholder="Input Your Password...">
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 px-1">
                                <div class="form-group formlogin mb-3 mt-2">
                                    <div class="input-icon">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" name="password_confirmation" id="password-confirm" class="style-form-input" required autocomplete="on" title="minimum 8 characters" minlength="8" placeholder="Confirm Your Password...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btnlogin d-block mx-auto mt-2" type="submit">Register</button>
                    </form>
                    <!-- end of form -->
                    <div class="text-center mt-3">
                        Already have an account? <a href="{{ route('login') }}">Log in here</a>
                    </div>
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
</body>
</html>
