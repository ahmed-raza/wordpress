<?php 
/*
  Plugin Name: Demo
  Plugin URL: http://www.google.com
  Version: 1.04
  Author: Ahmed
 */

  add_action( 'login_post', 'demo_login_submit' );
  
  function demo_login_submit(){
    $submit_url = "/wp-admin/upload.php";
    return $submit_url;
  }

?>
