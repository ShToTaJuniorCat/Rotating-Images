<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
        // header("location: index.php");
        echo "Logged in as " . $_SESSION["username"] . " | <a href='logout.php'>Log out</a>";
    } else {
        die("You must <a href='register.php'>register</a> or <a href='login.php'>login</a> to use this site.");
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
    $username = $_SESSION["username"];
    
    
    if($_POST["url"] != null) {
        $images = array();
        foreach($_POST["url"] as $url) {
            if($url != "") {
                array_push($images, $url);
            }
        }
        $stmt = $conn->prepare("UPDATE user_imgs SET images=? WHERE username=?");
        $stmt -> bind_param("ss", join(", ", $images), $username);
        $images = array_filter($images);
        $stmt -> execute();
    }
    
    $username_url = preg_replace("/[^A-Za-z0-9]/", '', $username);
    echo "<br><br>visit <a href=\"https://peleghtml.000webhostapp.com/myhtml/rot_imgs/test/$username_url.gif\">https://peleghtml.000webhostapp.com/myhtml/rot_imgs/test/$username_url.gif</a>!";
    
    echo "\n<br><br>\nYour images:\n";
    
    if($_POST["url"] != null) {
        foreach($images as $img) {
            echo "<br><img src=\"$img\" />\n";
        }
    } else {
        $stmt = $conn->prepare("SELECT images FROM user_imgs WHERE username=?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $stmt -> store_result();
        $stmt -> bind_result($images);
        $stmt -> fetch();
        
        $images = explode(", ", $images);

        foreach($images as $img) {
            echo "<br><img src=\"$img\" />\n";
        }
    }
?>

<form action="" method="POST">
    <br>
    <div id="inputs">
        <input type="text" name="url[]" placeholder="Image 1" />
        <br>
        <input type="text" name="url[]" placeholder="Image 2" />
        <br>
        <input type="text" name="url[]" placeholder="Image 3" />
        <br>
        <input type="text" name="url[]" placeholder="Image 4" />
        <br>
        <input type="text" name="url[]" placeholder="Image 5" />
        <br>
        <input type="text" name="url[]" placeholder="Image 6" />
        <br>
        <input type="text" name="url[]" placeholder="Image 7" />
        <br>
        <input type="text" name="url[]" placeholder="Image 8" />
        <br>
    </div>
    <input type="submit" />
</form>

<script>
    var images = [<?php if(count($images) > 0) { echo "\"" . join($images, "\", \"") . "\""; } ?>];
    for(let i = 0; i < images.length; i++) {
        let e = document.createElement("input");
        e.type = "text";
        e.name = "url[]";
        e.placeholder = "Image " + (i + 9);
        document.getElementById("inputs").appendChild(e);
        
        e = document.createElement("br");
        document.getElementById("inputs").appendChild(e);
    }
    
    for(let i = 0; i < images.length; i++) {
        document.getElementsByName("url[]")[i].value = images[i];
    }
</script>