<?php
/**
 * Plugin Name: WP Plugin on-the-fly
 * Plugin URI: http://github.com
 * Description: This plugin helps to create additional test plugins fast.
 * Version: 1.0.0
 * Author: Andreas Neuber
 * Author URI: https://github.com/andreasneuber
 * License: GPL2
 */


defined( 'ABSPATH' ) or die( 'Dont be so direct..' );


class wp_plugin_on_the_fly {


	public $page_template_file_name;
	public $page_template_name;


	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page_create_plugin_page' ) );
	}


	public function add_page_create_plugin_page(){

		add_management_page(
			'Create Plugin',
			'Create Plugin',
			'manage_options',
			'wp_plugin_on_the_fly',
			array( $this, 'create_plugin_admin_page' ) );

	}


	public function create_plugin_admin_page(){

		//TODO - the usual sanitize, validate, nonce etc.

		$success = false;

		if($_POST){

			if (
				! isset( $_POST['create_wp_plugin_form_nonce'] )
				|| ! wp_verify_nonce( $_POST['create_wp_plugin_form_nonce'], 'create_wp_plugin' )
			) {
				print 'Sorry, your nonce did not verify.';
				exit;
			}
			else {
			    echo "<pre>";print_r( $_POST );echo "</pre>";
            }

			$plugin_name 	= filter_var( $_POST['plugin_name'], FILTER_SANITIZE_STRING );
			$plugin_purpose = filter_var( $_POST['plugin_purpose'], FILTER_SANITIZE_STRING );
			$author_name 	= filter_var( $_POST['author_name'], FILTER_SANITIZE_STRING );
			$url_author		= filter_var( $_POST['url_author'], FILTER_SANITIZE_STRING );
			$url_plugin		= filter_var( $_POST['url_plugin'], FILTER_SANITIZE_STRING );
			$plugin_extras	= filter_var( $_POST['plugin_extras'], FILTER_SANITIZE_STRING );


            // CREATE plugin folder with base plugin file
			$plugin_folder_name 	= trim( strtolower( $plugin_name ) );
			$plugin_folder_name 	= str_replace( ' ', '_' , $plugin_folder_name );
			mkdir( $this->get_plugins_dir() . $plugin_folder_name . '/' );

            $placeholders = array('{PLUGIN_NAME}', '{PLUGIN_PURPOSE}', '{PLUGIN_AUTHOR}', '{URL_AUTHOR}', '{URL_PLUGIN}', '{CLASS_NAME}' );
            $replacements = array( $plugin_name, $plugin_purpose, $author_name, $url_author, $url_plugin, $plugin_folder_name );


			switch( $plugin_extras ){

				case 'base_class':
                    $file = $this->replace_placeholders( $placeholders, $replacements, 'plugin_base_page_with_class.php' );
					break;

				case 'base_class_admin_page':
                    $file = $this->replace_placeholders( $placeholders, $replacements, 'plugin_base_with_admin_page.php' );
					break;


				default:
                    $file = $this->replace_placeholders( $placeholders, $replacements, 'plugin_base_page.php' );
					break;
			}

			$this->create_plugin_base_file( $plugin_folder_name, $file );

			$success = true;
		}
		?>

		<div class="wrap">

			<?php
			if( $success ){
				$url = admin_url() . 'plugins.php'
				?>
				<div class="notice notice-success is-dismissible">
					<?php echo "<p>Success! Visit <a href='{$url}' target='_blank'>Plugins > Installed Plugins</a> and activate your new plugin.</p>"; ?>
				</div>
				<?php
			}
			?>

			<div id="icon-plugins" class="icon32"></div>
			<h2>Create your plugin</h2>

			<form method="post" action="tools.php?page=wp_plugin_on_the_fly&ts=<?php echo time(); ?>">

				<table class="form-table">

					<tr valign="top">
						<th scope="row">Name of plugin</th>
						<td>
							<input name="plugin_name"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">What does the plugin do?</th>
						<td>
							<input name="plugin_purpose"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Name of author</th>
						<td>
							<input name="author_name"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Author url</th>
						<td>
							<input name="url_author"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Plugin url</th>
						<td>
							<input name="url_plugin"/>
						</td>
					</tr>

                    <tr valign="top">
						<th scope="row">Plugin extras</th>
						<td>
							<select name="plugin_extras"/>
								<option value="nada">--None--</option>
								<option value="base_class">Base class</option>
								<option value="base_class_admin_page">Base class with 1 admin page in Tools section</option>
							</select>
						</td>
					</tr>

				</table>


				<?php
				wp_nonce_field( 'create_wp_plugin', 'create_wp_plugin_form_nonce' );
				submit_button();
				?>
			</form>

		</div>
		<?php
	}


	private function get_plugins_dir(){
		return $plugin_dir = ABSPATH . 'wp-content/plugins/';
	}


	private function replace_placeholders( $placeholders, $replacements, $tmpl_file ){
        $plugin_base_sample_file    = plugin_dir_path(__FILE__) . 'templates/' . $tmpl_file;
        $file                       = file_get_contents( $plugin_base_sample_file );
        $file                       = str_replace($placeholders, $replacements, $file);
        return $file;
    }


    private function create_plugin_base_file( $plugin_folder_name , $file_contents ){
		$file_path = $this->get_plugins_dir() . $plugin_folder_name . '/' . $plugin_folder_name . '.php';
		file_put_contents($file_path, $file_contents );
	}


}

$plugin_onthefly = new wp_plugin_on_the_fly();
