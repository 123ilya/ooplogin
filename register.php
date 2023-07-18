<?php
require './core/init.php';


if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        // echo 'i have been run';
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6

            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
            )

        ));
        if ($validation->passed()) {
            //register user
            // Session::flash('success', 'You registered successfully!');
            // header('Location: index.php');
            $user =  new User();
            $salt =  Hash::salt(32);
            try {
                $user->create(
                    array(
                        'username' => Input::get('username'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        // 'salt' => $salt,
                        'salt' => mb_convert_encoding($salt, 'UTF-8'), //Принудительно конвертируем в UTF-8 так как
                        //если этого не сделать, то возникнет проблемма с дабавлением значения в БД
                        'name' => Input::get('name'),
                        'joined' => date("Y-m-d H:i:s"),
                        'group' => 1
                    )
                );
                Session::flash('home', 'You have bin registered and can now log in!');
                // Redirect::to('index.php');
                Redirect::to(404);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            //output errors
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <form action="" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo  escape(Input::get('username')) ?>" autocomplete="off">
        </div>
        <div class="field">
            <label for="password">Choose a password</label>
            <input type="password" name="password" id="password">
        </div>
        <div class="field">
            <label for="password_again">Enter your password again</label>
            <input type="password" name="password_again" id="password_again">
        </div>
        <div class="field">
            <label for="name">Your name </label>
            <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')) ?>">
        </div>
        <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
        <input type="submit" value="Register">
    </form>
</body>

</html>