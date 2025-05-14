<?php
/**
 * This file contains the definition of the Wp_Login_Ajaxify_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Wp_Login_Ajaxify
 * @subpackage    Wp_Login_Ajaxify/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Wp_Login_Ajaxify_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-login-ajaxify',
			false,
			dirname( WP_LOGIN_AJAXIFY_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
