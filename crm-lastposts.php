<?php

/*
Plugin Name: CRM LastPosts Widget
Plugin URI: http://www.cromorama.com/blog/crm-lastposts-widget
Description: Shows the last, most popular or random posts of any category you choose using a selected thumbnail image and different effects.
Version: 1.4.2
Author: Cromorama.com
Author URI: http://www.cromorama.com
*/

//Registramos el archivo CSS del Plugin
function crm_css() {
	wp_register_style('crmStyle', plugins_url( 'css/crm-lastposts.css' , __FILE__ ) );
	wp_enqueue_style( 'crmStyle');
}
add_action('wp_enqueue_scripts', 'crm_css');
add_action('admin_enqueue_scripts', 'crm_css');

//Creamos el WidGet
class crm_lastposts extends WP_Widget {

	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'crm_lastposts', 
		// Widget name will appear in UI
		__('CRM LastPosts Widget', 'crm-lastposts'), 
		// Widget description
		array( 'description' => __('Shows the last, most popular or random posts of any category you choose using a selected thumbnail image and different effects.', 'crm-lastposts'), ) 
		);
	}
	
	//FrontEnd del Widget
	public function widget($args, $instance) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num_posts = $instance['num_posts'];
		$crm_category = $instance['crm_category'];
		$crm_order = $instance['crm_order'];
		$crm_order_short = $instance['crm_order_short'];
		$crm_effect = $instance['crm_effect'];
		$crm_thumb = $instance['crm_thumb'];
		$crm_text_container_class_activate = $instance['crm_text_container_class_activate'];
		$crm_text_container_class = $instance['crm_text_container_class'];
		$crm_title_class = $instance['crm_title_class'];
		$crm_date_class = $instance['crm_date_class'];
		
		echo $args['before_widget'];
		
			if (!empty($title))
				echo $args['before_title'] . $title . $args['after_title'];
?>
			<div class="textwidget">	
				<div style="padding-bottom:10px;">
<?php               
                	wp_reset_postdata();
					$id_counter = 0;
				
					$r = new WP_Query (array('orderby' => $crm_order, 'order' => $crm_order_short, 'posts_per_page' => $num_posts, 'cat' => $crm_category, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));

					if ($r->have_posts()) :
				
						while ( $r->have_posts() ) : $r->the_post(); 
?>
                            <a href="<?php the_permalink(); ?>" title="" rel="bookmark">
                            
                            	<div class="<?php echo $crm_effect; ?>" onmouseover="hacer_hover_<?php echo $id_counter; ?>()" onmouseout="quitar_hover_<?php echo $id_counter; ?>()">
<?php
									if (has_post_thumbnail()){
                                        	the_post_thumbnail($crm_thumb);

									}else{
											echo '<img src="' . plugins_url( 'img/no-image.jpg' , __FILE__ ) . '" > ';
									}
?>
                                    
                                    <div id="fecha<?php echo $id_counter; ?>" class="text">
                                        <div class="<?php echo $crm_effect; ?>Mask">
<?php
                                            if($crm_text_container_class_activate == 1){
?>
                                                <div id="" class="<?php echo $crm_text_container_class; ?>">
<?php
                                            }
?>                                   
                                                    <h4 class="<?php echo $crm_title_class; ?>"><?php the_title(); ?></h4>
                                                    <p class="<?php echo $crm_date_class; ?>"><?php echo get_the_date(); ?></p>
<?php
                                                    if($crm_order == "comment_count"){
?>
                                                        <p class="<?php echo $crm_date_class; ?>" style="margin-top:5px;"><?php echo comments_number(); ?></p>
<?php
                                                    }
                                                
                                            if($crm_text_container_class_activate == 1){
?>
                                                </div>
<?php	
                                            }
?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <script type="text/javascript">
			
								function hacer_hover_<?php echo $id_counter; ?>()
								{
									document.getElementById('fecha<?php echo $id_counter; ?>').className = "nuevoClass";
								}
								
								function quitar_hover_<?php echo $id_counter; ?>()
								{
									document.getElementById('fecha<?php echo $id_counter; ?>').className = "text";
								}
				
							</script>
                            
<?php 					$id_counter++;
						endwhile; 

					//Reseteamos la query para que no de conflicto con otros widgets, plugins, etc...
					wp_reset_postdata();
					endif;					
?>           
				</div>
            </div>
