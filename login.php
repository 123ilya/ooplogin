<?php
require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));
        if ($validation->passed()) {

            //log user in
            $user = new User();
          
            $login = $user->login(Input::get('username'), Input::get('password')); 

            if ($login) {
                echo 'Success';
            } else {
                echo '<p> Sorry! Loggin in failed! </p>';
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
// -------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">

</head>

<body>
    <form action="" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" autocomplete="off">
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" autocomplete="off">
        </div>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Log in">
    </form>
</body>

</html>