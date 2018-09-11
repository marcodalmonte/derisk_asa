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
        
        <!-- jQuery UI -->
        <link type="text/css" rel="stylesheet" href="/js/jquery-ui/jquery-ui-1.9.2.custom.min.css" />
        
        <!-- Data Tables -->
        <link type="text/css" rel="stylesheet" href="/css/datatables/dataTables.bs.min.css" />
        <link type="text/css" rel="stylesheet" href="/css/datatables/autoFill.bs.min.css" />
        <link type="text/css" rel="stylesheet" href="/css/datatables/fixedColumns.dataTables.min.css" />
        
        <!-- Fancybox -->
        <link type="text/css" rel="stylesheet" href="/js/fancybox/jquery.fancybox.css" />
        
        <link type="text/css" rel="stylesheet" href="/css/validations/bootstrapValidator.min.css" />

        <!-- Derisk CSS -->
        <link type="text/css" rel="stylesheet" href="/css/duk.css{{ time() }}" />
        <link type="text/css" rel="stylesheet" href="/css/derisk.css{{ time() }}" />
        <link type="text/css" rel="stylesheet" href="/css/fra.css{{ time() }}" />

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
        <script type="text/javascript" src="/js/datatables/dataTables.fixedColumns.min.js"></script>
        
        <!-- Fancybox -->
        <script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
        
        <!-- Validations JS -->
        <script type="text/javascript" src="/js/validations/bootstrapValidator.min.js"></script>
        <script type="text/javascript" src="/js/validations/custom-validator.js"></script>

        <!-- Common JS -->
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/duk.js{{ time() }}"></script>
        <script type="text/javascript" src="/js/derisk.js{{ time() }}"></script>
        <script type="text/javascript" src="/js/fra.js{{ time() }}"></script>

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
        <div class="container-fluid fancy">
            <div class="dashboard-wrapper fancy">
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
                
                <div class="main-container fancy">                    
                    @yield('main-content')
                </div>
            </div>
        </div>
    </body>
</html>