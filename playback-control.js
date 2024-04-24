function showControl() {
    homeLayout = document.getElementById("home-layout") ;
    homeLayout.style.display = homeLayout.style.display === "none" ? "block" : "none";

    var voiceControl = document.getElementById("voice-control");

    var computedStyle = window.getComputedStyle(voiceControl);

    if (computedStyle.display === "none") {
        voiceControl.style.display = "block"; 
    } else {
        voiceControl.style.display = "none"; 
    }
    
    var controlLayout = document.getElementById("control-layout");
    controlLayout.classList.toggle("active");

    var controlRow = document.querySelector(".control-row");
    controlRow.classList.toggle("active");

    var playerControl= document.querySelector(".player-control");
    playerControl.classList.toggle("active");

    var img = document.getElementById("player-img");
    img.classList.toggle("active");

    var expandIcon = document.getElementById("expand");
    if (expandIcon.classList.contains("fa-expand")) {
        expandIcon.classList.remove("fa-expand");
        expandIcon.classList.add("fa-compress");
    } else {
        expandIcon.classList.remove("fa-compress");
        expandIcon.classList.add("fa-expand");
    }

    var activeLink = document.getElementById("home");
    if (activeLink.classList.contains("active")) {
        activeLink.classList.remove("active");
    } else {
        activeLink.classList.add("active");
    }

    var activeSelector = document.getElementById("selector");
    var computedSelector = window.getComputedStyle(activeSelector);

    if (computedSelector.getPropertyValue('display') === "block") {
        activeSelector.style.display = "none"; 
    } else {
        activeSelector.style.display = "block"; 
    }

}
