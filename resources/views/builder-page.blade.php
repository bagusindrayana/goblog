<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Bagus Indrayana">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{ $page->caption   }}">
  <title>{{ $page->title }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ url('favicon.ico') }}">

  
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />

  <script src="{{ asset('js/app.js') }}" ></script>
  
  <script src="{{ asset('admin/js/scripts.js') }}"></script>

</head>

<body>
  <div class="page-wrapper">
      {!! $page->content !!}
  </div>
  
</body>
</html>