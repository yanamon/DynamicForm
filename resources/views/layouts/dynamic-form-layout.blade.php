<!--Next:  
    -Pisahin resource layout view
    -edit layout (sidebar dn navbar)
    -button submit di modal add input
    -buat onclick card-input yg udah di hover
-->
<!DOCTYPE html>
<html>
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dropbox Data Access</title>
        
        <!-- Resource -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">        
        <link rel="stylesheet" href="{{asset("/css/sidebar.css")}}">

        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{asset('/js/style.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> 

        
        <script> var x = 1; var y = 0; var keys = []; var table_modal_json = 0;</script>
        <script src="{{asset('/js/select-input.js')}}"></script>
        <script src="{{asset('/js/submit-input.js')}}"></script>
        <script src="{{asset('/js/update-input.js')}}"></script>

        <link rel="stylesheet" href="{{asset('/css/style.css')}}">
        <style>
            input[type=radio],input[type=checkbox] {margin-right:5px;}
            .select2-selection__arrow {margin-top:3px!important;}
            .select2-selection.select2-selection--single {height: 36px!important; padding:3px !important;}
        </style>
    </head>
    <body id="body">
        <div class="wrapper">
            @guest
            @else
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>Dropbox Data Access</h3>
                </div>
                <ul class="list-unstyled components">
                    <p>Create dropbox data model access!</p>
                    {{-- <li class="active"><a href="/">Home</a></li> --}}
                    <li><a href="/all-project">Your Project</a></li>
                    <li><a href="/create-project">Create Project</a></li>
                </ul>
                 <ul class="list-unstyled CTAs">
                    <li>
                        <a class="download" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                             LOGOUT
                         </a>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                             @csrf
                         </form>
                    </li>
                </ul>
            </nav>
            @endguest
            <div id="content" style="padding-bottom:30px">
                @guest
                @else
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-align-left"></i>
                            <span>Toggle Sidebar</span>
                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         {{ Auth::user()->name }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>    
                @endguest    
                @section('body')
                @show
            </div>
        </div>
    </body>
</html>
    

<script>$('.select2').select2({ width: '100%' });</script>