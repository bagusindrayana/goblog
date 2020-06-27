<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="{{ config('app.description', 'Laravel') }}">
  <meta name="author" content="Bagus Indrayana">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

  <script src="{{ asset('js/app.js') }}"></script>

  @stack('scripts')

 
  @stack('head')

  <style>
      body {
          padding-top: 56px;
      }
  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link {!! Helper::active_class("/",'active') !!}" href="{{ url('/') }}">Home
              
            </a>
          </li>
          @php
              $public_menu = Helper::listMenu();
          @endphp
          @if($public_menu)
       
              @foreach($public_menu as $menu)
              <li class="nav-item {!! Helper::active_class($menu['link'],'active') !!} @if($menu['child']) dropdown @endif">
              <a  class="nav-link {!! Helper::active_class($menu['link'],'active') !!} @if($menu['child']) dropdown-toggle @endif" @if($menu['child']) role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @endif id="menu{{ $menu['id'] }}" href="{{ $menu['link'] }}" title="">{{ $menu['label'] }}</a>
                  @if( $menu['child'] )
                    <ul class="dropdown-menu" aria-labelledby="menu{{ $menu['id'] }}">
                        @foreach( $menu['child'] as $child )
                            <a href="{{ $child['link'] }}"  title="" class="dropdown-item {!! Helper::active_class($child['link'],'active') !!}">{{ $child['label'] }}</a>
                        @endforeach
                    </ul><!-- /.sub-menu -->
                  @endif
              </li>
              @endforeach
          @endif
          
          
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">
    <div class="row">

      <!-- Blog Entries Column -->
      <div class="col-md-8">
  
        @yield('content')
  
      </div>
  
      <!-- Sidebar Widgets Column -->
      <div class="col-md-4">
  
        <!-- Search Widget -->
        <div class="card my-4">
          <h5 class="card-header">Search</h5>
          <div class="card-body">
            <form action="" class="form">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for..." name="s" value="{{ request()->s }}">
                <span class="input-group-btn">
                  <button class="btn btn-secondary" type="submit">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>
  
        <!-- Categories Widget -->
        @include('layouts.includes.widget.category')
  
        <!-- Tags Widget -->
        @include('layouts.includes.widget.tag')

        <!-- Archives Widget -->
        @include('layouts.includes.widget.archive')
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; {{ config('app.name', 'Laravel') }} {{ date('Y') }}</p>
    </div>
    <!-- /.container -->
  </footer>


</body>

</html>
