<?php

namespace MatrixAddons\MatrixQuickView;

final class Main
{
    private static $_instance = null;

    protected function __construct()
    {
        $this->define_constant();
        register_activation_hook(__FILE__, [$this, 'activate']);
        $this->load_helpers();
        $this->dispatch_hook();
    }

    public function define_constant()
    {
        define('MATRIX_QUICK_VIEW_ABSPATH', dirname(MATRIX_QUICK_VIEW_FILE) . '/');
        define('MATRIX_QUICK_VIEW_PLUGIN_BASENAME', plugin_basename(MATRIX_QUICK_VIEW_FILE));
        define('MATRIX_QUICK_VIEW_ASSETS_DIR_PATH', MATRIX_QUICK_VIEW_PLUGIN_DIR . 'assets/');
        define('MATRIX_QUICK_VIEW_ASSETS_URI', MATRIX_QUICK_VIEW_PLUGIN_URI . 'assets/');
    }

    public function load_helpers()
    {
        include_once MATRIX_QUICK_VIEW_ABSPATH . 'includes/Helpers/main.php';

    }

    public function init_plugin()
    {
        $this->load_textdomain();
    }

    public function dispatch_hook()
    {
        add_action('init', [$this, 'init_plugin']);


        if (is_admin()) {
            new \MatrixAddons\MatrixQuickView\Admin\Main();
        }
        new Hooks();
        new Frontend();
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('matrix-quick-view', false, dirname(MATRIX_QUICK_VIEW_PLUGIN_BASENAME) . '/languages');
    }

    public function activate()
    {
        Install::install();
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public function plugin_path()
    {
        return untrailingslashit(plugin_dir_path(MATRIX_QUICK_VIEW_FILE));
    }

    public function template_path()
    {
        return apply_filters('matrix_quick_view_template_path', 'matrix_quick_view/');
    }


    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
