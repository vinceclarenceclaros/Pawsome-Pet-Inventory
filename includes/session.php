<?php
include_once('database.php');
session_start();

class Session {
  private $db;
  public $msg;
  private $user_is_logged_in = false;

  function __construct(){
    $this->flash_msg();
    $this->userLoginSetup();
    $this->db = new MySqli_DB();
  }

  public function isUserLoggedIn(){
    return $this->user_is_logged_in;
  }

  public function login($user_id) {
    $_SESSION['user_id'] = $user_id;
    $user_id = $_SESSION['user_id'];
    $conn = $this->db->db_connect();
    $sql = "INSERT INTO login_trail (user_id, activity) VALUES ($user_id, 'Logged In')";
    $result = $this->db->query($sql);

    if ($result) {
      echo "Login successful";
    } else {
      echo "Login failed";
    }
  }


  private function userLoginSetup(){
    if(isset($_SESSION['user_id'])){
      $this->user_is_logged_in = true;
    } else {
      $this->user_is_logged_in = false;
    }

  }

  public function logout() {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    unset($_SESSION['user_id']);

    if ($user_id) {
        $conn = $this->db->db_connect();
        $sql = "INSERT INTO login_trail (user_id, activity) VALUES ($user_id, 'Logged Out')";
        $result = $this->db->query($sql);

        if ($result) {
            echo "Logout successful";
        } else {
            echo "Logout failed";
        }
    }
  } 

  public function msg($type ='', $msg =''){
    if(!empty($msg)){
      if(strlen(trim($type)) == 1){
        $type = str_replace( array('d', 'i', 'w','s'), array('danger', 'info', 'warning','success'), $type );
      }
      $_SESSION['msg'][$type] = $msg;
    } else {
      return $this->msg;
    }
  }

  private function flash_msg(){
    if(isset($_SESSION['msg'])) {
      $this->msg = $_SESSION['msg'];
      unset($_SESSION['msg']);
    } else {
      $this->msg;
    }
  }
}

$session = new Session();
$msg = $session->msg();

?>
