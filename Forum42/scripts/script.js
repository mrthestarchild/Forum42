// when a button is clicked that has a menu I.E. the forum search menu and the settings after login
// a invisible modal is created so that is someone clicks off of it then we close the menu 
window.onclick = function(event) {
  var overlayMenu = document.getElementById("overlayMenu");
  var overlaySettings = document.getElementById("overlaySettings");
  var overlayLogin = document.getElementById("overlayLogin");
  var overlayCreateThread = document.getElementById("overlayCreateThread");
  if (event.target == overlayMenu) {
    toggleMenu();
  }
  if (event.target == overlaySettings) {
    toggleSettings();
  }
  if (event.target == overlayLogin) {
    loginDropdown();
  }
  if(event.target == overlayCreateThread){
    createThread();
  }
}

// toggle the search menu for Forums
function toggleMenu() {
  document.getElementById("myDropdown").classList.toggle("show");
  document.getElementById("overlayMenu").classList.toggle("show");
  if(document.getElementById('infoDropdown') && document.getElementById('infoDropdown')){
    var settings = document.getElementById("infoDropdown");
    if(settings.classList.contains("show")){
      toggleSettings();
    }
  }
  if(document.getElementById('loginDropdown') && document.getElementById('loginDropdown')){
    var menu = document.getElementById("loginDropdown");
    if(menu.classList.contains("show")){
      loginDropdown();
    }
  }
}

// toggle the settings menu after login
function toggleSettings() {
  document.getElementById("infoDropdown").classList.toggle("show");
  document.getElementById("overlaySettings").classList.toggle("show");
  var menu = document.getElementById("myDropdown");
  if(menu.classList.contains("show")){
    toggleMenu();
  }
}

// this opens and closes the login form
function loginDropdown() {
  document.getElementById("loginDropdown").classList.toggle("showLogin");
  document.getElementById("overlayLogin").classList.toggle("show");
  var menu = document.getElementById("myDropdown");
  if(menu.classList.contains("show")){
    toggleMenu();
  }
}

// this opens and closes the comment form for a user to create a comment
function toggleComment(commentType){
  var comment = document.getElementById(commentType);
  comment.classList.toggle("showComment");
  comment.classList.toggle("hideComment");
}

// this creates an modal for a user to create a thread
function createThread(){
  document.getElementById("create-thread").classList.toggle("show");
  document.getElementById("overlayCreateThread").classList.toggle("show");
  document.getElementById("create-thread-form").reset();
}


// this toggles the ability for a user to update their email
// turning the submit input on and off
function showInput(type){
  if(type == "email"){
    document.getElementById("update-email").classList.toggle("show");
    document.getElementById("show-email").classList.toggle("show");
  }
  if(type == "password"){
    document.getElementById("update-password").classList.toggle("show");
    document.getElementById("show-password").classList.toggle("show");
  }
}

// when you create a thread this toggles the ability for a user to either
// select a image or a link that they want to upload.
function chooseUploadType(){
  var url = document.getElementById("url");
  var image = document.getElementById("image");
  if(url.checked){
    document.getElementById("url-upload").classList.toggle("show");
    document.getElementById("url-radio").style.background = "#2ac48e";
    if(document.getElementById("photo-upload").classList.contains("show")){
      document.getElementById("photo-upload").classList.toggle("show");
      document.getElementById("threadPhoto").value = "";
      document.getElementById("photo-radio").style.background = "";
    }
  }
  if(image.checked){
    document.getElementById("photo-upload").classList.toggle("show");
    document.getElementById("photo-radio").style.background = "#2ac48e";
    if(document.getElementById("url-upload").classList.contains("show")){
      document.getElementById("url-upload").classList.toggle("show");
      document.getElementById("threadURL").value = "";
      document.getElementById("url-radio").style.background = "";
    }
  }
}

// when you type into the Forums search this fires on change so that the
// menu is filtered base on what is typed
function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = document.getElementById("myDropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

// when you click on a comment it slides down.
// this function preloaads the animation so it isn't fired off
// what the page is loaded
function loadAnimation(){
  setTimeout(function(){
    var classes = document.getElementsByClassName('preload-animation')
    while(classes.length > 0){
      classes[0].classList.remove('preload-animation');
    }
  },1000);
}
window.onload = loadAnimation;


// this toggles the thread footer
function toggleFooter(threadId){
  var thread = document.getElementById(threadId);
  thread.classList.toggle("showThread");
  thread.classList.toggle("hideThread");
}

// when a image is clicked on this function creates the modal
// for a image to pop up over the screen
function openPhoto(modalId, imgId, modalImgId, closeId){
  var modal = document.getElementById(modalId);
  var img = document.getElementById(imgId);
  var modalImg = document.getElementById(modalImgId);
  modal.style.display = "block";
  modalImg.src = img.src;

  var close = document.getElementById(closeId);
  close.onclick = function() { 
    modal.style.display = "none";
  }
}

function removeSpaces(id){
  var inputId = document.getElementById(id)
  inputId.value = inputId.value.replace(/\s/g, "");
}

function showContent(type){
  var forum = document.getElementById('forums');
  var forumTab = document.getElementById('tab-forums');
  var thread = document.getElementById('threads');
  var threadTab = document.getElementById('tab-threads');
  var comment = document.getElementById('comments');
  var commentTab = document.getElementById('tab-comments');
  if(type == "forums"){
    forum.classList.toggle('hidden', false);
    forumTab.classList.toggle('selected', true);
    thread.classList.toggle('hidden', true);
    threadTab.classList.toggle('selected', false);
    comment.classList.toggle('hidden', true);
    commentTab.classList.toggle('selected', false);
  }
  if(type == "threads"){
    forum.classList.toggle('hidden', true);
    forumTab.classList.toggle('selected', false);
    thread.classList.toggle('hidden', false);
    threadTab.classList.toggle('selected', true);
    comment.classList.toggle('hidden', true);
    commentTab.classList.toggle('selected', false);
  }
  if(type == "comments"){
    forum.classList.toggle('hidden', true);
    forumTab.classList.toggle('selected', false);
    thread.classList.toggle('hidden', true);
    threadTab.classList.toggle('selected', false);
    comment.classList.toggle('hidden', false);
    commentTab.classList.toggle('selected', true);
  }
}
