<?php
/*
Plugin Name: Projects Module
Plugin URI: http://actionapp.hookedup.com
Version: 1.0.2
Author: Hookedup, inc.
Author URI: http://tech.hookedup.com
*/

/* package: actappmodprojects */

class ActAppProjectsModule {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	//--- Update this if the forms update and need cache busting
	public static function form_version(){
		return "0515210834";
	}


	public static function echo_relative() {
		echo (ACTAPP_PROJECTS_PREFIX_URL);
		return ACTAPP_PROJECTS_PREFIX_URL;
	}

	public static function admin_menu() {
		// if( !current_user_can( 'administrator' ) ){
		// 	remove_menu_page( 'edit.php?post_type=project' );
		// }
	}

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new ActAppProjectsModule();
		}
		return self::$instance;
	}

	public static function plugin_initialize() {
		
	}

	/**
	 * Runs when plugin is activated
	 */
	public static function activation_hook() {
		self::init();
		flush_rewrite_rules(); 
		self::plugin_initialize();
	}
	/**
	 * Init it
	 */
	public static function init() {
		self::custom_post_project();
        
        /**
         * Class with commonly used static functions
         * 
         * Usage: ActAppModProjects::doSomething()
         */
        require ACTAPP_PROJECTS_PLUGIN_DIR . '/inc/ActAppModProjects.php';
       
        add_filter( 'template_include', 'project_list_template', 99 );
        function project_list_template( $template ) {

            $tmpType = 'project';
            $tmpPluginDir = ACTAPP_PROJECTS_PLUGIN_DIR;

            $tmpFN = '';
            if ( is_archive( $tmpType )  ) {
                $tmpFN = '/tpl/archive-'.$tmpType.'.php';
                if ( is_singular( $tmpType )  ) {
                    $tmpFN = '/tpl/list-'.$tmpType.'.php';
                }
            } else if ( is_singular( $tmpType )  ) {
                $tmpFN = '/tpl/single-'.$tmpType.'.php';
            }
            if( $tmpFN != ''){
                if ( file_exists( $tmpPluginDir . $tmpFN ) ) {
                    return $tmpPluginDir . $tmpFN;
                }
            }

            return $template;
        }


        // add_filter( 'single-project_template', function( $template ) {
        //     if ( ! $template ) {
        //         $template = dirname( __FILE__ ) . '/tpl/default-project-single.php';
        //     }
            
        //     return $template;
        // });

	}


	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		//PLACEHOLDER

	}

	
	

	private function custom_post_project() {
	/**
	 * Post Type: Projects.
	 */

	$labels = [
		"name" => "Projects",
		"singular_name" => "Project",
	];

	$args = [
		"label" => "Projects",
		"labels" => $labels,
		"description" => "A project we did or are doing.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => "projects",
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "project", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "comments" ],
		"taxonomies" => [ "category" ],
		"show_in_graphql" => false,
	];

	register_post_type( "project", $args );

	}

    


}

	
if ( !defined( 'ACTAPP_PROJECTS_PLUGIN_DIR' ) ) {
	define( 'ACTAPP_PROJECTS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( !defined( 'ACTAPP_PROJECTS_PLUGIN_URL' ) ) {
	define( 'ACTAPP_PROJECTS_PLUGIN_URL', wp_make_link_relative(plugins_url( 'actappmodprojects' ) ));
}

if ( !defined( 'ACTAPP_PROJECTS_PREFIX_URL' ) ) {
	define( 'ACTAPP_PROJECTS_PREFIX_URL',  wp_make_link_relative(get_site_url()) . '/donate' );
}

if ( !defined( 'ACTAPP_PROJECTS_ASSETS_DIR' ) ) {
	define( 'ACTAPP_PROJECTS_ASSETS_DIR', ACTAPP_PROJECTS_PLUGIN_DIR . '/assets');
}


register_activation_hook( __FILE__, array( 'ActAppProjectsModule', 'activation_hook' ) );

add_action( 'plugins_loaded', array( 'ActAppProjectsModule', 'get_instance' ) );
add_action( 'init', array( 'ActAppProjectsModule', 'init' ) );
add_action( 'admin_menu', array( 'ActAppProjectsModule', 'admin_menu' ));

