document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("searchIcon").addEventListener("click", search);

    function search() {
        var searchQuery = document.getElementById("searchInput").value;
        $.ajax({
            method: "GET",
            data: { searchQuery: searchQuery },
            success: function(data) {
                console.log("successful");
                // Redirect to Search-Result.php
                window.location.href = "Search-Result.php?searchQuery=" + encodeURIComponent(searchQuery);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }
});
