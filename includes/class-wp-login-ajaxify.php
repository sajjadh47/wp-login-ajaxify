<?php
/**
 * This file contains the definition of the Wp_Login_Ajaxify class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Wp_Login_Ajaxify
 * @subpackage    Wp_Login_Ajaxify/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks and public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Wp_Login_Ajaxify {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Wp_Login_Ajaxify_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		$this->version     = defined( 'WP_LOGIN_AJAXIFY_PLUGIN_VERSION' ) ? WP_LOGIN_AJAXIFY_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'wp-login-ajaxify';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Login_Ajaxify_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Login_Ajaxify_Admin.  Defines all hooks for the admin area.
	 * - Wp_Login_Ajaxify_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WP_LOGIN_AJAXIFY_PLUGIN_PATH . 'includes/class-wp-login-ajaxify-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WP_LOGIN_AJAXIFY_PLUGIN_PATH . 'admin/class-wp-login-ajaxify-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WP_LOGIN_AJAXIFY_PLUGIN_PATH . 'public/class-wp-login-ajaxify-public.php';

		$this->loader = new Wp_Login_Ajaxify_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wp_Login_Ajaxify_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Wp_Login_Ajaxify_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts', 1 );

		$this->loader->add_action( 'login_form', $plugin_public, 'login_form' );

		$this->loader->add_action( 'wp_ajax_nopriv_wpla_login_ajax', $plugin_public, 'login_ajax' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Wp_Login_Ajaxify_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
