<?php

namespace MatrixAddons\MatrixQuickView;

defined('ABSPATH') || exit;

/**
 * Main Install Class.
 *
 * @class Matrix Quick View
 */
final class Install
{

    private static $update_callbacks = array();

    public static function install()
    {
        if (!is_blog_installed()) {
            return;
        }

        $matrix_quick_view_version = get_option('matrix_quick_view_plugin_version');

        if (empty($matrix_quick_view_version)) {
            self::create_options();

        }
        //save install date
        if (false == get_option('matrix_quick_view_install_date')) {
            update_option('matrix_quick_view_install_date', current_time('timestamp'));
        }

        self::versionwise_update();
        self::update_matrix_quick_view_version();
    }


    private static function create_options()
    {


    }


    private static function versionwise_update()
    {
        $matrix_quick_view_version = get_option('matrix_quick_view_plugin_version', null);

        if ($matrix_quick_view_version == '' || $matrix_quick_view_version == null || empty($matrix_quick_view_version)) {
            return;
        }
        if (version_compare($matrix_quick_view_version, MATRIX_QUICK_VIEW_VERSION, '<')) { // 2.0.15 < 2.0.16

            foreach (self::$update_callbacks as $version => $callbacks) {

                if (version_compare($matrix_quick_view_version, $version, '<')) { // 2.0.15 < 2.0.16

                    self::exe_update_callback($callbacks);
                }
            }
        }
    }

    private static function exe_update_callback($callbacks)
    {
        include_once MATRIX_QUICK_VIEW_ABSPATH . 'includes/Helpers/update.php';

        foreach ($callbacks as $callback) {

            call_user_func($callback);

        }
    }

    /**
     * Update Matrix Quick View version to current.
     */
    private static function update_matrix_quick_view_version()
    {
        delete_option('matrix_quick_view_plugin_version');
        delete_option('matrix_quick_view_plugin_db_version');
        add_option('matrix_quick_view_plugin_version', MATRIX_QUICK_VIEW_VERSION);
        add_option('matrix_quick_view_plugin_db_version', MATRIX_QUICK_VIEW_VERSION);
    }

    public static function init()
    {

        add_action('init', array(__CLASS__, 'check_version'), 5);


    }

    public static function check_version()
    {
        if (!defined('IFRAME_REQUEST') && version_compare(get_option('matrix_quick_view_plugin_version'), MATRIX_QUICK_VIEW_VERSION, '<')) {
            self::install();
            do_action('matrix_quick_view_updated');
        }
    }


}

Install::init();
