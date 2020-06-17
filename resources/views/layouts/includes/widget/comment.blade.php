@php
    $currentUrl=Request::url(); //gets the current url of the post
    $pageIndentifier='route/'. implode(Request::segments(),"/"); //page indentifier
@endphp
@push('scripts')
    <script>

       
        var disqus_config = function () {
            this.page.url = '{{$currentUrl}}';
            this.page.identifier = '{{ $pageIndentifier }}';
        };
        (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = '{{ env("EMBED_DISQUS") }}';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
@endpush