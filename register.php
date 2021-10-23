<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>
            Register
        </title>
        <style>
            body {font-family: Arial, Helvetica, sans-serif;}
            * {box-sizing: border-box;}
            
            /* Full-width input fields */
            input[type=text], input[type=password] {
              width: 100%;
              padding: 15px;
              margin: 5px 0 22px 0;
              display: inline-block;
              border: none;
              background: #f1f1f1;
            }
            s
            /* Add a background color when the inputs get focus */
            input[type=text]:focus, input[type=password]:focus {
              background-color: #ddd;
              outline: none;
            }
            
            /* Set a style for all buttons */
            button {
              background-color: #4CAF50;
              color: white;
              padding: 14px 20px;
              margin: 8px 0;
              border: none;
              cursor: pointer;
              width: 100%;
              opacity: 0.9;
            }
            
            button:hover {
              opacity:1;
            }
            
            /* Extra styles for the cancel button */
            .cancelbtn {
              padding: 14px 20px;
              background-color: #f44336;
            }
            
            /* Float cancel and signup buttons and add an equal width */
            .cancelbtn, .signupbtn {
              width: 100%;
            }
            
            /* Add padding to container elements */
            .container {
              padding: 16px;
            }
            
            /* The Modal (background) */
            .modal {
              position: fixed; /* Stay in place */
              z-index: 1; /* Sit on top */
              left: 0;
              top: 0;
              width: 100%; /* Full width */
              height: 100%; /* Full height */
              overflow: auto; /* Enable scroll if needed */
              background-color: #474e5d;
              padding-top: 50px;
            }
            
            /* Modal Content/Box */
            .modal-content {
              background-color: #050;
              margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
              border: 1px solid #888;
              width: 80%; /* Could be more or less, depending on screen size */
            }
            
            /* Style the horizontal ruler */
            hr {
              border: 1px solid #f1f1f1;
              margin-bottom: 25px;
            }
             
            /* The Close Button (x) */
            .close {
              position: absolute;
              right: 35px;
              top: 15px;
              font-size: 40px;
              font-weight: bold;
              color: #f1f1f1;
            }
            
            .close:hover,
            .close:focus {
              color: #f44336;
              cursor: pointer;
            }
            
            /* Clear floats */
            .clearfix::after {
              content: "";
              clear: both;
              display: table;
            }
            
            /* Change styles for cancel button and signup button on extra small screens */
            @media screen and (max-width: 300px) {
              .cancelbtn, .signupbtn {
                 width: 100%;
              }
            }
        </style>
    </head>
    <body>
        <?php
            function sqlConnect() {
				$servername = "Localhost";
				$username = DATABSE_USERNAME;
				$password = DATABASE_PASS;
				$dbname = DATABASE_NAME;
            
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                return $conn;
            }
        
            $conn = sqlConnect();
        
            $username = $_POST["username"];
            $password = $_POST["password"];
            $repassword = $_POST["repassword"];
            
            // Make sure the user entered username, password and email
            if($username == null or $password == null) {
        ?>
        <div id="id01" class="modal">
          <form class="modal-content" action="" method="POST">
            <div class="container">
              <h1>Sign Up</h1>
              <p>To get best experience from this site, please sign up.</p>
              <hr>
              <label><b>Username</b></label>
              <input type="text" placeholder="Enter Username" name="username" required>
        
              <label for="psw"><b>Password</b></label>
              <input type="password" placeholder="Enter Password" name="password" required>
        
              <label for="psw-repeat"><b>Repeat Password</b></label>
              <input type="password" placeholder="Repeat Password" name="repassword" required>
              
              <label>
                <input type="checkbox" checked="checked" name="rememberme" style="margin-bottom:15px"> Remember me
              </label>
                
                <p>Already have an account? <a href="login.php" style="color: #000;">login!</a></p>
                
                <p>By creating an account you agree to our <a href="#" style="color: dodgerblue;">Terms & Privacy</a>.</p>
        
              <div class="clearfix">
                <button type="submit" class="signupbtn">Sign Up</button>
              </div>
            </div>
          </form>
        </div>
        <?php
            } else if($password != $repassword) {
                die("Password and repeated password do not match!");
            } else if(!ctype_alnum(str_replace(" ", "", $username))) {
                die("Username must not include any non alphabet letters or numbers. Your user name: " . $username);
            } else {
                // Check if username exists
                $stmt = $conn->prepare("SELECT username FROM user_imgs WHERE username=?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt -> store_result();
                $stmt -> bind_result($row);
                $stmt -> fetch();
                
                if ($row != null) {
                    die("Username Taken!");
                }

                $stmt = $conn->prepare("INSERT INTO `user_imgs`(`username`, `password`, `images`, `url_path`) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $username, $password, $images, $path);
                $images = "";
                $path = preg_replace("/[^A-Za-z0-9]/", '', $username) . ".gif";
                $stmt->execute() or die("Error");
                
                mkdir("test/$path");
                # $myfile = fopen("test/$path/index.php", "w") or die("Unable to open file");
                copy("template.php", "test/$path/index.php");
                
                // If remember me was checked, make the cookie expire in 2 years. Else, it will expire when current session is ended.
                $_SESSION["loggedin"] = true;
                // $_SESSION["id"] = $id;
                $_SESSION["username"] = $_POST["username"];

                echo "<script>window.location.href='index.php';</script>";
                exit;
            }
        
            $conn->close();
        ?>
    </body>
</html>