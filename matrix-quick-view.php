<?php
/**
 * Plugin Name: Matrix Quick View
 * Plugin URI: https://wordpress.org/plugins/matrix-quick-view
 * Description: Matrix Quick View - WooCommerce Quick View plugin allows you to add a quick view button in the product archive ( loop )  so that your customer can quickly view the product without refreshing or navigating the product description page.
 * Author: MatrixAddons
 * Author URI: https://profiles.wordpress.org/matrixaddons
 * Version: 1.0.6
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: matrix-quick-view
 * Domain Path: /languages/
 * WC requires at least: 6.0
 * WC tested up to: 6.5
 *
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Define MATRIX_QUICK_VIEW_PLUGIN_FILE.
if (!defined('MATRIX_QUICK_VIEW_FILE')) {
    define('MATRIX_QUICK_VIEW_FILE', __FILE__);
}

// Define MATRIX_QUICK_VIEW_VERSION.
if (!defined('MATRIX_QUICK_VIEW_VERSION')) {
    define('MATRIX_QUICK_VIEW_VERSION', '1.0.6');
}

// Define MATRIX_QUICK_VIEW_PLUGIN_URI.
if (!defined('MATRIX_QUICK_VIEW_PLUGIN_URI')) {
    define('MATRIX_QUICK_VIEW_PLUGIN_URI', plugins_url('/', MATRIX_QUICK_VIEW_FILE));
}

// Define MATRIX_QUICK_VIEW_PLUGIN_DIR.
if (!defined('MATRIX_QUICK_VIEW_PLUGIN_DIR')) {
    define('MATRIX_QUICK_VIEW_PLUGIN_DIR', plugin_dir_path(MATRIX_QUICK_VIEW_FILE));
}
/**
 * Initializes the main plugin
 *
 * @return \MatrixAddons\MatrixQuickView\Main
 */
if (!function_exists('matrix_quick_view')) {
    function matrix_quick_view()
    {
        return \MatrixAddons\MatrixQuickView\Main::getInstance();
    }
}

matrix_quick_view();
