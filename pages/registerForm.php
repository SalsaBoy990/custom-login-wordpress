<h3 class="ag_custom_login_header"><?php _e('Register New Account'); ?></h3>

<?php
// show any error messages after form submission
$this->showErrorMessages();
?>

<form id="ag_registration_form" name="ag_registration_form" class="ag_form" action="" method="POST">
    <fieldset>
        <p>
            <label for="ag_user_register"><?php _e('Username*'); ?></label><br />
            <input name="ag_user_register" id="ag_user_register" class="required" type="text" autocomplete="username" />
        </p>
        <p>
            <label for="ag_user_email"><?php _e('Email*'); ?></label><br />
            <input name="ag_user_email" id="ag_user_email" class="required" type="email" autocomplete="email" />
        </p>
        <p>
            <label for="ag_user_first"><?php _e('First Name'); ?></label><br />
            <input name="ag_user_first" id="ag_user_first" type="text" autocomplete="given-name" />
        </p>
        <p>
            <label for="ag_user_last"><?php _e('Last Name'); ?></label><br />
            <input name="ag_user_last" id="ag_user_last" type="text" autocomplete="family-name" />
        </p>
        <p>
            <label for="password"><?php _e('Password*'); ?></label><br />
            <input name="ag_user_pass" id="password" class="required" type="password" autocomplete="new-password" />
        </p>
        <p>
            <label for="password_again"><?php _e('Password Again*'); ?></label><br />
            <input name="ag_user_pass_confirm" id="password_again" class="required" type="password" autocomplete="new-password" />
        </p>
        <p>
            <input type="hidden" name="ag_register_nonce" value="<?php echo $agRegisterFormNonce; ?>" />
            <input id="ag_register_submit" type="submit" value="<?php _e('Register Your Account'); ?>" />
        </p>
    </fieldset>
</form>