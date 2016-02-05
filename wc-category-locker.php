<?php

class WC_Category_Locker
{
    /**
     * main plugin class that does all the magic
     * @author Lukas Juhas
     * @date   2016-02-05
     */
    public function __construct()
    {
        add_action('login_form_postpass', array($this, 'password'));
    }

    /**
     * triggered when you enter password
     * @author Lukas Juhas
     * @date   2016-02-05
     * @return [type]     [description]
     */
    public function password()
    {
        // extract request
        extract($_POST);

        // validate fileds
        if(empty($wcl_cat_id) || empty($wcl_cat_password))
            $this->error(); // TODO: add error message

        // get current category id password
        $cat_pass = get_woocommerce_term_meta($wcl_cat_id, 'wcl_cat_password', true);

        // if password is not valid
        if($cat_pass !== $wcl_cat_password) {
            // redirect back
            // TODO add error message
            $this->error();
        } else {
            $handle_cookies = $this->handle_cookies($wcl_cat_id);
            if($handle_cookies) {
                wp_safe_redirect( wp_get_referer() );
                exit();
            }
        }
    }

    /**
     * do the error
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $message [description]
     * @return [type]              [description]
     */
    private function error($message = false) {
        // redirect back
        // TODO: probably add some error message - added attribute already
        wp_safe_redirect( wp_get_referer() );
        exit();
    }

    /**
     * check the cookie
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $cat_id [description]
     * @return [type]             [description]
     */
    private function handle_cookies($cat_id) {
        // get all present wcl_cookies as there might be multiple categories
        // that are password protected
        foreach($_COOKIE as $ec => $ec_val) {
          if(strpos($ec, 'wcl_') !== false) {
             $wcl_cookies[$ec] = $ec_val;
          }
        }

        $matched = array();
        if(empty($wcl_cookies)) {
            $this->generate_cat_pass_cookie($cat_id);
        }

        return true;
    }

    /**
     * generate encrypted cookie
     * @author Lukas Juhas
     * @date   2016-02-05
     * @param  [type]     $cat_id [description]
     * @return [type]             [description]
     */
    private function generate_cat_pass_cookie($cat_id) {
        // encrypted cookie
        $cat_pass = get_woocommerce_term_meta($cat_id, 'wcl_cat_password', true);
        $crypt = new Crypt();
        $crypt->setKey($cat_pass);
        $crypt->setData($cat_id);
        $cookie = $crypt->encrypt();
        if(!isset($_COOKIE[$cookie])) {
            // create cookie for 30min
            setcookie( $cookie, 1, time() + 30*60, COOKIEPATH, COOKIE_DOMAIN, false);
            return $cookie;
        }
        return false;
    }
}
# init
$WC_Category_Locker = new WC_Category_Locker();
