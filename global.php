<?php
session_start();

define(VAORIGIN, 'http://app.vavavoos.com');
define(VABASEURL, VAORIGIN . '/app/index/');
define(VAFIXIESBASEURL, VAORIGIN . '/app/fixies/');
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

$uriid = '';
if(isset($_GET['fid'])){
    $urlparams = vavavoos_clean_param($_GET);
    $uriid = str_replace(' ', '-', $urlparams['cat'] . '--' . $urlparams['item']) . '--' . $urlparams['fid'];
}
if(isset($_GET['fixie'])){
    $uriid = vavavoos_clean_param($_GET['fixie']);
}

$VA = isset($_SESSION['vavavoos'])?$_SESSION['vavavoos']:array();


?>