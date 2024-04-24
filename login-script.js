function signup() {
    document.getElementById("Signup-form").style.display = 'block';
    document.getElementById("Login-form").style.display = 'none';
    document.getElementById("Admin-form").style.display = 'none';
}

function back() {
    document.getElementById("Signup-form").style.display = 'none';
    document.getElementById("Login-form").style.display = 'block';
    document.getElementById("Admin-form").style.display = 'none';
}

function admin() {
    document.getElementById("Admin-form").style.display = 'block';
    document.getElementById("Login-form").style.display = 'none';
    document.getElementById("Signup-form").style.display = 'none';
}

function user() {
    document.getElementById("Admin-form").style.display = 'none';
    document.getElementById("Login-form").style.display = 'block';
    document.getElementById("Signup-form").style.display = 'none';
}


// Function for password checking
function validate() {
    var password = document.getElementById("enter-ps").value;
    var message = "";
    if (password.length >= 8) {
        // Check for lowercase, uppercase, numbers, and special characters
        var lowercase = /[a-z]/;
        var uppercase = /[A-Z]/;
        var numerals = /[0-9]/;
        var specialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;

        if (lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)) {
            // Password meets all requirements
            message = "<i class='fa fa-check'></i>";
        } else {
            // Password does not meet all requirements
            message = "At least 1 lowercase, 1 uppercase, 1 number, 1 special character";
        }

    } else {
        message = "At least 8 characters.";
    }
    document.getElementById("pwd-requirement").innerHTML = message;
}


document.getElementById("enter-ps").addEventListener("input", validate);


// Function for validating password matching
var confirmationPS = function () {
    if (document.getElementById('enter-ps').value == document.getElementById('repeat-ps').value) {
        document.getElementById('confirmation').style.color = 'green';
        document.getElementById('confirmation').innerHTML = "<i class='fa fa-check'></i>";
    } else {
        document.getElementById('confirmation').style.color = 'red';
        document.getElementById('confirmation').innerHTML = 'Password not matched.';
    }
}
window.onload = function () {
    document.getElementById('repeat-ps').addEventListener('keyup', confirmationPS);
};

// Function for validating the sign up form
function validateForm() {
    var password = document.getElementById("enter-ps").value;
    var confirmPassword = document.getElementById("repeat-ps").value;

    if (password.length >= 8 && password == confirmPassword && lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)){
        return true;
    }
    else {
        alert("Please fill in all required fields correctly before submitting.");
        return false;
    }
}

