<?php
/**
 * The template for displaying passwrod form on category pages
 *
 * DO NOT OVERWRITE - make a copy to your theme/woocomerce folder
 * and you can modify markup there.
 *
 * @since 1.0
 */

 if (! defined('ABSPATH')) {
     exit; // Exit if accessed directly
 }

get_header(); ?>

<div id="primary" class="content-area">
  	<main id="main" class="site-main" role="main">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <?php do_action('wcl_before_passform'); ?>
  		  <?php echo wcl_get_the_password_form(); ?>
        <?php do_action('wcl_after_passform'); ?>
  	</main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
