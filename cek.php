<?php
//jika belum log

if (isset($_SESSION['log'])) {

}else{
    header("location: login.php");
}
?>