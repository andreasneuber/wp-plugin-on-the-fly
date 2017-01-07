<?php
/**
 * Plugin Name: {PLUGIN_NAME}
 * Plugin URI: {URL_AUTHOR}
 * Description: {PLUGIN_PURPOSE}
 * Version: 1.0.0
 * Author: {PLUGIN_AUTHOR}
 * Author URI: {URL_PLUGIN}
 * License: GPL2
 */


defined( 'ABSPATH' ) or die( 'Dont be so direct..' );

class {CLASS_NAME} {


    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_page_{CLASS_NAME}' ) );
        add_action( 'admin_init', array( $this, 'register_{CLASS_NAME}_settings' ) );
        add_action( 'admin_init', array( $this, 'myPlugin_admin_scripts' ) );
        add_action( 'admin_head', array( $this, 'additional_css_for_success_modal') );
    }


    public function add_admin_page_{CLASS_NAME}(){

        add_options_page(
            '{PLUGIN_NAME}',
            '{PLUGIN_NAME}',
            'manage_options',
            '{CLASS_NAME}',
            array( $this, '{CLASS_NAME}_admin_page' ) );

    }



    public function {CLASS_NAME}_admin_page(){
        $op_val_1 = get_option( '{CLASS_NAME}_option_1' );
        $op_val_2 = get_option( '{CLASS_NAME}_option_2' );
        ?>


        <div class="wrap">

            <div id="icon-plugins" class="icon32"></div>
            <h2>Plugin admin page</h2>

            <div id="saveResult"></div>

            <form method="post" action="options.php" id="options_form" >
                <?php
                settings_fields( '{CLASS_NAME}-option-group' );
                do_settings_sections( '{CLASS_NAME}-option-group' );
                ?>

                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">{CLASS_NAME} option 1</th>
                        <td>
                            <input type='text' name="{CLASS_NAME}_option_1" value="<?php echo $op_val_1; ?>" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">{CLASS_NAME} option 2</th>
                        <td>
                            <input type='text' name="{CLASS_NAME}_option_2" value="<?php echo $op_val_2; ?>" />
                        </td>
                    </tr>

                </table>


                <?php
                wp_nonce_field( '{CLASS_NAME}', '{CLASS_NAME}_form_nonce' );
                submit_button();
                ?>
            </form>


            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#options_form').submit(function() {
                        jQuery(this).ajaxSubmit({
                            success: function(){
                                jQuery('#saveResult').html("<div id='saveMessage' class='success_modal'></div>");
                                jQuery('#saveMessage').append("<p><?php echo htmlentities(__('Settings Saved Successfully','wp'),ENT_QUOTES); ?></p>").show();
                            },
                            timeout: 5000
                        });
                        setTimeout("jQuery('#saveMessage').hide('slow');", 5000);
                        return false;
                    });
                });
            </script>

        </div>
        <?php
    }


    function register_{CLASS_NAME}_settings() {
        register_setting( '{CLASS_NAME}-option-group', '{CLASS_NAME}_option_1' );
        register_setting( '{CLASS_NAME}-option-group', '{CLASS_NAME}_option_2' );
    }


    function myPlugin_admin_scripts() {
        if ( is_admin() ){
            if ( isset($_GET['page']) && $_GET['page'] == '{CLASS_NAME}' ) {
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-form' );
            }
        }
    }


    function additional_css_for_success_modal(){
        echo '<style>
            .success_modal {
            display: block;
            /* position: fixed;
            top: 45%;
            left: 25%;
            */
            width: 300px;
            height: auto;
            padding: 5px 20px;
            border: 3px solid green;
            background-color: #EFE;
            z-index:1002;
            overflow: auto;
            -moz-border-radius: 15px; 
            -webkit-border-radius: 15px;
            -moz-box-shadow: 5px 5px 10px #cfcfcf;
            -webkit-box-shadow: 5px 5px 10px #cfcfcf;
            }
        </style>';
    }


}


${CLASS_NAME} = new {CLASS_NAME}();
