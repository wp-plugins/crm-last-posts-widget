<?php

/*
Plugin Name: CRM LastPosts Widget
Plugin URI: http://www.cromorama.com/blog/crm-lastposts-widget
Description: Shows the last, most popular or random posts of any category you choose using a selected thumbnail image and different effects.
Version: 1.4.11
Author: Cromorama.com
Author URI: http://www.cromorama.com
*/

//Añadimos el archivo de funciones
include_once('includes/functions.php');

//Registramos el archivo CSS del Plugin
function crm_css() {
	wp_register_style('crmStyle', plugins_url( 'css/crm-lastposts.css' , __FILE__ ) );
	wp_enqueue_style('crmStyle');
	wp_enqueue_style('wp-color-picker');
	
	wp_register_style('crmStyleUI', 'http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css');
	wp_enqueue_style('crmStyleUI');
}
add_action('wp_enqueue_scripts', 'crm_css');
add_action('admin_enqueue_scripts', 'crm_css');

//Resgistramos el JS para el Color Picker
function crm_js() {
	wp_enqueue_script('wp-color-picker');
	wp_enqueue_script('cpa_custom_js', plugins_url().'/crm-last-posts-widget/js/crm-lastposts.js');
	
	wp_enqueue_script('cpa_custom_jsUI', 'http://code.jquery.com/ui/1.10.4/jquery-ui.js');
}
add_action('admin_enqueue_scripts', 'crm_js');

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
		$crm_post_title_activate = $instance['crm_post_title_activate'];
		$crm_post_date_activate = $instance['crm_post_date_activate'];
		$crm_num_color_activate = $instance['crm_num_color_activate'];
		$crm_text_container_class_activate = $instance['crm_text_container_class_activate'];
		$crm_title_color = $instance['crm_title_color'];
		$crm_date_color = $instance['crm_date_color'];
		$crm_posts_color = $instance['crm_posts_color'];
		$crm_cont_color = $instance['crm_cont_color'];
		$crm_title_size = $instance['crm_title_size'];
		$crm_date_size = $instance['crm_date_size'];
		$crm_posts_size = $instance['crm_posts_size'];
		$crm_cont_opac = $instance['crm_cont_opac'];
		$crm_cont_radious = $instance['crm_cont_radious'];
		
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
                                                <div id="" style="background:<?php echo hex2rgb($crm_cont_color, $crm_cont_opac) ?>;-webkit-border-radius: <?php echo $crm_cont_radious; ?>px;-moz-border-radius: <?php echo $crm_cont_radious; ?>px;border-radius: <?php echo $crm_cont_radious; ?>px;" class="defaultBox">
<?php
                                            }
													if($crm_post_title_activate == 1){
?>                                   
                                                    <h4 style="color:<?php echo $crm_title_color; ?>;font-size:<?php echo $crm_title_size; ?>px;" class="defaultTitle"><?php the_title(); ?></h4>
<?php
													}
													if($crm_post_date_activate == 1){
?>
                                                    <p style="color:<?php echo $crm_date_color; ?>;font-size:<?php echo $crm_date_size; ?>px;" class="defaultDate"><?php echo get_the_date(); ?></p>
<?php
													}
													if($crm_num_color_activate == 1){
?>
                                                        <p class="defaultNum" style="color:<?php echo $crm_posts_color; ?>;font-size:<?php echo $crm_posts_size; ?>px;"><?php echo comments_number(); ?></p>
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
		if (isset($instance['crm_post_title_activate'])){ $crm_post_title_activate = $instance['crm_post_title_activate']; }else{ $crm_post_title_activate = 0; }
		if (isset($instance['crm_post_date_activate'])){ $crm_post_date_activate = $instance['crm_post_date_activate']; }else{ $crm_post_date_activate = 0; }
		if (isset($instance['crm_num_color_activate'])){ $crm_num_color_activate = $instance['crm_num_color_activate']; }else{ $crm_num_color_activate = 0; }
		if (isset($instance['crm_text_container_class_activate'])){ $crm_text_container_class_activate = $instance['crm_text_container_class_activate']; }else{ $crm_text_container_class_activate = 0; }
		if (isset($instance['crm_title_color'])){ $crm_title_color = $instance['crm_title_color']; }else{ $crm_title_color = "#FFF"; }
		if (isset($instance['crm_date_color'])){ $crm_date_color = $instance['crm_date_color']; }else{ $crm_date_color = "#FFF"; }
		if (isset($instance['crm_posts_color'])){ $crm_posts_color = $instance['crm_posts_color']; }else{ $crm_posts_color = "#FFF"; }
		if (isset($instance['crm_cont_color'])){ $crm_cont_color = $instance['crm_cont_color']; }else{ $crm_cont_color = "#FFF"; }
		if (isset($instance['crm_title_size'])){ $crm_title_size = $instance['crm_title_size']; }else{ $crm_title_size = "24"; }
		if (isset($instance['crm_date_size'])){ $crm_date_size = $instance['crm_date_size']; }else{ $crm_date_size = "16"; }
		if (isset($instance['crm_posts_size'])){ $crm_posts_size = $instance['crm_posts_size']; }else{ $crm_posts_size = "18"; }
		if (isset($instance['crm_cont_opac'])){ $crm_cont_opac = $instance['crm_cont_opac']; }else{ $crm_cont_opac = "0.6"; }
		if (isset($instance['crm_cont_radious'])){ $crm_cont_radious = $instance['crm_cont_radious']; }else{ $crm_cont_radious = "5"; }
		
		//Recuperacion de Categorias
		$cat_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC'
		);
		
		$categories = get_categories($cat_args);
		//FIN Recuperacion de Categorías
	
