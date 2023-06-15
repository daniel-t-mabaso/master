function request(request, arguments="", id=false) {
    /*
    * request: request string
    * arguments: any ~ seperated arguments to be used in ajax
    * id: id of where the response must be placed
    */
   var load = false;
   
   var formData = new FormData();
    if(request == 'save-alert'){
        var items = document.getElementsByClassName("alert-input");
        for(var i=0, l=items.length; i<l; i++){
            if(i>0){arguments += "~";}
            arguments +=  escape(items[i].value);
        }
        load = "load-alerts";
        
    }
    if (request.length == 0) { 
        popUp('danger','Error: Unknown request.');
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(!id){
                    if(this.responseText.includes("Error:")){
                        popUp("danger", this.responseText);
                        console.log(this.response);
                    }
                    else{
                        popUp("success", this.responseText);
                        // console.log(this.response);
                        if (load =='load-alerts'){
                            closeAddPanel();
                        }else{

                        }

                    }
                } else if (id == 'populate-alert-editor') {
                    var data = this.responseText.split(" [~] ");
                    var items = document.getElementsByClassName("alert-input");
                    var title = document.getElementById('add-panel-title');
                    title.innerHTML = "Edit Alert";
                    toggleAddPanel();
                    var button = document.getElementById('add-alert-button');
                    button.innerHTML = "SAVE";
                    for (var i=0, l=items.length; i<l; i++){
                            items[i].value = (((!isNaN(data[i]) && data[i] > 0) || isNaN(data[i])) ? data[i] : '');
                    }
                    console.log(data);
                } else if (id.includes('populate-alert-modal')) {
                    var data = this.responseText.split(" [~] ");
                    console.log(data[0]);
                    $("#view-alert-modal h2").html(data[0]);
                    $("#view-alert-modal p").html(data[1]);
                    $("#view-alert-modal .view").attr("href", data[3]);
                    $("#view-alert-modal .download").attr("href", data[3]);
                } else {
                    document.getElementById(id).innerHTML = this.responseText;
                }
            }else{
                // popUp("danger", this.responseText);
            }
        };
        xmlhttp.open("POST", "./assets/php/server.php?request=" + request + "&arguments=" + arguments, true);
        xmlhttp.send(formData);
    }
}

function forward_request(request, arguments="", id=false){
    request(request, arguments, id);
}

function popUp(type, msg){
    var div = document.getElementById('small-toast');
    div.innerHTML = msg ?? "An Error has occured.";
    if(div.classList.contains('hide')){
        div.classList.remove('hide');
    }
    if(type == "success"){
        if(div.classList.contains("danger-bg")){
            div.classList.remove("danger-bg");
        }
        if(!div.classList.contains("success-bg")){
            div.classList.add("success-bg");
            div.classList.add("white-txt");
        }
    }
    else if(type == "danger"){
        if(div.classList.contains("success-bg")){
            div.classList.remove("success-bg");
        }
        if(!div.classList.contains("danger-bg")){
            div.classList.add("danger-bg");
            div.classList.add("white-txt");
        }
    }

    setTimeout(closePopUp, 10000);

}

function closePopUp(){
    var div = document.getElementById('small-toast');
    div.innerHTML = "";
    if(!div.classList.contains('hide')){
        div.classList.add('hide');
    }
    if(div.classList.contains("success-bg")){
        div.classList.remove("success-bg");
        div.classList.remove("white-txt");
    }else if(div.classList.contains("danger-bg")){
        div.classList.remove("danger-bg");
        div.classList.remove("white-txt");
    }
}