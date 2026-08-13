<?php
get_header();
?>
<section class="content-page">
    <div class="wrap">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('card'); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <h1>Contenu introuvable</h1>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
