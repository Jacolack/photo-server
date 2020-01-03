<?php
session_start();
$hashedkey = '$2y$10$WQRe0OL8WE5GIku9BKXH7uKZBpm5w8MaN/kY583PkrWFQ6p1r8BhS';
# Create a hash for a password with hashgenerator.php; in this case, I used "test1234"
if (isset($_SESSION["verified"]) && $_SESSION["verified"]) {
  header("Location: /index.php");
  # Check if a user has been previously verified first, in order to redirect them as quickly as possible.
}

if (isset($_POST["key"])) {
  $key = trim($_POST["key"]);
  $verifiedpassword = password_verify(
    base64_encode(
      hash("sha256", $key, true)
    ),
    $hashedkey
  );
  # Sanitized input to make it easier the enter in the password; it is very easy to strengthen these restrictions, or lessen them.
  if ($verifiedpassword) {
    $_SESSION["verified"] = true;
    $whitelist = ["/index.php"];
    $whitelist = ["/search.php"];
    # Add any other pages you wish to be accessible through the continue param.
    $nextpage = $_GET["continue"];
    if (isset($nextpage) && in_array($nextpage, $whitelist)) {
      header("Location: $nextpage");
    } else {
      header("Location: /index.php");
    }
  } else {
    $error = "That key is invalid!";
  }
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Verify to Continue</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/assets/login.css">
  </head>
  <body>
	<div>
    <h1>Verify to Continue</h1>
    <p>Please enter the verification key to continue.</p>
    <form action="login.php<?php if (isset($_GET["continue"])) echo "?continue=" . htmlentities($_GET["continue"]); ?>" method="post" autocomplete="off">
      <input type="password" name="key" id="key" placeholder="Key">
<?php if (isset($error)) {
echo "<p id='errorMsg'>$error</p>\n";
}
	?>
      <input type="submit" value="Verify">
    </form>
	</div>
  </body>
</html>
