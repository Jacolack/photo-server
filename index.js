var rgtClickContextMenu = document.getElementById('div-context-menu');
var rgtClickContextMenuFolder = document.getElementById('div-context-menu-folder');

/** close the right click context menu on click anywhere else in the page*/
document.onclick = function(e){
  rgtClickContextMenu.style.display = 'none';
  rgtClickContextMenuFolder.style.display = 'none';
}

/**
present the right click context menu ONLY for the elements having the right class
by replacing the 0 or any digit after the "to-" string with the element id , which
triggered the event
*/
document.oncontextmenu = function(e){
	var elmnt = e.target;
  	rgtClickContextMenu.style.display = 'none';
//	alert(elmnt.className);
	if ( elmnt.className === "cls-context-menu-image") {
//		alert("Is image");
		var infoLink = "/viewphoto.php?id=" + elmnt.id;
		e.preventDefault();
		rgtClickContextMenu.style.left = e.pageX + 'px'
		rgtClickContextMenu.style.top = e.pageY + 'px'
		rgtClickContextMenu.style.display = 'block'
		rgtClickContextMenu.innerHTML = rgtClickContextMenu.innerHTML.replace("theLink",infoLink)
	} else if ( elmnt.className === "cls-context-menu-folder") {
		var deleteFolderLink = "/deleteFolder.php?id=" + elmnt.id;
		e.preventDefault();
		rgtClickContextMenuFolder.style.left = e.pageX + 'px'
		rgtClickContextMenuFolder.style.top = e.pageY + 'px'
		rgtClickContextMenuFolder.style.display = 'block'
		rgtClickContextMenuFolder.innerHTML = rgtClickContextMenuFolder.innerHTML.replace("theLink",deleteFolderLink)

	}
}

var uploadModal = document.getElementById("newImageModal");
var modal = document.getElementById("newFolderModal");

// Get the button that opens the modal
var btn = document.getElementById("folderBtn");

// Get the <span> element that closes the modal
var span = document.getElementById("closeFolder");
var uploadSpan = document.getElementById("closeUpload");

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
uploadSpan.onclick = function() {
  uploadModal.style.display = "none";
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  if (event.target == uploadModal) {
    uploadModal.style.display = "none";
  }
}


var uploadInput = document.getElementById("imageUploadInput");
var uploadBtn = document.getElementById("uploadBtn");
// When the user clicks the button, open the modal 
uploadBtn.onclick = function() {
	uploadInput.click();
}

uploadInput.onchange = function() {
  uploadModal.style.display = "block";
}
