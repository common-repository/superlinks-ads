<?php  
/* 
Plugin Name: Superlinks Google Ads Plugin
Plugin URI: http://www.superlinks.com/ 
Version: 1.0
Author: Super-Links
Description: The easiest way to start earning more revenue with display ads on your WordPress site!
*/  

function sp_admin() {
    include('sp_admin.php');
}

function sp_admin_actions() { 
    add_menu_page( 'Superlinks' , 'Superlinks' , 'manage_options' , __FILE__ , 'sp_admin',''.plugins_url( 'images/logo-small.png' , __FILE__ ).'','26.6' );
}
add_action('admin_menu', 'sp_admin_actions');


include('sp_actions.php');	
include('sp_widgets.php');

?>