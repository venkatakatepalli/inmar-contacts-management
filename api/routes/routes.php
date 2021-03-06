<?php 

// require 'db.php';

// signup
$app->post('/signup', function($request){
   $input = $request->getParsedBody();
   $firstname = $input['firstname'];
   $lastname = $input['lastname'];
   $email = $input['email'];
   $password = $input['password'];
   $aadharno = $input['aadharno'];
   // $date = new DateTime();
   // $date->format('U = Y-m-d H:i:s');
   $sql = "INSERT INTO `users`(`firstname`, `lastname`, `email`, `password`, `aadharno`, `account_status`) VALUES ('$firstname','$lastname','$email', '$password', '$aadharno',1)";
   try {
     $db = getDB();
     if($stmt = $db->query($sql)==true)
     {
      $data = array('status' => 'success', 'message' => 'Registered Sucessfully, You can Login Now');
     }else
      $data = array('status' => 'failed', 'message' => 'Failed Try Again');
     $db = null;
     echo json_encode($data);
   } catch(PDOException $e) {
     echo '{"error":{"text":'. $e->getMessage() .'}}'; 
   }
});

$app->post('/login',function($request,$response){
  $input = $request->getParsedBody();
  $email = $input['email'];
  $password = $input['password'];
  $sql = "SELECT * FROM `users` where email='$email' and password='$password'";
  try {
    $db = getDB();
    $stmt = $db->query($sql);  
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    $users = $users[0];
    $data = array(
      'status' => 'success', 
      'token' => 'token', 
      'userDetails' => $users
      );
    echo json_encode($data);
  } catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
  }
});

// contacts by id
$app->post('/contacts', function (Request $request, Response $response) {
  $input = $request->getParsedBody();
  $u_id = $input['u_id'];
  $sql = "SELECT * FROM `contacts` WHERE user_id=$u_id";
  try {
    $db = getDB();
    $stmt = $db->query($sql);  
    $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    $key = 'codejunkers';
    $id = 10;
    $key2="";
    $signature = hash_hmac('SHA256',"$key,$id",$key2);

    $data = array(
      'status' => 'success', 
      'token' => $signature, 
      'contacts' => $contacts
     );
    echo json_encode($data);
  } catch(PDOException $e) {
  //     //error_log($e->getMessage(), 3, '/var/tmp/php.log');
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
  }
});

// manage contact add edit
$app->post('/manage_contact', function($request){
   $input = $request->getParsedBody();
   $name = $input['name'];
   $mobile = $input['mobile'];
   $email = $input['email'];
   $group_id = $input['group_id'];
   // if data contains id it is update
   if ($input['id']) {
     # code...
    $id=$input['id'];
   $sql = "UPDATE `contacts` SET `name`='$name',`mobile`='$mobile', `email`='$email',`group_id`=$group_id WHERE id=$id";
   try {
     $db = getDB();
     if($stmt = $db->query($sql)==true)
     {
      $data = array('status' => 'success', 'message' => 'Updated Sucessfully');
     }else
      $data = array('status' => 'failed', 'message' => 'Failed Try Again');
     $db = null;
     echo json_encode($data);
   } catch(PDOException $e) {
     echo '{"error":{"text":'. $e->getMessage() .'}}'; 
   }

   } else {
    // else add new
    $u_id = $input['u_id'];
    $sql = "INSERT INTO `contacts`(`name`, `mobile`, `email`, `group_id`, `user_id`) VALUES ('$name','$mobile', '$email', '$group_id', $u_id)";
    try {
      $db = getDB();
      if($stmt = $db->query($sql)==true)
      {
       $data = array('status' => 'success', 'message' => 'Added Sucessfully');
      }else
       $data = array('status' => 'failed', 'message' => 'Failed Try Again');
      $db = null;
      echo json_encode($data);
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
   }
   
});

// manage contact add edit
$app->post('/delete_contact', function($request){
    $input = $request->getParsedBody();
    $id=$input['id'];
    $sql = "Delete from `contacts` WHERE id=$id";
    try {
      $db = getDB();
      if($stmt = $db->query($sql)==true)
      {
       $data = array('status' => 'success', 'message' => 'Deleted Sucessfully');
      }else
       $data = array('status' => 'failed', 'message' => 'Failed Try Again');
      $db = null;
      echo json_encode($data);
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
});

// contacts by id
$app->post('/contacts_groups', function (Request $request, Response $response) {
  $input = $request->getParsedBody();
  $u_id = $input['u_id'];
  $sql = "SELECT * FROM `contact_groups` WHERE user_id=$u_id";
  try {
    $db = getDB();
    $stmt = $db->query($sql);  
    $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    $key = 'codejunkers';
    $id = 10;
    $key2="";
    $signature = hash_hmac('SHA256',"$key,$id",$key2);

    $data = array(
      'status' => 'success', 
      'token' => $signature, 
      'contacts' => $contacts
     );
    echo json_encode($data);
  } catch(PDOException $e) {
  //     //error_log($e->getMessage(), 3, '/var/tmp/php.log');
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
  }
});

// manage contact add edit
$app->post('/manage_contacts_group', function($request){
   $input = $request->getParsedBody();
   $name = $input['name'];
   $status = $input['status'];
   // if data contains id it is update
   if ($input['id']) {
     # code...
    $id=$input['id'];
   $sql = "UPDATE `contact_groups` SET `name`='$name',`status`=$status WHERE id=$id";
   try {
     $db = getDB();
     if($stmt = $db->query($sql)==true)
     {
      $data = array('status' => 'success', 'message' => 'Updated Sucessfully');
     }else
      $data = array('status' => 'failed', 'message' => 'Failed Try Again');
     $db = null;
     echo json_encode($data);
   } catch(PDOException $e) {
     echo '{"error":{"text":'. $e->getMessage() .'}}'; 
   }

   } else {
    // else add new
    $u_id = $input['u_id'];
    $sql = "INSERT INTO `contact_groups`(`user_id`, `name`, `status`) VALUES ($u_id, '$name', $status)";
    try {
      $db = getDB();
      if($stmt = $db->query($sql)==true)
      {
       $data = array('status' => 'success', 'message' => 'Added Sucessfully');
      }else
       $data = array('status' => 'failed', 'message' => 'Failed Try Again');
      $db = null;
      echo json_encode($data);
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
   }
   
});

// manage contact add edit
$app->post('/delete_contacts_group', function($request){
    $input = $request->getParsedBody();
    $id=$input['id'];
    $sql = "Delete from `contact_groups` WHERE id=$id";
    try {
      $db = getDB();
      if($stmt = $db->query($sql)==true)
      {
       $data = array('status' => 'success', 'message' => 'Deleted Sucessfully');
      }else
       $data = array('status' => 'failed', 'message' => 'Failed Try Again');
      $db = null;
      echo json_encode($data);
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
});

$app->post('/users', function (Request $request, Response $response) {

  $sql = "SELECT * FROM userslists";
  try {
    $db = getDB();
    $stmt = $db->query($sql);  
    $contests = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    $key = 'codejunkers';
    $id = 10;
    $key2="";
    $signature = hash_hmac('SHA256',"$key,$id",$key2);

    $data = array('token' => $signature ,'contests' => $contests );
    echo json_encode($data);
  } catch(PDOException $e) {
  //     //error_log($e->getMessage(), 3, '/var/tmp/php.log');
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
  }
});
?>