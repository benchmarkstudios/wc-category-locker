<?php
/**
 * Get the passowrd form
 *
 * @param boolean $category_id
 * @since 1.0
 * @return void
 */
function wcl_get_the_password_form($category_id = false)
{
    if (!$category_id) {
        global $wp_query;
        $category_id = $wp_query->get_queried_object_id();
    }
    $label = 'wcl_pwbox-' . (!isset($category_id) ? rand() : $category_id);

    // default values with possible filters
    $form_classes = apply_filters('wcl_passform_classes', '');
    $submit_button_label = apply_filters('wcl_passform_submit_label', 'Submit');
    $submit_button_classes = apply_filters('wcl_passform_submit_classes', 'button btn');
    $password_input_classes = apply_filters('wcl_passform_input_classes', '');
    $password_input_placeholder = apply_filters('wcl_passform_input_placeholder', '');
    $description = apply_filters('wcl_passform_description', '<p>' . __('This content is password protected. To view it please enter your password below:') . '</p>');

    // output form
    $output = '
        <form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" class="wcl-post-password-form ' . $form_classes . '" method="post">
           <input type="hidden" name="wcl_cat_id" value="' . $category_id . '" />
	         ' . $description . '
	         <p>
              <label for="' . $label . '">' . __('Password:') . '
                  <div class="form__group form-group">
                      <input class="' . $password_input_classes . '" placeholder="' . $password_input_placeholder . '" name="wcl_cat_password" id="' . $label . '" type="password" size="20" />
                  </div>
              </label>
           </p>
           <p>
               <div class="form__group form-group">
                  <input type="submit" class="' . $submit_button_classes . '" name="Submit" value="' . esc_attr__($submit_button_label) . '" />
               </div>
           </p>
        </form>
	';

    /*
     * Filter the HTML output for the protected post password form.
     *
     * If modifying the password field, please note that the core database schema
     * limits the password field to 20 characters regardless of the value of the
     * size attribute in the form input.
     *
     * @since 1.0
     *
     * @param string $output The password form HTML output.
     */
    return apply_filters('wcl_password_form', $output);
}

/**
 * Get locked categories array
 *
 * @since 1.0
 * @return array
 */
function wcl_get_locked_categories()
{
    $locked = [];
    $shop_terms = get_terms('product_cat');
    foreach ($shop_terms as $term) {
        $is_password_protected = get_woocommerce_term_meta($term->term_id, 'wcl_cat_password_protected');
        if ($is_password_protected) {
            $locked[] = $term->term_id;
        }
    }

    return $locked;
}