<?php		
		echo $args['after_widget'];
	
	}
			
	//Widget BackEnd 
	public function form($instance) {
		
		if (isset($instance['title'])){ $title = $instance['title']; }else{ $title = __('Title', 'crm-lastposts');	}
		if (isset($instance['num_posts'])){	$num_posts = $instance['num_posts']; }else{ $num_posts = __('nposts', 'crm-lastposts');}
		if (isset($instance['crm_category'])){ $crm_category = $instance['crm_category']; }else{ $crm_category = __('all', 'crm-lastposts'); }
		if (isset($instance['crm_order'])){ $crm_order = $instance['crm_order']; }else{ $crm_order = __('date', 'crm-lastposts'); }
		if (isset($instance['crm_order_short'])){ $crm_order_short = $instance['crm_order_short'];	}else{ $crm_order_short = "ASC"; }
		if (isset($instance['crm_effect'])){ $crm_effect = $instance['crm_effect']; }else{ $crm_effect = "postContainerOpacity"; }
		if (isset($instance['crm_thumb'])){	$crm_thumb = $instance['crm_thumb']; }else{ $crm_thumb = "medium"; }
		if (isset($instance['crm_text_container_class_activate'])){ $crm_text_container_class_activate = $instance['crm_text_container_class_activate']; }else{ $crm_text_container_class_activate = 0; }
		if (isset($instance['crm_text_container_class'])){ $crm_text_container_class = $instance['crm_text_container_class']; }else{ $crm_text_container_class = "defaultBox"; }
		if (isset($instance['crm_title_class'])){ $crm_title_class = $instance['crm_title_class']; }else{ $crm_title_class = "defaultTitle"; }
		if (isset($instance['crm_date_class'])){ $crm_date_class = $instance['crm_date_class']; }else{ $crm_date_class = "defaultDate"; }
		
		//Recuperacion de Categorias
		$cat_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC'
		);
		
		$categories = get_categories($cat_args);
		//FIN Recuperacion de Categorías
	
?>
			<p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'crm-lastposts'); ?>:</label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
            
            <div class="adminDoubleContainer marginBottomEnd">
            
                <p class="leftSide">
                    <label for="<?php echo $this->get_field_id( 'crm_category' ); ?>"><?php _e('Category to show', 'crm-lastposts'); ?>:</label> 
                    <select class="widefat" id="<?php echo $this->get_field_id( 'crm_category' ); ?>" name="<?php echo $this->get_field_name( 'crm_category' ); ?>">
                
                	<option value="All" <?php if($crm_category == "All"){ echo "SELECTED"; } ?> > <?php _e('All Categories', 'crm-lastposts'); ?> </option>
                
<?php  
                	foreach($categories as $all_category) { 
?>
                    	<option value="<?php echo $all_category->cat_ID; ?>" <?php if($crm_category == $all_category->cat_ID){ echo "SELECTED"; } ?> > <?php echo $all_category->name." (".$all_category->count.")" ?></option>
<?php
                } 
