<?php get_header(); ?>

<main class="main" role="main">
   <div class="container clear">

      <article <?php post_class(); ?>>

        <?php if(is_cart()) : ?>
            <header class="page-header" role="heading">
                <h1 class="post__title"><?php the_title(); ?></h1>
            </header>
        <?php else : ?>
            <h1 class="shop-page-title"><?php the_title(); ?></h1>
        <?php endif; ?>

        <div class="row">
            <div class="col col--xs-12 col--sm-12 col--md-12 col--lg-12">
                <?php echo wcl_get_the_password_form(); ?>
            </div>
        </div>

      </article>


   </div>
</main>
<?php get_footer(); ?>
