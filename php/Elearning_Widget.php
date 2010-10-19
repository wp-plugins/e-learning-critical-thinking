<?php

class Elearning_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Elearning_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'intellum_elearning', 'description' => __('An ELearning widget to enable deeper learning.', 'Elearning') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'intellum-elearning-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'intellum-elearning-widget', __('E-Learning Corner', 'Elearning'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
                global $post;

               
         $title = apply_filters('widget_title', $instance['title'] );
        
                
                $IntellumElearning = new IntellumElearning();
                $meta_box = $IntellumElearning->meta_box;
                $meta_count = get_post_meta($post->ID, 'dbt_text', true);

     if ( (is_single() or is_page() ) && (is_array($meta_count))) {
		/* Before widget (defined by themes). */
		echo $before_widget;
                echo '<div id="elearning_wrapper">';
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo "$before_title $title $after_title";


                    for ($i=0;$i<count($meta_count);$i++) {

                           $meta = get_post_meta($post->ID, $meta_box['fields'][2]['id'], true);



                           echo '<div class="elearning_sidebar_wrapper Elearning_'.$meta[$i].'">';


                        foreach ($meta_box['fields'] as $field) {
                             $meta = get_post_meta($post->ID, $field['id'], true);



                             switch($field['type']) {
                                 case 'text':
                                     echo "<h3>{$meta[$i]}</h3>";
                                     break;
                                 case 'textarea':
                                     echo "<p>{$meta[$i]}</p>";
                                     break;

                             }


                        }
         
                           echo '</div>';
                    }



               $admin_options = $IntellumElearning->getAdminOptions();
                        if ($admin_options['intellum_link']=='on') {
                            echo '<a href="http://www.intellum.com">E-Learning</a> by Intellum';
                        }

            

                echo '</div>';
		/* After widget (defined by themes). */
		echo $after_widget;
             }
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );


		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Elearning Widget', 'Elearning') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

	
	<?php
	}
}