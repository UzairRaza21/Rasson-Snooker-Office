function openMenu(){
   document.getElementById("nav-col-links").classList.toggle("nav-col-links");
}

// Side Bar

window.onload = function () {
   var sidebar = document.getElementById("sidebar");
   var content = document.getElementById("content");
   var resizing = false;
 
   sidebar.addEventListener("mousemove", function (e) {
     if (
       e.clientX >= sidebar.offsetWidth - 10 &&
       e.clientX <= sidebar.offsetWidth + 10
     ) {
       sidebar.style.cursor = "ew-resize";
       document.body.style.userSelect = "none";
     } else {
       sidebar.style.cursor = "default";
     }
   });
 
   sidebar.addEventListener("mousedown", function (e) {
     if (
       e.clientX >= sidebar.offsetWidth - 10 &&
       e.clientX <= sidebar.offsetWidth + 10
     ) {
       resizing = true;
       document.body.style.cursor = "ew-resize";
     }
   });
 
   document.addEventListener("mouseup", function (e) {
     resizing = false;
     document.body.style.cursor = "default";
     document.body.style.userSelect = "auto";
   });
 
   document.addEventListener("mousemove", function (e) {
     if (resizing) {
       // Ensure sidebar width does not go below minimum width
       var newWidth = Math.max(150, Math.min(400, e.clientX));
       sidebar.style.width = newWidth + "px";
       content.style.marginLeft = newWidth + "px";
     }
   });
 };
 

//  Notifications

function fSuccess() {
  var x = document.querySelector(".success-message");
  x.classList.add("show");
  setTimeout(function(){ x.classList.remove("show"); }, 3000);
}

function fError() {
  var y = document.querySelector(".error-message");
  y.classList.add("show");
  setTimeout(function(){ y.classList.remove("show"); }, 3000);
}

function fInfo() {
  var z = document.querySelector(".info-message");
  z.classList.add("show");
  setTimeout(function(){ z.classList.remove("show"); }, 3000);
}

function fWarning() {
  var a = document.querySelector(".warning-message");
  a.classList.add("show");
  setTimeout(function(){ a.classList.remove("show"); }, 3000);
}
