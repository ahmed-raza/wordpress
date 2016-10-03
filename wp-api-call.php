<?php
require( dirname(__FILE__) . '/wp-load.php' );
function api_get_as_json( $params ) {
 
    $api_endpoint = "http://stg.one2three.network/oauth/token";
 
    // Create URL with params
    $url = $api_endpoint . '?' . http_build_query($params);
 
    // Use curl to make the query
    $ch = curl_init();
 
    curl_setopt_array(
        $ch, array( 
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true
        )
    );
 
    $output = curl_exec($ch);
 
    // Decode output into an array
    $json_data = json_decode( $output, true );
 
    curl_close( $ch );
 
    return $json_data;
}

function check_user_by_email($username, $password){
  $query = mysql_query("SELECT * FROM `wp_users` WHERE `user_login` LIKE $username");
  if ($query) {
    return true;
  }else{
    if(wp_create_user( $username,$password, $username )){
      return true;
    }
  }
}

// API call to rails for credentials confirmation
if (!empty($_POST)) {
  if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = $_POST['username'];
    if (isset($_POST['password']) && !empty($_POST['password'])) {
      $password = $_POST['password'];
      $grant_type = "password";
      $params = ['grant_type'=>$grant_type, 'password'=>$password, 'username'=>$username];
      if (api_get_as_json( $params )) {
        if (!empty(api_get_as_json( $params )['access_token'])) {
          if (check_user_by_email($username)) {
            echo json_encode(['status'=>'submit_login','message'=>'Login successful.']);
          }
        }else{
          echo json_encode(['status'=>'error','message'=>'User not found.']);
        }
      }else{
        echo json_encode(['status'=>'error','message'=>'Failed to send request.']);
      }
    }else{
      echo json_encode(['status'=>'error','message'=>'Password field is required.']);
    }
  }else{
    echo json_encode(['status'=>'error','message'=>'Username field is required.']);
  }
}

?>
