<h3 class="ag_custom_login_header"><?php _e('Login'); ?></h3>

<?php
// show any error messages after form submission
$this->validate->showErrorMessages();
?>

<form id="ag_login_form" class="ag_form" action="" method="post">
    <fieldset>
        <p>
            <label for="ag_user_login">Username</label><br />
            <input name="ag_user_login" id="ag_user_login" class="required" type="text" autocomplete="username" />
        </p>
        <p>
            <label for="ag_user_pass">Password</label><br />
            <input name="ag_user_pass" id="ag_user_pass" class="required" type="password" autocomplete="current-password" />
        </p>
        <p class="forgetmenot">
            <label>
                <input name="ag_remember_me" type="checkbox" id="rememberme" value="forever" tabindex="90" />
                Remember Me
            </label>
        </p>
        <p>
            <input type="hidden" name="ag_login_nonce" value="<?php echo $agLoginFormNonce; ?>" />
            <input id="ag_login_submit" type="submit" value="Login" />
        </p>
    </fieldset>
    <p id="nav">
        <a href="<?php echo site_url() ?>/wp-login.php?action=lostpassword" title="Password Lost and Found">Lost your password?</a>
    </p>
</form>