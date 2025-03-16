<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @package    Wp_Login_Ajaxify
 * @subpackage Wp_Login_Ajaxify/public
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Wp_Login_Ajaxify_Public
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name     The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    		The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @param    string    $plugin_name   	The name of the plugin.
	 * @param    string    $version   		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name 	= $plugin_name;
		
		$this->version 		= $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style( $this->plugin_name, WP_LOGIN_AJAXIFY_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script( $this->plugin_name, WP_LOGIN_AJAXIFY_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'WP_LOGIN_AJAXIFY', array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'failedMsg'	=> __( 'Something went wrong! Please try again later!', 'wp-login-ajaxify' ),
		) );
	}

	/**
	 * Handles AJAX login requests.
	 *
	 * This function processes AJAX login requests, authenticates the user using WordPress's
	 * `wp_signon()` function, and sends a JSON response indicating the login status.
	 *
	 * @since   2.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function login_ajax()
	{
		// Verify nonce for security.
		if ( ! isset( $_POST['wpla_login_nonce'] ) || ! wp_verify_nonce( $_POST['wpla_login_nonce'], 'wpla_login_action' ) )
		{
			wp_send_json( array( 'wp_error' => __( 'Security check failed.', 'wp-login-ajaxify' ) ) );
			
			die();
		}

		// Sanitize and validate input.
		$login 						= sanitize_text_field( $_POST['login'] ?? '' );
		$pass 						= sanitize_text_field( $_POST['pass'] ?? '' );
		$rememberme 				= sanitize_text_field( $_POST['rememberme'] ?? '' );

		$credentials 				= array(
			'user_login' 	=> $login,
			'user_password'	=> $pass,
			'remember' 		=> $rememberme
		);

		$loginResult 				= wp_signon( $credentials );

		$result 					= array();

		if ( is_a( $loginResult, 'WP_User' ) )
		{
			$result['wp_success']	= apply_filters( 'wpla_login_success_message', __( 'Successfully Logged in, redirecting...', 'wp-login-ajaxify' ) );
			
			wp_send_json( $result );
		}
		elseif ( is_wp_error( $loginResult ) )
		{
			$result['wp_error'] 	= $loginResult->get_error_message();
			
			wp_send_json( $result );
		}
		
		die();
	}

	/**
	 * Adds custom content to the login page form, including a loading GIF and nonce.
	 *
	 * This function adds inline CSS to display a loading GIF during AJAX login requests
	 * and includes a hidden nonce field for security.
	 *
	 * @since   2.0.0
	 * @access  public
	 * @return  void
	 */
	public function login_form()
	{
		// Get the URL of the loading GIF.
		$loading_gif = WP_LOGIN_AJAXIFY_PLUGIN_URL . '/public/images/loading.gif';
		
		// Output inline CSS to display the loading GIF.
		echo "
		<style>
			.updating::before {
				background-image: url( '". esc_url( $loading_gif ) ."' );
			}
		</style>";

		// Output a hidden nonce field for security.
		echo '<input type="hidden" id="wpla_login_nonce" value="'. wp_create_nonce( 'wpla_login_action' ) .'">';
	}
}
