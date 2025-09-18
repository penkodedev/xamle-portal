<aside class="sidebar animate fadeInLeft" role="complementary">
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 01')) ?>

	<div class="sidebox">
		<h3>Ultimos Recursos publicados</h3>

		<ul>
			<?php
			global $post;
			$args = array(
				'post_type' => 'recursos',
				'posts_per_page' => 8
			);
			$myposts = get_posts($args);
			foreach ($myposts as $post) : setup_postdata($post); ?>
				<li><a href="<?php the_permalink(); ?>">
						<?php the_title(); ?></a></li>
			<?php endforeach;
			wp_reset_postdata(); ?>
		</ul>

	</div>
</aside>