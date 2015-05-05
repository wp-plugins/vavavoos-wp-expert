<?php
/**
 * Plugin permission page
 */
function vavavoos_setupJPermissions()
{
    ?>
    <script>
        window.onload = function () {
            jQuery.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                options.crossDomain = {
                    crossDomain: true
                };
                options.xhrFields = {
                    withCredentials: true
                };
            });

            jQuery.ajax({
                url: '<?php echo VABASEURL."wppem"?>',
                type: 'POST',
                data: null,
                dataType: 'json',
                success: function (msg) {
                    jQuery.each(msg.data, function (e, user) {
                        Request_syn_cb("action=checkpermission&email=" + user.email, function (e) {
                            jQuery(".tablepermissions").append("<tr><td>" + user.email + "</td><td>" + e + "</td><td><a class='makeadmin'>Make Admin</a>");
                        });

                    });

                    jQuery(document).on("click", ".makeadmin", function (e) {
                        email = jQuery(this).parents("tr").find("td:eq(0)").text();
                        Request_syn_cb("action=makeadmin&email=" + email, function (e) {
                            window.location.reload();
                        });
                    });
                }
            });

        };

        function Request_syn_cb(datarequest, functioncommand) {

            var returnString = "";
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: datarequest,
                success: function (data) {
                    returnString = data;
                    functioncommand(data);
                },
                dataType: "text",
                async: false

            });
            return returnString;
        }
    </script>
<?php
}

/**
 * Make the selected hired assistant as wordpress admin
 */
function vavavoos_makeadmin()
{
    $user_email = vavavoos_clean_param($_POST["email"]);
    if (email_exists($user_email) == false) {
        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
        $user_id = wp_create_user(split("@", $user_email)[0], $random_password, $user_email);
        wp_update_user(array('ID' => $user_id, 'role' => "administrator"));
    } else {
        $user = get_user_by("email", $user_email);
        wp_update_user(array('ID' => $user->ID, 'role' => "administrator"));
    }
    wp_die();
}

/**
 * Check wordpress permission of the hired assistant
 */
function vavavoos_checkpermission()
{
    $email = vavavoos_clean_param($_POST["email"]);
    if (email_exists($email) == true) {
        $user = get_user_by("email", $email);
        echo $user->roles[0];

    } else {
        echo "non";
    }
    wp_die();
}

/**
 * Plugin Generate Index page
 */
function vavavoos_plugin_page()
{
    ?>
    <div class="">
        <?php include('layout.phtml');?>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            <?php
            $action = isset($_GET['menu'])?(vavavoos_clean_param($_GET['menu'])):'start';
            ?>
            varefresh('<?php echo $action;?>', '<?php echo VAMENUPAGE;?>');
        });
    </script>
<?php
}

function vavavoos_permission_page()
{

    vavavoos_setupJPermissions();
    ?>
    <table class="tablepermissions wp-list-table widefat fixed ">
        <tr>
            <td>Email Address</td>
            <td>Current Permission</td>
            <td>Action</td>
        </tr>
    </table>

<?php
}

function vavavoos_pluginmanageassistant_page()
{
    ?>
    <div class="">
        <?php include('layout.phtml');?>
    </div>
    <script type="text/javascript">
        jQuery(function ($) {
            <?php
           $action = isset($_GET['menu'])?vavavoos_clean_param($_GET['menu']):'manage';
           ?>
            varefresh('<?php echo $action;?>', '<?php echo VAMENUPAGE;?>');
        });
    </script>
<?php
}

/**
 * Initialize and load plugin scripts
 */
function vavavoos_init_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-widget');

    $custom_scripts = array(
        array(
            'wpe_file_upload',
            VAVAVOOS_PLUGINURL . "assets/js/jquery.fileupload.js"
        ),
        array(
            'wpe_master',
            VAVAVOOS_PLUGINURL . "assets/js/master.js"
        ),
        array(
            'wpe_deserialize',
            VAVAVOOS_PLUGINURL . "assets/js/jquery.deserialize.js"
        ),
        array(
            'wpe_iframe',
            VAVAVOOS_PLUGINURL . "assets/js/jquery.iframe-transport.js"
        ),
        array(
            'wpe_bootstrap',
            VAVAVOOS_PLUGINURL . "assets/js/bootstrap.min.js"
        ),
        array(
            'wpe_momentjs',
            VAVAVOOS_PLUGINURL . "assets/js/moment.min.js"
        ),
        array(
            'wpe_bootstrapdatetimepicker',
            VAVAVOOS_PLUGINURL . "assets/js/bootstrap-datetimepicker.min.js"
        )
    );

    foreach ($custom_scripts as $script) {
        wp_register_script(
            $script[0],
            $script[1],
            array('jquery', 'jquery-ui-widget')
        );

        wp_enqueue_script($script[0]);
    }

    // Conditional polyfills
    $conditional_scripts = array(
        'wpe_html5shiv' => VAVAVOOS_PLUGINURL . 'assets/js/html5shiv.js',
        'wpe_respond' => VAVAVOOS_PLUGINURL . 'assets/js/respond.min.js'
    );

    foreach ($conditional_scripts as $handle => $src) {
        wp_enqueue_script($handle, $src, array(), '', false);
    }

    add_filter('script_loader_tag', function ($tag, $handle) use ($conditional_scripts) {
        if (array_key_exists($handle, $conditional_scripts)) {
            $tag = "<!--[if lt IE 9]>$tag<![endif]-->";
        }
        return $tag;
    }, 10, 2);


}

function vavavoos_init_styles()
{
    //wp_enqueue_style( $handle, $src, $deps, $ver, $media );
    wp_enqueue_style('wpe_bootstrap', VAVAVOOS_PLUGINURL . 'assets/css/bootstrap.min.css', array(), false, 'screen');
    wp_enqueue_style('wpe_bootstraptheme', VAVAVOOS_PLUGINURL . 'assets/css/bootstrap-theme.min.css', array(), false, 'screen');
    wp_enqueue_style('wpe_style', VAVAVOOS_PLUGINURL . 'assets/css/style.css', array(), false, 'screen');
    wp_enqueue_style('wpe_fontawesome', VAVAVOOS_PLUGINURL . 'assets/css/font-awesome.min.css');
    wp_enqueue_style('wpe_bootstrapdatepicker', VAVAVOOS_PLUGINURL . 'assets/css/bootstrap-datetimepicker.min.css', array(), false, 'screen');
    wp_enqueue_style('wpe_customdesign', VAVAVOOS_PLUGINURL . 'assets/css/design.css');
}

/**
 * Clean http parameters sanitize
 */
function vavavoos_clean_param($params = array())
{
    if (!is_array($params)) {
        return sanitize_text_field($params);
    }

    foreach ($params as &$param) {
        sanitize_text_field($param);
    }
    return $params;
}

?>