<?php $this->setLayoutVar('title', 'Login') ?>

<h2>Login</h2>

<p>
    <a href="<?php echo $base_url; ?>/account/signup">New User registration</a>
</p>

<form action="<?php echo $base_url; ?>/account/authenticate" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

    <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
    <?php endif; ?>

    <?php echo $this->render('account/inputs', array(
        'user_name' => $user_name, 'password' => $password,
    )); ?>

    <p>
        <input type="submit" value="Login" />
    </p>
</form>
