<?php

namespace MatrixAddons\MatrixQuickView;

use MatrixAddons\MatrixQuickView\Admin\Settings\QuickView;

class Hooks
{
    public function __construct()
    {
        add_filter('woocommerce_get_settings_pages', array($this, 'settings'));
    }

    public function settings($settings)
    {
        $settings[] = new QuickView();

        return $settings;
    }
}