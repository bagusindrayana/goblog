<div class="card my-4">
    <h5 class="card-header">Tags</h5>
    <div class="card-body">
      <div class="row">
        @foreach (Helper::tagList() as $slug => $name)
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="{{ route('blog.filter.tag',$slug) }}">{{ $name }}</a>
                    </li>
                </ul>
            </div>
        @endforeach
      </div>
    </div>
</div>