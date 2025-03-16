<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Wp_Login_Ajaxify
 * @subpackage Wp_Login_Ajaxify/includes
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Wp_Login_Ajaxify_i18n
{
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'wp-login-ajaxify',
			false,
			dirname( WP_LOGIN_AJAXIFY_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
