<!DOCTYPE HTML>

<html lang="en">
   

<head>

    <!-- META -->
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!-- PAGE TITLE -->
    <title>{{isset($title) ? $title : 'Matcha'}}</title>

    <!-- FAVICON -->
    <!-- <link rel="shortcut icon" href="assets/img/favicon.png"> -->

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Bellefair&amp;subset=latin-ext" rel="stylesheet">

    <!-- STYLESHEETS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/light-bootstrap-default.css" rel="stylesheet" />
    <!-- SEARCH STYLESHEET FILE-->
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/search.css" rel="stylesheet">
    @yield('stylesheet')

</head>


<body>

<div class="wrapper">
    <div class="sidebar hh" data-image="img/sidebar/sidebar-9.jpg" data-color="blue">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

    Tip 2: you can also add an image using data-image tag
    -->
        <div class="sidebar-wrapper">
            <a href="/"><img src="img/logo1.png" class="img-responsive lo
                go" alt="logo"></a>
            <ul class="nav">
                <li class="nav-item @yield('home')">
                    <a class="nav-link" href="/">
                        <i class="fa fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="@yield('user')">
                    <a class="nav-link" href="{{ucfirst(strtolower(Auth::user()->login))}}">
                        <i class="fa fa-user-circle-o"></i>
                        <p>User Profile</p>
                    </a>
                </li>
                <li class="@yield('following')">
                    <a class="nav-link" href="/following">
                        <i class="fa fa-eye"></i>
                        <p>Following</p>
                    </a>
                </li>
                <li class="@yield('followers')">
                    <a class="nav-link" href="/followers">
                        <i class="fa fa-eye-slash"></i>
                        <p>Followers</p>
                    </a>
                </li>
                <li class="@yield('chat')">
                    <a class="nav-link" href="/chat">
                        <i class="fa fa-users"></i>
                        <p>Chat</p>
                    </a>
                </li>
                <li class="@yield('settings')">
                    <a class="nav-link" href="/settings">
                        <i class="fa fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <li class="@yield('maps')">
                    <a class="nav-link" href="/maps">
                        <i class="fa fa-map-marker"></i>
                        <p>Maps</p>
                    </a>
                </li>
                <li class="@yield('notifi')">
                    <a class="nav-link" href="/notifications">
                        <i class="fa fa-bell"></i>
                        <p>Notifications</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg " color-on-scroll="500">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <span class="no-icon sign_login pattaya_style c-e74">{{ucfirst(strtolower(Auth::user()->login))}}</span>
                </a>
                <span class="default_user_rating c-e74">
                    <span>Rating:</span>
                    {{number_format((float)Auth::user()->rating, 2, '.', '')}}
                </span>
                <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar burger-lines"></span>
                    <span class="navbar-toggler-bar burger-lines"></span>
                    <span class="navbar-toggler-bar burger-lines"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    <ul class="nav navbar-nav mr-auto">
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <i class="fa fa-spinner"></i>
                                <span class="d-lg-none">Style</span>
                            </a>
                            <ul class="dropdown-menu">
                                <a class="dropdown-item" href="#">Notification 1</a>
                                <a class="dropdown-item" href="#">Notification 2</a>
                                <a class="dropdown-item" href="#">Notification 3</a>
                                <a class="dropdown-item" href="#">Notification 4</a>
                                <a class="dropdown-item" href="#">Another notification</a>
                            </ul>
                        </li>
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                                <span class="notification notification_count" style="display: none"></span>
                                <span class="d-lg-none">Notification</span>
                            </a>
                            <ul class="dropdown-menu notification_ul">
                            </ul>
                        </li>
                        <li class="nav-item search_btn">
                            <div class="nav-link search-box-collapse">
                                    <i class="fa fa-search"></i>
                                    <span class="d-lg-block">&nbsp;Search</span>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="no-icon">Dropdown</span>
                                </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div class="divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link logout">
                                <span class="no-icon">Log out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        
        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>
        <!-- /CONTENT -->
    
    </div>
</div>

    <!-- SEARCH CONTENT -->
    @include('components.search')
    <!-- /SEARCH CONTENT -->

    
    <!-- JAVASCRIPTS -->
    <!--   Core JS Files   -->
    <script src="js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <script src="js/core/popper.min.js" type="text/javascript"></script>
    <script src="js/core/bootstrap.min.js" type="text/javascript"></script>
    <!-- TEMPL SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <!-- HELPER -->
    <script type="text/javascript" src="js/helper.js"></script>
    <!-- DEFAULT -->
    <script type="text/javascript" src="js/default.js"></script>
    <!-- Control Center for Light Bootstrap Dashboard: scripts -->
    <script src="js/light-bootstrap-default.js" type="text/javascript"></script>
     <!--SEARCH -->
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/scrollreveal/scrollreveal.min.js"></script>
    <script src="js/search.js"></script>
    @if (Auth::user()->first_entry)
        <!-- FIRST ENTRY -->
        <script type="text/javascript" src="js/first_entry.js"></script>
        <!-- /FIRST ENTRY -->
    @endif

    @yield('script')
</body>
</html>