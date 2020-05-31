@extends('admin.layouts.app')

@section('content')

@push('styles')

    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
  

    <style>
       
        .input-tag {
            background: white;
            border: 1px solid #d6d6d6;
            border-radius: 2px;
            display: flex;
            flex-wrap: wrap;
            padding: 5px 5px 0;
        }
        .input-tag input {
            border: none;
            width: 100%;
        }
        .input-tag__tags {
            display: inline-flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .input-tag__tags li {
            align-items: center;
            background: #85a3bf;
            border-radius: 2px;
            color: white;
            display: flex;
            font-weight: 300;
            list-style: none;
            margin-bottom: 5px;
            margin-right: 5px;
            padding: 5px 10px;
        }
        .input-tag__tags li button {
            align-items: center;
            appearance: none;
            background: #333;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: inline-flex;
            font-size: 12px;
            height: 15px;
            justify-content: center;
            line-height: 0;
            margin-left: 8px;
            padding: 0;
            transform: rotate(45deg);
            width: 15px;
        }
        .input-tag__tags li.input-tag__tags__input {
            background: none;
            flex-grow: 1;
            padding: 0;
        }
        
    </style>
@endpush

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <b>Add Post</b>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-light">
                            Save Draft
                        </button>
                        <button class="btn btn-success">
                            Publish
                        </button>
                    </div>
                    
                </div>

                <div class="card-body">
                    
                    <form action="{{ route('admin.post.update',$post->id) }}" class="form" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" required placeholder="Title" value="{{ old('title',@$post->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" name="content" hidden>{{$post->content}}</textarea>
                            <div style="display: none" id="list-input-setting">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success">
                                Publish
                            </button>
                        </div>

              
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
    <script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script src="{{ asset('js/jsx.js') }}" type="text/babel"></script>
    <script>
        $(document).ready(function(){
            Laraberg.init('content');  
            
            setTimeout(function(){
                const pm = new PermalinkSetting('{{ $post->slug }}')
                const ct = new CategorySetting({!! json_encode($categories) !!})
                const tg = new TagSetting({!! json_encode($tags) !!},{!! json_encode($post->Tags()->pluck('name')) !!})
                const fi = new FeaturedImageSetting('{{ $post->featured_images }}')
                
            },2000)
            
        })

        
    </script>
    
@endpush
