<?php

namespace MatrixAddons\MatrixQuickView\Admin\Settings;

class QuickView extends \WC_Settings_Page
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 'quick-view';
        $this->label = __('Quick View', 'matrix-quick-view');

        parent::__construct();
    }


    protected function get_settings_for_default_section()
    {

        $settings =
            array(

                array(
                    'title' => __('General Options', 'matrix-quick-view'),
                    'type' => 'title',
                    'id' => 'matrix_quick_view_general_options',
                ),

                array(
                    'title' => __('Enable Quick View', 'matrix-quick-view'),
                    'desc' => __('Enable/disable quick view', 'matrix-quick-view'),
                    'id' => 'matrix_quick_view_enable',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'desc_tip' => __('You can enable/disable quick view', 'matrix-quick-view'),
                ),
                array(
                    'title' => __('Enable Quick View on mobile', 'matrix-quick-view'),
                    'desc' => __('Enable/disable quick view on mobile', 'matrix-quick-view'),
                    'id' => 'matrix_quick_view_mobile_enable',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'desc_tip' => __('You can enable/disable quick view on mobile', 'matrix-quick-view'),
                ),
                array(
                    'title' => __('Quick View Button Label', 'matrix-quick-view'),
                    'id' => 'matrix_quick_view_button_label',
                    'default' => 'Quick View',
                    'type' => 'text',
                    'desc_tip' => __('Quick View Button Label', 'matrix-quick-view'),
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'matrix_quick_view_general_options',
                ),

                array(
                    'title' => __('Style Options', 'matrix-quick-view'),
                    'type' => 'title',
                    'id' => 'matrix_quick_view_style_options',
                ),
                array(
                    'title' => __('Modal Background Color', 'matrix-quick-view'),
                    /* translators: %s: default color */
                    'desc' => sprintf(__('Modal Window Background Color. Default %s.', 'matrix-quick-view'), '<code>#ffffff</code>'),
                    'id' => 'matrix_quick_view_modal_background_color',
                    'type' => 'color',
                    'css' => 'width:6em;',
                    'default' => '#ffffff',
                    'autoload' => false,
                    'desc_tip' => true,
                ),
                array(
                    'title' => __('Close Button Color', 'matrix-quick-view'),
                    /* translators: %s: default color */
                    'desc' => sprintf(__('Close Button Color. Default %s.', 'matrix-quick-view'), '<code>#95979c</code>'),
                    'id' => 'matrix_quick_view_close_button_color',
                    'type' => 'color',
                    'css' => 'width:6em;',
                    'default' => '#95979c',
                    'autoload' => false,
                    'desc_tip' => true,
                ),
                array(
                    'title' => __('Close Hover Background', 'matrix-quick-view'),
                    /* translators: %s: default color */
                    'desc' => sprintf(__('Close Button Hover Background Color. Default %s.', 'matrix-quick-view'), '<code>#4C6298</code>'),
                    'id' => 'matrix_quick_view_close_button_hover_background_color',
                    'type' => 'color',
                    'css' => 'width:6em;',
                    'default' => '#4C6298',
                    'autoload' => false,
                    'desc_tip' => true,
                ),
                array(
                    'title' => __('Navigation Box Background', 'matrix-quick-view'),
                    /* translators: %s: default color */
                    'desc' => sprintf(__('Navigation Box Background Color. Default %s.', 'matrix-quick-view'), '<code>#ffffff</code>'),
                    'id' => 'matrix_quick_view_navigation_box_background_color',
                    'type' => 'color',
                    'css' => 'width:6em;',
                    'default' => '#ffffff',
                    'autoload' => false,
                    'desc_tip' => true,
                ),
                array(
                    'title' => __('Navigation Box Text Color', 'matrix-quick-view'),
                    /* translators: %s: default color */
                    'desc' => sprintf(__('Navigation Box Background Text Color. Default %s.', 'matrix-quick-view'), '<code>#000000</code>'),
                    'id' => 'matrix_quick_view_navigation_box_text_color',
                    'type' => 'color',
                    'css' => 'width:6em;',
                    'default' => '#000000',
                    'autoload' => false,
                    'desc_tip' => true,
                ),

                array(
                    'type' => 'sectionend',
                    'id' => 'matrix_quick_view_style_options',
                ),
            );

        return apply_filters('matrix_quick_view_general_settings', $settings);
    }


}