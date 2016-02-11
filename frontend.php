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
        add_action('pre_get_posts', array($this, 'update_shop_queries'), 26);
        add_action('template_redirect', array($this, 'redirect_from_locked_product'));
    }

    /**
     * get plugin cookies
     * @author Lukas Juhas
     * @date   2016-02-10
     * @return [type]     [description]
     */
    public function get_cookies()
    {
        // loop thorugh the cookies and ones with our prefix put in to
        // new array which we then return`
        $wcl_cookies = array();
        foreach ($_COOKIE as $ec => $ec_val) {
            if (strpos($ec, 'wcl_') !== false) {
                $wcl_cookies[$ec] = $ec_val;
            }
        }

        return $wcl_cookies;
    }

    /**
     * main front end function wchich decides if the category is password
     * protected or not
     *
     * @author Lukas Juhas
     * @date   2016-02-05
     * @return [type]     [description]
     */
    public function password($query)
    {
        // make sure current category is "product_cat"
        if (!isset(get_queried_object()->taxonomy) || (!isset(get_queried_object()->taxonomy) && (get_queried_object()->taxonomy !== 'product_cat'))) {
            return;
        }

        // make sure temr id is set / that the page is actually a category
        if (isset(get_queried_object()->term_id)) :

            $cookie = 'wcl_' . md5( get_queried_object()->term_id );
            $hash = isset($_COOKIE[ wp_unslash( $cookie ) ]) ? $_COOKIE[ wp_unslash( $cookie ) ] : false;

            if(!$hash) {
                add_filter( 'template_include', array($this, 'replace_template') );
            } else {
                $is_password_protected = get_woocommerce_term_meta(get_queried_object()->term_id, 'wcl_cat_password_protected');
                if ($is_password_protected) {
                    // get current category id password
                    $cat_pass = get_woocommerce_term_meta(get_queried_object()->term_id, 'wcl_cat_password', true);
                    // decrypt cookie
                    require_once ABSPATH . WPINC . '/class-phpass.php';
                    $hasher = new PasswordHash( 8, true );

                    $check = $hasher->CheckPassword($cat_pass, $hash);

                    if ($check) {
                        return;
                    } else {
                        add_filter( 'template_include', array($this, 'replace_template') );
                    }
                }
            }
        endif;
    }

    /**
     * replace template with a password form
     *
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $template [description]
     * @return [type]               [description]
     */
    public function replace_template($template)
    {
        // see if tempalte exists in the theme
        $located = locate_template('woocommerce/password-form.php');
        if (!empty($located)) {
            // if yes, use theme template
            $template = get_template_directory() . '/woocommerce/password-form.php';
        } else {
            // otherwise use default plugin template
            $template = WCL_PLUGIN_DIR . '/templates/password-form.php';
        }

        return $template;
    }

    /**
     * exclude all categories that are locked for the visitor
     *
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $query [description]
     * @return [type]            [description]
     */
    public function update_shop_queries($query)
    {
        // make sure it's main query
        if (! $query->is_main_query()) {
            return;
        }

        // make sure its archive page
        if (! $query->is_post_type_archive()) {
            return;
        }

        // get locked categories / taxonomies
        $locked = wcl_get_locked_categories();

        // set query to exclude locked ones
        $query->set('tax_query', array(array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $locked,
            'operator' => 'NOT IN'
        )));
    }

    /**
     * if viewing product single page and the product belongs to locked
     * category, redirect visitor to enter password
     *
     * @author Lukas Juhas
     * @date   2016-02-09
     * @return [type]     [description]
     */
    public function redirect_from_locked_product()
    {
        global $post;

        // make sure we can access $post global to prevent errors
        if(!isset($post)) return false;

        // if it's not product page, we don't need to check further
        if(!is_product()) return false;

        // get terms of current "post" / "page"
        $terms = get_the_terms($post->ID, 'product_cat');

        // make sure this "post" has product categories
        if (!empty($terms)) {
            $product_cat_ids = array();
            foreach ($terms as $term) {
                $product_cat_ids[] = $term->term_id;
            }

            // see if product has locked category
            $locked = wcl_get_locked_categories();

            // intersect both arrays
            $result_intersect = array_intersect($locked, $product_cat_ids);

            // tidy up our array, make sure it starts form 0
            $result = array_values( $result_intersect );

            // get all present wcl_cookies as there might be multiple categories
            // that are password protected
            $wcl_cookies = $this->get_cookies();

            $matched = array();
            if (!empty($wcl_cookies)) {
                foreach ($wcl_cookies as $wclc_hash => $wclc_val) {
                    // make sure value is 1
                    if ($wclc_val) {
                        // get current category id password
                        $cat_pass = get_woocommerce_term_meta($terms[0]->term_id, 'wcl_cat_password', true);
                        // decrypt cookie
                        $crypt = new Crypt();
                        $crypt->setKey($cat_pass);
                        $crypt->setData($wclc_hash);
                        $matched[] = $crypt->decrypt();
                    }
                }

                // if there are cookies and they match, that means the category
                // where product is is current unlocked - let visitor in.
                if((isset($result) && isset($matched)) && $result[0] == $matched[0] ) {
                  return;
                }
            }

            // if yes, redirect to the category page (to enter password)
            // we add is_product() check so the redirect doesn't end up being
            // in a loop.
            if (!empty($result) && is_product()) {
                wp_safe_redirect(get_term_link($result[0]));
                exit();
            }
        }
    }
}

# init
$WC_Category_Locker_Frontend = new WC_Category_Locker_Frontend();
