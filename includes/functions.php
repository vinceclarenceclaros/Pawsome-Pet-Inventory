<?php
$errors = array();

function real_escape($str) {
  global $con;
  return mysqli_real_escape_string($con, $str);
}

function remove_junk($str) {
  return htmlspecialchars(strip_tags(nl2br($str)), ENT_QUOTES);
}

function first_character($str) {
  return ucfirst(str_replace('-', ' ', $str));
}

function validate_fields($fields) {
  global $errors;
  foreach ($fields as $field) {
    $val = remove_junk($_POST[$field] ?? '');
    if (empty($val)) {
      $errors[] = $field . " can't be blank.";
    }
  }
}

function display_msg($msg, $isSuccess = false) {
  $alertClass = $isSuccess ? 'alert-success' : 'alert-danger';
  $output = '';

  if (!empty($msg)) {
    $output .= '<div class="alert ' . $alertClass . '">';

    if (is_array($msg)) {
      foreach ($msg as $error) {
        if (is_array($error)) {
          foreach ($error as $e) {
            $output .= '<p>' . $e . '</p>';
          }
        } else {
          $output .= '<p>' . $error . '</p>';
        }
      }
    } else {
      $output .= '<p>' . $msg . '</p>';
    }

    $output .= '</div>';
  }

  if ($output !== '<div class="alert ' . $alertClass . '"></div>') {
    echo $output;
  }
}

function display_success_msg($msg) {
  display_msg($msg, true); // Call the existing display_msg function with $isSuccess set to true
}

function redirect($url, $permanent = false) {
  if (!headers_sent()) {
    header('Location: ' . $url, true, ($permanent ? 301 : 302));
  }
  exit();
}

function total_price($totals) {
  $sum = $sub = 0;
  foreach ($totals as $total) {
    $sum += $total['total_saleing_price'];
    $sub += $total['total_buying_price'];
  }
  $profit = $sum - $sub;
  return array($sum, $profit);
}

function read_date($str) {
  return $str ? date('F j, Y, g:i:s a', strtotime($str)) : null;
}

function make_date() {
  return date('Y-m-d H:i:s');
}

function count_id() {
  static $count = 1;
  return $count++;
}

function randString($length = 5) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  $str = '';
  $max = strlen($characters) - 1;
  for ($i = 0; $i < $length; $i++) {
    $str .= $characters[mt_rand(0, $max)];
  }
  return $str;
}

function find_by_column($table, $column, $value) {
  global $db;
  $escaped_value = $db->escape($value);
  $query = "SELECT * FROM {$table} WHERE {$column} = '{$escaped_value}' LIMIT 1";
  $result = $db->query($query);
  return $db->fetch_assoc($result);
}

?>
