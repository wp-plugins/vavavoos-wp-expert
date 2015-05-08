<?php
session_start();

define(VAORIGIN, 'http://va');
define(VABASEURL, VAORIGIN . '/app/index/');
define(VAVAVOOS_PLUGINURL, plugin_dir_url(__FILE__));
define(VAMENUPAGE, admin_url() . 'admin.php?page=Virtual_Assistant');
define(VACURRENTURL, $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);


if (isset($_GET['action'])) {
    if (vavavoos_clean_param($_GET['action']) == 'createsession') {
        $_SESSION['vavavoos'] = vavavoos_clean_param($_POST);
    }

    if(vavavoos_clean_param($_GET['action']) == 'unsetsession'){
        unset($_SESSION['vavavoos']);
    }
}

if(isset($_GET['menu'])){
    if(vavavoos_clean_param($_GET['menu']) == 'thanks'){
        unset($_SESSION['vavavoos']);
    }
}



$VA = isset($_SESSION['vavavoos'])?$_SESSION['vavavoos']:array();


?>