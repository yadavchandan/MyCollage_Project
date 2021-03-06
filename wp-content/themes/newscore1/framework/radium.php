<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
 * Radium Framework Core - A WordPress theme development framework.
 *
 * This file is a part of the RadiumFramework core.
 * Please be extremely cautious editing this file.
 * Modifying the contents of this file can be a poor life decision if you don't know what you're doing.
 *
 * @category RadiumFramework
 * @package  MetroCorp WP
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 */

/**
 * The Radium_Framework class launches the framework.  It's the organizational structure behind the entire theme.
 * This class should be loaded and initialized before anything else within the theme is called to properly use
 * the framework.
 *
 * Initializes the framework by doing some basic things like defining constants
 * and loading framework components
 *
 * The framework is contained in "framework/" while customizable theme files are contained in "includes/"
 * @since 2.1.0
 */

if ( !class_exists( 'Radium_Framework' ) ) : //check if a similar class exists

	class Radium_Framework {

		/**
		 * Current version of the framework.
		 *
		 * @since 2.1.3
		 *
		 * @var string
		 */
		public $version = '2.2.5';

		/** Magic *****************************************************************/

		/**
		 * The Radium Framework uses many variables and functions, most of which can be filtered to customize
		 * the way it works. To prevent unauthorized access, these variables
		 * are stored in a private array that is magically updated using PHP 5.2+
		 * methods. This is to prevent third party plugins from tampering with
		 * essential information indirectly, which would cause issues later.
		 *
		 * @see radium_framework::setup_globals()
		 * @var array
		 */

		private $data;

		/** Not Magic *************************************************************/

		/**
		 * @var obj Add-ons append to this (Akismet, BuddyPress, etc...)
		 */
		public $extend;

		/**
		 * @var array Overloads get_option()
		 */
		public $options = array();


		/** Carlton *************************************************************/

		/**
		 * @var radium_framework The one true radium framework
		 */
		private static $instance;

		/**
		 * Main radium Instance
		 *
		 * Please load it only one time
		 * For this, we thank you
		 *
		 * Insures that only one instance of the radium framework exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 2.1.0
		 * @static var array $instance
		 * @uses radium_framework::setup_globals() Setup the globals needed
		 * @uses radium_framework::includes() Include the required files
		 * @uses radium_framework::setup_actions() Setup the hooks and actions
		 * @see radium_framework()
		 * @return The one true radium framework
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->pre();
				self::$instance->setup_globals();
				self::$instance->includes();
				self::$instance->setup_actions();
			}
			return self::$instance;
		}

		/** Magic Methods *********************************************************/

		/**
		 * A dummy constructor to prevent the radium framework from being loaded more than once.
		 *
		 * @since 2.1.0
		 * @see radium_framework::instance()
		 * @see radium_framework();
		 */
		private function __construct() { /* Do nothing here */ }

		/**
		 * A dummy magic method to prevent the radium framework from being cloned
		 *
		 * @since 2.1.0
		 */
		public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'radium' ), '2.1.0' ); }

		/**
		 * A dummy magic method to prevent the radium framework from being unserialized
		 *
		 * @since 2.1.0
		 */
		public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'radium' ), '2.1.0' ); }

		/**
		 * Magic method for checking the existence of a certain custom field
		 *
		 * @since 2.1.0
		 */
		public function __isset( $key ) { return isset( $this->data[$key] ); }

		/**
		 * Magic method for getting radium variables
		 *
		 * @since 2.1.0
		 */
		public function __get( $key ) { return isset( $this->data[$key] ) ? $this->data[$key] : null; }

		/**
		 * Magic method for setting radium variables
		 *
		 * @since 2.1.0
		 */
		public function __set( $key, $value ) { $this->data[$key] = $value; }

		/**
		* Run the radium_pre Hook
		*
		* @since 2.1.0
		*/
		public function pre() { do_action( 'radium_pre_init', array( &$this ) ); }

		/*
		 * Setup the config array for which features the
		 * theme supports. This can easily be filtered
		 * giving you a chance to disable/enable the theme's various features.
		 *
		 * set each feature to true or false
		 *
		 * radium_feature_setup
		 *
		 * @since 2.0.0
		 */
		public function feature_setup() { }

		/**
		 * Check which features are currently supported.
		 * Please note that this function is loaded very early in the framework
		 * don't move it or you'll break something :)
		 *
		 * @since 2.0.0
		 *
		 * @param string $group primary, meta, comments, admin, meta
		 * @param string $feature feature key to check
		 * @return boolean
		 */

		public function theme_supports( $group, $feature ) {
			$setup = $this->feature_setup();
			if( isset( $setup[$group][$feature] ) && $setup[$group][$feature] )
				return true;
			else
				return false;
		}

		/**
		 * Check which plugins are currently activated.
		 *
		 * @since 2.1.0
		 *
		 * @param plugin
		 * @return boolean
		 */
		public function is_plugin_active( $plugin ) {
		    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
		}

		/** Private Methods *******************************************************/

		/**
		 * Set some smart defaults to class variables. Allow some of them to be
		 * filtered to allow for early overriding.
		 *
		 * @since 1.0
		 * @access private
		 * @uses get_template_directory() To generate theme path
		 * @uses get_template_directory_uri() To generate bbPress theme url
		 * @uses apply_filters() calls various filters
		 */

		private function setup_globals() { }


		/**
		 * Loads all the framework files and features.
		 *
		 * The radium_pre_framework action hook is called before any of the files are
		 * required().
		 *
		 * @since 1.0.0
		 */
		public function includes() { }

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 2.1.0
		 * @access private
		 * @todo Not use radium_is_deactivation()
		 * @uses register_activation_hook() To register the activation hook
		 * @uses register_deactivation_hook() To register the deactivation hook
		 * @uses add_action() To add various actions
		 */
		private function setup_actions() {

			// Add actions to plugin activation and deactivation hooks
			add_action( 'activate_'   . $this->theme_slug, 'radium_activation'   );
			add_action( 'deactivate_' . $this->theme_slug, 'radium_deactivation' );
			// If theme is being deactivated, do not add any actions

			/** Run the radium_pre_framework Hook */
			do_action( 'radium_pre_framework' );

 			do_action_ref_array( 'radium_after_setup_actions', array( &$this ) );

			/** Run the radium_init hook */
			do_action( 'radium_init', array( &$this )  );

			/** Run the radium_setup hook */
			do_action( 'radium_setup', array( &$this )  );

		}


	}

	/**
	 * Helper flag function for the framework.
	 *
	 * @since 2.1.0
	 *
	 * @return bool True if a radium theme is active
	 */
	function is_radium_theme() { return true; }

endif; // class_exists check