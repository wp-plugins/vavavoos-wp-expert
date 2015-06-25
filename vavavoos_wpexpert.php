<?php
/**
 * Plugin Name: vaplugin
 * Plugin URI: http://vavavoos.com
 * Description: Find your virtual assistant to help you out with all your WP Needs
 * Version: 0.0.9
 * Author: Scott Underhill
 * Author URI: http://vavavoos.com
 * Network: true
 */
add_action('admin_menu', 'vavavoos_plugin_menu');
add_action('wp_ajax_checkpermission', 'vavavoos_checkpermission');
add_action('wp_ajax_makeadmin', 'vavavoos_makeadmin');
add_action('vavavoos_init_scripts','vavavoos_init_scripts');
add_action('vavavoos_init_styles','vavavoos_init_styles');

require_once('functions.php');
require_once('global.php');


do_action('vavavoos_init_scripts');
do_action('vavavoos_init_styles');


function vavavoos_plugin_menu()
{
    add_menu_page("Find WP Experts", "Find WP Experts", "manage_options", "Virtual_Assistant", vavavoos_plugin_page);
    add_submenu_page("Virtual_Assistant", "Manage My Expert/s", "Manage My Expert/s", "manage_options", "Manage_Assistant", vavavoos_pluginmanageassistant_page);
    add_submenu_page("Virtual_Assistant", "Manage WP Access", "Temporary Access Rights Admin", "manage_options", "permission", vavavoos_permission_page);
}
