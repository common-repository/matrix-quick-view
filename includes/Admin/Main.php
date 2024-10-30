<?php

namespace MatrixAddons\MatrixQuickView\Admin;
final class Main
{

    /**
     * The single instance of the class.
     *
     * @var Main
     * @since 1.0.0
     */
    protected static $_instance = null;


    /**
     * Main Main Instance.
     *
     * Ensures only one instance of Yatra_Admin is loaded or can be loaded.
     *
     * @return Main - Main instance.
     * @since 1.0.0
     * @static
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Main Constructor.
     */
    public function __construct()
    {
        $this->init();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     */
    private function init_hooks()
    {


        add_action('admin_notices', array($this, 'admin_notice'));
        add_action('admin_init', array($this, 'ignore_notice'));
        add_filter("plugin_action_links_" . plugin_basename(MATRIX_QUICK_VIEW_FILE), array($this, 'settings_link'));


    }

    function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=wc-settings&tab=quick-view">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }


    public function ignore_notice()
    {
        global $current_user;
        $user_id = $current_user->ID;

        if (isset($_GET['matrix_quick_view_ignore_notice']) && '0' == $_GET['matrix_quick_view_ignore_notice']) {

            add_user_meta($user_id, 'matrix_quick_view_ignore_notice', 'true', true);
        }
    }

    public function admin_notice()
    {

        $install_date = get_option('matrix_quick_view_install_date', '');
        $install_date = date_create($install_date);
        $date_now = date_create(date('Y-m-d G:i:s'));
        $date_diff = date_diff($install_date, $date_now);

        if ($date_diff->format("%d") < 7) {

            return false;
        }

        global $current_user;
        $user_id = $current_user->ID;

        if (!get_user_meta($user_id, 'matrix_quick_view_ignore_notice')) {

            echo '<div class="updated"><p>';

            printf(__('Awesome, you\'ve been using <a href="admin.php?page=wc-settings&tab=quick-view">WooCommerce Quick View</a> for more than 1 week. May we ask you to give it a 5-star rating on WordPress? | <a href="%2$s" target="_blank">Ok, you deserved it</a> | <a href="%1$s">I alredy did</a> | <a href="%1$s">No, not good enough</a>', 'matrix-quick-view'), '?matrix_quick_view_ignore_notice=0', 'https://wordpress.org/plugins/matrix-quick-view/');
            echo "</p></div>";
        }
    }


    /**
     * Include required core files used in admin.
     */
    public function init()
    {
        //    Assets::init();
    }


}
