const { select,dispatch,withSelect, withDispatch } = wp.data;
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { CheckboxControl,FormTokenField,TextControl,SelectControl } = wp.components;

const { useState } = wp.element;
const { withState } = wp.compose;


//aditional
class ShowHide extends React.Component {
    constructor() {
      super();
      this.state = {
        childVisible: false
      }
    }
  
    render() {
      return (
        <div>
          <div onClick={() => this.onClick()}>
            <button className="components-button editor-post-taxonomies__hierarchical-terms-add is-link" type="button">{ this.props.title }</button>
          </div>
          {
            this.state.childVisible
              ? this.props.children
              : null
          }
        </div>
      )
    }
  
    onClick() {
      this.setState(prevState => ({ childVisible: !prevState.childVisible }));
    }
};


//------------- SETTING -------------- //

//permalink
class PermalinkSetting {
    constructor(newSlug = ""){
        this.currentUrlSlug = newSlug;
        this.registerComponent()
        this.renderHiddenElemen()
    }

    

    renderHiddenElemen = () =>{
        let htmls = `<input type="hidden" name="slug" value="${this.currentUrlSlug}" />`;
        const cek = $("#list-input-setting").find("#slug-input")
        if(cek.length){
            cek.html(htmls)
        } else {
            $("#list-input-setting").append(`<div id="slug-input">${htmls}</div>`)
        }
    }


    registerComponent = () =>{
        const _this = this
        class SlugInput extends React.Component {
            constructor(props) {
                super(props);
                
                var input = document.getElementById("title");
                if(input){
                    _this.currentUrlSlug = this.string_to_slug(input.value)
                }
                _this.renderHiddenElemen()
                this.state = { value: _this.currentUrlSlug };
                
            }
            

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
        
            onChangeHandler = (e) => {
                _this.currentUrlSlug = this.string_to_slug(e)
                this.setState({value: this.string_to_slug(e)});
                _this.renderHiddenElemen()
            
            }
        
            
            
            render() {
                return (
                    <TextControl
                            id="slug-input-setting"
                            label="URL Slug"
                            value={ this.state.value }
                            onChange={ this.onChangeHandler }
                        />
                );
            }
        }
        
        const PermalinkPanel = () => (
            <PluginDocumentSettingPanel
                name="permalink-panel"
                title="Permalink"
                opened={true}
                onToggle={true}
                isEnabled={true}
                initialOpen={true}>
                <SlugInput/>
            </PluginDocumentSettingPanel>
        );

        registerPlugin( 'plugin-document-setting-panel-permalink', { render: PermalinkPanel,icon: null } )
        dispatch('core/edit-post').toggleEditorPanelOpened('plugin-document-setting-panel-permalink/permalink-panel')
    
        
    }
    
}

//category
class CategorySetting {
    constructor(newCat = [],selectedCat = []){
        this.currentCategory = newCat;
        this.selectedCat = selectedCat
        this.registerComponent()
        this.renderHiddenElemen()

        
    }


    renderHiddenElemen = (subs = null) =>{
        const datas = (subs)?subs:this.currentCategory;
        let htmls = '';
        datas.map((cat, i) =>{
            if(cat.checked){
                htmls += `<input type="hidden" name="category[]" value="${cat.name}" />`
                
            }

            if(cat.subs.length){
                htmls += this.renderSubHiddenElemen(cat.subs)
            }
        })
        const cek = $("#list-input-setting").find("#category-input")
        if(cek.length){
            cek.html(htmls)
        } else {
            $("#list-input-setting").append(`<div id="category-input">${htmls}</div>`)
        }
        
    }

    renderSubHiddenElemen  = (subs) =>{
        const datas = subs;
        let htmls = '';
        datas.map((cat, i) =>{
            if(cat.checked){
                htmls += `<input type="hidden" name="category[]" value="${cat.name}" />`
                
            }

            if(cat.subs.length){
               
                htmls += this.renderSubHiddenElemen(cat.subs)
            }
        })
        return htmls;
        
    }


