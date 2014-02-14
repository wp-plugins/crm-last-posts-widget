<?php

/*
Plugin Name: CRM LastPosts Widget
Plugin URI: http://www.cromorama.com/blog/crm-lastposts-widget
Description: Muestra los últimos posts de una categoría concreta de manera visual.
Version: 1.2.5
Author: Cromorama.com
Author URI: http://www.cromorama.com
*/


//Registramos el archivo CSS del Plugin
function crm_css() {

  $pluginURL = WP_PLUGIN_URL."/crm-lastposts/";
  
  wp_register_style('crmStyle', $pluginURL.'css/crm-lastposts.css');
  wp_enqueue_style( 'crmStyle');
  
}
add_action('wp_enqueue_scripts', 'crm_css');

//Creamos el WidGet
class crm_lastposts extends WP_Widget {

	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'crm_lastposts', 
		// Widget name will appear in UI
		__('CRM LastPosts Widget', 'wpb_widget_domain'), 
		// Widget description
		array( 'description' => __( 'Shows the last posts of any category you choose using the thumbnail image with some effects.', 'wpb_widget_domain' ), ) 
		);
	}
	
	//FrontEnd del Widget
	public function widget($args, $instance) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num_posts = $instance['num_posts'];
		$crm_category = $instance['crm_category'];
		$crm_thumb = $instance['crm_thumb'];
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
					
					$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $num_posts, 'cat' => $crm_category, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
							
					if ($r->have_posts()) :
				
						while ( $r->have_posts() ) : $r->the_post(); 
?>
                            <a href="<?php the_permalink(); ?>" title="" rel="bookmark">
                                <div class="postContainer" onmouseover="hacer_hover_<?php echo $id_counter; ?>()" onmouseout="quitar_hover_<?php echo $id_counter; ?>()">
<?php
									if (has_post_thumbnail()){
?>
										<div class="mask">	
                                        	<?php the_post_thumbnail($crm_thumb); ?>
                                    	</div>
<?php
									}else{
?>
                                    	<div class="mask">
<?php
											echo '<img src="' . plugins_url( 'img/no-image.jpg' , __FILE__ ) . '" > ';
?>
                                        </div>
<?php
									}
?>
                                    <div id="fecha<?php echo $id_counter; ?>" class="text">
                                        <p class="<?php echo $crm_title_class; ?>"><?php the_title(); ?></p>
                                        <p class="<?php echo $crm_date_class; ?>"><?php echo get_the_date(); ?></p>
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
		
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}else{
			$title = __( 'Título', 'wpb_widget_domain' );
		}
		
		if (isset($instance['num_posts'])) {
			$num_posts = $instance['num_posts'];
		}else{
			$num_posts = __( '3', 'wpb_widget_domain' );
		}
		
		if (isset($instance['crm_category'])) {
			$crm_category = $instance['crm_category'];
		}else{
			$crm_category = __( 'Sin categoría', 'wpb_widget_domain' );
		}
		
		if (isset($instance['crm_thumb'])) {
			$crm_thumb = $instance['crm_thumb'];
		}else{
			$crm_thumb = __( 'medium', 'wpb_widget_domain' );
		}
		
		if (isset($instance['crm_title_class'])) {
			$crm_title_class = $instance['crm_title_class'];
		}else{
			$crm_title_class = __( 'defaultTitle', 'wpb_widget_domain' );
		}
		
		if (isset($instance['crm_date_class'])) {
			$crm_date_class = $instance['crm_date_class'];
		}else{
			$crm_date_class = __( 'defaultDate', 'wpb_widget_domain' );
		}
		
		//Recuperación de Categorías
		$cat_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC'
		);
		
		$categories = get_categories($cat_args);
		//FIN Recuperación de Categorías
	
			// Widget admin form
?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
            
            <p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e( 'Posts Number:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" type="text" value="<?php echo esc_attr( $num_posts ); ?>" />
			</p>
            <p>
            <label for="<?php echo $this->get_field_id( 'crm_category' ); ?>"><?php _e( 'Category:' ); ?></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'crm_category' ); ?>" name="<?php echo $this->get_field_name( 'crm_category' ); ?>">
<?php  
			foreach($categories as $all_category) { 
?>
				<option value="<?php echo $all_category->cat_ID; ?>" <?php if($crm_category == $all_category->cat_ID){ echo "SELECTED"; } ?> > <?php echo $all_category->name." (".$all_category->count.")" ?></option>
<?php
			} 
?>
			</select> 
            </p>
            
            <p>
			<label for="<?php echo $this->get_field_id( 'crm_thumb' ); ?>"><?php _e( 'Thumbnail:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'crm_thumb' ); ?>" name="<?php echo $this->get_field_name( 'crm_thumb' ); ?>" type="text" value="<?php echo esc_attr( $crm_thumb ); ?>" />
			</p>
            
            <p>
			<label for="<?php echo $this->get_field_id( 'crm_title_class' ); ?>"><?php _e( 'Post Title CSS Class:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'crm_title_class' ); ?>" name="<?php echo $this->get_field_name( 'crm_title_class' ); ?>" type="text" value="<?php echo esc_attr( $crm_title_class ); ?>" />
			</p>
            
            <p>
			<label for="<?php echo $this->get_field_id( 'crm_date_class' ); ?>"><?php _e( 'Post Date CSS Class:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'crm_date_class' ); ?>" name="<?php echo $this->get_field_name( 'crm_date_class' ); ?>" type="text" value="<?php echo esc_attr( $crm_date_class ); ?>" />
			</p>
<?php 
		}
		
	//Actualizamos las opciones del WidGet
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['num_posts'] = ( ! empty( $new_instance['num_posts'] ) ) ? strip_tags( $new_instance['num_posts'] ) : '';
			$instance['crm_category'] = ( ! empty( $new_instance['crm_category'] ) ) ? strip_tags( $new_instance['crm_category'] ) : '';
			$instance['crm_thumb'] = ( ! empty( $new_instance['crm_thumb'] ) ) ? strip_tags( $new_instance['crm_thumb'] ) : '';
			$instance['crm_title_class'] = ( ! empty( $new_instance['crm_title_class'] ) ) ? strip_tags( $new_instance['crm_title_class'] ) : '';
			$instance['crm_date_class'] = ( ! empty( $new_instance['crm_date_class'] ) ) ? strip_tags( $new_instance['crm_date_class'] ) : '';
		return $instance;
	
	}
} //wpb_widget Acaba aquí.


//Registramos y cargamos el WidGet
function wpb_load_widget() { register_widget( 'crm_lastposts' );}
add_action( 'widgets_init', 'wpb_load_widget' );