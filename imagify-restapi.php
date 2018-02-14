<?php
/*
 ----------------------------------------------------------------------------------------------------------------------

  Plugin Name: Imagify Rest API
* Version: 0.0.1
  Plugin URI: 
  Description: A plugin to setup rest endpoints for imagify.
  Author: Brandon Hubbard
  Author URI: https://brandonhubbard.com
  Text Domain: imagify-restapi
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html

 ----------------------------------------------------------------------------------------------------------------------
*/	
	
/**
 * Imforza Rest Routes initialization class.
 */
class Imagify_Control_REST {

	/**
	 * Background process request for updating genesis layouts.
	 *
	 * @var GenesisLayoutProcess
	 */
	protected $process_request;

	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'settings', array(
				'methods'  => array('get', 'post', 'put', 'patch'),
				'callback' => array( $this, 'settings' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'api_key' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Setting for Imagify API Key.',
						'type'        => 'string',
					),
					'optimization_level' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Default optimization Level.',
						'type'        => 'string',
					),
					'auto_optimize_upload' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Automatically optimize every image you upload to WordPress.',
						'type'        => 'bool',
					),
					'backup_original_images' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Keep your original images in a separate folder before optimization process.',
						'type'        => 'bool',
					),
					'resize_larger_images' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Setting to resize larger images.',
						'type'        => 'bool',
					),
					'exif_data' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Keep all EXIF data from your images.',
						'type'        => 'bool',
					),
					'show_admin_bar' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Show awesome quick access menu on my admin bar.',
						'type'        => 'bool',
					),


				),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'tools/api/version', array(
				'methods'  => 'get',
				'callback' => array( $this, 'api_version' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'pricing/plans', array(
				'methods'  => 'get',
				'callback' => array( $this, 'imagify_plans_pricing' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'pricing/packs', array(
				'methods'  => 'get',
				'callback' => array( $this, 'imagify_packs_pricing' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'pricing/all', array(
				'methods'  => 'get',
				'callback' => array( $this, 'imagify_all_pricing' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'server-status', array(
				'methods'  => 'get',
				'callback' => array( $this, 'server_status' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'bulk-optimize', array(
				'methods'  => 'get',
				'callback' => array( $this, 'bulk_optimize' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'imagify/v1', 'optimize', array(
				'methods'  => array('get', 'post'),
				'callback' => array( $this, 'optimize' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'file_path' => array(
					'required'    => true,
					'default'     => '', 
					'description' => 'Absolute path to the image file.',
					'type'        => 'string',
					),
					
					'backup' => array(
					'required'    => false,
					'default'     => '', 
					'description' => 'Force a backup of the original file.',
					'type'        => 'bool',
					),
					
					'optimization_level' => array(
					'required'    => false,
					'default'     => '', 
					'description' => 'The optimization level (2=ultra, 1=aggressive, 0=normal).',
					'type'        => 'int',
					),
					
					'keep_exif' => array(
					'required'    => false,
					'default'     => '', 
					'description' => 'To keep exif data or not.',
					'type'        => 'bool',
					),

				),
			));
		});

	}
	
	/**
	 * api_version function.
	 * 
	 * @access public
	 * @return void
	 */
	public function api_version() {
		
		$api_version = get_imagify_api_version();
		
		return rest_ensure_response( $api_version );
		
	}
	
	/**
	 * api_status function.
	 * 
	 * @access public
	 * @return void
	 */
	public function api_status() {
		
		$api_status = get_imagify_status();
		
		return rest_ensure_response( $api_status );
		
	}
	
	/**
	 * settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settings() {
		
		return rest_ensure_response( 'true' );
		
	}
	
	/**
	 * imagify_plans_pricing function.
	 * 
	 * @access public
	 * @return void
	 */
	public function imagify_plans_pricing() {
		
		$imagify_plans_prices = get_imagify_plans_prices();
		
		return rest_ensure_response( $imagify_plans_prices );
		
	}
	
	/**
	 * imagify_packs_pricing function.
	 * 
	 * @access public
	 * @return void
	 */
	public function imagify_packs_pricing() {
		
		$imagify_packs_prices = get_imagify_packs_prices();
		
		return rest_ensure_response( $imagify_packs_prices );
		
	}
	
	/**
	 * imagify_all_pricing function.
	 * 
	 * @access public
	 * @return void
	 */
	public function imagify_all_pricing() {
		
		$imagify_all_prices = get_imagify_all_prices();
		
		return rest_ensure_response( $imagify_all_prices );
		
	}
	
	
	/**
	 * optimize function.
	 * 
	 * @access public
	 * @return void
	 */
	public function optimize() {
		
		$imagify = new Imagify();
		
 
		$results = $imagify->optimize( $optimization_level = null, $metadata = array('4') );
		
		return rest_ensure_response( $results );
		
	}
	
	/**
	 * server_status function.
	 * 
	 * @access public
	 * @return void
	 */
	public function server_status() {
		
		$results = is_imagify_servers_up();
		
		return rest_ensure_response( $results );
	}
	
	
	/**
	 * Check whether the function is allowed to be run.
	 *
	 * Must have either capabilities to enact action, or a valid nonce.
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function permission_check( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'forbidden', 'You are not allowed to do that.', array( 'status' => 403 ) );
		}

		return true;
	}
	
}	


new Imagify_Control_REST();
