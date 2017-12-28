<?php $this->setLayoutVar('title', 'Account') ?>

<h2>Account</h2>
<p>
    User ID:
    <a href="<?php echo $base_url ?>/user/<?php echo $this->escape($user['user_name']); ?>">
        <strong><?php echo $this->escape($user['user_name']); ?></strong>
    </a>
</p>

<ul>
    <li>
        <a href="<?php echo $base_url; ?>/">Home</a>
    </li>
    <li>
        <a href="<?php echo $base_url; ?>/account/signout">Logout</a>
    </li>
</ul>

<h3>Following</h3>

<?php if (count($followings) > 0): ?>
<ul>
    <?php foreach ($followings as $following): ?>
    <li>
        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($following['user_name']); ?>">
            <?php echo $this->escape($following['user_name']); ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