    registerComponent = () =>{
        const _this = this
        class InputCategory extends React.Component {
            constructor() {
                super();
                
                this.state = {
                    newCategoryName:"",
                    parentCategory:"Cat 1",
                    categorys: _this.currentCategory,
                };

                if(_this.selectedCat != null){
                    for (let i = 0; i < _this.selectedCat.length; i++) {
                        const element = _this.selectedCat[i];
                        let indexs = this.findCat(element)
                        
                        if(indexs.length){
                            this.setChecked(_this.currentCategory,indexs,true)
                        }
                        
                    }
                }
                
            }

            findCat = (search,subs = null) => {
                let indexs = [];
                const datas = (subs)?subs:this.currentCategory;
                let htmls = '';
                datas.map((cat, i) =>{
                    if(cat.name == search){
                        indexs.push(i)
                        
                    }
        
                    if(cat.subs.length){
                        subIndexs += this.findCat(search,cat.subs)
        
                        indexs = indexs.concat(subIndexs)
                    }
                })
        
                return indexs;
        
                
            }
        
            searchable =(e)=> {
                // Variable to hold the original version of the list
                let currentList = [];
                    // Variable to hold the filtered list before putting into state
                let newList = [];
        
                    // If the search bar isn't empty
                if (e.target.value !== "") {
                        // Assign the original list to currentList
                    currentList = _this.currentCategory;
        
                        // Use .filter() to determine which items should be displayed
                        // based on the search terms
                    newList = currentList.filter(item => {
                            // change current item to lowercase
                        const lc = item.name.toLowerCase();
                                // change search term to lowercase
                        const filter = e.target.value.toLowerCase();
                                // check to see if the current list item includes the search term
                                // If it does, it will be added to newList. Using lowercase eliminates
                                // issues with capitalization in search terms and search content
                        return lc.includes(filter);
                    });
                } else {
                        // If the search bar is empty, set newList to original task list
                        newList = _this.currentCategory;
                }
                    // Set the filtered state based on what our rules added to newList
                this.setState({
                    categorys: newList
                });
            }
        
            onChangeHandler =(i,c)=>{
                // this.currentCategory[i].checked = c
                this.setChecked(_this.currentCategory,i,c)
                _this.renderHiddenElemen()
                this.setState({ categorys: _this.currentCategory });
            }

            setChecked = (categories, categoryIndexes,c)=>{
                let obj = null;
                for (let index of categoryIndexes) {
                    obj = categories[index];
                    categories = obj.subs;
                }
                obj.checked = c;
            }

            getChecked = (categoryIndexs) => {
                let tmp_array = _this.currentCategory;
                for(var i = 0; i < categoryIndexs.length;i++){
                    if(i <= 0){
                        tmp_array = tmp_array[categoryIndexs[i]]
                    } else {
                        tmp_array = tmp_array.subs[categoryIndexs[i]]
                    }
                }
              
                return tmp_array.checked
            }
            
            renderSub(parentIndex = [],subs = []){

                return (
                    <div className="pl-2">
                        { subs.map((category, x) => {
                           
                            return (<div key={x}>
                                <CheckboxControl
                                    
                                    value={category.name}
                                    label={category.name}
                                    checked={this.getChecked(parentIndex.concat([x]))}
                                    onChange={( check ) => {
                                        //categorys[i].checked = check
                                    
                                        this.onChangeHandler(parentIndex.concat([x]),check)
                                    }}
                                />
                
                                {(category.subs.length > 0)?this.renderSub(parentIndex.concat([x]),category.subs):null}
                            </div>)
                            
                        })}
                    </div>
                );
            }


            dataSelectOption = (subs = null,padding = "") =>{
                const datas = (subs != null)?subs:this.state.categorys;
              
                let options = [];

                if(subs != null){
                    padding += "---";
                }

                for(var i = 0;i < datas.length;i++){
                    const e = datas[i];
                    options.push({
                        label:padding+e.name,value:e.name
                    });

                    if(e.subs.length){
                        options = options.concat(this.dataSelectOption(e.subs,padding))
                    }
                }

                return options;

            }

            submitNewCategory = () => {
                const {newCategoryName,parentCategory} = this.state
                fetch("/api/editor/new-category",{
                    method: 'POST',
                    headers: {
                      'Accept': 'application/json',
                      'Content-Type': 'application/json',
                      'Authorization':'Bearer '+window.api_token
                    },
                    body: JSON.stringify({
                        name: newCategoryName,
                        parent: parentCategory,
                    })
                  })
                .then(res => res.json())
                .then(
                    (result) => {
                        _this.currentCategory = result
                        this.setState({
                            categorys : result
                        })
                        this.setState( { newCategoryName:'',parentCategory:'' } )
                        //console.log(result)
                    },
                    (error) => {
                        console.log(error)
                    }
                )
                
            }
            
          
            render() {
                const { categorys } = this.state;
                console.log(categorys)
                let options = [
                    {
                        label:"-- Dont Have --",value:null
                    }
                ]
                const list = this.dataSelectOption()
                options = options.concat(list)
                
                return (
                    <div className="input-category">
                        <input type="text" className="form-control mb-4" placeholder="Search..." onChange={this.searchable} />
                        <div className="list-checkbox">
                            { categorys.map((category, i) =>{
                            
                            return (
                                <div key={i}>
                                    <CheckboxControl
                                        
                                        value={category.name}
                                        label={category.name}
                                        checked={categorys[i].checked}
                                        onChange={( check ) => {
                                            //categorys[i].checked = check
                                        
                                            this.onChangeHandler([i],check)
                                        }}
                                    />
                    
                                    {(category.subs.length > 0)?this.renderSub([i],category.subs):null}
                                </div>
                            )
                        })}
                        </div>
                        <ShowHide title="Add new category">
                            <TextControl
                            label="New Category Name"
                            value={ this.state.newCategoryName }
                            onChange={ ( newCategoryName ) => { this.setState( { newCategoryName } ) } }
                            />
                            <SelectControl
                                label="Parent Category"
                                value={ this.state.parentCategory }
                                options={ options }
                                onChange={ ( parentCategory ) => { this.setState( { parentCategory } ) } }
                            />

                            <button type="button" onClick={this.submitNewCategory} className="components-button is-secondary">Add New Category</button>
                        </ShowHide>
                    </div>
                );
            }
        }

        
        const CategoryPanel = () => (
            <PluginDocumentSettingPanel
                name="category-panel"
                title="Category"
                className="category-panel"
                opened={true}
                onToggle={true}
                isEnabled={true}
                initialOpen={true}>
                 <InputCategory />
            </PluginDocumentSettingPanel>
        );
        
        registerPlugin( 'plugin-document-setting-panel-category', { render: CategoryPanel,icon: null } );
        dispatch('core/edit-post').toggleEditorPanelOpened('plugin-document-setting-panel-category/category-panel')
        
    }
    
}