?>
                	</select> 
				</p>
                <p class="rightSide">
                	<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Posts Number', 'crm-lastposts'); ?>:</label> 
                	<input class="widefat" id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" type="text" value="<?php echo esc_attr( $num_posts ); ?>"  style="text-align:right;"/>
                </p>
                
            </div>
            
            <p class="marginKill">
            	<label for="<?php echo $this->get_field_id( 'crm_order' ); ?>"><?php _e('Order By', 'crm-lastposts'); ?>:</label>
            </p>
            
            <div class="adminDoubleContainer">
            
                <p class="leftSide">
                    <select class="widefat" id="<?php echo $this->get_field_id( 'crm_order' ); ?>" name="<?php echo $this->get_field_name( 'crm_order' ); ?>">
                    
                        <option value="date" <?php if($crm_order == "date"){ echo "SELECTED"; } ?> ><?php _e('Date', 'crm-lastposts'); ?></option>
                        <option value="comment_count" <?php if($crm_order == "comment_count"){ echo "SELECTED"; } ?> ><?php _e('Most Popular', 'crm-lastposts'); ?></option>
                        <option value="rand" <?php if($crm_order == "rand"){ echo "SELECTED"; } ?> ><?php _e('Random', 'crm-lastposts'); ?></option>
                    
                    </select>
                </p>
                <p class="rightSide">
                    <select class="widefat" id="<?php echo $this->get_field_id( 'crm_order_short' ); ?>" name="<?php echo $this->get_field_name( 'crm_order_short' ); ?>">
                    
                        <option value="ASC" <?php if($crm_order_short == "ASC"){ echo "SELECTED"; } ?> ><?php _e('ASC', 'crm-lastposts'); ?></option>
                        <option value="DESC" <?php if($crm_order_short == "DESC"){ echo "SELECTED"; } ?> ><?php _e('DESC', 'crm-lastposts'); ?></option>
                    
                    </select>
                </p>
            
            </div>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'crm_effect' ); ?>"><?php _e('Effect', 'crm-lastposts'); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'crm_effect' ); ?>" name="<?php echo $this->get_field_name( 'crm_effect' ); ?>">
                
                    <option value="postContainerOpacity" <?php if($crm_effect == "postContainerOpacity"){ echo "SELECTED"; } ?> ><?php _e('White Opacity', 'crm-lastposts'); ?></option>
                    <option value="postContainerOpacityBlack" <?php if($crm_effect == "postContainerOpacityBlack"){ echo "SELECTED"; } ?> ><?php _e('Black Opacity', 'crm-lastposts'); ?></option>
                    <option value="postContainerGrow" <?php if($crm_effect == "postContainerGrow"){ echo "SELECTED"; } ?> ><?php _e('Image Grow', 'crm-lastposts'); ?></option>
                    <option value="postContainerShrink" <?php if($crm_effect == "postContainerShrink"){ echo "SELECTED"; } ?> ><?php _e('Image Shrink', 'crm-lastposts'); ?></option>
                    <option value="postContainerRotateText" <?php if($crm_effect == "postContainerRotateText"){ echo "SELECTED"; } ?> ><?php _e('Rotate Text', 'crm-lastposts'); ?></option>
                
                </select>
			</p>
            
            <p class="relativePar">
                <label for="<?php echo $this->get_field_id( 'crm_thumb' ); ?>"><?php _e('Thumbnail', 'crm-lastposts'); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'crm_thumb' ); ?>" name="<?php echo $this->get_field_name( 'crm_thumb' ); ?>" type="text" value="<?php echo esc_attr( $crm_thumb ); ?>" />
				
				<a href="http://codex.wordpress.org/Function_Reference/add_image_size" target="_blank"><?php echo '<img src="' . plugins_url( 'img/help-icon.png' , __FILE__ ) . '" class="thumbHelpIcon"> '; ?></a>
			</p>
            
            <p>
            	<input type="checkbox" name="<?php echo $this->get_field_name( 'crm_text_container_class_activate' ); ?>" value="1" <?php if($crm_text_container_class_activate == 1)echo "checked"; ?> > <?php _e('Click for active box', 'crm-lastposts'); ?>
            </p>
                
            <label for="<?php echo $this->get_field_id( 'crm_title_class' ); ?>"><?php _e('Advanced Options', 'crm-lastposts'); ?>:</label> 
			<hr />
            
            <p>
                <label for="<?php echo $this->get_field_id( 'crm_title_class' ); ?>"><?php _e('Post Title CSS Class', 'crm-lastposts'); ?>:</label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'crm_title_class' ); ?>" name="<?php echo $this->get_field_name( 'crm_title_class' ); ?>" type="text" value="<?php echo esc_attr( $crm_title_class ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'crm_date_class' ); ?>"><?php _e('Post Date CSS Class', 'crm-lastposts'); ?>:</label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'crm_date_class' ); ?>" name="<?php echo $this->get_field_name( 'crm_date_class' ); ?>" type="text" value="<?php echo esc_attr( $crm_date_class ); ?>" />
        	</p>
                <label for="<?php echo $this->get_field_id( 'crm_text_container_class' ); ?>"><?php _e('Post Text Container CSS Class', 'crm-lastposts'); ?>:</label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'crm_text_container_class' ); ?>" name="<?php echo $this->get_field_name( 'crm_text_container_class' ); ?>" type="text" value="<?php echo esc_attr( $crm_text_container_class ); ?>" />
            <p>
<?php 
		}
		
	//Actualizamos las opciones del WidGet
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['num_posts'] = ( ! empty( $new_instance['num_posts'] ) ) ? strip_tags( $new_instance['num_posts'] ) : '';
			$instance['crm_category'] = ( ! empty( $new_instance['crm_category'] ) ) ? strip_tags( $new_instance['crm_category'] ) : '';
			$instance['crm_order'] = ( ! empty( $new_instance['crm_order'] ) ) ? strip_tags( $new_instance['crm_order'] ) : '';
			$instance['crm_order_short'] = ( ! empty( $new_instance['crm_order_short'] ) ) ? strip_tags( $new_instance['crm_order_short'] ) : '';
			$instance['crm_effect'] = ( ! empty( $new_instance['crm_effect'] ) ) ? strip_tags( $new_instance['crm_effect'] ) : '';
			$instance['crm_thumb'] = ( ! empty( $new_instance['crm_thumb'] ) ) ? strip_tags( $new_instance['crm_thumb'] ) : '';
			$instance['crm_text_container_class_activate'] = ( ! empty( $new_instance['crm_text_container_class_activate'] ) ) ? strip_tags( $new_instance['crm_text_container_class_activate'] ) : '';
			$instance['crm_text_container_class'] = ( ! empty( $new_instance['crm_text_container_class'] ) ) ? strip_tags( $new_instance['crm_text_container_class'] ) : '';
			$instance['crm_title_class'] = ( ! empty( $new_instance['crm_title_class'] ) ) ? strip_tags( $new_instance['crm_title_class'] ) : '';
			$instance['crm_date_class'] = ( ! empty( $new_instance['crm_date_class'] ) ) ? strip_tags( $new_instance['crm_date_class'] ) : '';
		return $instance;
	
	}
}

function wpb_load_widget() { 
	register_widget( 'crm_lastposts' );
	load_plugin_textdomain( 'crm-lastposts', false, 'crm-last-posts-widget/languajes' );
}
add_action( 'widgets_init', 'wpb_load_widget' );