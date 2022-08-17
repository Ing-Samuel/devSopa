<?php
@session_start();

if (isset($_SESSION["user"])) {
    session_destroy();
    echo "<script type='text/javascript'>";
        echo "window.location = '../index.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
        echo "window.location = '../index.php';";
    echo "</script>";  
}

?>