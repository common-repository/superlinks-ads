<?php
function sp_init() {
		wp_enqueue_script('jquery');	
}
add_action('init', 'sp_init');  

/**/


/**/
//Insert ads before post content.

add_filter( 'the_content', 'sp_prefix_insert_before_post' );
function sp_prefix_insert_before_post( $content ) {
	
	if ( is_single() ) {
	
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
			
		$spProd1 = get_option('spProd1');
		$spExclude = get_option('spExcludeDisplay');
		$spPosts = get_option('spPosts');
		$spPostsWithinContent = get_option('spPostsAbovePost');
		$spDimension = get_option('spPostsAbovePostSZ');
		$spAlignment = get_option('spPostsAbovePostAL');
		
		}
	}	
	
	if ( is_page() ) {
		
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
			
		$spProd1 = get_option('spProd1');
		
		$spPosts = get_option('spPages');
		$spPostsWithinContent = get_option('spPagesAboveContent');
		$spDimension = get_option('spPagesAboveContentSZ');
		$spAlignment = get_option('spPagesAboveContentAL');
		} 
	}
	
		
		if($spAlignment == 'left') { $spAlignment = 'float:left;margin:0 25px 5px 0;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;margin:0 0 5px 25px;'; }	
	
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';
		
		$ad_code = '<div style="'.$spSize.$spAlignment.';display:block;">'.stripslashes($curr_adtag).'</div><div style="clear:both;"></div>';
		
		if ( $spPosts == '1' && $spProd1 == '1' && $spPostsWithinContent== '1') {
				
			return sp_prefix_insert_before_paragraph( $ad_code, 1, $content );
		}
	
	return $content;
}





/**/
//Insert ads after first paragraph of single post content.

add_filter( 'the_content', 'sp_prefix_insert_post_ads' );
function sp_prefix_insert_post_ads( $content ) {
	
		if ( is_single() ) {
	
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
			if(in_array($currPage, $spExclude)) {
				$spPosts = '0';	
			} else {
				
			$spProd1 = get_option('spProd1');
			$spPosts = get_option('spPosts');
			$WithinContent = get_option('spPostsWithinContent');
			$spDimension = get_option('spPostsWithinContentSZ');
			$spPostsWithinContentAL = get_option('spPostsWithinContentAL');
			}
		}
		
		if ( is_page() ) {
	
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
			if(in_array($currPage, $spExclude)) {
				$spPosts = '0';	
			} else {
				
			$spProd1 = get_option('spProd1');
			$spPosts = get_option('spPages');
			$WithinContent = get_option('spPagesWithinContent');
			$spDimension = get_option('spPagesWithinContentSZ');
			$spPostsWithinContentAL = get_option('spPagesWithinContentAL');
			
			}
		}
		
		if($spPostsWithinContentAL == 'left') { $spAlignment = 'float:left;margin:0 25px 5px 0;'; }
		if($spPostsWithinContentAL == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spPostsWithinContentAL == 'right') { $spAlignment = 'float:right;margin:0 0 5px 25px;'; }	
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';
		
		$ad_code = '<div style="'.$spSize.$spAlignment.';display:block;">'.stripslashes($curr_adtag).'</div>';
		
		if ( $spPosts == '1' && $spProd1 == '1' && $WithinContent== '1') {
				
			return sp_prefix_insert_before_paragraph( $ad_code, 2, $content );
		}
	
	return $content;
}

 
// Parent Function that makes the magic happen
 
function sp_prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}
	
	return implode( '', $paragraphs );
}

function sp_prefix_insert_before_paragraph( $insertion, $paragraph_id, $content ) {
	$closing_p = '<p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}
	
	return implode( '', $paragraphs );
}

/**/


