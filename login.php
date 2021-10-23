<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
        // header("location: index.php");
        header("Location: index.php");
        exit;
    }
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
    
    
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)) {
        if($_POST["username"] != null and $_POST["password"] != null) {
            // Check if user exists
            // Check if username exists
            $stmt = $conn->prepare("SELECT password FROM user_imgs WHERE username=?");
            $stmt->bind_param("s", $username);
            $username = $_POST["username"];
            $stmt->execute();
            $stmt -> store_result();
            $stmt -> bind_result($password);
            $stmt -> fetch();

            if ($password == null) {
                die("Username not found!");
            }
            
            // Check if username matches password
            if($password != $_POST["password"] and isset($_GET["submitted"])) {
                die("Wrong username or password!");
            } else {
                // If remember me was checked, make the cookie expire in 2 years. Else, it will expire when current session is ended.
                $_SESSION["loggedin"] = true;
                // $_SESSION["id"] = $id;
                $_SESSION["username"] = $_POST["username"];
                header("Location: index.php");
                // echo "Successfully logged in to user " . $_POST["username"] . ", cookies were created with $time time.";
            }
        } else if(isset($_GET["submitted"])) {
            die("Wrong username or password!");
        }
    }
?>

<!DOCTYPE HTML>
<html>
    <head>
        <link rel="icon" href="https://peleghtml.000webhostapp.com/images/waterbird.png" type="image/png" sizes="256x256">
        <title>Log In</title>
        <style> 
        </style>
    </head>
    <body>
        <div class="container">
            <form action="login.php?&submitted=true" method="POST">
                <input type="text" name="username" placeholder="username">
                <input type="password" name="password" placeholder="password"></br>
                <input type="checkbox" name="remeberme" checked="checked"> Remember me?
                <input type="submit">
            </form>
        </div>
    </body>
</html>

<?php
    $conn->close();
?>