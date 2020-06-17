@extends('layouts.app')



@section('content')
    <!-- Title -->
    <h1 class="mt-4">{{ $page->title }}</h1>

    <!-- Author -->
    <p class="lead">
        by
        <a href="{{ url('author/'.$page->User->email) }}">{{ $page->User->name }}</a>
    </p>

    <hr>

    <!-- Date/Time -->
    <p>Posted on {{ $page->created_at->format('F m,Y') }}</p>

    <hr>

   
    <hr>

    <!-- Post Content -->
    {!! $page->content !!}

    <hr>
    <div id="disqus_thread" class="my-4"></div>
  
@endsection
@include('layouts.includes.widget.comment')