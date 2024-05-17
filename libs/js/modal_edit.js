function confirmEdit() {
  // Fetch the input values
  var username = document.getElementById("edit_username").value;
  var password = document.getElementById("edit_password").value;

  // Perform AJAX request to the PHP file for authentication
  $.ajax({
    type: "POST",
    url: "modal_auth.php",
    data: { username: username, password: password },
    dataType: "json", // Expect JSON response
    success: function(response) {
      // Handle the response from the server
      console.log(response);

      // Check if authentication was successful
      if (response.status === "success") {
        // Redirect to the user-specific page
        window.location.href = "edit_user.php?id=" + response.user_id;
      } else {
        // Display an alert for failed authentication
        alert("Authentication failed. Please check your username and password.");
      }
    },
    error: function() {
      // Handle errors, if any
      console.error("Error in AJAX request");
    }
  });
}