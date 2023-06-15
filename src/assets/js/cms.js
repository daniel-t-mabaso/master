function cms(request){
    switch(request){
        case 'new-section':
            var doc = document.getElementById('html-content');
            var old = doc.innerHTML;
            doc.innerHTML = old + "<div class='row hide-overflow white-bg max-width small-height primary-txt'><div class='blurred'></div><div class='background-media hide-overflow'><img id='background-image-url' alt='background image' class='max-width' src='assets/media/images/placeholder.jpg'/></div><div class='content center small-side-padding  small-height medium-padding' onmouseover='getEditButtons(this.children[0]);'><div class='edit-buttons float-right'></div></div</div>";
            break;
    }
}

function toggleEditPanel(){
    document.getElementById("edit-page-panel").classList.toggle("hide");
    var txt = document.getElementById("edit-page-button").innerHTML;
    if (txt.localeCompare('Hide Panel')){
        document.getElementById("edit-page-button").innerHTML = "Hide Panel";}
    else{
        document.getElementById("edit-page-button").innerHTML= "Edit Content";
    }
}

var row;
function editSection(obj){
    var div = document.getElementById('section-edit');
    var m = document.getElementById('pop-up-msg');
    m.innerHTML = '<div class="subheading">Section Editor</div>';
    div.classList.toggle('hide');
    document.getElementById('screen-cover').classList.toggle('hide');
    row = obj;
}
function editCard(obj){
    var div = document.getElementById('section-edit');
    var m = document.getElementById('pop-up-msg');
    m.innerHTML = '<div class="subheading">Section Editor</div>';
    div.classList.toggle('hide');
    document.getElementById('screen-cover').classList.toggle('hide');
    row = obj;
}
function updateMediaUrlInput(obj){
    if(obj.checked){
        document.getElementById('media-url').classList.remove('hide');
        document.getElementById('background-colour-tool').classList.add('hide');
    }else{
        document.getElementById('media-url').classList.add('hide');
        document.getElementById('background-colour-tool').classList.remove('hide');
    }
}
function updateListFields(){
    var val = document.getElementById("list-type-value").value;
    var links = document.getElementsByName('list-item-link');
    if (val.includes('ll')){
        links.forEach(className => {
                className.classList.remove("hide");
        });
    }
    else{
        links.forEach(className => {
                className.classList.add("hide");
        });
    }
}
function updateFormFields(){
    var val = document.getElementById("form-type-value").value;
    var custom = document.getElementById("form-input-panel");
    var email = document.getElementById("form-email");
    if (val.includes('custom')){
        custom.classList.remove("hide");
        email.classList.remove("hide");
    }
    else if (val.includes('contact')){
        custom.classList.add("hide");
        email.classList.remove("hide");
    }
    else{
        email.classList.add("hide");
        custom.classList.add("hide");
    }
}
function previewSection(obj){
    var div = document.getElementById('section-edit');
    var m = document.getElementById('pop-up-msg');
    m.innerHTML = '<div class="subheading">Section Editor</div>';
    div.classList.toggle('hide');
    document.getElementById('screen-cover').classList.toggle('hide');

    var action = document.getElementById('section-edit-action');
    
    if(row && action!=null){
        var content = row.parentElement;
        if(row.classList.contains('card-edit-buttons')){
            content = row;
        }
        var color = '';
        switch (action.value){
            case 'style':
            
            color = document.getElementById('bg-colour');
            content.parentElement.classList.forEach(className => {
            if (className.includes('-bg')) {
                content.parentElement.classList.remove(className);
            }
            });
            if(document.getElementById('media-background-check').checked){
                var url = document.getElementById('media-url').value;
                var img = document.getElementById('background-image-url');

                img.src = 'assets/media/images/'+url;
            }
            else{
              content.parentElement.classList.add(color.value+'-bg');
            }
                var height = document.getElementsByName('height');
                var oldHeight = '';
                content.parentElement.classList.forEach(className => {
                    if (className.includes('-height')) {
                        content.parentElement.classList.remove(className);
                        oldHeight = className;
                    }
                  });
                height.forEach(className => {
                    if (className.checked) {
                        if(!className.value.includes('auto')){
                        content.parentElement.classList.add(className.value+'-height');
                        }
                        else{
                            content.parentElement.classList.add(oldHeight);
                        }                   
                    }
                  });
                    
            break;

            case 'clear':
                content.innerHTML = content.innerHTML.slice(0,434);
                content.parentElement.classList.forEach(className => {
                    if (className.includes('-bg')) {
                        content.parentElement.classList.remove(className);
                    }
                  });
                break;

            case 'text':
            var color = document.getElementById('font-colour');
            var string = document.getElementById('edit-long-text-input').value.replace(/(?:\r\n|\r|\n)/g, '<br>');
            var style = document.getElementById('font-style-value').value;
            var classes = color.value+'-txt '+style+' ';
            var pos = document.getElementsByName('position');
            var alignment = document.getElementsByName('alignment');
            var width = document.getElementsByName('width');
            
            pos.forEach(className => {
                if (className.checked) {
                    classes += className.value+' ';
                }
              });
            
            width.forEach(className => {
                if (className.checked) {
                    classes += className.value+"-width ";
                }
              });
            
            alignment.forEach(className => {
                if (className.checked) {
                    classes += className.value+"-txt ";
                }
              });

            content.innerHTML += "<div class='"+classes+"'>"+string+"</div>";
            break;

            case 'button':
                var color = document.getElementById('bg-colour');
                var classes = color.value+'-bg ';
                color = document.getElementById('font-colour');
                classes += color.value+'-txt ';
                var text = document.getElementById('edit-short-text-input').value;
                var link = document.getElementById('edit-link-text-input').value;
                
                var pos = document.getElementsByName('position');
            
            pos.forEach(className => {
                if (className.checked) {
                    classes += className.value+" ";
                }
              });
              content.innerHTML += "<a href='"+link+"'><div class='button "+classes+"'>"+text+"</div></a>";
            break;

            case 'circle':
                var text = document.getElementById('edit-short-text-input').value.slice(0,1).toUpperCase();
                var color = document.getElementById('bg-colour');
                var classes = color.value+'-bg ';
                color = document.getElementById('font-colour');
                classes += color.value+'-txt ';
                var pos = document.getElementsByName('position');
            
                pos.forEach(className => {
                    if (className.checked) {
                        classes += className.value+" ";
                    }
                });
                content.innerHTML += "<div class='small-padding'><div class='extra-small-square  hide-overflow "+classes+" extra-small-square-line-height circle center-txt'><div class='title bold'>"+text+"</div></div></div>";
            break;

            case 'list':
            var listType = document.getElementById('list-type-value').value;
            var heading = document.getElementById('edit-short-text-input').value;;
            var items = document.getElementsByName('list-item-text');
            var links = document.getElementsByName('list-item-link');
            var alignment = document.getElementsByName('alignment');

            var string = '';
            var classes='';

            var width = document.getElementsByName('width');
            width.forEach(className => {
                if (className.checked) {
                    classes += className.value+"-width ";
                }
              });
            var pos = document.getElementsByName('position');
            
                pos.forEach(className => {
                    if (className.checked) {
                        classes += className.value+" ";
                    }
                });
                
            alignment.forEach(className => {
                if (className.checked) {
                    classes += className.value+"-txt ";
                }
              });
            if(listType.includes('ul')){
                string += '<ul><div class="list-heading">'+heading+'</div>';
                for (var i=0, l = items.length; i<l; i++){
                    string+='<li class="list-item">';
                    
                    if(listType=='ull'){string+= '<a href="'+links[i].value+'">'}
                        string+= items[i].value;
                    if(listType=='ull'){string+= '</a>'}
                    
                    string+='</li>'
                }
                string +='</ul>';
            }
            else if(listType.includes('ol')){
                string += '<ol><div class="list-heading">'+heading+'</div>';
                for (var i=0, l = items.length; i<l; i++){
                    string+='<li class="list-item">';
                    
                    if(listType=='oll'){string+= '<a href="'+links[i].value+'">'}
                        string+= items[i].value;
                    if(listType=='oll'){string+= '</a>'}
                    
                    string+='</li>'
                }
                string +='</ol>';
            }

            content.innerHTML += "<div class='"+classes+"'>"+string+"</div>";
            break;

            case 'card':
                var color = document.getElementById('bg-colour');
                var classes = color.value+'-bg ';
                var width = document.getElementsByName('width');
                width.forEach(className => {
                    if (className.checked) {
                        classes += className.value+"-width ";
                    }
                });
                var pos = document.getElementsByName('position');
                
                    pos.forEach(className => {
                        if (className.checked) {
                            classes += className.value+" ";
                        }
                    });
                var height = document.getElementsByName('height');
                
                    height.forEach(className => {
                        if (className.checked) {
                            classes += className.value+"-height ";
                        }
                    });
            
            content.innerHTML += "<div class='card "+classes+" white-bg shadow small-side-padding  small-padding rounded' onmouseover='getCardEditButtons(this.children[0]);'><div class='card-edit-buttons float-right'></div></div>";
            break;
            case 'image':
            break;
            case 'video':
            break;
            case 'form':
            var email = document.getElementById('form-email').value;
            var type = document.getElementById('form-type-value').value;
            var classes = '';
            var width = document.getElementsByName('width');
            width.forEach(className => {
                if (className.checked) {
                    classes += className.value+"-width ";
                }
            });
            var pos = document.getElementsByName('position');
            
                pos.forEach(className => {
                    if (className.checked) {
                        classes += className.value+" ";
                    }
                    });

            var string = '<div class="small-padding '+classes+'"><form class="max-width" name="form" method="post" action="forms.php">';
            if(type=='contact'){
                string += "<div class='subheading'>Contact us</div>";
                string += "<input class='form-input text-box extra-small-padding block border rounded' type='text' placeholder='What is your name?' name='form-name'/>";
                string += "<input class='form-input text-box extra-small-padding block border rounded' type='email' placeholder='What is your email?' name='form-input'/>";
                string += "<input class='form-input text-box extra-small-padding block border rounded' type='text' placeholder='What is the subject?' name='form-input'/>";
                string += "<textarea class='form-input text-box extra-small-padding block border rounded' id='edit-long-text-input' rows='15' cols='30' placeholder='What is your message?' name='form-input'></textarea>";
                string += "<br><input type='checkbox' name='send'/> I am a human?";
                string += "<input class='button primary-bg white-txt block center' type='submit' name='submit-form' value='Send'/>";
            }
            else if(type=='custom'){
                var heading =  document.getElementById('form-heading').value;
                string += "<div class='subheading'>"+heading+"</div>";
                var inputs= [];
                if(document.getElementsByName('text-input-label')){
                    input = document.getElementsByName('text-input-label');
                    input.forEach(className => {
                        string += "<input class='form-input text-box extra-small-padding block border rounded' type='text' placeholder='"+className.value+"' name='form-input'/>";
                    });
                }
                var textAreas = [];
                if(document.getElementsByName('text-area-label')){
                    textAreas = document.getElementsByName('text-area-label');
                    textAreas.forEach(className => {
                        string += "<textarea class='form-input text-box extra-small-padding block border rounded' id='edit-long-text-input' rows='10' cols='30' placeholder='"+className.value+"' name='form-input'></textarea>";
                    });
                }
                var submit =  document.getElementById('submit-text').value;
                string += "<input class='button primary-bg white-txt block center' type='submit' name='submit-form' value='"+submit+"'/>";
            }
            string +='<input type="hidden" name="sendTo" value="'+email+'"/></form></div>';
            content.innerHTML += string;
            break;
        }
    }
    row = obj;
}

