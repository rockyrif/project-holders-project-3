<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>signin/signup</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://kit.fontawesome.com/ac9ee0c52c.js" crossorigin="anonymous"></script>



  <script>
    // Function to get URL parameter by name
    function getParameterByName(name, url = window.location.href) {
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return "";
      return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    // Function to show alert message if signup query parameter exists
    function showAlert() {
      var signupParam = getParameterByName("signup");
      if (signupParam && signupParam === "1") {
        alert("Sign up success. Please sign in."); // You can customize the alert message here
      }
    }

    // Call showAlert function when the page loads
    window.onload = showAlert;
  </script>
</head>

<body>
  <div class="body">

    <!-- Alert start -->
    <?php
    if (isset($_SESSION['response'])) {
      echo '<div style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); margin-top:0px; margin-bottom:10px; width:70%; z-index:10; text-align:center; background-color: #fff3cd; border-color: #ffeeba; color: #856404; border: 1px solid transparent; border-radius: 4px; padding: 15px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);" id="alertContainer" role="alert">
    ' . $_SESSION['response'] . '
    <button type="button" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 1.5rem; line-height: 1; color: #000; cursor: pointer;" onclick="closeAlert()" aria-label="Close">&times;</button>
  </div>';

      unset($_SESSION['response']);
    }
    ?>
    <script>
      function closeAlert() {
        var alertContainer = document.getElementById('alertContainer');
        alertContainer.style.opacity = '0';
        setTimeout(function() {
          alertContainer.style.display = 'none';
        }, 150);
      }

      // Automatically remove the alert after 4 seconds
      setTimeout(function() {
        var alertContainer = document.getElementById('alertContainer');
        if (alertContainer) {
          closeAlert();
        }
      }, 4000);
    </script>
    <!-- Alert end -->


    <div class="form-box">
      <h1 id="tittle">Sign up</h1>
      <form name="form" method="post" id="form">
        <div class="input-group">
          <div class="input-field" id="nameField">
            <i class="fa-solid fa-user"></i>
            <input type="text" placeholder="Name" name="name" />
          </div>

          <div class="input-field" id="emailField">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" autocomplete="off" placeholder="Email" name="email" />
          </div>

          <div class="input-field" id="passwordField">
            <i class="fa-solid fa-lock"></i>
            <input type="password" placeholder="Password" name="password" />
          </div>
          <?php
          $privilage = isset($_GET['privilage']) ? $_GET['privilage'] : '';
          ?>
          <input type="hidden" value="<?= $privilage ?>" name="privilage" />

        </div>
        <div class="btn-field">
          <button type="button" id="signupBtn">Sign up</button>
          <button type="button" id="signinBtn" class="disable">
            Sign in
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Button function
    let signupBtn = document.getElementById("signupBtn");
    let signinBtn = document.getElementById("signinBtn");
    let tittle = document.getElementById("tittle");
    let nameField = document.getElementById("nameField");
    let form = document.getElementById("form");

    signupBtn.clicked = true;

    signupBtn.addEventListener("click", handleClick2);

    function handleClick2() {
      if (this.clicked) {
        // Second click action
        form.action = "signup.php";
        form.submit();
      } else {
        // First click action
        nameField.style.maxHeight = "65px";
        tittle.innerHTML = "Sign Up";
        signupBtn.classList.remove("disable");
        signinBtn.classList.add("disable");
        this.clicked = true;
      }
    }

    signinBtn.addEventListener("click", function() {
      signupBtn.clicked = false;
    });

    signinBtn.addEventListener("click", handleClick1);

    function handleClick1() {
      if (this.clicked) {
        // Second click action
        form.action = "login.php";
        form.submit();
      } else {
        // First click action
        nameField.style.maxHeight = "0";
        tittle.innerHTML = "Sign In";
        signupBtn.classList.add("disable");
        signinBtn.classList.remove("disable");
        this.clicked = true;
      }
    }

    signupBtn.addEventListener("click", function() {
      signinBtn.clicked = false;
    });
  </script>
</body>

</html>