?>
			<div style="margin-bottom:20px;overflow:auto;">
            
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
            
            <p>
				<?php $sizes = get_image_sizes(); ?>
                			
				<label for="<?php echo $this->get_field_id( 'crm_thumb' ); ?>"><?php _e('Thumbnail', 'crm-lastposts'); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'crm_thumb' ); ?>" name="<?php echo $this->get_field_name( 'crm_thumb' ); ?>">

					<?php foreach($sizes as $size => $name ){?>
                    	<option value="<?php echo $size; ?>" <?php if($crm_thumb == $size){ echo "SELECTED"; } ?> ><?php echo $size; ?></option>
                    <?php } ?>
                
				</select>
			</p>
            
            <label for="<?php echo $this->get_field_id( 'crm_title_color' ); ?>"><?php _e('Customization Options', 'crm-lastposts'); ?>:</label> 
			<hr />
            
            <div class="crmCustomTitlesLeft">
            	<p class="crmCustomTopsText"><?php _e('Post Title', 'crm-lastposts'); ?>:</p>
            </div>
            <div class="crmCustomTitlesRight">
            	<p class="crmCustomTopsText"><input class="NumPostTitle active_<?php echo $crm_post_title_activate; ?>" type="checkbox" name="<?php echo $this->get_field_name( 'crm_post_title_activate' ); ?>" value="1" <?php if($crm_post_title_activate == 1)echo "checked"; ?> > <?php _e('Check to activate', 'crm-lastposts'); ?></p>
            </div>
            <div class="crmCustomContainer NumPostTitleContainer">
            	<p><input type="text" id="<?php echo $this->get_field_id( 'crm_title_color' ); ?>" name="<?php echo $this->get_field_name( 'crm_title_color' );?>" value="<?php echo $crm_title_color; ?>" class="crm-color-picker" ></p>
            	                
                <div class="crmCustomTitlesLeft" style="margin-top:4px;">
                	<div class="slButtonTitle"></div>
                </div>
                <div class="crmCustomTitlesRightSizer">
                	<p style="crmSizerContent"><input class="widefat crmSizer slTitle" id="<?php echo $this->get_field_id( 'crm_title_size' ); ?>" name="<?php echo $this->get_field_name( 'crm_title_size' ); ?>" type="text" value="<?php echo esc_attr( $crm_title_size ); ?>" /> <?php _e('Size (px)', 'crm-lastposts'); ?></p>
                </div>
                
               	<script type='text/javascript'>
       				var slTitleValue = <?php echo esc_attr( $crm_title_size ); ?>;
        		</script>

            </div>
            
            <div class="crmCustomTitlesLeft">
            	<p class="crmCustomTopsText"><?php _e('Post Date', 'crm-lastposts'); ?>:</p>
            </div>
            <div class="crmCustomTitlesRight">
            	<p class="crmCustomTopsText"><input class="NumPostDate active_<?php echo $crm_post_date_activate; ?>" type="checkbox" name="<?php echo $this->get_field_name( 'crm_post_date_activate' ); ?>" value="1" <?php if($crm_post_date_activate == 1)echo "checked"; ?> > <?php _e('Check to activate', 'crm-lastposts'); ?></p>
            </div>
            <div class="crmCustomContainer NumPostDateContainer">
            	<p><input type="color" id="<?php echo $this->get_field_id( 'crm_date_color' ); ?>" name="<?php echo $this->get_field_name( 'crm_date_color' );?>" value="<?php echo $crm_date_color; ?>" class="crm-color-picker"></p>
            
            	<div class="crmCustomTitlesLeft" style="margin-top:4px;">
                	<div class="slButtonDate"></div>
                </div>
                <div class="crmCustomTitlesRightSizer">
                	<p><input class="widefat crmSizer slDate" id="<?php echo $this->get_field_id( 'crm_date_size' ); ?>" name="<?php echo $this->get_field_name( 'crm_date_size' ); ?>" type="text" value="<?php echo esc_attr( $crm_date_size ); ?>" /> <?php _e('Size (px)', 'crm-lastposts'); ?></p>
                </div>
                
                <script type='text/javascript'>
       				var slDateValue = <?php echo esc_attr( $crm_date_size ); ?>;
        		</script>
                
            </div>
            
            <div class="crmCustomTitlesLeft">
           		<p class="crmCustomTopsText"><?php _e('Posts Num Color', 'crm-lastposts'); ?>:</p>
            </div>
            <div class="crmCustomTitlesRight">
            	<p class="crmCustomTopsText"><input class="NumColorCheck active_<?php echo $crm_num_color_activate; ?>" type="checkbox" name="<?php echo $this->get_field_name( 'crm_num_color_activate' ); ?>" value="1" <?php if($crm_num_color_activate == 1)echo "checked"; ?> > <?php _e('Check to activate', 'crm-lastposts'); ?></p>
            </div>
            <div class="crmCustomContainer NumColorCheckContainer">
            	<p><input type="color" id="<?php echo $this->get_field_id( 'crm_posts_color' ); ?>" name="<?php echo $this->get_field_name( 'crm_posts_color' );?>" value="<?php echo $crm_posts_color; ?>" class="crm-color-picker"></p>
            
            	<div class="crmCustomTitlesLeft" style="margin-top:4px;">
                	<div class="slButtonComments"></div>
                </div>
                <div class="crmCustomTitlesRightSizer">
                	<p><input class="widefat crmSizer slComments" id="<?php echo $this->get_field_id( 'crm_posts_size' ); ?>" name="<?php echo $this->get_field_name( 'crm_posts_size' ); ?>" type="text" value="<?php echo esc_attr( $crm_posts_size ); ?>" /> <?php _e('Size (px)', 'crm-lastposts'); ?></p>
                </div>
                
                <script type='text/javascript'>
       				var slCommentsValue = <?php echo esc_attr( $crm_posts_size ); ?>;
        		</script>
            
            </div>
            
            
            <div class="crmCustomTitlesLeft">
            	<p class="crmCustomTopsText"><?php _e('Box', 'crm-lastposts'); ?>:</p>
            </div>
            <div class="crmCustomTitlesRight">
            	<p class="crmCustomTopsText"><input class="boxCheck active_<?php echo $crm_text_container_class_activate; ?>" type="checkbox" name="<?php echo $this->get_field_name( 'crm_text_container_class_activate' ); ?>" value="1" <?php if($crm_text_container_class_activate == 1)echo "checked"; ?> > <?php _e('Check to activate', 'crm-lastposts'); ?></p>
            </div>
            <div class="crmCustomContainer boxCheckContainer">
            	<p><input type="color" id="<?php echo $this->get_field_id( 'crm_cont_color' ); ?>" name="<?php echo $this->get_field_name( 'crm_cont_color' );?>" value="<?php echo $crm_cont_color; ?>" class="crm-color-picker"></p>
            	
                <div class="crmCustomTitlesLeft" style="margin-top:4px;">
                	<div class="slButtonBoxOpacity"></div>
                </div>
                <div class="crmCustomTitlesRightSizer">
               		<p><input class="widefat crmSizer slBoxOpacity" id="<?php echo $this->get_field_id( 'crm_cont_opac' ); ?>" name="<?php echo $this->get_field_name( 'crm_cont_opac' ); ?>" type="text" value="<?php echo esc_attr( $crm_cont_opac ); ?>" /> <?php _e('Opacity', 'crm-lastposts'); ?></p>
                </div>
            
            	<script type='text/javascript'>
       				var slOpacityValue = <?php echo esc_attr( $crm_cont_opac ); ?>;
        		</script>

                <div class="crmCustomTitlesLeft" style="margin-top:4px;">
                	<div class="slButtonBoxRadious"></div>
                </div>
                <div class="crmCustomTitlesRightSizer">
               		<p><input class="widefat crmSizer slBoxRadious" id="<?php echo $this->get_field_id( 'crm_cont_radious' ); ?>" name="<?php echo $this->get_field_name( 'crm_cont_radious' ); ?>" type="text" value="<?php echo esc_attr( $crm_cont_radious ); ?>" /> <?php _e('Border Radious', 'crm-lastposts'); ?></p>
                </div>
            
            	<script type='text/javascript'>
       				var slRadiousValue = <?php echo esc_attr( $crm_cont_radious ); ?>;
        		</script>
            
            </div> 
            
            </div>
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
			$instance['crm_post_title_activate'] = ( ! empty( $new_instance['crm_post_title_activate'] ) ) ? strip_tags( $new_instance['crm_post_title_activate'] ) : '';
			$instance['crm_post_date_activate'] = ( ! empty( $new_instance['crm_post_date_activate'] ) ) ? strip_tags( $new_instance['crm_post_date_activate'] ) : '';
			$instance['crm_num_color_activate'] = ( ! empty( $new_instance['crm_num_color_activate'] ) ) ? strip_tags( $new_instance['crm_num_color_activate'] ) : '';
			$instance['crm_text_container_class_activate'] = ( ! empty( $new_instance['crm_text_container_class_activate'] ) ) ? strip_tags( $new_instance['crm_text_container_class_activate'] ) : '';
			$instance['crm_title_color'] = ( ! empty( $new_instance['crm_title_color'] ) ) ? strip_tags( $new_instance['crm_title_color'] ) : '';
			$instance['crm_date_color'] = ( ! empty( $new_instance['crm_date_color'] ) ) ? strip_tags( $new_instance['crm_date_color'] ) : '';
			$instance['crm_posts_color'] = ( ! empty( $new_instance['crm_posts_color'] ) ) ? strip_tags( $new_instance['crm_posts_color'] ) : '';
			$instance['crm_cont_color'] = ( ! empty( $new_instance['crm_cont_color'] ) ) ? strip_tags( $new_instance['crm_cont_color'] ) : '';
			$instance['crm_title_size'] = ( ! empty( $new_instance['crm_title_size'] ) ) ? strip_tags( $new_instance['crm_title_size'] ) : '';
			$instance['crm_date_size'] = ( ! empty( $new_instance['crm_date_size'] ) ) ? strip_tags( $new_instance['crm_date_size'] ) : '';
			$instance['crm_posts_size'] = ( ! empty( $new_instance['crm_posts_size'] ) ) ? strip_tags( $new_instance['crm_posts_size'] ) : '';
			$instance['crm_cont_opac'] = ( ! empty( $new_instance['crm_cont_opac'] ) ) ? strip_tags( $new_instance['crm_cont_opac'] ) : '';
			$instance['crm_cont_radious'] = ( ! empty( $new_instance['crm_cont_radious'] ) ) ? strip_tags( $new_instance['crm_cont_radious'] ) : '';
		return $instance;
	
	}
}

function wpb_load_widget() { 
	register_widget( 'crm_lastposts' );
	load_plugin_textdomain( 'crm-lastposts', false, 'crm-last-posts-widget/languajes' );
}
add_action( 'widgets_init', 'wpb_load_widget' );