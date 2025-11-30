<?php

if(!function_exists('CPWPWM_popup_post_type_init')) {
    function CPWPWM_popup_post_type_init()
    {
        $labels = array(
            'name' => _x('Popup Maker', 'Post Type General Name', 'CPWPWM'),
            'singular_name' => _x('Popup Maker', 'Post Type Singular Name', 'CPWPWM'),
            'menu_name' => __('Popup Maker', 'CPWPWM'),
            'parent_item_colon' => __('Parent Popup Maker', 'CPWPWM'),
            'all_items' => __('All Popup Maker', 'CPWPWM'),
            'view_item' => __('View Popup Maker', 'CPWPWM'),
            'add_new_item' => __('Add New Popup Maker', 'CPWPWM'),
            'add_new' => __('Add New', 'CPWPWM'),
            'edit_item' => __('Edit Popup Maker', 'CPWPWM'),
            'update_item' => __('Update Popup Maker', 'CPWPWM'),
            'search_items' => __('Search Popup Maker', 'CPWPWM'),
            'not_found' => __('Not Found', 'CPWPWM'),
            'not_found_in_trash' => __('Not found in Trash', 'CPWPWM'),
        );
        $args = array(
            'label' => __('Popup Maker', 'CPWPWM'),
            'description' => __('Popup Maker', 'CPWPWM'),
            'labels' => $labels,
            'supports' => array('title', 'editor'),

            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_in_rest' => true,
        );

        register_post_type('CPWPWM_POST', $args);
    }

    add_action('init', 'CPWPWM_popup_post_type_init');
}
