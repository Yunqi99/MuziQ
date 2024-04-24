function menuToggle() {
  const toggleMenu = document.querySelector(".user-dropdown");
  toggleMenu.classList.toggle("active");
}

document.addEventListener("click", function(event) {
  const toggleMenu = document.querySelector(".user-dropdown");
  const userIcon = document.querySelector(".user-icon");
  
  // Check if the click occurred outside of the dropdown and user icon
  if (!toggleMenu.contains(event.target) && !userIcon.contains(event.target)) {
      toggleMenu.classList.remove("active");
  }
});

// Navigation bar effect
$(document).ready(function () {
  // Function to set the position and width of the selector
  function updateSelector() {
      var activeItem = $('.tabs .active');
      var selector = $('.tabs .selector');
      if (activeItem.length && selector.length) {
          var activeWidth = activeItem.innerWidth();
          var itemPos = activeItem.position();
          selector.css({
              "left": itemPos.left + "px",
              "width": activeWidth + "px"
          });
      }
  }

  // Call updateSelector function on page load
  updateSelector();

  // Click event for tab links
  $(".tabs").on("click", "a", function (e) {
      e.preventDefault();

      // Store the target URL
      var targetUrl = $(this).attr('href');

      // Add a delay of 3 seconds before redirecting
      setTimeout(function () {
          window.location.href = targetUrl;
      }, 700);

      // Update the active state and selector position
      $('.tabs a').removeClass("active");
      $(this).addClass('active');
      updateSelector();
  });
});


// Scrolling effect
$(document).ready(function () {
  $(window).scroll(function () {
    // Get the scroll position
    var scroll = $(window).scrollTop();

    if (scroll > 50) {
      $('.wrapper')
          .css('background-color', 'rgb(0, 0, 30)') 
          .css('box-shadow', '0px 0px 5px 3px rgba(182, 173, 254, 0.6)')
          .css('transition', 'background-color 0.3s, box-shadow 0.3s');
      } else {
        $('.wrapper')
          .css('background-color', 'transparent')
          .css('box-shadow', 'none')
          .css('transition', 'background-color 0.3s, box-shadow 0.3s');
      }
  });
});



// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches('.btn-sort')) {
    var dropdowns = document.getElementsByClassName("sort-group");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

// Contact through Whatsapp 
// function contact() {
//   var phoneNumber = "60108439360"; 
//   var message = "We're thrilled to have you connect with us. Any specific inquiry or issue you'd like assistance with?"; 

//   var whatsappURL = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);

//   window.location.href = whatsappURL;
// }

