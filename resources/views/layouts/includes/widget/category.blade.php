<div class="card my-4">
    <h5 class="card-header">Categories</h5>
    <div class="card-body">
      <div class="row">
        @foreach (Helper::categoryList() as $slug => $name)
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="{{ route('blog.filter.category',$slug) }}">{{ $name }}</a>
                    </li>
                </ul>
            </div>
        @endforeach
      </div>
    </div>
</div>