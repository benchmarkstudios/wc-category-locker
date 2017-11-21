<?php

/**
 * Woocommerce Category Locker
 */
class WC_Category_Locker
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('login_form_postpass', [$this, 'password']);
    }

    /**
     * Triggered password entry
     *
     * @since 1.0
     * @return void
     */
    public function password()
    {
        // extract request
        extract($_POST);

        // validate fileds
        if (empty($wcl_cat_id) || empty($wcl_cat_password)) {
            $this->error();
        } // TODO: add error message

        // get current category id password
        $cat_pass = get_woocommerce_term_meta($wcl_cat_id, 'wcl_cat_password', true);

        // if password is not valid
        if ($cat_pass !== $wcl_cat_password) {
            // redirect back
            // TODO add error message
            $this->error();
        } else {
            $handle_cookies = $this->handle_cookies($wcl_cat_id);
            if ($handle_cookies) {
                wp_safe_redirect(wp_get_referer());
                exit();
            }
        }
    }

    /**
     * Handle error error
     *
     * @param boolean $message
     * @since 1.0
     * @return void
     */
    protected function error($message = false)
    {
        // redirect back
        // TODO: probably add some error message - added attribute already
        wp_safe_redirect(wp_get_referer());
        exit();
    }

    /**
     * Handle cookies
     *
     * @param Integer $cat_id
     * @since 1.0
     * @return void
     */
    protected function handle_cookies($cat_id)
    {
        $cookie = 'wcl_' . md5($cat_id);
        $hash = isset($_COOKIE[wp_unslash($cookie)]) ? $_COOKIE[wp_unslash($cookie)] : false;

        if (!$hash) {
            $this->generate_cat_pass_cookie($cat_id);
        }

        return true;
    }

    /**
     * Generate enctypted category password cookie
     *
     * @param Integer $cat_id
     * @since 1.0
     * @return void
     */
    protected function generate_cat_pass_cookie($cat_id)
    {
        // encrypted cookie
        $cat_pass = get_woocommerce_term_meta($cat_id, 'wcl_cat_password', true);

        require_once ABSPATH . WPINC . '/class-phpass.php';

        $hasher = new PasswordHash(8, true);

        $cookie = 'wcl_' . md5($cat_id);
        if (!isset($_COOKIE[$cookie])) {
            // create cookie for 30min by default
            $expire = apply_filters('wcl_password_expires', time() + 30 * 60, COOKIEPATH);

            // set cookie
            setcookie($cookie, $hasher->HashPassword(wp_unslash($cat_pass)), $expire, COOKIE_DOMAIN, false);

            return $cookie;
        }
        return false;
    }
}
// init
$WC_Category_Locker = new WC_Category_Locker();
