<?php
/**
 * Plugin Name: Post type archive settings
 * Description: Settings for post type archives.
 *
 * Plugin URI: https://github.com/trendwerk/post-type-archive-settings
 * 
 * Author: Trendwerk
 * Author URI: https://github.com/trendwerk
 * 
 * Version: 1.0.0
 */

class TP_Post_Type_Archive_Settings {

	var $post_type = '_post_type_archives';
	var $post_types = array();

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'localization' ) );

		add_action( 'init', array( $this, 'init' ), 1000 );
		add_action( 'admin_init', array( $this, 'maybe_create' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'parent_file', array( $this, 'admin_menu_highlight' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Load localization
	 */
	function localization() {
		load_muplugin_textdomain( 'post-type-archive-settings', dirname( plugin_basename( __FILE__ ) ) . '/assets/lang/' );
	}

	/**
	 * Initialize
	 */
	function init() {
		$post_types = get_post_types();

		if( 0 === count( $post_types ) )
			return;

		foreach( $post_types as $post_type ) {
			if( post_type_supports( $post_type, 'archive-settings' ) )
				$this->post_types[] = $post_type;
		}

		$this->register();
	}

	/**
	 * Register post type
	 */
	function register() {
		register_post_type( $this->post_type, apply_filters( 'tp_pt_archive_settings_register', array(
			'labels'            => array(
				'edit_item'     => __( 'Archive settings', 'post-type-archive-settings' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'supports'          => array( 'title', 'editor' ),
		) ) );
	}

	/**
	 * Show in admin menu
	 */
	function admin_menu() {
		global $menu, $submenu, $parent_file, $submenu_file;

		if( 0 === count( $this->post_types ) )
			return;

		foreach( $this->post_types as $post_type ) {
			if( $archive = $this->get( $post_type ) )
				add_submenu_page( 'edit.php?post_type=' . $post_type, __( 'Archive settings', 'post-type-archive-settings' ), __( 'Archive settings', 'post-type-archive-settings' ), 'publish_posts', str_replace( admin_url(), '', get_edit_post_link( $archive->ID ) ) );
		}
	}

	/**
	 * Admin menu highlight
	 */
	function admin_menu_highlight( $slug ) {
		global $post, $submenu_file;

		if( ! isset( $post ) )
			return $slug;

		if( $post->post_type !== $this->post_type )
			return $slug;

		$submenu_file = str_replace( admin_url(), '', get_edit_post_link( $post->ID ) );

		return 'edit.php?post_type=' . get_post_meta( $post->ID, '_post_type', true );
	}

	/**
	 * Enqueue admin scripts
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'tp-post-type-archive-settings', plugins_url( 'assets/sass/admin.css', __FILE__ ) );
	}

	/**
	 * Maybe create post
	 */
	function maybe_create() {
		if( 0 === count( $this->post_types ) )
			return;

		foreach( $this->post_types as $post_type ) {
			if( ! $this->get( $post_type ) )
				$this->create( $post_type );
		}
	}

	/**
	 * Get archive for post type
	 *
	 * @param string $post_type
	 */
	function get( $post_type ) {
		$_posts = get_posts( array(
			'post_type'      => $this->post_type,
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'    => '_post_type',
					'value'  => $post_type,
				),
			),
		) );

		if( 0 === count( $_posts ) )
			return false;

		return $_posts[0];
	}

	/**
	 * Create archive for post type
	 *
	 * @param string $post_type
	 */
	function create( $post_type ) {
		$post_type = get_post_type_object( $post_type );

		$_post_id = wp_insert_post( array(
			'post_type'   => $this->post_type,
			'post_title'  => $post_type->labels->name,
			'post_status' => 'publish',
		) );

		update_post_meta( $_post_id, '_post_type', $post_type->name );

		return $_post_id;
	}

} new TP_Post_Type_Archive_Settings;
