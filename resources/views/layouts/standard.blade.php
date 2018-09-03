<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="/img/favicon.ico">
        
        <title>@yield('title')</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link type="text/css" rel="stylesheet" href="/css/bootstrap.min.css" media="screen" />

        <!-- Main CSS -->
        <link type="text/css" rel="stylesheet" href="/css/main.css" media="screen" />

        <!-- Icomoon Icons CSS -->
        <link type="text/css" rel="stylesheet" href="/fonts/icomoon/icomoon.css" />
        
        <!-- Font Awesome CSS -->
        <link type="text/css" rel="stylesheet" href="/fonts/font-awesome.css" />
        
        <!-- jQuery UI -->
        <link type="text/css" rel="stylesheet" href="/js/jquery-ui/jquery-ui-1.9.2.custom.min.css" />

        <!-- Data Tables -->
        <link type="text/css" rel="stylesheet" href="/css/datatables/dataTables.bs.min.css" />
        <link type="text/css" rel="stylesheet" href="/css/datatables/autoFill.bs.min.css" />
        <link type="text/css" rel="stylesheet" href="/css/datatables/fixedHeader.bs.css" />
        
        <!-- Fancybox -->
        <link type="text/css" rel="stylesheet" href="/js/fancybox/jquery.fancybox.css" />
        
        <link type="text/css" rel="stylesheet" href="/css/validations/bootstrapValidator.min.css" />
        
        <!-- Editor HTML -->
        <link type="text/css" rel="stylesheet" href="/css/wysiwyg-editor/editor.css" />

        <!-- Derisk CSS -->
        <link type="text/css" rel="stylesheet" href="/css/duk.css" />
        <link type="text/css" rel="stylesheet" href="/css/derisk.css" />
        <link type="text/css" rel="stylesheet" href="/css/fra.css" />

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="/js/jquery.js"></script>
        <script type="text/javascript" src="/js/jquery-migrate-3.0.0.js"></script>
        
        <!-- jQuery UI -->
        <script type="text/javascript" src="/js/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui/jquery-ui-i18n.js"></script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>

        <!-- Sparkline graphs -->
        <script type="text/javascript" src="/js/sparkline/retina.js"></script>

        <!-- jquery ScrollUp JS -->
        <script type="text/javascript" src="/js/scrollup.min.js"></script>

        <!-- D3 JS -->
        <script type="text/javascript" src="/js/d3.min.js"></script>

        <!-- Data Tables -->
        <script type="text/javascript" src="/js/datatables/dataTables.min.js"></script>
        <script type="text/javascript" src="/js/datatables/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/datatables/dataTables.tableTools.js"></script>
        <script type="text/javascript" src="/js/datatables/autoFill.min.js"></script>
        <script type="text/javascript" src="/js/datatables/autoFill.bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/datatables/fixedHeader.min.js"></script>
        
        <!-- Fancybox -->
        <script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>        
        
        <!-- Validations JS -->
        <script type="text/javascript" src="/js/validations/bootstrapValidator.min.js"></script>
        <script type="text/javascript" src="/js/validations/custom-validator.js"></script>
        
        <!-- Editor HTML -->
        <script type="text/javascript" src="/js/wysiwyg-editor/editor.js"></script>

        <!-- Common JS -->
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/duk.js"></script>
        <script type="text/javascript" src="/js/derisk.js"></script>
        <script type="text/javascript" src="/js/fra.js"></script>

        <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="/js/html5shiv.js"></script>
        <script type="text/javascript" src="/js/respond.min.js"></script>
        <![endif]-->

        <!-- Scripts -->
        <script type="text/javascript">
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head> 

    <body>
        <header class="clearfix">
            <div class="logo">
                <img src="/img/logo.png" alt="Logo" />
            </div>

            <div class="pull-right">
                <ul id="header-actions" class="clearfix">
                    <li class="list-box user-admin dropdown">
                        <div class="admin-details">
                            <div class="name">Welcome</div>
                            <div class="designation">{{Auth::user()->name}}</div>
                        </div>
                        <a id="drop4" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-account_circle"></i>
                        </a>
                        <ul class="dropdown-menu sm">
                            <li class="dropdown-content">
                                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        </ul>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form> 
                    </li>
                </ul>
            </div>
        </header>

        <div class="container-fluid">
            <div class="dashboard-wrapper">
@php 

$cpath = env('APP_URL');

$url = str_replace($cpath,'',Request::url());

@endphp

                <nav class="navbar navbar-default">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li<?php echo (('/home' == $url) ? ' class="active"' : '') ?>>
                                <a href="/home"><i class="icon-home2"></i>Dashboard</a>
                            </li>
@if ('1' == Auth::user()->usertype)
                            <li<?php echo (('/users' == $url) ? ' class="active"' : '') ?>>
                                <a href="/users"><i class="icon-account_circle"></i>Users</a>
                            </li>
                            <li<?php echo (('/clients' == $url) ? ' class="active"' : '') ?>>
                                <a href="/clients"><i class="icon-old-phone"></i>Clients</a>
                            </li>
                            <li<?php echo (('/surveys' == $url) ? ' class="active"' : '') ?>>
                                <a href="/surveys"><i class="icon-notification2"></i>Surveys</a>
                            </li>
                            <li<?php echo (('/specs' == $url) ? ' class="active"' : '') ?>>
                                <a href="/specs"><i class="icon-notification2"></i>Removals</a>
                            </li>
                            <li<?php echo (('/shops' == $url) ? ' class="active"' : '') ?>>
                                <a href="/shops"><i class="icon-shopping-cart"></i>Site Locations</a>
                            </li>
                            <li<?php echo (('/fire-risk-assessments' == $url) ? ' class="active"' : '') ?>>
                                <a href="/fire-risk-assessments"><i class="icon-open-book"></i>Fire Risk Assessments</a>
                            </li>
@endif
                            
@if ('2' == Auth::user()->usertype)
                            <li<?php echo (('/users' == $url) ? ' class="active"' : '') ?>>
                                <a href="/users"><i class="icon-account_circle"></i>Users</a>
                            </li>
                            <li<?php echo (('/clients' == $url) ? ' class="active"' : '') ?>>
                                <a href="/clients"><i class="icon-old-phone"></i>Clients</a>
                            </li>
                            <li<?php echo (('/surveys' == $url) ? ' class="active"' : '') ?>>
                                <a href="/surveys"><i class="icon-notification2"></i>Surveys</a>
                            </li>
@endif
                            
@if ('3' == Auth::user()->usertype)
                            <li<?php echo (('/shops' == $url) ? ' class="active"' : '') ?>>
                                <a href="/shops"><i class="icon-shopping-cart"></i>Site Locations</a>
                            </li>
                            <li<?php echo (('/fire-risk-assessments' == $url) ? ' class="active"' : '') ?>>
                                <a href="/fire-risk-assessments"><i class="icon-open-book"></i>Fire Risk Assessments</a>
                            </li>
@endif
                        </ul>
                    </div>
                </nav>

                <div class="top-bar clearfix">
                    <div class="page-title">
                        <h4>@yield('header-title')</h4>
                    </div>                        
                </div>

                <div id="confirmation-message" class="main-container fancy">                    
                    <div class="row gutter">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-light">
                                <div id="message" class="panel-body">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="main-container">                    
                    @yield('main-content')
                </div>

                <footer class="footer">
                    &copy; Derisk Ltd <span>2017</span>
                </footer>
            </div>
        </div>
    </body>
</html>