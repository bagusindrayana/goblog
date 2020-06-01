@extends('admin.layouts.app')

@section('content')

@push('styles')

    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">

    <style>
        /* for mobile */
        #laraberg__editor {
            height: 60vh !important;
        }

        /* for desktop */
        @media only screen and (min-width: 768px) {
            #laraberg__editor {
                height: auto !important;
            }
        }
    </style>
  
@endpush
<form action="{{ route('admin.post.store') }}" class="form" method="POST" id="form">
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <a class="btn btn-info text-white" name="status" type="button" value="Draft">
                                Back To Dashboard
                            </a>
                        </div>
                        <div class="float-right">
                            <button class="btn btn-light" name="status" type="submit" value="Draft">
                                Save Draft
                            </button>
                            <button class="btn btn-success" name="status" type="submit" value="Publish">
                                Publish
                            </button>
                        </div>
                        
                    </div>

                    <div class="card-body">
                        
                        
                            <div class="form-group">
                                {{-- <label for="title">Title</label> --}}
                                <input type="text" class="form-control" name="title" id="title" required placeholder="Title">
                            </div>
                            <div class="form-group" >
                                {{-- <label for="content">Content</label> --}}
                                <textarea id="content" name="content" hidden></textarea>
                                <div style="display: none" id="list-input-setting">
                                
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-light" name="status" type="submit" value="Draft">
                                    Save Draft
                                </button>
                                <button class="btn btn-success" name="status" type="submit" value="Publish">
                                    Publish
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
    <script src="https://unpkg.com/react@16.8.6/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.development.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('js/jsx.js') }}" type="text/babel"></script>
    <script>
        $(document).ready(function(){
            Laraberg.init('content',{laravelFilemanager: true});  
            string_to_slug = (str) => {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();
              
                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to   = "aaaaeeeeiiiioooouuuunc------";
                for (var i=0, l=from.length ; i<l ; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }
            
                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes
            
                return str;
            }

            $(document).on('change','#title',function(){
                var input = document.getElementById("slug-input-setting");
                if(input){
                    var setValue = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
                    setValue.call(input, string_to_slug($(this).val()));
                    var e = new Event('input', { bubbles: true });
                    input.dispatchEvent(e);
                } else {
                    input = document.getElementById("slug-input");
            
                    var setValue = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
                    setValue.call(input.childNodes[0], string_to_slug($(this).val()));
                    var e = new Event('input', { bubbles: true });
                    input.dispatchEvent(e);
                }
                
                
            })

            setTimeout(function(){
                const pm = new PermalinkSetting(`{{ old("slug") }}`)
                const ct = new CategorySetting({!! json_encode($categories) !!},{!! json_encode(old('category',[])) !!})
                const tg = new TagSetting({!! json_encode($tags) !!},{!! json_encode(old('tags',[])) !!})
                const fi = new FeaturedImageSetting(`{{ old("featured_image") }}`)
            },2000)
            
        })

        
    </script>
    
@endpush
