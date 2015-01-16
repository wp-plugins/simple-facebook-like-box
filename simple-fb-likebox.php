<?php
/*
Plugin Name: Simple Facebook Like Box
Description: Allows you to simply and easily put the Facebook Like Box on your Wordpress site via a widget.
Version: 1.0.1
Author: Joe Stein
License: GPL2
*/

class Simple_FB_Likebox extends WP_Widget {
   var $default_instance = array(
      'appId' => '',
      'pageId' => '',
      'colorscheme' => 'light',
      'show-faces' => 'true',
      'show-header' => 'true',
      'stream' => 'false',
      'show-border' => 'true',
      'width' => '300',
      'bgcolor' => ''
   );
   
   function __construct() {
		parent::__construct(
			'simple_fb_likebox', // Base ID
			__('FB Like Box', 'sfbl'), // Name
			array( 'description' => __( 'A simple Facebook like box', 'sfbl' ), ) // Args
		);
	}
   
   public function widget( $args, $instance ) {
      $instance = array_merge( $this->default_instance, $instance );
      extract($args);
      
      echo $before_widget;
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php if (!empty($instance['appId'])) echo '&appId='.$instance['appId']; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div <?php if (!empty($instance['bgcolor'])) echo 'style="background-color: '.$instance['bgcolor'].';"'; ?>>
<div class="fb-like-box" data-href="http://www.facebook.com/<?php echo $instance['pageId']; ?>" data-colorscheme="<?php echo $instance['colorscheme']; ?>" data-show-faces="<?php echo $instance['show-faces']; ?>" data-width="<?php echo $instance['width']; ?>" data-header="<?php echo $instance['show-header']; ?>" data-stream="<?php echo $instance['stream']; ?>" data-show-border="<?php echo $instance['show-border']; ?>"></div>
</div>
<?php
      echo $after_widget;
   }
   
   public function form ( $instance ) {
      $instance = array_merge( $this->default_instance, $instance );
      foreach (array('App' => 'Optional', 'Page' => 'Required') as $fieldName => $placeholder) :
         $fval = $instance[strtolower($fieldName).'Id'];
?>
   <p>
		<label for="<?php echo $this->get_field_id( strtolower($fieldName).'Id' ); ?>"><?php _e( $fieldName.' ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( strtolower($fieldName).'Id' ); ?>" name="<?php echo $this->get_field_name( strtolower($fieldName).'Id' ); ?>" type="text" value="<?php echo esc_attr( $fval ); ?>" placeholder="<?php echo $placeholder; ?>">
	</p>
<?php
      endforeach;
?>
   <p>
		<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Color Scheme:' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'colorscheme' ); ?>" name="<?php echo $this->get_field_name( 'colorscheme' ); ?>">
<?php
      foreach (array('Light','Dark') as $opt) {
         $lower = strtolower($opt);
         echo '<option value="'.$lower.'"';
         if ($instance['colorscheme'] == $lower) echo ' selected';
         echo '>'.$opt.'</option>';
      }
?>
	   </select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo intval( $instance['width'] ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'bgcolor' ); ?>"><?php _e( 'Background Color:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'bgcolor' ); ?>" name="<?php echo $this->get_field_name( 'bgcolor' ); ?>" value="<?php echo esc_attr( $instance['bgcolor'] ); ?>" placeholder="Optional" />
	</p>
<?php
      $boolopts = array(
         'show-faces' => 'Show Faces?',
         'show-header' => 'Show Header?',
         'stream' => 'Show Stream?',
         'show-border' => 'Show Border?'
      );
      foreach ($boolopts as $slug => $desc) :
?>
       <p>
		   <label for="<?php echo $this->get_field_id( $slug ); ?>"><?php _e( $desc ); ?></label> 
		   <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( $slug ); ?>" name="<?php echo $this->get_field_name( $slug ); ?>" <?php if ( $instance[$slug] == 'true') echo 'checked '; ?>">
	   </p>
<?php
      endforeach;
   }
   
   public function update( $new_instance, $old_instance ) {
      foreach (array('show-faces','show-header','stream','show-border') as $boolopt) {
         if (!empty($new_instance[$boolopt])) {
            $new_instance[$boolopt] = 'true';
         } else {
            $new_instance[$boolopt] = 'false';
         }
      }
      foreach (array('appId','pageId') as $txtip) {
         $new_instance[$txtip] = trim($new_instance[$txtip]);
      }
      return $new_instance;
   }
}

add_action('widgets_init',
   create_function('', 'return register_widget("Simple_FB_Likebox");')
);
