<?php
 /**
  * The template for displaying passwrod form on category pages
  *
  * This template can be overridden by copying it to yourtheme/woocommerce/password-form.php.
  *
  * @since 1.0
  */

 if (!defined('ABSPATH')) {
     exit; // Exit if accessed directly
 }

get_header('shop'); ?>

    <?php
        /**
        * woocommerce_before_main_content hook.
        *
        * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
        * @hooked woocommerce_breadcrumb - 20
        */
        do_action('woocommerce_before_main_content');
    ?>

    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>

        <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

    <?php endif; ?>

    <?php
        /**
        * woocommerce_archive_description hook.
        *
        * @hooked woocommerce_taxonomy_archive_description - 10
        * @hooked woocommerce_product_archive_description - 10
        */
        do_action('woocommerce_archive_description');
    ?>

    <?php
        /**
         * Password form with hooks.
         */
        do_action('wcl_before_passform');
        echo wcl_get_the_password_form();
        do_action('wcl_after_passform');
    ?>

    <?php
        /**
        * woocommerce_after_main_content hook.
        *
        * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
        */
        do_action('woocommerce_after_main_content');
    ?>

    <?php
        /**
        * woocommerce_sidebar hook.
        *
        * @hooked woocommerce_get_sidebar - 10
        */
        do_action('woocommerce_sidebar');
    ?>

<?php get_footer('shop'); ?>
