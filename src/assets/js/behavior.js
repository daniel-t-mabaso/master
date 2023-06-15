window.addEventListener("scroll", function(){
    var target = document.getElementById('top');
    if(window.pageYOffset > 500){
        if(target)
            target.style.display = "block"; 
    }
    else if(window.pageYOffset < 500){
        if(target)
            target.style.display = "none";
    }
  },false);
  

function scrollToTop(){
    var c = document.documentElement.scrollTop || document.body.scrollTop;
    if (c > 0) {
      window.requestAnimationFrame(scrollToTop);
      window.scrollTo(0, c - c / 8);
    }
  };

function edit_page(){
    var forms = document.getElementsByClassName("dashboard-form");
    var page = 'home';

    console.log(page);
    switch (page){
        case "home":
            console.log("Showing home");
            for(var i = 0; i < forms.length; i++){
                if(!forms[i].classList.contains('hide')){
                    forms[i].classList.add("hide");}
                if (document.getElementById('home-page-form').classList.contains('hide')){
                document.getElementById('home-page-form').classList.remove('hide');}
            }
        break;
        case "services":
            console.log("Showing services");
            for(var i = 0; i < forms.length; i++){
                if(!forms[i].classList.contains('hide')){
                    forms[i].classList.add("hide");}
                if (document.getElementById('services-page-form').classList.contains('hide')){
                document.getElementById('services-page-form').classList.remove('hide');}
            }
        break;
        case "about":
        console.log("Showing about");
        for(var i = 0; i < forms.length; i++){
            if(!forms[i].classList.contains('hide')){
                forms[i].classList.add("hide");}
            if (document.getElementById('about-page-form').classList.contains('hide')){
            document.getElementById('about-page-form').classList.remove('hide');}
        }
        break;
        case "contact":
        console.log("Showing contact");
        for(var i = 0; i < forms.length; i++){
            if(!forms[i].classList.contains('hide')){
                forms[i].classList.add("hide");}
            if (document.getElementById('contact-page-form').classList.contains('hide')){
            document.getElementById('contact-page-form').classList.remove('hide');}
        }
        break;
        case "other":
        console.log("Showing other");
        for(var i = 0; i < forms.length; i++){
            if(!forms[i].classList.contains('hide')){
                forms[i].classList.add("hide");}
            if (document.getElementById('other-content-form').classList.contains('hide')){
            document.getElementById('other-content-form').classList.remove('hide');}
        }
        break;

    }

}

function dashboardView(tab){
    var tab = tab + "-tab";
    console.log(tab);
    var tabs = document.getElementsByClassName('dashboard-tabs');

    for(var i= 0; i < tabs.length; i++){
        if(tab == tabs[i].id && tabs[i].classList.contains("hide")){
            tabs[i].classList.remove("hide");
        }
        else if (tab != tabs[i].id && !tabs[i].classList.contains("hide")){
            tabs[i].classList.add("hide");
        }
    }
}
function toggleMenu(){
    var x = document.getElementById('burger');
    x.classList.toggle("change");
    if(document.getElementById('section-edit') && document.getElementById('section-edit').classList.contains('hide')){
    document.getElementById('screen-cover').classList.toggle('hide');}
    else{
        document.getElementById('screen-cover').classList.toggle('z-20');
    }
    var items = document.getElementsByClassName('nav-item');
    if (items.length >0 ){
        for(var i = 0; i< items.length; i++){
                items[i].classList.toggle('hide');
        }
    }
}

function changeColour(){
    var div = document.getElementById('color-div').style.backgroundColor = document.getElementById('color-name').value;    
}
window.addEventListener("load", function(){
    // Add click event listener to all dashboard navigation items
    var items = document.getElementsByClassName("dashboard-navigation-item");
    var obj = "";
    for(var i=0, l=items.length; i<l; i++){
        obj = items[i];
        items[i].addEventListener("click", toggleDashboardView);
    }
    var items = document.getElementsByClassName("close-parent");
    var obj = "";
    for(var i=0, l=items.length; i<l; i++){
        obj = items[i];
        items[i].addEventListener("click", closeParent);
    }

    // Add click event to dashboard form close button
     // Add click event to submit button
     var close_form_button = document.getElementById("close-add-form");
     if (close_form_button){
        close_form_button.addEventListener("click", closeDashboardForm);
     }
     var discard_form_button = document.getElementById("discard-dasboard-form");
     if (discard_form_button){
        discard_form_button.addEventListener("click", closeDashboardForm);
     }

    // Add click event to add-newsletter-button
    var add_form_button = document.getElementById("add-newsletter-button");
     if (add_form_button){
        add_form_button.addEventListener("click", showAddNewsletterForm);
     }

    // Add click event to submit button
    var submit_button = document.getElementById("submit-dasboard-form");
    if (submit_button){
        submit_button.addEventListener("click", function(){
            request("post-"+document.getElementById("dashboard-type").value);
            request("load-newsletters", "", "dashboard-newsletter-container");
        });
    }
    // Add click event to save button
    var save_form_button = document.getElementById("save-dasboard-form");
     if (save_form_button){
        save_form_button.addEventListener("click", function(){
        request("save-"+document.getElementById("dashboard-type").value);
        request("load-newsletters", "", "dashboard-newsletter-container");
        });
    }
  },false);

