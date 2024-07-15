<html>  
<head>  
    <title>Login Form</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
</head>
<style>
 .box
 {
  width:100%;
  max-width:400px;
  background-color:#f9f9f9;
  border:1px solid #ccc;
  border-radius:5px;
  padding:16px;
  margin:0 auto;
 }
</style>
<body>  
<?php
require_once 'vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
?>
<div class="container">
        <div class="box">
        <div class="form-group">
        <label for="email">Emailid: <?php echo $email; ?></label>
        <label for="name">Name: <?php echo $name; ?></label>


        <?php
        // (G) OTP SETTINGS
        define("OTP_VALID", "15"); // otp valid for n minutes
        define("OTP_LEN", "6");    // otp length

        // (D2) CREATE RANDOM OTP
        $alphabets = "0123456789";
        $count = strlen($alphabets) - 1;
        $pass = "";
        for ($i=0; $i<OTP_LEN; $i++) { $pass .= $alphabets[rand(0, $count)]; }
    ?>
    <div id="OTP">OTP: <?php echo $pass; ?></div>
        </div>
        </div>

    </div>
<?php } else {?>
      <div class="container">  
    <div class="table-responsive">  
    <h3 align="center">Login using Google with PHP</h3>
     <div class="box">
      <div class="form-group">
       <label for="email">Emailid</label>
       <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" required />
      </div>
      <div class="form-group">
       <label for="password">Password</label>
       <input type="password" name="pwd" id="pwd" placeholder="Enter Password" class="form-control"/>
      </div>
      <div class="form-group">
       <input type="submit" id="login" name="login" value="Login" class="btn btn-success form-control"/>
       <hr>
       <center><a href="<?php echo $client->createAuthUrl() ?>"><img src="google-signin.png" width="256"></a></center>
      </div>
      </div>
   </div>  
  </div>
<?php } ?>
 </body>  

 <script>
// Select the element
var messageElement = document.getElementById('OTP');

// Set timeout to hide the element after 1 minute (60000 milliseconds)
setTimeout(function() {
    messageElement.style.display = 'none';
}, 60000);
</script>

</html>