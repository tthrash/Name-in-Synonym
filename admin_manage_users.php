<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/main_style.css" type="text/css">
  <title>Final Project</title>
</head>
<?PHP
//session_start();
  require('session_validation.php');
  include('admin_manage_users_helper.php')
?>
<body>
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>
  <div class="nav-wrapper">
    <div class="navBar">
      <?PHP echo getTopNav(); ?>
    </div>
  </div>
  <!-- NOTE: How do we want to handle edit and delete? Should we do an a link and put info in url or should it be private and wrap form around table? -->
  <!-- TODO: Need option to edit and delete user. Should admin be able to see/change user_password and activation_token -->
  <table class="userTable">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Verified</th>
        <th>Admin Status</th>
      </tr>
    </thead>
    <tbody>
      <?PHP echo create_user_table(); ?>
    </tbody>
  </table> 
</body>
</html>
