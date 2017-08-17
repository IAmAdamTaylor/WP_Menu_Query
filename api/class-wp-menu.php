<?php

/**
 * The object that represents a single WordPress Menu.
 *
 * @link       https://github.com/AdamEDGECreative/WP_Menu_Query
 * @since      1.0.0
 *
 * @package    WP_Menu_Query
 * @subpackage WP_Menu_Query/api
 */

/**
 * The object that represents a single WordPress Menu.
 *
 * Gets a reference to a single menu created in
 * Appearance > Menus
 *
 * @package    WP_Menu_Query
 * @subpackage WP_Menu_Query/api
 * @author     Adam Taylor <adam@edge-creative.com>
 */
class WP_Menu {

	/**
	 * The location of the menu.
	 * @var string
	 */
	private $_menu_location;

	/**
	 * The object representing the menu given by WordPress.
	 * @var string
	 */
	private $_menu_object;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $menu_location The location the menu exists at, 
	 *                                      when setup with register_nav_menus.
	 */
	public function __construct( $menu_location ) {
		$this->_menu_location = $menu_location;
		$this->_menu_object = null;

		if ( $this->_location_is_registered() ) {
			
			if ( $this->_location_has_menu() ) {
				
				$this->_init_menu_object();
				
			} else {

				trigger_error_with_context( "The location '{$this->_menu_location}' does not have an attached menu", E_USER_WARNING );
				
			}

		} else {

			trigger_error_with_context( "The location '{$this->_menu_location}' is not registered", E_USER_WARNING );

		}
	}

	private function _location_is_registered() {
		$registered_locations = get_registered_nav_menus();
		
		return isset( $registered_locations[ $this->_menu_location ] );
	}

	private function _location_has_menu() {
		$menu_locations = get_nav_menu_locations();
		
		return isset( $menu_locations[ $this->_menu_location ] );
	}

	/**
	 * Get the menu object for the location chosen.
	 */
	private function _init_menu_object() {
		$menu_locations = get_nav_menu_locations();

		if ( isset( $menu_locations[ $this->_menu_location ] ) ) {
			
			$this->_menu_object = wp_get_nav_menu_object( $menu_locations[ $this->_menu_location ] );

		}
	}

	public function get_items() {
		return new WP_Menu_Query( array( 'location' => $this->_menu_location ) );
	}

	public function __get( $name ) {
		if ( null !== $this->_menu_object ) {
			return $this->_menu_object->{$name};
		} else {
			return '';
		}
	}

}