function closeDashboardForm(){
    document.getElementById("custom-form-inputs").innerHTML = "";
    document.getElementById("dashboard-type").value="";
    if (!document.getElementById('dashboard-add-form').classList.contains("hide")){
        toggleHideById("dashboard-add-form");
    }
}
function showAddForm(){
    toggleHideById("add-panel-container");
    toggleHideById("add-panel");
}

function closeAddPanel(){
    toggleAddPanel();
}

function toggleAddPanel(){
    var items = document.getElementsByClassName("alert-input");
    for(var i=0, l=items.length; i<l; i++){
        items[i].value = '';
    }
    showAddForm();

}
function toggleParentHide(){
    toggleHideById(this.parentElement.id);
}
function toggleHideById(id){
    document.getElementById(id).classList.toggle("hide");
}
function toggleDashboardView(){
    var items = document.getElementsByClassName("dashboard-main-panel-content");
    for(var i=0, l=items.length; i<l; i++){
        if(this.innerHTML.toLowerCase().includes(items[i].id.toLowerCase())){
            if(items[i].classList.contains("hide")){
                items[i].classList.toggle("hide")
            }
        }
        else{
            if(!items[i].classList.contains("hide")){
                items[i].classList.toggle("hide")
            }
        }
        if (this.innerHTML.toLowerCase().includes("messages")){
            request("load-newsletters", "", "dashboard-newsletter-container");    
        }
        else if (this.innerHTML.toLowerCase().includes("subscribers")){
            request("load-subscribers", "", "dashboard-subscribers-container");    
        }
        else if (this.innerHTML.toLowerCase().includes("users")){
            request("load-users", "", "dashboard-users-container");
        }
    }
}
function edit_item(type, id){
    showAddNewsletterForm();
    // scrollToTop()
    document.getElementById("add-form-title").innerHTML = "EDIT " +type.toUpperCase();
    // Send Ajax request to fetch data and display on the form
    request("fetch-newsletter-data", id, "populate-"+type+"-editor");
}

function archive_item(type, id){
    // send ajax request to delete $type with $id
    request("archive-"+type, id);
    request("load-newsletters", "", "dashboard-newsletter-container");
}

function delete_item(type, id){
    // send ajax request to delete $type with $id
    request("delete-"+type, id);
    request("load-newsletters", "", "dashboard-newsletter-container");
}


function restore_item(type, id){
    // send ajax request to delete $type with $id
    request("restore-"+type, id);
    request("load-newsletters", "", "dashboard-newsletter-container");
}

function subscribe_item(email){
    request("subscribe", email);
    request("load-subscribers", "", "dashboard-subscribers-container");
}

function unsubscribe_item(email){
    request("unsubscribe", email);
    request("load-subscribers", "", "dashboard-subscribers-container");
}

function view(type, id){
    request("fetch-newsletter-data-with-br", id, "populate-newsletter-modal");
    toggleHideById("view-newsletter-modal");
    toggleHideById("modal-container");
}

function closeParent(){
    var parent = this.parentElement;
    parent.classList.toggle("hide");
    if (parent.parentElement.children[0].classList.contains("background-blur")){
        parent.parentElement.classList.toggle("hide");
    }
}

function changeUserRole(email, role){
    var data = email + "~" + role;
    request("change-user-role", data);
    request("load-users", "", "dashboard-users-container");
}

function addAlert(){
    request('save-alert');
    request('display-alerts', '', 'alerts-panel')
}

function restoreAlert(id){
    request("restore-alert", id);
    request('display-alerts', '', 'alerts-panel')
}

function pauseAlert(id){
    request("pause-alert", id);
    request('display-alerts', '', 'alerts-panel')
}
function deleteAlert(id){
    request("delete-alert", id);
    request('display-alerts', '', 'alerts-panel')
}