add_action( 'the_post' , 'sp_insert_before_posts' );
function sp_insert_before_posts( $post ) {
   global $mh_post_count;
   $mh_after_head = did_action( 'wp_enqueue_scripts' );
   
   		$spProd1 = get_option('spProd1');
		$spHomepage = '0';
		
		if ( $_SERVER["REQUEST_URI"] == '/' ) { 
		$spHomepage = get_option('spHomepage');
		
		$spHpAboveFirst = get_option('spHomepageAboveFirstPost');
		$spAlignment = get_option('spHomepageAboveFirstPostAL');
		$spDimension = get_option('spHomepageAboveFirstPostSZ');
			
		$spHpBelowFirst = get_option('spHomepageBelowFirstPost');
		$spAlignment = get_option('spHomepageBelowFirstPostAL');
		$spDimension = get_option('spHomepageBelowFirstPostSZ');

		$spHpBelowSecond = get_option('spHomepageBelowSecondPost');
		$spAlignment = get_option('spHomepageBelowSecondPostAL');
		$spDimension = get_option('spHomepageBelowSecondPostSZ');

		$spHpBelowThird = get_option('spHomepageBelowThirdPost');
		$spAlignment = get_option('spHomepageBelowThirdPostAL');
		$spDimension = get_option('spHomepageBelowThirdPostSZ');

		$spHpBelowFourth = get_option('spHomepageBelowFourthPost');
		$spAlignment = get_option('spHomepageBelowFourthPostAL');
		$spDimension = get_option('spHomepageBelowFourthPostSZ');

		$spHpBelowFifth = get_option('spHomepageBelowFifthPost');
		$spAlignment = get_option('spHomepageBelowFifthPostAL');
		$spDimension = get_option('spHomepageBelowFifthPostSZ');
		
		$spHpBelowLast = get_option('spHomepageBelowLastPost');
		$spAlignment = get_option('spHomepageBelowLastPostAL');
		$spDimension = get_option('spHomepageBelowLastPostSZ');
		}
		
/*cat*/
		$spCategory = '0';
		if(is_category()){
			$spCategory = get_option('spCategory');
		
			$spAboveFirst = get_option('spCategoryAboveFpost');
			$spAlignment = get_option('spCategoryAboveFpostAL');
			$spDimension = get_option('spCategoryAboveFpostSZ');
				
			$spBelowFirst = get_option('spCategoryBelowFpost');
			$spAlignment = get_option('spCategoryBelowFpostAL');
			$spDimension = get_option('spCategoryBelowFpostSZ');

			$spBelowSecond = get_option('spCategoryBelowSpost');
			$spAlignment = get_option('spCategoryBelowSpostAL');
			$spDimension = get_option('spCategoryBelowSpostSZ');
			
			$spBelowThird = get_option('spCategoryBelowTpost');
			$spAlignment = get_option('spCategoryBelowTpostAL');
			$spDimension = get_option('spCategoryBelowTpostSZ');
			
			$spBelowFourth = get_option('spCategoryBelowFrpost');
			$spAlignment = get_option('spCategoryBelowFrpostAL');
			$spDimension = get_option('spCategoryBelowFrpostSZ');
			
			$spBelowFifth = get_option('spCategoryBelowFvpost');
			$spAlignment = get_option('spCategoryBelowFvpostAL');
			$spDimension = get_option('spCategoryBelowFvpostSZ');
			
			$spBelowLast = get_option('spCategoryBelowLpost');
			$spAlignment = get_option('spCategoryBelowLpostAL');
			$spDimension = get_option('spCategoryBelowLpostSZ');
		}
				
		


		if($spAlignment == 'left') { $spAlignment = 'float:left; margin:0 20px 5px 0;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;margin:0 0 5px 20px;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}


		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';
		
		$ad_code = '<div style="'.$spSize.$spAlignment.';display:block;">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div>';
   
   if ( ( $mh_after_head == 1 ) and ( $mh_post_count < get_option('posts_per_page') ) ) {
      $mh_post_count++;

	  
	  	
	if ( $_SERVER["REQUEST_URI"] == '/' ) { 
	
		if($mh_post_count==1){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpAboveFirst== '1') {
			
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
				}
			
			}
		  }
	  
 
	  
	if($mh_post_count == 2){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowFirst== '1' ) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  if($mh_post_count== 3){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowSecond== '1') {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  
	  if($mh_post_count == 4){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowThird== '1') {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  if($mh_post_count == 5){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowFourth== '1') {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  if($mh_post_count == 6){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowFifth== '1') {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }	 


	  if($mh_post_count == (get_option('posts_per_page'))){
	  	if ( $spHomepage == '1' && $spProd1 == '1' && $spHpBelowLast== '1') {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	
	} else {

	/*cat*/
	  if($mh_post_count==1){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spAboveFirst== '1')) {
			
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		
		}
	  }	 	  
	  
	/*cat*/  
	if($mh_post_count == 2){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowFirst== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  /*cat*/
	  if($mh_post_count== 3){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowSecond== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  /*cat*/
	  if($mh_post_count== 4){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowThird== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  /*cat*/
	  if($mh_post_count== 5){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowFourth== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  /*cat*/
	  if($mh_post_count== 6){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowFifth== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	  
	/*cat*/
	if($mh_post_count == (get_option('posts_per_page'))){
	  	if ( (is_category() && $spHomepage == '0' && $spCategory == '1' && $spProd1 == '1' && $spBelowLast== '1')) {
			if ( $ad_code != '' ) {
				echo $ad_code, "\n";
			}
		}							
      }
	  
	 						
	  
	}
	  
	  
      
      }
}



/**/
//Insert ads after Comment section

add_filter( 'comment_form_after', 'sp_insert_ads_after_comments' );

function sp_insert_ads_after_comments( ) {
	
	if ( is_single() ) {
		
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
		
		$spProd1 = get_option('spProd1');
		$spPosts = get_option('spPosts');
			
		$spBelowPost = get_option('spPostsBelowComments');
		$spAlignment = get_option('spPostsBelowCommentsAL');
		$spDimension = get_option('spPostsBelowCommentsSZ');
		
		}


		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}


		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';
		
		$ad_code = '<div style="'.$spSize.$spAlignment.';display:block;">'.stripslashes($curr_adtag).'</div>';

		if ( $spPosts == '1' && $spProd1 == '1' && $spBelowPost== '1') {
				
			echo $ad_code;
		}
	}

}


function sp_add_post_content($content) {
	
	if ( is_single() ) {
		
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
	
		$spProd1 = get_option('spProd1');
		$spPosts = get_option('spPosts');
			
		$spBelowPost = get_option('spPostsBelowPost');
		$spAlignment = get_option('spPostsBelowPostAL');
		$spDimension = get_option('spPostsBelowPostSZ');
		
		}
	}	
	
	if ( is_page() ) {
	
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
		$spProd1 = get_option('spProd1');
		$spPosts = get_option('spPages');
			
		$spBelowPost = get_option('spPagesBelowContent');
		$spAlignment = get_option('spPagesBelowContentAL');
		$spDimension = get_option('spPagesBelowContentSZ');
		}
	}
			


		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}


		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		$ad_code = '<div style="'.$spSize.$spAlignment.';display:block;">'.stripslashes($curr_adtag).'</div>';
		if ( $spPosts == '1' && $spProd1 == '1' && $spBelowPost== '1') {		
			$content .= $ad_code;
		}
	
	return $content;
}
add_filter('the_content', 'sp_add_post_content');



/**FOOTER WRAPPER HOOK**/
function sp_hook_superfooter()
{

	$custom_content = '';
	$SuperFooterStatus = get_option('SuperFooterStatus');
	$SuperLinksStatus = get_option('SuperLinksStatus');
	$SuperTowerStatus = get_option('SuperTowerStatus');
	$SuperInterstitialStatus = get_option('SuperInterstitialStatus');
	$spProd2 = get_option('spProd2');
	$spProd3 = get_option('spProd3');
	$spProd4 = get_option('spProd4');
	$spProd5 = get_option('spProd5');
	
		$currPage = get_the_ID();	
		$spExcludeExit = get_option('$spExcludeExit');
		$spExcludeFooter = get_option('$spExcludeFooter');
		$spExcludeTower = get_option('$spExcludeTower');
		$spExcludeStitial = get_option('$spExcludeStitial');
		
		$spExcludeExit = explode(',',$spExcludeExit);
		$spExcludeFooter = explode(',',$spExcludeFooter);
		$spExcludeTower = explode(',',$spExcludeTower);
		$spExcludeStitial = explode(',',$spExcludeStitial);
		
		if(in_array($currPage, $spExcludeExit)) {
			$SuperLinksStatus = '0';	
		} 		
		if(in_array($currPage, $spExcludeFooter)) {
			$SuperFooterStatus = '0';	
		} 
		if(in_array($currPage, $spExcludeTower)) {
			$SuperTowerStatus = '0';	
		} 
		if(in_array($currPage, $spExcludeStitial)) {
			$SuperInterstitialStatus = '0';	
		} 
		
			
	
	if($SuperLinksStatus == '1' && $spProd2 == '1'){
		$custom_content = get_option('SuperLinksWrap');
		echo stripslashes($custom_content);
	}
	if($SuperFooterStatus == '1'  && $spProd3 == '1'){
		$custom_content = get_option('SuperFooterWrap');
		echo stripslashes($custom_content);
	}	
	if($SuperTowerStatus == '1'  && $spProd4 == '1'){
		$custom_content = get_option('SuperTowerWrap');
		echo stripslashes($custom_content);
	}
	
	if($SuperInterstitialStatus == '1'  && $spProd5 == '1'){
		$stitialCode = get_option('SuperInterstitialWrap');
		$stitialCode = explode('src=',$stitialCode); 	$stitialCode = explode('"',$stitialCode[1]);	
		$stitialCode = $stitialCode[1];
	?>
	
	<script type='text/javascript'>DST('<?=stripslashes($stitialCode)?>'); 
		jQuery( document ).ready(function() {
			//var outerLayer =  jQuery("#sp_top").next().outerWidth(); 
			var outerLayer =  jQuery("div:gt(3)").outerWidth();  
			jQuery('#sp_top').css('width',''+outerLayer+'px');
			jQuery('#sp_top').show();
			
			
			
			jQuery('#sp_footer').css('width',''+outerLayer+'px');
			
			
		});
	</script>
	<?

	} else {
	?>
	
	<script type='text/javascript'>
		jQuery( document ).ready(function() {
			//var outerLayer =  jQuery("#sp_top").next().outerWidth(); 
			var outerLayer =  jQuery("div:gt(3)").outerWidth();  
			jQuery('#sp_top').css('width',''+outerLayer+'px');
			
			jQuery('#sp_footer').css('width',''+outerLayer+'px');
			
			
			
			
		});
	</script>
	<?	
	}

		if(is_front_page()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spHomepage');
		
		$spBelowSite = get_option('spHomepageBelowSite');
		$spDimension = get_option('spHomepageBelowSiteSZ');
		$spAlignment = get_option('spHomepageBelowSiteAL');
		}
		
		if(is_single()){
			$currPage = get_the_ID();	
			$spExclude = get_option('spExcludeDisplay');
			$spExclude = explode(',',$spExclude);

			if(in_array($currPage, $spExclude)) {
			$spHomepage = '0';	
			} else {
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPosts');

			$spBelowSite = get_option('spPostsBelowSite');
			$spDimension = get_option('spPostsBelowSiteSZ');
			$spAlignment = get_option('spPostsBelowSiteAL');
			}
		}
		
		if(is_page()){
			$currPage = get_the_ID();	
			$spExclude = get_option('spExcludeDisplay');
			$spExclude = explode(',',$spExclude);

			if(in_array($currPage, $spExclude)) {
			$spHomepage = '0';	
			} else {	
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPages');

			$spBelowSite = get_option('spPagesBelowSite');
			$spDimension = get_option('spPagesBelowSiteSZ');
			$spAlignment = get_option('spPagesBelowSiteAL');
			}
		}
		
		if(is_search()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spSearches');
		
		$spBelowSite = get_option('spSearchesBelowSite');
		$spDimension = get_option('spSearchesBelowSiteSZ');
		$spAlignment = get_option('spSearchesBelowSiteAL');
		}
		
		if(is_category()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spCategory');
		
		$spBelowSite = get_option('spCategoryBelowSite');
		$spDimension = get_option('spCategoryBelowSiteSZ');
		$spAlignment = get_option('spCategoryBelowSiteAL');
		}
		
		if(is_author()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spAuthor');
		
		$spBelowSite = get_option('spAuthorBelowSite');
		$spDimension = get_option('spAuthorBelowSiteSZ');
		$spAlignment = get_option('spAuthorBelowSiteAL');
		}

		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		
		$ad_code = '<div id="sp_footer" style="margin:0 auto;margin-bottom:10px;"><div style="'.$spSize.$spAlignment.';">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div></div>';		
		
		if ( $spHomepage == '1' && $spProd1 == '1' && $spBelowSite== '1') {
			echo $ad_code;
		}
	
	
}
add_action('wp_footer', 'sp_hook_superfooter');
    

add_filter('wp_head','sp_after_body',11);
function sp_after_body() {
?>
	<script type='text/javascript'>
	function DST(url) 
	{
	var s = document.createElement('script');
	s.type='text/javascript';
	s.src= url;
	document.getElementsByTagName('body')[0].insertBefore(s,document.getElementsByTagName('body')[0].firstChild);
	}
	</script>
<?
}



function sp_hefo_replace($buffer) {
    global $post;

    if (empty($buffer))
        return '';
    for ($i = 1; $i <= 5; $i++) {
        $buffer = str_replace('[snippet_' . $i . ']', $hefo_options['snippet_' . $i], $buffer);
    }

    return $buffer;
}

function sp_hefo_execute($buffer) {
    if (empty($buffer))
        return '';
    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_clean();
    return $buffer;
}


add_action('wp_head', 'sp_hefo_wp_head_post', 1);

function sp_hefo_wp_head_post() {
    global $wp_query, $wpdb;
    $buffer = '';
    		
		if(is_front_page()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spHomepage');
		
		$spAboveSite = get_option('spHomepageAboveSite');
		$spDimension = get_option('spHomepageAboveSiteSZ');
		$spAlignment = get_option('spHomepageAboveSiteAL');
		}
		
		if(is_single()){
			$currPage = get_the_ID();	
			$spExclude = get_option('spExcludeDisplay');
			$spExclude = explode(',',$spExclude);
			
			if(in_array($currPage, $spExclude)) {
				$spHomepage = '0';	
			} else {
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPosts');
			
			$spAboveSite = get_option('spPostsAboveSite');
			$spDimension = get_option('spPostsAboveSiteSZ');
			$spAlignment = get_option('spPostsAboveSiteAL');
			}
		}
		
		if(is_page()){
			$currPage = get_the_ID();	
			
			$spExclude = trim(get_option('spExcludeDisplay'));
			$spExclude = explode(',',$spExclude);
			
			
			if(in_array($currPage, $spExclude)) {
				$spHomepage= '0';	
			} else {
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPages');
			
			$spAboveSite = get_option('spPageAboveSite');
			$spDimension = get_option('spPageAboveSiteSZ');
			$spAlignment = get_option('spPageAboveSiteAL');
			}
		}
		
		if(is_search()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spSearches');
		
		$spAboveSite = get_option('spSearchesAboveSite');
		$spDimension = get_option('spSearchesAboveSiteSZ');
		$spAlignment = get_option('spSearchesAboveSiteAL');
		}
		
		if(is_category()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spCategory');
		
		$spAboveSite = get_option('spCategoryAboveSite');
		$spDimension = get_option('spCategoryAboveSiteSZ');
		$spAlignment = get_option('spCategoryAboveSiteAL');
		}
		
		if(is_author()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spAuthor');
		
		$spAboveSite = get_option('spAuthorAboveSite');
		$spDimension = get_option('spAuthorAboveSiteSZ');
		$spAlignment = get_option('spAuthorAboveSiteAL');
		}

		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		
		$ad_code = '<div id="sp_top" style="margin:0 auto;display:block;"><div style="'.$spSize.$spAlignment.';">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div></div>';		
		
		if ( $spHomepage == '1' && $spProd1 == '1' && $spAboveSite== '1') {
			//echo $ad_code;
			$buffer .= $ad_code;
	
		}
	
	
	
	
	
        

    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
}


function spcallback($buffer) {
	
		if(is_front_page()){
			$spStr = 'spHomepage';
			$spHomepage = get_option($spStr);
		}elseif(is_single()){ $spStr = 'spPosts'; 
			$spHomepage = get_option($spStr);
		}elseif(is_page()){ $spStr = 'spPages'; 
			$spHomepage = get_option($spStr);
		}elseif(is_author()){ $spStr = 'spAuthor';
			$spHomepage = get_option($spStr);
		}elseif(is_category()){ $spStr = 'spCategory';
			$spHomepage = get_option($spStr);
		}elseif(is_search() || isset($_GET['s'])){ $spStr = 'spSearches';
			$spHomepage = get_option($spStr);
		}
							
									
		
	
		$spProd1 = get_option('spProd1');
		
		$spAboveMenu = get_option($spStr.'AboveMenu');
		$spDimension = get_option($spStr.'AboveMenuSZ');
		$spAlignment = get_option($spStr.'AboveMenuAL');
		

		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		
		$ad_code = '<div class="aboveMenu" style="margin:0 auto;margin-bottom:10px;display:block;"><div style="'.$spSize.$spAlignment.'">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div></div>';		
		
		if ( $spHomepage == '1' && $spProd1 == '1' && $spAboveMenu== '1') {
			
			$one = array("<div id=\"navigation\"","<div id=\"header-nav\"","<div id=\"nav\"","<ul id=\"header-nav\"","<ul id=\"nav\"","<nav class=\"nav-header\"");
			$two   = array($ad_code."<div id=\"navigation\"",$ad_code."<div id=\"header-nav\"",$ad_code."<div id=\"nav\"",$ad_code."<ul id=\"header-nav\"",$ad_code."<ul id=\"nav\"",$ad_code."<nav class=\"nav-header\"");
		  
		  	$buffer =  (str_replace($one,$two,$buffer));
	
		}
		
	
	/**/
	if(is_single()){
		
		
		
		$currPage = get_the_ID();	
		$spExclude = get_option('spExcludeDisplay');
		$spExclude = explode(',',$spExclude);
		
		if(in_array($currPage, $spExclude)) {
			$spPosts = '0';	
		} else {
		$spProd1 = get_option('spProd1');
		$spPosts = get_option('spPosts');
		
		$spAboveComment = get_option('spPostsAboveComments');
		$spAlignment = get_option('spPostsAboveCommentsAL');
		$spDimension = get_option('spPostsAboveCommentsSZ');
		}

		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		
		$ad_code = '<div class="aboveMenu" style="margin:0 auto;margin-bottom:10px;display:block;"><div style="'.$spSize.$spAlignment.'">'.stripslashes($curr_adtag).'</div><div style="clear:both"></div></div>';		
		
		if ( $spPosts == '1' && $spProd1 == '1' && $spAboveComment== '1') {
			
			$one = array("<ol class=\"commentlist\"","<ul class=\"commentlist\"");
			$two   = array($ad_code."<ol class=\"commentlist\"",$ad_code."<ul class=\"commentlist\"");
		  
		  	return  (str_replace($one,$two,$buffer));
		
		}else {
			return $buffer;
		}
	
	}else {
			return $buffer;
		}
  
}

function spbuffer_start() { 
	
		ob_start("spcallback"); 
	
}

function spbuffer_end() { 
	
		ob_end_flush(); 
	
}

add_action('wp_head', 'spbuffer_start');
add_action('wp_footer', 'spbuffer_end');

	
	
	
function sp_pw_load_scripts() {

		if(is_front_page()){
	
		$spProd1 = get_option('spProd1');
		$spHomepage = get_option('spHomepage');
		
		$spBelowMenu = get_option('spHomepageBelowMenu');
		$spDimension = get_option('spHomepageBelowMenuSZ');
		$spAlignment = get_option('spHomepageBelowMenuAL');
		}
		
		if(is_single()){
			$currPage = get_the_ID();	
			$spExclude = get_option('spExcludeDisplay');
			$spExclude = explode(',',$spExclude);
			
			if(in_array($currPage, $spExclude)) {
				$spHomepage = '0';	
			} else {
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPosts');
			
			$spBelowMenu = get_option('spPostsBelowMenu');
			$spDimension = get_option('spPostsBelowMenuSZ');
			$spAlignment = get_option('spPostsBelowMenuAL');
			}
		}
		
		if(is_page()){
			$currPage = get_the_ID();	
			
			$spExclude = trim(get_option('spExcludeDisplay'));
			$spExclude = explode(',',$spExclude);
			
			
			if(in_array($currPage, $spExclude)) {
				$spHomepage= '0';	
			} else {
			$spProd1 = get_option('spProd1');
			$spHomepage = get_option('spPages');
			
			$spBelowMenu = get_option('spPagesBelowMenu');
			$spDimension = get_option('spPagesBelowMenuSZ');
			$spAlignment = get_option('spPagesBelowMenuAL');
			}
		}

		if($spAlignment == 'left') { $spAlignment = 'float:left;'; }
		if($spAlignment == 'center') { $spAlignment = 'margin:0 auto;'; }
		if($spAlignment == 'right') { $spAlignment = 'float:right;'; }	
						
		
		$DisplayAdsCount = get_option('SuperDisplayCount');
		for($i=1;$i<=($DisplayAdsCount);$i++){
			if($spDimension == get_option('SuperDisplayWrapSize'.$i)){
				$curr_adtag = get_option('SuperDisplayWrap'.$i);
			}
		}

		$spSize = explode('x',$spDimension);
		$spSize = 'width:'.$spSize[0].'px;height:'.$spSize[1].'px;';

		
		if ( $spHomepage == '1' && $spProd1 == '1' && $spBelowMenu== '1') {
		}
 wp_enqueue_script('pw-script', plugin_dir_url( __FILE__ ) . 'js/sp-script.js');
}
add_action('wp_enqueue_scripts', 'sp_pw_load_scripts');	
	
?>