<?php
ini_set('display_errors', 'On');

require_once(__DIR__ . "/../config/admin/config.php");
require_once(__DIR__ . "/../db/src/Admin.php");
require_once(__DIR__ . "/../db/AdminManager.php");


// Set up doctrine objects
$emf = new EntityManagerFactory();
$em = $emf->getEntityManager();
$am = new AdminManager($em);

// Setup the template engine
require($_SERVER['DOCUMENT_ROOT'] . '/config/admin/mustache.php');
$tpl = $mustache->loadTemplate('admin_profile');

// If the user is logged in, proceed.  Otherwise, show the login screen.
if( array_key_exists('logged_in',$_SESSION) && $_SESSION['logged_in'] == "true") {
  $data['user_info'] = true;
  $data['page_title'] = 'Update Profile';
} else {
  $data['page_title'] = 'Log In';
}
$data['title'] = 'Project Phoenix - Admin';

$data['admin']['id'] = $_SESSION['id'];
$data['admin']['email'] = $_SESSION['email'];

// Process any form input
if(isset($_POST['action'])) {
  switch($_POST['action']) {
    case "update":
      try {
        // Load the provided admin from the database
        $admin = $am->load($_POST['id']);
      } catch (Exception $e) {
        $data['error'] = "An error has occurred.  The object could not be retrieved.";
        break;
      }
      // Populate the data element for the template engine
      $data['admin']['id'] = $admin->getId();
      $data['email']['id'] = $admin->getEmail();
    break;
  }
}

// Pass the resulting data into the template
echo $tpl->render($data);
?>