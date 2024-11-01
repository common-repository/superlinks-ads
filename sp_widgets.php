<?php

class AdBannerWidget2 extends WP_Widget
{
  function AdBannerWidget2()
  {
    $widget_ops = array('classname' => 'AdBannerWidget2', 'description' => 'Use this widget to add Superlinks - Super Display Ads to your website.' );
    $this->WP_Widget('AdBannerWidget2', 'Ads: Superlinks - Super Display Ads', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
	$dimension = $instance['dimension'];
	$alignment = $instance['alignmentt'];

?>
<Script language="JavaScript">


jQuery(document).ready(function(){
		jQuery('.dimension').on('change', function () {
			 var $this = jQuery(this);
			 if($this.val() == 'add'){
				$this.prop('selectedIndex',0);
				window.open('http://superlinks.com/publisher/widget-setup-selection.php','_blank');
			 }
		 });
});
</script>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title (optional): <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  <p>
  	  <label>Ad Dimensions:</label><br>
	  <select name="<?php echo $this->get_field_name('dimension'); ?>" id="<?php echo $this->get_field_id('dimension'); ?>" class="dimension" style="min-width:100px;">
	  
	  
	  <option>Select Dimension</option>
<?
	$DisplayAdsCount = get_option('SuperDisplayCount');
	for($i=1;$i<=($DisplayAdsCount);$i++){
	 $WrapSize = get_option('SuperDisplayWrapSize'.$i);
	 $DisplayBannerSize[$i] = $WrapSize;
	}
	
	foreach($DisplayBannerSize as $bannerSize) {
	?>
	<option value="<?php echo $bannerSize; ?>" <?php if($dimension==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
	<?						
	}
?>	
	  <option value="add">Add New</option>
	  </select>
  </p>
  
  <p>
  	<input name="<?php echo $this->get_field_name('alignmentt'); ?>" id="<?php echo $this->get_field_id('alignmentt'); ?>" type="radio" value="left" <? if($alignment=='left' || $alignment=='')echo 'checked="checked"'; ?> /> Left
  	<input name="<?php echo $this->get_field_name('alignmentt'); ?>" id="<?php echo $this->get_field_id('alignmentt'); ?>" type="radio" value="center" <? if($alignment=='center')echo 'checked="checked"'; ?> /> Center
  	<input name="<?php echo $this->get_field_name('alignmentt'); ?>" id="<?php echo $this->get_field_id('alignmentt'); ?>" type="radio" value="right" <? if($alignment=='right')echo 'checked="checked"'; ?> /> Right  
  </p>
  
  <p><a target="_blank" href="<?=get_bloginfo('wpurl');?>">View site</a></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['dimension'] = $new_instance['dimension'];
	$instance['alignmentt'] = $new_instance['alignmentt'];	
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
 	
 	$curr_dimension = $instance['dimension'];
	$DisplayAdsCount = get_option('SuperDisplayCount');
	for($i=1;$i<=($DisplayAdsCount);$i++){
		if($curr_dimension == get_option('SuperDisplayWrapSize'.$i)){
			$curr_adtag = get_option('SuperDisplayWrap'.$i);
		}
	}
	
	
	$curr_dimension = explode('x',$curr_dimension);
	$curr_dimension = 'width:'.$curr_dimension[0].'px;height:'.$curr_dimension[1].'px;';
	
	$curr_alignment = $instance['alignmentt'];
	
	if($curr_alignment == 'left'){
		$alignment_ = 'float:left;';
	} elseif($curr_alignment == 'center'){
		$alignment_ = 'margin:0 auto;';
	} elseif($curr_alignment == 'right'){
		$alignment_ = 'float:right;';
	}
    

 $sp_text =  '<div style="'.$curr_dimension.$alignment_.'display:block">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div>';
			
			if ( $sp_text != '' ) {
				echo $sp_text, "\n";
			}
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("AdBannerWidget2");') );
?>