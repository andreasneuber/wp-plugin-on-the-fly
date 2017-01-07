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
        ?>


        <div class="wrap">

            <div id="icon-plugins" class="icon32"></div>
            <h2>Plugin admin page</h2>

            <form method="post" action="options.php">

                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">{CLASS_NAME} option 1</th>
                        <td>
                            <input name="{CLASS_NAME}_option_1"/>
                        </td>
                    </tr>

                </table>


                <?php
                wp_nonce_field( '{CLASS_NAME}', '{CLASS_NAME}_form_nonce' );
                submit_button();
                ?>
            </form>

        </div>
        <?php
    }




}


${CLASS_NAME} = new {CLASS_NAME}();