//tag
class TagSetting {
    constructor(newTag = [],sTag = []){
        this.currentTag = newTag;
        this.selectedTags = sTag;

        this.registerComponent()
        this.renderHiddenElemen()
    }

    renderHiddenElemen = () =>{
        let htmls = '';
        if(this.selectedTags != null){
            { this.selectedTags.map((tag, i) => (
                htmls += `<input type="hidden" name="tag[]" value="${tag}" />`
            ))}
        }
        
        const cek = $("#list-input-setting").find("#tag-input")
        if(cek.length){
            cek.html(htmls)
        } else {
            $("#list-input-setting").append(`<div id="tag-input">${htmls}</div>`)
        }
    }


    registerComponent = () =>{
        const _this = this
        
        const MyFormTokenField = withState({
            tags:_this.selectedTags,
            suggestions:_this.currentTag
        })( ( { tags,suggestions, setState } ) => ( 
            <PluginDocumentSettingPanel
                name="tag-panel"
                title="Tag"
                className="tag-panel"
                opened={true}
                onToggle={true}
                isEnabled={true}
                initialOpen={true}>   
                 <FormTokenField 
                    value={ tags } 
                    suggestions={ suggestions } 
                    onChange={ tags => {
                        setState( { tags } );
                        _this.selectedTags = tags
                        _this.renderHiddenElemen()
                        
                    } }
                    placeholder="Type a continent"
                />
            </PluginDocumentSettingPanel>
            
        ) );
    
        

        registerPlugin( 'plugin-document-setting-panel-tag', { render: MyFormTokenField,icon: null } );
        dispatch('core/edit-post').toggleEditorPanelOpened('plugin-document-setting-panel-tag/tag-panel')
        
    }
    
}

//featured image
class FeaturedImageSetting{
    constructor(newUrl = null){
     
        this.imgUrl = newUrl;
        this.registerComponent()
        this.renderHiddenElemen()
    }

    renderHiddenElemen = () =>{
        let htmls =  `<input type="hidden" name="featured_image" value="${this.imgUrl}" />`;
        
        const cek = $("#list-input-setting").find("#featured-image-input")
        if(cek.length){
            cek.html(htmls)
        } else {
            $("#list-input-setting").append(`<div id="featured-image-input">${htmls}</div>`)
        }
    }

    registerComponent = () => {
        const _this = this
        class TestPostFeaturedImage extends React.Component {
            constructor(props) {
                super(props);
                
            }
        
            onClick = (e) => {
                var route_prefix = '/filemanager';
                var target_input = $('#featured_image');
                var target_preview = $('#holder');
                window.open(route_prefix + '?type=image', 'FileManager', 'width=900,height=600');
                window.SetUrl = function (items) {

                    console.log(items)
                    var file_path = items.map(function (item) {
                        return item.url;
                    }).join(',');

                    
                    target_input.val('').val(file_path).trigger('change');
                    _this.imgUrl = file_path

                    items.forEach(function (item) {
                        target_preview.attr('src', item.thumb_url)
                    });
                    _this.renderHiddenElemen()
                };

                
                return false;
            
            }
        
            
            
            render() {
                return (
                    <div>
                        <div className="input-group">
                            <span className="input-group-btn">
                                <a id="lfm" onClick={this.onClick}  className="btn btn-primary text-white">
                                <i className="fa fa-picture-o"></i> Choose
                                </a>
                            </span>
                            <input id="featured_image" className="form-control" type="text" onChange={(e) => {
                                    console.log(e)
                                }
                            } value={_this.imgUrl}/>
                            <img id="holder" src={_this.imgUrl} onChange={(e) => {
                                    console.log(e)
                                }
                            }/>
                        </div>
                        
                    </div>
                );
            }
        }

        const MyFormFileUpload = () => (
            <PluginDocumentSettingPanel
                name="featured-image-panel"
                title="Featured Image"
                className="featured-image-panel"
                opened={true}
                onToggle={true}
                isEnabled={true}
                initialOpen={true}>   
                    <TestPostFeaturedImage/>
            </PluginDocumentSettingPanel>
            
        );

        registerPlugin( 'plugin-document-setting-panel-featured-image', { render: MyFormFileUpload,icon: null } );
        dispatch('core/edit-post').toggleEditorPanelOpened('plugin-document-setting-panel-featured-image/featured-image-panel')
       
    }
}