function deleteSection(obj){
    obj.parentNode.parentNode.removeChild(obj.parentNode);
}

function changeSectionEditorActionPanel(){
    var action = document.getElementById('section-edit-action').value;
    var panels = document.getElementsByClassName('section-tool');

    for(var i=0, l = panels.length; i<l; i++){
        if(!panels[i].classList.contains('hide')){
            panels[i].classList.add('hide');
        }
    }

    width = document.getElementById('width-tool');
    fontStyle = document.getElementById('font-style-tool');
    size = document.getElementById('size-tool');
    height = document.getElementById('height-tool');
    fontColour = document.getElementById('font-colour-tool');
    backgroundColour = document.getElementById('background-colour-tool');
    shortText = document.getElementById('short-text-tool');
    longText = document.getElementById('long-text-tool');
    linkText = document.getElementById('link-text-tool');
    position = document.getElementById('position-tool');
    textAlignment = document.getElementById('text-alignment-tool');
    listType = document.getElementById('list-type-tool');
    form = document.getElementById('form-tool');
    mediaUrl = document.getElementById('media-url-tool');
    switch (action){
        case 'style':
        height.classList.remove('hide');
        backgroundColour.classList.remove('hide');
        mediaUrl.classList.remove('hide');
        break;
        case 'text':
        fontStyle.classList.remove('hide');
        fontColour.classList.remove('hide');
        longText.classList.remove('hide');
        position.classList.remove('hide');
        width.classList.remove('hide');
        textAlignment.classList.remove('hide');
        break;
        case 'button':
        fontColour.classList.remove('hide');
        backgroundColour.classList.remove('hide');
        shortText.classList.remove('hide');
        linkText.classList.remove('hide');
        position.classList.remove('hide');
        break;
        case 'circle':
        fontColour.classList.remove('hide');
        backgroundColour.classList.remove('hide');
        shortText.classList.remove('hide');
        position.classList.remove('hide');
        break;
        case 'list':
        width.classList.remove('hide');
        listType.classList.remove('hide');
        shortText.classList.remove('hide');
        position.classList.remove('hide');
        textAlignment.classList.remove('hide');
        break;
        case 'card':
        backgroundColour.classList.remove('hide');
        position.classList.remove('hide');
        width.classList.remove('hide');
        height.classList.remove('hide');
        break;
        case 'image':
        position.classList.remove('hide');
        width.classList.remove('hide');
        height.classList.remove('hide');
        break;
        case 'video':
        position.classList.remove('hide');
        width.classList.remove('hide');
        height.classList.remove('hide');
        break;
        case 'form':
        width.classList.remove('hide');
        position.classList.remove('hide');
        form.classList.remove('hide');
        break;
    }
}
function getEditButtons(obj){
    obj.style.display = 'none';
    var loaded = obj.classList.contains('edit-bottons-loaded');
    if (document.getElementById('editButtonHolder') && !loaded){
    obj.innerHTML = document.getElementById('editButtonHolder').innerHTML;}
    setTimeout(function(){
        obj.innerHTML = document.getElementById('editButtonHolder').innerHTML;
    }, 400);
    setTimeout(function(){
        loaded = obj.classList.add('edit-bottons-loaded');
    }, 500);
    
    obj.style.display = 'block';
}
function getCardEditButtons(obj){
    obj.style.display = 'none';
    var loaded = obj.classList.contains('edit-bottons-loaded');
    if (document.getElementById('cardEditButtonHolder') && !loaded){
    obj.innerHTML = document.getElementById('cardEditButtonHolder').innerHTML;}
    setTimeout(function(){
        obj.innerHTML = document.getElementById('cardEditButtonHolder').innerHTML;
    }, 400);
    setTimeout(function(){
        loaded = obj.classList.add('edit-bottons-loaded');
    }, 500);
    
    obj.style.display = 'block';
}

function addListItems(obj){
    var val = document.getElementById("list-type-value").value;
    document.getElementById('list-input-panel').innerHTML += '<br><input class="form-input text-box extra-small-padding block border rounded" type="text" placeholder="List item text" name="list-item-text"/>';
    if (!val.includes('ll')){
        document.getElementById('list-input-panel').innerHTML += '<input class="form-input text-box tertiary-bg extra-small-padding hide block border rounded" type="text" placeholder="List item link" name="list-item-link"/>';
    }else{
        document.getElementById('list-input-panel').innerHTML += '<input class="form-input text-box tertiary-bg extra-small-padding block border rounded" type="text" placeholder="List item link" name="list-item-link"/>';
    }
}
function addFormItems(obj){
    var button = obj;
    
    if (button.id == 'add-short'){
        document.getElementById('form-input-fields').innerHTML += '<input class="form-input text-box extra-small-padding block border rounded" type="text" placeholder="Short input field label" name="text-input-label"/>';
    }else if (button.id == 'add-long'){
        document.getElementById('form-input-fields').innerHTML += '<input class="form-input text-box alternative-bg extra-small-padding block border rounded" type="text" placeholder="Long input field label" name="text-area-label"/>';
    }
}
