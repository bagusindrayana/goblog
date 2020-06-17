<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $action }} Page - {{ $page->title ?? "New Page" }}</title>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />

    <script src="{{ asset('js/app.js') }}" ></script>
    
    <script src="{{ asset('admin/js/scripts.js') }}"></script>
    <link href="{{ asset('admin/web-builder/css/grapes.min.css') }}" rel="stylesheet">
    <script src="{{ asset('admin/web-builder/grapes.min.js') }}"></script>
    <script src="{{ asset('admin/web-builder') }}/grapesjs-blocks-bootstrap4.min.js"></script>

    <style>
      body,
      html {
        height: 100%;
        margin: 0;
      }

      .panel__top {
        padding: 0;
        width: 100%;
        display: flex;
        position: initial;
        justify-content: center;
        justify-content: space-between;
      }
      .panel__basic-actions {
        position: initial;
      }

  
    </style>
  </head>

  <body>
    <div class="panel__top">
      <div class="panel__basic-actions"></div>
  </div>
    <div id="gjs" style="height:0px; overflow:hidden;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>
                        Collumn 12
                    </h1>
                </div>
                <div class="col-md-6">
                    <h1>
                        Collumn 6
                    </h1>
                </div>
                <div class="col-md-6">
                    <h1>
                        Collumn 6
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis harum odit hic amet architecto, officia dignissimos blanditiis nostrum optio cupiditate recusandae consequatur ratione exercitationem possimus accusamus, itaque ipsum, deserunt similique.</p>
                    <img src="https://dummyimage.com/800x500/999/222" class="img-fluid"/>
                </div>
                
            </div>
            <div class="row">
              <div class="col-md-12">
                <P>Footer</P>
              </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Page</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="title">Page Title</label>
              <input type="text" class="form-control" id="title" value="{{ @$page->title }}" required maxlength="200" placeholder="Page Title..." name="title">
            </div>
            <div class="form-group">
              <label for="caption">Page Caption/Description</label>
              <textarea name="caption" id="caption" class="form-control">{{ @$page->caption }}</textarea>
            </div>
  
           
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button class="btn btn-sm btn-info" name="status" id="saveDraft" type="button" value="Draft">
                Save Draft
            </button>
            <button class="btn btn-sm btn-success" name="status" id="publishPage" type="button" value="Publish">
                Publish
            </button>
          </div>
        </div>
      </div>
    </div>

  

    <script type="text/javascript">
      $(document).ready(function(){
        var editor = grapesjs.init({
          height: '90vh',
          showOffsets: 1,
          noticeOnUnload: 0,
          storageManager: {
              autosave: false,
              setStepsBeforeSave: 1,
              type: 'remote',
              urlStore: '{{ route("admin.page.save.web-builder") }}',
              urlLoad: '{{ route("admin.page.load.web-builder",($page->id ?? 0)) }}',
              contentTypeJson: true,
              params: { 
                _token: '{{ csrf_token() }}',
                title : $("#title").val(),
             
              },
          },
          container: '#gjs',
          fromElement: true,
          plugins: ['grapesjs-blocks-bootstrap4'],
            pluginsOpts: {
                'grapesjs-blocks-bootstrap4': {
                blocks: {
                    // ...
                },
                blockCategories: {
                    // ...
                },
                labels: {
                    // ...
                },
                // ...
                }
            },
            canvas: {
                styles: [
                'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'
                ],
                scripts: [
                'https://code.jquery.com/jquery-3.3.1.slim.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
                'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
                ],
            },
          assetManager: {
            storageType  	: '',
                storeOnChange  : true,
                storeAfterUpload  : true,
                upload: 'https://localhost/assets/upload',        //for temporary storage
                assets    	: [ ],
                uploadFile: function(e) {
                  alert("okoc")
                },
          },
        });

        function savePage(status){
            editor.StorageManager.init({
                autosave: false,
                setStepsBeforeSave: 1,
                type: 'remote',
                urlStore: '{{ (isset($page))?route("admin.page.update.web-builder",$page->id):route("admin.page.save.web-builder") }}',
        
                contentTypeJson: true,
                params: { 
                    _token: '{{ csrf_token() }}',
                    title : $("#title").val(),
                    status:status
                    
                }
            })
            editor.store(res=>{
                if(res.status == 200){
                    window.location = '{{ route("admin.page.index") }}'
                } else {
                    alert('Ops page failed to save')
                }
            
            });
        }

        $(document).on('click','#publishPage',function(e){
            savePage("Publish")
        });

        $(document).on('click','#saveDraft',function(e){
            savePage("Draft")
        });

        editor.Panels.addButton
          ('options',
            [{
              id: 'save-db',
              className: 'fa fa-floppy-o',
              command: 'save-db',
              attributes: {title: 'Save DB'}
            }]
        );

        editor.Commands.add
          ('save-db', {
            run: function(editor, sender)
            {
              sender && sender.set('active',0); // turn off the button
              $('#exampleModal').modal('toggle')
              
            }
        });

        editor.Commands.add('open-assets', {
            run(editor,sender, opts = {}) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=Images', 'FileManager', "width="+(screen.availWidth-100)+",height="+(screen.availHeight-100));
                window.SetUrl = function (items) {
    
                  items.forEach(function (item) {
                    opts.target.set('src',item.url)
                  });
                };
            }
        })
        
        editor.on('storage:load', function(e) { console.log('Loaded ', e);});
        editor.on('storage:store', function(e) {
          console.log('Stored ', e);
          
        });

        editor.Panels.addPanel({
          id: 'panel-top',
          el: '.panel__top',
        });
        editor.Panels.addPanel({
          id: 'basic-actions',
          el: '.panel__basic-actions',
          buttons: [
            {
              id: 'back-to-dashboard',
              active: false,
              className: 'btn-toggle-borders',
              label: '<u>Back To Dashboard</u>',
              command:'back-to-dashboard'
              
            }
          ],
        });

        editor.Commands.add
          ('back-to-dashboard', {
            run: function(editor, sender)
            {
              window.location = '{{ (isset($data))?route("admin.page.edit",$data->id):route("admin.page.create") }}'
              
            }
        });

      });
        
    </script>


  </body>
</html>