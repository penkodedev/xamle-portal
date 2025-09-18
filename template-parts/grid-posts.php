<!-- Abre el grid de posts -->
<section class="post-grid">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="post-col col-5">
                <!-- /change between col or full on main.scss -->
                <div class="grid-item">
                    <?php
                    // Obtenemos el contador de "me gusta" y el nonce de seguridad
                    $like_count = get_post_meta(get_the_ID(), '_recurso_like_count', true);
                    $like_count = !empty($like_count) && intval($like_count) > 0 ? intval($like_count) : '';
                    ?>
                    <div class="like-heart" data-post-id="<?php echo get_the_ID(); ?>">
                        <span class="like-count"><?php echo $like_count; ?></span>
                    </div>
                    <div class="grid-item-content">
                        <h5>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>

                        <p class="grid-item-excerpt">
                            <?php echo excerpt('500'); ?>
                        </p>

                        <?php
                        // Muestra la autoría si existe
                        $autoria = get_post_meta(get_the_ID(), '_recurso_autoria', true);
                        if (!empty($autoria)) : ?>
                            <p class="recurso-autoria"><strong>Autoría:</strong> <?php echo esc_html($autoria); ?></p>
                        <?php endif; ?>


                        <?php get_template_part( 'template-parts/post-meta-archive' ); ?>
                        <?php get_template_part('template-parts/player-speech'); ?>
                        
                        <div class="post-links">
                            <a href="<?php the_permalink(); ?>" class="button"><?php _e('Ver y descargar', 'xamle'); ?></a>
                        </div>

                    </div>
                </div>
            </div>
    <?php endwhile;
    endif; ?>
</section> <!-- Cierre del grid de posts -->