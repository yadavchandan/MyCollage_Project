<?php
/**
 * Register content_slider Element
 * @return array();
 */
function radium_builder_content_grid_events_element( $elements ) {
	
	// Setup array for categories select
	$categories_select = array();
	$categories_select['all'] = __( 'All Categories', 'radium' );
	$categories = get_terms( 'tribe_events_cat', array('hide_empty' => false) );
    	
	foreach( $categories as $category ) {
		$categories_select[$category->term_taxonomy_id] = $category->name;
 	}
	
	// Setup array for categories group of checkboxes
	$categories_multicheck = $categories_select;
	unset( $categories_multicheck['null'] );
 	
	//Carousel
	$element_options = array(
 		 
		array(
			'id' 		=> 'title',
			'name'		=> __( 'Title', 'radium' ),
			'desc'		=> __( '', 'radium' ),
			'std'		=> '',
			'type'		=> 'text'
		),
	
		array(
			'id' 		=> 'content_source',
			'name'		=> __( 'Events Sources', 'radium' ),
			'desc'		=> __( 'Select where this element should get content from.', 'radium' ),
			'type'		=> 'select',
			'options'		=> array(
				'category' 	=> __( 'Category', 'radium' ),
				'latest' 	=> __( 'Latest Events', 'radium' ),
			) 
		),
		
		array(
			'id' 		=> 'categories',
			'name'		=> __( 'Categories', 'radium' ),
			'desc'		=> __( 'Select the categories you\'d like to pull events from. Note that selecting "All Categories" will override any other selections.', 'radium' ),
			'std'		=> array( 'all' => 1 ),
			'type'		=> 'multicheck',
			'options'	=> $categories_multicheck
		),
		
		'subgroup_start_1' => array(
			'type'		=> 'subgroup_start',
			'class'		=> 'show-toggle'
		),
		array(
			'id' 		=> 'limit',
			'name'		=> __( 'How many items to show', 'radium' ),
			'desc'		=> __( 'Minimum of 17 if carousel is enabled', 'radium' ),
			'std'		=> 16,
			'type'		=> 'text'
		),
		
		array(
			'id' 		=> 'orderby',
			'name'		=> __( 'Order By', 'radium' ),
			'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'radium' ),
			'type'		=> 'select',
			'std'		=> 'date',
			'options'	=> array(
		        'date' 			=> __( 'Publish Date', 'radium' ),
		        'title' 		=> __( 'Post Title', 'radium' ),
		        'comment_count' => __( 'Number of Comments', 'radium' ),
		        'rand' 			=> __( 'Random', 'radium' )
			),
		),
		array(
			'id' 		=> 'order',
			'name'		=> __( 'Order', 'radium' ),
			'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'radium' ),
			'type'		=> 'select',
			'std'		=> 'DESC',
			'options'	=> array(
		        'DESC' 	=> __( 'Descending (highest to lowest)', 'radium' ),
		        'ASC' 	=> __( 'Ascending (lowest to highest)', 'radium' )
			),
		),
		array(
			'id' 		=> 'offset',
			'name'		=> __( 'Offset', 'radium' ),
			'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', 'radium' ),
			'type'		=> 'text',
			'std'		=> '0',
		),
		array(
			'id' 		=> 'exclude',
			'name'		=> __( 'Exclude', 'radium' ),
			'desc'		=> __( 'Enter the ids of posts you would like to exclude from this query. Comma Separated eg: 1, 2, 3, 4', 'radium' ),
			'type'		=> 'text',
			'std'		=> '',
		),
		'subgroup_end_1' => array(
			'type'		=> 'subgroup_end'
		),
		
		array(
			'id' 		=> 'carousel',
			'name'		=> __( 'Enable Carousel', 'radium' ),
			'desc'		=> __( '', 'radium' ),
			'std'		=> 1,
			'type'		=> 'checkbox'
		)

	);

	$elements['content_grid_events'] = array(
		'info' => array(
			'name' 	=> 'Content Grid Events',
			'id'	=> 'content_grid_events',
            'cache' => true,
			'desc' 	=> __( 'Grid Events content', 'radium' )
		),
		'options' => $element_options,
		'style' => apply_filters( 'radium_builder_element_style_config', array() ),

	);

	return $elements;

}
add_filter('radium_builder_elements', 'radium_builder_content_grid_events_element');
