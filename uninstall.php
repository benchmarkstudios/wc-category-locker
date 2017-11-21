<?php
// check if uninstallation is triggered
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

/**
 * Untinstall
 *
 * @since 1.0
 * @return void
 */
function wcl_delete_plugin()
{
    /**
     * Unfortunatelly, this has to be a bit painful due to woocommerce delete
     * function.
     *
     * Loop through all categories and remove plugin options
     */
    if (function_exists('delete_woocommerce_term_meta')) {
        $terms = get_terms('product_cat');
        if (!empty($terms)) :
            foreach ($terms as $term) {
                delete_woocommerce_term_meta($term_id, 'wcl_cat_password_protected');
                delete_woocommerce_term_meta($term_id, 'wcl_cat_password');
            }
        endif;
    }
}
wcl_delete_plugin();
