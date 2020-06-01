@extends('layouts.app')

@section('content')
  <h1 class="my-4">{{ @$title }}
    <small>{{ @$subTitle }}</small>
  </h1>

  @forelse ($posts as $post)
    <div class="card mb-4">
      <img class="card-img-top" src="{{ $post->featured_image ?? 'http://placehold.it/750x300' }}" alt="Card image cap">
      <div class="card-body">
        <h2 class="card-title">{{ $post->title }}</h2>
        <p class="card-text">{!! strip_tags($post->min_content) !!}</p>
        <br>
        <a href="{{ url($post->link) }}" class="btn btn-primary ">Read More &rarr;</a>
      </div>
      <div class="card-footer text-muted">
        Posted on {{ $post->created_at->format('F d,Y') }} by
        <a href="{{ url('author/'.$post->User->email) }}">{{ $post->User->name }}</a>
      </div>
    </div>
  @empty
    <p>
      No Post Yet :(
    </p>
  @endforelse
  





  <!-- Pagination -->
  <div class="justify-content-center mb-4">
    {!! $posts->links() !!}
  </div>

@endsection
