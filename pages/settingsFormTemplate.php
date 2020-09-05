<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h1><?php esc_attr_e('Login Page Settings'); ?></h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">

                        <h2><span><?php esc_attr_e('Set Up Properties'); ?></span></h2>

                        <div class="inside">
                            <form action="options.php" method="post">
                                <?php
                                settings_errors();

                                settings_fields(self::FIELD_PREFIX . 'custom_login');

                                // page slug needed as arg!
                                do_settings_sections('custom_login_opts');

                                submit_button();

                                ?>
                            </form>
                        </div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables .ui-sortable -->
            </div>
            <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h2><span><?php esc_attr_e('Usage of shortcodes'); ?></span></h2>

                        <div class="inside">
                            <p>Add a login form to a page/post:
                                <code>[login_form]</code>
                            </p>
                            <p>Add a register form to a page/post:
                                <code>[register_form]</code>
                            </p>
                        </div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->
            </div>
            <!-- #postbox-container-1 .postbox-container -->
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div> <!-- .wrap -->