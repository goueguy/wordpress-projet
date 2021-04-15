<?php 
	$tax_query = $wp_query->get( 'tax_query' );
	if( is_array( $tax_query) && !( 'category' == $tax_query[0]['taxonomy'] ) ){
		return false;
	}
	if (is_category()) {
	    $category = get_category(get_query_var('cat'));
	    $cat_id = $category->cat_ID;
	    $category_name = $category->cat_name;
	}
?>

<span><?php esc_html_e('Category:', 'stack'); ?></span>
<div class="masonry-filter-holder masonry-filter-holder-post">
	<div class="masonry__filters blog-filters">
		<ul>
			<?php 
				if( $tax_query ){
					$idObj = get_category_by_slug($tax_query[0]['terms']);
					echo '<li class="active js-no-action">'. $idObj->name .'</li>';
					wp_list_categories('child_of='. $idObj->term_id .'&title_li=');
				} elseif (is_category()) {
					echo '<li class="active js-no-action">'. $category_name .'</li>';
					wp_list_categories('title_li=&exclude='. $cat_id); 
					echo '<li class="js-no-action"><a href="'. esc_url(get_permalink( get_option( 'page_for_posts' ) )) .'">'. esc_html__('All Categories', 'stack') .'</a></li>';
				} else {
					echo '<li class="active js-no-action">'. esc_html__('All Categories', 'stack') .'</li>';
					wp_list_categories('title_li='); 
				}
			?>
		</ul>
	</div>
</div>