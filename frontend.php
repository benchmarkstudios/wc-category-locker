<?php

class WC_Category_Locker_Frontend
{
    /**
     * constructor, all front-end related stuff.
     *
     * @author Lukas Juhas
     * @date   2016-02-04
     */
    public function __construct()
    {
        add_action('pre_get_posts', array($this, 'password'), 25);
    }

    /**
     * main front end function wchich decides if the category is password
     * protected or not
     * @author Lukas Juhas
     * @date   2016-02-05
     * @return [type]     [description]
     */
    public function password()
    {
        if (!isset(get_queried_object()->taxonomy) || (!isset(get_queried_object()->taxonomy) && (get_queried_object()->taxonomy !== 'product_cat'))) {
            return;
        }

        if (isset(get_queried_object()->term_id)) :
           $is_password_protected = get_woocommerce_term_meta(get_queried_object()->term_id, 'wcl_cat_password_protected');
        if ($is_password_protected) {
            add_filter('template_include', array($this, 'replace_template'));
        }
        endif;
    }

    /**
     * replace template with a password form
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $template [description]
     * @return [type]               [description]
     */
    public function replace_template($template)
    {
        // TODO: make this dynamic ability to overwrite this template if you
        // copy it to your theme
        return WCL_PLUGIN_DIR . '/templates/password-form.php';
    }
}
# init
$WC_Category_Locker_Frontend = new WC_Category_Locker_Frontend();
