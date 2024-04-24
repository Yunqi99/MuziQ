// Declare secIndex in the global scope
let secIndex;

function createNewPlaylist() {
  document.querySelector(".overlay").style.display = "block";
  document.getElementById("newPlaylist").style.display = "block";
}

function createNewTrack() {
  document.querySelector(".overlay").style.display = "block";
  document.getElementById("newTrack").style.display = "block";
}

function editUserDashboard() {
  document.querySelector(".overlay").style.display = "block";
  document.getElementById("editUD").style.display = "block";
}

function hidePop() {
  document.querySelector(".overlay").style.display = "none";
  document.getElementById("showreason").style.display = "none";
  document.getElementById("newPlaylist").style.display = "none";
  document.getElementById("newTrack").style.display = "none";
  document.getElementById("editUD").style.display = "none";
}



// Preview Playlist Image
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      // Display the uploaded image preview
      document.getElementById('preview-image').src = e.target.result;
      document.getElementById('preview-image').style.display = 'block';
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Preview Track Image
function readURL2(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      // Display the uploaded image preview
      document.getElementById('preview-image2').src = e.target.result;
      document.getElementById('preview-image2').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Preview Avatar Image
function showPreview(event) {
  if (event.target.files.length > 0) {
      var src = URL.createObjectURL(event.target.files[0]);
      var preview = document.getElementById("preview-image3");
      preview.src = src;
      preview.style.display = "block";
  }
}



