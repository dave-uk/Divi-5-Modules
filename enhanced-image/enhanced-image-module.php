<?php
/*
Plugin Name: Enhanced Image Module
Plugin URI:
Description: Enhanced Image module for Divi 5 with optional Caption & Description support, full design controls, and safe HTML support.
Version:     1.0.0
Author:      Dave Rutlidge
Author URI:  https://rutlidge.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( 'Direct access forbidden.' );
}

// Setup constants used throughout this plugin.
define( 'ENHANCED_IMAGE_MODULE_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENHANCED_IMAGE_MODULE_URL', plugin_dir_url( __FILE__ ) );

// Load Divi 5 server runtime which registers the module via dependency tree.
require_once ENHANCED_IMAGE_MODULE_PATH . 'server/index.php';

/**
 * Enqueue CSS styles for the Enhanced Image module on the frontend.
 */
function enhanced_image_module_enqueue_styles() {
    wp_enqueue_style(
        'enhanced-image-module',
        ENHANCED_IMAGE_MODULE_URL . 'assets/css/enhanced-image.css',
        array(),
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'enhanced_image_module_enqueue_styles' );

/**
 * Register Visual Builder (VB) assets so the module renders in the editor.
 */
function enhanced_image_module_enqueue_visual_builder_assets() {
  if ( function_exists('et_core_is_fb_enabled') && function_exists('et_builder_d5_enabled') && et_core_is_fb_enabled() && et_builder_d5_enabled() ) {
        \ET\Builder\VisualBuilder\Assets\PackageBuildManager::register_package_build(
            [
                'name'    => 'enhanced-image-module-visual-builder',
                'version' => '1.0.0',
                'script'  => [
                    'src'                => ENHANCED_IMAGE_MODULE_URL . 'visual-builder/build/enhanced-image-module.js',
                    'deps'               => [
                        'react',
                        'jquery',
                        'divi-module-library',
                        'wp-hooks',
                        'divi-rest',
                    ],
                    'enqueue_top_window' => false,
                    'enqueue_app_window' => true,
                ],
            ]
        );
    }
}
add_action( 'divi_visual_builder_assets_before_enqueue_scripts', 'enhanced_image_module_enqueue_visual_builder_assets' );