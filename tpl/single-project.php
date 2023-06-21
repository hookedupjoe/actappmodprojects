<?php
/**
 * The template for displaying this custom type page
 *
 * @package actappmodprojects
 */

get_header();
?>

<div class="row">
	<div class="col-sm-12 col-md-9 pad3">  
		<div class="ui segment black">
		<?php
		while ( have_posts() ) :
			the_post();
			echo ('<div class="ui header blue medium">');
			echo get_the_title();
			echo ('</div>');
			get_template_part( 'template-parts/content', 'page' );
		endwhile; // End of the loop.
		?>
		
		</div>
	</div>  <?php // End Content ?>
	<div class="col-sm-12 col-md-3 pad3">
	<div class="ui segment black">
	<?php ActAppModProjects::processSidebarForCategory();	?>
	</div>
	<div class="ui segment black">
		<?php get_sidebar(); ?> 
	</div>
	</div> <?php // End Sidebar ?>
</div> <?php // End Row ?>
<?php get_footer(); ?>

