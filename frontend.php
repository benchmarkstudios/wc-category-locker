<?php
/**
 * Front-end
 */
class WC_Category_Locker_Frontend
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('pre_get_posts', [$this, 'password'], 10);
        add_action('pre_get_posts', [$this, 'update_shop_queries'], 10);
        add_action('template_redirect', [$this, 'redirect_from_locked_product']);
    }

    /**
     * Get cookies
     *
     * @since 1.0
     * @return void
     */
    public function get_cookies()
    {
        // loop thorugh the cookies and ones with our prefix put in to
        // new array which we then return`
        $wcl_cookies = [];
        foreach ($_COOKIE as $ec => $ec_val) {
            if (strpos($ec, 'wcl_') !== false) {
                $wcl_cookies[$ec] = $ec_val;
            }
        }

        return $wcl_cookies;
    }

    /**
     * Front end password handling
     *
     * @param object $query
     * @since 1.0
     * @return void
     */
    public function password($query)
    {
        // don't run if it's admin
        if (is_admin()) {
            return;
        }

        // make sure current category is "product_cat"
        if (!isset(get_queried_object()->taxonomy) || (!isset(get_queried_object()->taxonomy) && (get_queried_object()->taxonomy !== 'product_cat'))) {
            return;
        }

        // make sure temr id is set / that the page is actually a category
        if (isset(get_queried_object()->term_id)) {
            $is_password_protected = get_woocommerce_term_meta(get_queried_object()->term_id, 'wcl_cat_password_protected');
            if ($is_password_protected) {
                $cookie = 'wcl_' . md5(get_queried_object()->term_id);
                $hash = isset($_COOKIE[wp_unslash($cookie)]) ? $_COOKIE[wp_unslash($cookie)] : false;

                if (!$hash) {
                    add_filter('template_include', [$this, 'replace_template']);
                } else {
                    // get current category id password
                    $cat_pass = get_woocommerce_term_meta(get_queried_object()->term_id, 'wcl_cat_password', true);
                    // decrypt cookie
                    require_once ABSPATH . WPINC . '/class-phpass.php';

                    $hasher = new PasswordHash(8, true);

                    $check = $hasher->CheckPassword($cat_pass, $hash);

                    if ($check) {
                        return;
                    } else {
                        add_filter('template_include', [$this, 'replace_template']);
                    }
                }
            }
        }
    }

    /**
     * Replace tempalte with password form
     *
     * @param string $template
     * @since 1.0
     * @return void
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
     * Update shop queries
     *
     * @param object $query
     * @return void
     */
    public function update_shop_queries($query)
    {
        // don't run if it's admin
        if (is_admin()) {
            return;
        }

        // make sure it's main query
        if (!$query->is_main_query()) {
            return;
        }

        // make sure its archive page
        if (!$query->is_post_type_archive()) {
            return;
        }

        // get locked categories / taxonomies
        $locked = wcl_get_locked_categories();

        // set query to exclude locked ones
        $query->set('tax_query', [[
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $locked,
            'operator' => 'NOT IN'
        ]]);

        return $query;
    }

    /**
     * Redirect from locked product
     *
     * @since 1.0
     * @return void
     */
    public function redirect_from_locked_product()
    {
        global $post;

        // make sure we can access $post global to prevent errors
        if (!isset($post)) {
            return false;
        }

        // if it's not product page, we don't need to check further
        if (!is_product()) {
            return false;
        }

        // get terms of current "post" / "page"
        $terms = get_the_terms($post->ID, 'product_cat');

        // make sure this "post" has product categories
        if (!empty($terms)) {
            $product_cat_ids = [];
            foreach ($terms as $term) {
                $product_cat_ids[] = $term->term_id;
            }

            // see if product has locked category
            $locked = wcl_get_locked_categories();

            // intersect both arrays
            $result_intersect = array_intersect($locked, $product_cat_ids);

            // if it doesn't belong to locked category return
            if (empty($result_intersect)) {
                return;
            }

            // tidy up our array, make sure it starts form 0
            $result = array_values($result_intersect);

            // check for cookie hash
            $cookie = 'wcl_' . md5($result[0]);
            $hash = isset($_COOKIE[wp_unslash($cookie)]) ? $_COOKIE[wp_unslash($cookie)] : false;

            if (!$hash) {
                nocache_headers();
                wp_safe_redirect(get_term_link($result[0]));
                exit();
            } else {
                // get current category id password
                $cat_pass = get_woocommerce_term_meta($result[0], 'wcl_cat_password', true);
                // decrypt cookie
                require_once ABSPATH . WPINC . '/class-phpass.php';

                $hasher = new PasswordHash(8, true);

                $check = $hasher->CheckPassword($cat_pass, $hash);

                if ($check) {
                    return;
                } else {
                    nocache_headers();
                    wp_safe_redirect(get_term_link($result[0]));
                    exit();
                }
            }
        }
    }
}

// init
$WC_Category_Locker_Frontend = new WC_Category_Locker_Frontend();
