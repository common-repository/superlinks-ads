<?php 
require('functions.inc.php');
if($_GET['logout']=='1'){
update_option('sp_username', '');
update_option('sp_username', '');
update_option('sp_userid', '');
}
		 
if($_POST['sp_hidden'] == 'Y') {
//		echo 'TEST';

		 update_option('sp_username', $_POST['sp_username']);
		 update_option('sp_passwd', md5($_POST['sp_passwd']));
		$data = 'http://api.superlinks.com/api/sites/listApproved?user='.$_POST['sp_username'].'&password='.md5($_POST['sp_passwd']);
		$infoArr = json_decode(get_data($data));
		
		try{
			$item_count = count($infoArr->items);
		} catch (Exception $e) { 
			$item_count=0;
		}	
		
		if($item_count>0){
			$spUserId =  stripHttp($infoArr->items[0]->user_id,true);
			update_option('sp_userid', $spUserId);
			
			$n = 1;
		}
		
		$spHomepage = '1';
		$spPages = '1';
		$spCategory = '1';
		$spSearches = '1';
		$spPosts = '1';
		$spAuthor = '1';
		
		
		update_option('spHomepage', '1');		
		update_option('spPages', '1');		
		update_option('spSearches', '1');		
		update_option('spCategory', '1');		
		update_option('spPosts', '1');		
		update_option('spAuthor', '1');		
		
		

?>
<script type="text/javascript">
<!--
window.location = "admin.php?page=superlinks-ads/superlinks-plugin.php";
//-->
</script>
<?		
		 
    } else {
        //Normal
		
		/* preg_match("/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/", parse_url("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], PHP_URL_HOST), $_currDomain);
		$_currDomain =  $_currDomain[0]; */
		
		$_currDomain = $_SERVER['HTTP_HOST'];
		if (strpos($_currDomain,'www.') !== false) {
			$_currDomain = explode("www.",$_currDomain);
			$_currDomain = $_currDomain[1];
		}	

		$sp_username = get_option('sp_username');
		$sp_passwd = get_option('sp_passwd');
		$sp_userid = get_option('sp_userid');
		
		$data = 'http://api.superlinks.com/api/sites/listApproved?user='.$sp_username.'&password='.$sp_passwd;
		$infoArr = json_decode(get_data($data));
		
		try{
			$item_count = count($infoArr->items);
		} catch (Exception $e) { 
			$item_count=0;
		}	
		
		if($item_count>0){
			
		 foreach($infoArr->items as $sp){
			 $spSite =  stripHttp($sp->name,true);
			 //$spSite = extractTLD( $spSite );
			 

			 
			 if(strpos( $_currDomain, $spSite) !== false){
			 	$spSiteId =  $sp->id;
				$spSiteStatus =  $sp->our_status;
				$spSiteGStatus =  $sp->google_status;
					
			/*
 			 if(strpos( $domain1, $spSite) !== false){*/
					//echo "Yes this is indeed the ".$spSite." domain";
			} else {
				$DomainNotApproved = 'Domain not approved';
				//echo $DomainNotApproved.' ';
			}
		  }
		}//
		
		
		$data = 'http://api.superlinks.com/api/default/getStats?user='.$sp_username.'&password='.$sp_passwd;
		$statsArr = json_decode(get_data($data));
		
		try{
			$item_count = count($statsArr->items);
		} catch (Exception $e) { 
			$item_count=0;
		}
		
		$monthly_earning = $statsArr->items[0]->earnings->monthly;
		$total_earning = $statsArr->items[0]->earnings->total;
	

		$data = 'http://api.superlinks.com/api/campaigns/listBySiteId?site_id='.$spSiteId.'&user='.$sp_username.'&password='.$sp_passwd;
		$campArr = json_decode(get_data($data));
		
		try{
			$camp_count = count($campArr->items);
		} catch (Exception $e) { 
			$camp_count=0;
		}	
		
		if($camp_count>0){
			$i=0;
			$d = 1;
		 foreach($campArr->items as $item){
			 
		  	  $myCampaigns[$i]['site_id'] = $item->site_id;
		  	  $myCampaigns[$i]['productype'] = $item->product_type;
		  	  $myCampaigns[$i]['name'] = $item->name;
		  	  $myCampaigns[$i]['status'] = $item->our_status;
		  	  $myCampaigns[$i]['banner_size'] = $item->banner_size;	  				  	  
			  $myCampaigns[$i]['wrapper_tags'] = addslashes($item->wrapper_tags);
			  
			  
			  if($item->product_type == 'Super Display Ads'){ 
			  		$superdisplayApproved = '1'; 
					update_option('SuperDisplaystatus', '1');		
					update_option('SuperDisplayWrapSize'.$d,  addslashes($item->banner_size));
					$DisplayBannerSize[$d] = $item->banner_size;
					
					update_option('SuperDisplayWrap'.$d,  addslashes($item->wrapper_tags));		
					update_option('SuperDisplayCount', $d);		
					$d++;
			  }
			  if($item->product_type == 'Super Footer'){ 
			  		$superfooterApproved = '1'; 
					update_option('SuperFooterStatus', '1');		
					update_option('SuperFooterWrap', addslashes($item->wrapper_tags));		
			  }
			  if($item->product_type == 'Super Tower'){ 
			  		$supertowerApproved = '1'; 
					update_option('SuperTowerStatus', '1');		
					update_option('SuperTowerWrap', addslashes($item->wrapper_tags));		
			  }
			  if($item->product_type == 'Super Links'){ 
			  		$superlinksApproved = '1'; 
					update_option('SuperLinksStatus', '1');		
					update_option('SuperLinksWrap', addslashes($item->wrapper_tags));		
			  }
			  if($item->product_type == 'Super Stitial'){ 
			  		$superinterstitialApproved = '1'; 
					update_option('SuperInterstitialStatus', '1');		
					update_option('SuperInterstitialWrap', addslashes($item->wrapper_tags));		
			  }
			  /*$superlinksApproved = '1'; 
			  update_option('$superlinksApproved', '1');		*/
			  
			  
			  $i++;
		 }
		}





		$spWebsites = get_option('spWebsites');
		$spProd1 = get_option('spProd1');
		$spProd2 = get_option('spProd2');
		$spProd3 = get_option('spProd3');
		$spProd4 = get_option('spProd4');
		$spProd5 = get_option('spProd5');		
		
		$spHomepage = get_option('spHomepage');
		$spPages = get_option('spPages');
		$spCategory = get_option('spCategory');
		$spSearches = get_option('spSearches');
		$spPosts = get_option('spPosts');
		$spAuthor = get_option('spAuthor');
		$spComments = get_option('spComments');
		$spExcludeDisplay  = get_option('spExcludeDisplay');
		$spExcludeExit  = get_option('spExcludeExit');
		$spExcludeFooter  = get_option('spExcludeFooter');
		$spExcludeFooter  = get_option('spExcludeTower');
		$spExcludeStitial  = get_option('spExcludeStitial');
		
		$spRefer = get_option('spRefer');
		
		/** HomePages **/
		$spHomepageAboveSite = get_option('spHomepageAboveSite');
		$spHomepageAboveSiteSZ = get_option('spHomepageAboveSiteSZ');
		$spHomepageAboveSiteAL = get_option('spHomepageAboveSiteAL');
		
		$spHomepageBelowSite = get_option('spHomepageBelowSite');
		$spHomepageBelowSiteSZ = get_option('spHomepageBelowSiteSZ');
		$spHomepageBelowSiteAL = get_option('spHomepageBelowSiteAL');
		
		$spHomepageAboveMenu = get_option('spHomepageAboveMenu');
		$spHomepageAboveMenuSZ = get_option('spHomepageAboveMenuSZ');
		$spHomepageAboveMenuAL = get_option('spHomepageAboveMenuAL');
		
		$spHomepageBelowMenu = get_option('spHomepageBelowMenu');
		$spHomepageBelowMenuSZ = get_option('spHomepageBelowMenuSZ');
		$spHomepageBelowMenuAL = get_option('spHomepageBelowMenuAL');		
	
		
		$spHomepageAboveFirstPost = get_option('spHomepageAboveFirstPost');
		$spHomepageAboveFirstPostSZ = get_option('spHomepageAboveFirstPostSZ');
		$spHomepageAboveFirstPostAL = get_option('spHomepageAboveFirstPostAL');
		
		$spHomepageBelowFirstPost = get_option('spHomepageBelowFirstPost');
		$spHomepageBelowFirstPostSZ = get_option('spHomepageBelowFirstPostSZ');
		$spHomepageBelowFirstPostAL = get_option('spHomepageBelowFirstPostAL');				
		
		$spHomepageBelowSecondPost = get_option('spHomepageBelowSecondPost');
		$spHomepageBelowSecondPostSZ = get_option('spHomepageBelowSecondPostSZ');
		$spHomepageBelowSecondPostAL = get_option('spHomepageBelowSecondPostAL');

		$spHomepageBelowThirdPost = get_option('spHomepageBelowThirdPost');
		$spHomepageBelowThirdPostSZ = get_option('spHomepageBelowThirdPostSZ');
		$spHomepageBelowThirdPostAL = get_option('spHomepageBelowThirdPostAL');		
		
		$spHomepageBelowFourthPost = get_option('spHomepageBelowFourthPost');
		$spHomepageBelowFourthPostSZ = get_option('spHomepageBelowFourthPostSZ');
		$spHomepageBelowFourthPostAL = get_option('spHomepageBelowFourthPostAL');				
		
		$spHomepageBelowFifthPost = get_option('spHomepageBelowFifthPost');
		$spHomepageBelowFifthPostSZ = get_option('spHomepageBelowFifthPostSZ');
		$spHomepageBelowFifthPostAL = get_option('spHomepageBelowFifthPostAL');						
		
		$spHomepageBelowLastPost = get_option('spHomepageBelowLastPost');
		$spHomepageBelowLastPostSZ = get_option('spHomepageBelowLastPostSZ');
		$spHomepageBelowLastPostAL = get_option('spHomepageBelowLastPostAL');		


		/** PAGES **/
		$spPageAboveSite = get_option('spPageAboveSite');
		$spPageAboveSiteSZ = get_option('spPageAboveSiteSZ');
		$spPageAboveSiteAL = get_option('spPageAboveSiteAL');
		
		$spPagesBelowSite = get_option('spPagesBelowSite');
		$spPagesBelowSiteSZ = get_option('spPagesBelowSiteSZ');
		$spPagesBelowSiteAL = get_option('spPagesBelowSiteAL');
		
		$spPagesAboveMenu = get_option('spPagesAboveMenu');
		$spPagesAboveMenuSZ = get_option('spPagesAboveMenuSZ');
		$spPagesAboveMenuAL = get_option('spPagesAboveMenuAL');
		
		$spPagesAboveContent = get_option('spPagesAboveContent');
		$spPagesAboveContentSZ = get_option('spPagesAboveContentSZ');
		$spPagesAboveContentAL = get_option('spPagesAboveContentAL');
		
		$spPagesBelowContent = get_option('spPagesBelowContent');
		$spPagesBelowContentSZ = get_option('spPagesBelowContentSZ');
		$spPagesBelowContentAL = get_option('spPagesBelowContentAL');	
		
		$spPagesWithinContent = get_option('spPagesWithinContent');
		$spPagesWithinContentSZ = get_option('spPagesWithinContentSZ');
		$spPagesWithinContentAL = get_option('spPagesWithinContentAL');	
		
			
		
		/** POSTS **/
		
		$spPostsAboveSite = get_option('spPostsAboveSite');
		$spPostsAboveSiteSZ = get_option('spPostsAboveSiteSZ');
		$spPostsAboveSiteAL = get_option('spPostsAboveSiteAL');
		
		$spPostsBelowSite = get_option('spPostsBelowSite');
		$spPostsBelowSiteSZ = get_option('spPostsBelowSiteSZ');
		$spPostsBelowSiteAL = get_option('spPostsBelowSiteAL');
		
		$spPostsAboveMenu = get_option('spPostsAboveMenu');
		$spPostsAboveMenuSZ = get_option('spPostsAboveMenuSZ');
		$spPostsAboveMenuAL = get_option('spPostsAboveMenuAL');
		
		$spPostsAbovePost = get_option('spPostsAbovePost');
		$spPostsAbovePostSZ = get_option('spPostsAbovePostSZ');
		$spPostsAbovePostAL = get_option('spPostsAbovePostAL');	
		
		$spPostsBelowPost = get_option('spPostsBelowPost');
		$spPostsBelowPostSZ = get_option('spPostsBelowPostSZ');
		$spPostsBelowPostAL = get_option('spPostsBelowPostAL');			
		
		$spPostsAboveTitle = get_option('spPostsAboveTitle');
		$spPostsAboveTitleSZ = get_option('spPostsAboveTitleSZ');
		$spPostsAboveTitleAL = get_option('spPostsAboveTitleAL');				

		$spPostsBelowTitle = get_option('spPostsBelowTitle');
		$spPostsBelowTitleSZ = get_option('spPostsBelowTitleSZ');
		$spPostsBelowTitleAL = get_option('spPostsBelowTitleAL');						
				
		$spPostsAboveComments = get_option('spPostsAboveComments');
		$spPostsAboveCommentsSZ = get_option('spPostsAboveCommentsSZ');
		$spPostsAboveCommentsAL = get_option('spPostsAboveCommentsAL');	
		
		$spPostsBelowComments = get_option('spPostsBelowComments');
		$spPostsBelowCommentsSZ = get_option('spPostsBelowCommentsSZ');
		$spPostsBelowCommentsAL = get_option('spPostsBelowCommentsAL');			
		
		$spPostsWithinContent = get_option('spPostsWithinContent');
		$spPostsWithinContentSZ = get_option('spPostsWithinContentSZ');
		$spPostsWithinContentAL = get_option('spPostsWithinContentAL');
		
		/** SEARCH **/
		$spStr = 'spSearches';
		$spSearchesAboveSite = get_option($spStr.'AboveSite');
		$spSearchesAboveSiteSZ = get_option($spStr.'AboveSiteSZ');
		$spSearchesAboveSiteAL = get_option($spStr.'AboveSiteAL');	
		
		$spSearchesBelowSite = get_option($spStr.'BelowSite');
		$spSearchesBelowSiteSZ = get_option($spStr.'BelowSite'.'SZ');
		$spSearchesBelowSiteAL = get_option($spStr.'BelowSite'.'AL');

		$spSearchesAboveMenu = get_option($spStr.'AboveMenu');
		$spSearchesAboveMenuSZ = get_option($spStr.'AboveMenu'.'SZ');
		$spSearchesAboveMenuAL = get_option($spStr.'AboveMenu'.'AL');			
		
		$spSearchesAboveContent = get_option('spSearchesAboveContent');
		$spSearchesAboveContentSZ = get_option('spSearchesAboveContentSZ');
		$spSearchesAboveContentAL = get_option('spSearchesAboveContentAL');		
		
		$spSearchesBelowContent = get_option('spSearchesBelowContent');
		$spSearchesBelowContentSZ = get_option('spSearchesBelowContentSZ');
		$spSearchesBelowContentAL = get_option('spSearchesBelowContentAL');				
		
		/** CATEGORY **/
		$spStr = 'spCategory';
		$spCategoryAboveSite = get_option($spStr.'AboveSite');
		$spCategoryAboveSiteSZ = get_option($spStr.'AboveSite'.'SZ');
		$spCategoryAboveSiteAL = get_option($spStr.'AboveSite'.'AL');	
		
		$spCategoryBelowSite = get_option($spStr.'BelowSite');
		$spCategoryBelowSiteSZ = get_option($spStr.'BelowSite'.'SZ');
		$spCategoryBelowSiteAL = get_option($spStr.'BelowSite'.'AL');

		$spCategoryAboveMenu = get_option($spStr.'AboveMenu');
		$spCategoryAboveMenuSZ = get_option($spStr.'AboveMenu'.'SZ');
		$spCategoryAboveMenuAL = get_option($spStr.'AboveMenu'.'AL');
		
		$spCategoryAboveContent = get_option('spCategoryAboveContent');
		$spCategoryAboveContentSZ = get_option('spCategoryAboveContentSZ');
		$spCategoryAboveContentAL = get_option('spCategoryAboveContentAL');				
		
		$spCategoryBelowContent = get_option('spCategoryBelowContent');
		$spCategoryBelowContentSZ = get_option('spCategoryBelowContentSZ');
		$spCategoryBelowContentAL = get_option('spCategoryBelowContentAL');				
		
		$spCategoryAboveFpost = get_option('spCategoryAboveFpost');
		$spCategoryAboveFpostSZ = get_option('spCategoryAboveFpostSZ');
		$spCategoryAboveFpostAL = get_option('spCategoryAboveFpostAL');						
		
		$spCategoryBelowFpost = get_option('spCategoryBelowFpost');
		$spCategoryBelowFpostSZ = get_option('spCategoryBelowFpostSZ');
		$spCategoryBelowFpostAL = get_option('spCategoryBelowFpostAL');						
		
		$spCategoryBelowSpost = get_option('spCategoryBelowSpost');
		$spCategoryBelowSpostSZ = get_option('spCategoryBelowSpostSZ');
		$spCategoryBelowSpostAL = get_option('spCategoryBelowSpostAL');	

		$spCategoryBelowTpost = get_option('spCategoryBelowTpost');
		$spCategoryBelowTpostSZ = get_option('spCategoryBelowTpostSZ');
		$spCategoryBelowTpostAL = get_option('spCategoryBelowTpostAL');

		$spCategoryBelowFrpost = get_option('spCategoryBelowFrpost');
		$spCategoryBelowFrpostSZ = get_option('spCategoryBelowFrpostSZ');
		$spCategoryBelowFrpostAL = get_option('spCategoryBelowFrpostAL');	

		$spCategoryBelowFvpost = get_option('spCategoryBelowFvpost');
		$spCategoryBelowFvpostSZ = get_option('spCategoryBelowFvpostSZ');
		$spCategoryBelowFvpostAL = get_option('spCategoryBelowFvpostAL');			
		
		$spCategoryBelowLpost = get_option('spCategoryBelowLpost');
		$spCategoryBelowLpostSZ = get_option('spCategoryBelowLpostSZ');
		$spCategoryBelowLpostAL = get_option('spCategoryBelowLpostAL');		

		/** AUTHOR **/
		$spStr = 'spAuthor';
		$spAuthorAboveSite = get_option($spStr.'AboveSite');
		$spAuthorAboveSiteSZ = get_option($spStr.'AboveSite'.'SZ');
		$spAuthorAboveSiteAL = get_option($spStr.'AboveSite'.'AL');	
		
		$spAuthorBelowSite = get_option($spStr.'BelowSite');
		$spAuthorBelowSiteSZ = get_option($spStr.'BelowSite'.'SZ');
		$spAuthorBelowSiteAL = get_option($spStr.'BelowSite'.'AL');

		$spAuthorAboveMenu = get_option($spStr.'AboveMenu');
		$spAuthorAboveMenuSZ = get_option($spStr.'AboveMenu'.'SZ');
		$spAuthorAboveMenuAL = get_option($spStr.'AboveMenu'.'AL');
		
		$spAuthorAboveContent = get_option('spAuthorAboveContent');
		$spAuthorAboveContentSZ = get_option('spAuthorAboveContentSZ');
		$spAuthorAboveContentAL = get_option('spAuthorAboveContentAL');	
		
		$spAuthorBelowContent = get_option('spAuthorBelowContent');
		$spAuthorBelowContentSZ = get_option('spAuthorBelowContentSZ');
		$spAuthorBelowContentAL = get_option('spAuthorBelowContentAL');	
		
		
		
		//echo $spHomepageAboveSite. ' b:'. $spHomepageAboveSiteAL. ' c:'. $spHomepageAboveSiteSZ;
		
		if($sp_userid != '') { $n=1; }
		
    }
	
	if($_POST['spOptionHidden']=='Y'){
	
		update_option('spRefer', $_POST['spRefer']);
		
		update_option('spWebsites', $_POST['spWebsites']);		
		update_option('spProd1', $_POST['spProd1']);		
		update_option('spProd2', $_POST['spProd2']);		
		update_option('spProd3', $_POST['spProd3']);		
		update_option('spProd4', $_POST['spProd4']);
		update_option('spProd5', $_POST['spProd5']);		
		
		update_option('spHomepage', $_POST['spHomepage']);		
		update_option('spPages', $_POST['spPages']);						
		update_option('spSearches', $_POST['spSearches']);
		update_option('spPosts', $_POST['spPosts']);		
		update_option('spAuthor', $_POST['spAuthor']);				
		update_option('spComments', $_POST['spComments']);						
		update_option('spExcludeDisplay', $_POST['spExcludeDisplay']);
		update_option('spExcludeExit', $_POST['spExcludeExit']);
		update_option('spExcludeFooter', $_POST['spExcludeFooter']);
		update_option('spExcludeTower', $_POST['spExcludeTower']);
		update_option('spExcludeStitial', $_POST['spExcludeStitial']);
		
		
		update_option('spHomepageAboveSite', $_POST['spHomepageAboveSite']);
		update_option('spHomepageAboveSiteAL', $_POST['spHomepageAboveSiteAL']);
		update_option('spHomepageAboveSiteSZ', $_POST['spHomepageAboveSiteSZ']);
		
		update_option('spHomepageBelowSite', $_POST['spHomepageBelowSite']);
		update_option('spHomepageBelowSiteSZ', $_POST['spHomepageBelowSiteSZ']);
		update_option('spHomepageBelowSiteAL', $_POST['spHomepageBelowSiteAL']);	
		
		update_option('spHomepageAboveMenu', $_POST['spHomepageAboveMenu']);
		update_option('spHomepageAboveMenuSZ', $_POST['spHomepageAboveMenuSZ']);
		update_option('spHomepageAboveMenuAL', $_POST['spHomepageAboveMenuAL']);	
		
		update_option('spHomepageBelowMenu', $_POST['spHomepageBelowMenu']);
		update_option('spHomepageBelowMenuSZ', $_POST['spHomepageBelowMenuSZ']);
		update_option('spHomepageBelowMenuAL', $_POST['spHomepageBelowMenuAL']);	

		
		update_option('spHomepageAboveFirstPost', $_POST['spHomepageAboveFirstPost']);
		update_option('spHomepageAboveFirstPostSZ', $_POST['spHomepageAboveFirstPostSZ']);
		update_option('spHomepageAboveFirstPostAL', $_POST['spHomepageAboveFirstPostAL']);	
		
		update_option('spHomepageBelowFirstPost', $_POST['spHomepageBelowFirstPost']);
		update_option('spHomepageBelowFirstPostSZ', $_POST['spHomepageBelowFirstPostSZ']);
		update_option('spHomepageBelowFirstPostAL', $_POST['spHomepageBelowFirstPostAL']);	

		update_option('spHomepageBelowSecondPost', $_POST['spHomepageBelowSecondPost']);
		update_option('spHomepageBelowSecondPostSZ', $_POST['spHomepageBelowSecondPostSZ']);
		update_option('spHomepageBelowSecondPostAL', $_POST['spHomepageBelowSecondPostAL']);	

		update_option('spHomepageBelowThirdPost', $_POST['spHomepageBelowThirdPost']);
		update_option('spHomepageBelowThirdPostSZ', $_POST['spHomepageBelowThirdPostSZ']);
		update_option('spHomepageBelowThirdPostAL', $_POST['spHomepageBelowThirdPostAL']);	
		
		update_option('spHomepageBelowFourthPost', $_POST['spHomepageBelowFourthPost']);
		update_option('spHomepageBelowFourthPostSZ', $_POST['spHomepageBelowFourthPostSZ']);
		update_option('spHomepageBelowFourthPostAL', $_POST['spHomepageBelowFourthPostAL']);	

		update_option('spHomepageBelowFifthPost', $_POST['spHomepageBelowFifthPost']);
		update_option('spHomepageBelowFifthPostSZ', $_POST['spHomepageBelowFifthPostSZ']);
		update_option('spHomepageBelowFifthPostAL', $_POST['spHomepageBelowFifthPostAL']);			
		
		update_option('spHomepageBelowLastPost', $_POST['spHomepageBelowLastPost']);
		update_option('spHomepageBelowLastPostSZ', $_POST['spHomepageBelowLastPostSZ']);
		update_option('spHomepageBelowLastPostAL', $_POST['spHomepageBelowLastPostAL']);			
		
		/** PAGES **/
		update_option('spPageAboveSite', $_POST['spPageAboveSite']);
		update_option('spPageAboveSiteSZ', $_POST['spPageAboveSiteSZ']);
		update_option('spPageAboveSiteAL', $_POST['spPageAboveSiteAL']);
		
		update_option('spPagesBelowSite', $_POST['spPagesBelowSite']);
		update_option('spPagesBelowSiteSZ', $_POST['spPagesBelowSiteSZ']);
		update_option('spPagesBelowSiteAL', $_POST['spPagesBelowSiteAL']);

		$spStr = 'spPages';
		update_option($spStr.'AboveMenu', $_POST[$spStr.'AboveMenu']);
		update_option($spStr.'AboveMenu'.'SZ', $_POST[$spStr.'AboveMenu'.'SZ']);
		update_option($spStr.'AboveMenu'.'AL', $_POST[$spStr.'AboveMenu'.'AL']);		
		
		update_option('spPagesAboveContent', $_POST['spPagesAboveContent']);
		update_option('spPagesAboveContentSZ', $_POST['spPagesAboveContentSZ']);
		update_option('spPagesAboveContentAL', $_POST['spPagesAboveContentAL']);		
		
		update_option('spPagesBelowContent', $_POST['spPagesBelowContent']);
		update_option('spPagesBelowContentSZ', $_POST['spPagesBelowContentSZ']);
		update_option('spPagesBelowContentAL', $_POST['spPagesBelowContentAL']);	
		
		update_option('spPagesWithinContent', $_POST['spPagesWithinContent']);
		update_option('spPagesWithinContentSZ', $_POST['spPagesWithinContentSZ']);
		update_option('spPagesWithinContentAL', $_POST['spPagesWithinContentAL']);	


		
		/** POSTS **/
		update_option('spPostsAboveSite', $_POST['spPostsAboveSite']);
		update_option('spPostsAboveSiteSZ', $_POST['spPostsAboveSiteSZ']);
		update_option('spPostsAboveSiteAL', $_POST['spPostsAboveSiteAL']);
		
		update_option('spPostsBelowSite', $_POST['spPostsBelowSite']);
		update_option('spPostsBelowSiteSZ', $_POST['spPostsBelowSiteSZ']);
		update_option('spPostsBelowSiteAL', $_POST['spPostsBelowSiteAL']);
		
		$spStr = 'spPosts';
		update_option($spStr.'AboveMenu', $_POST[$spStr.'AboveMenu']);
		update_option($spStr.'AboveMenu'.'SZ', $_POST[$spStr.'AboveMenu'.'SZ']);
		update_option($spStr.'AboveMenu'.'AL', $_POST[$spStr.'AboveMenu'.'AL']);
		
		update_option('spPostsAbovePost', $_POST['spPostsAbovePost']);
		update_option('spPostsAbovePostSZ', $_POST['spPostsAbovePostSZ']);
		update_option('spPostsAbovePostAL', $_POST['spPostsAbovePostAL']);
		
		
		update_option('spPostsBelowPost', $_POST['spPostsBelowPost']);
		update_option('spPostsBelowPostSZ', $_POST['spPostsBelowPostSZ']);
		update_option('spPostsBelowPostAL', $_POST['spPostsBelowPostAL']);		
		
		
		update_option('spPostsAboveTitle', $_POST['spPostsAboveTitle']);
		update_option('spPostsAboveTitleSZ', $_POST['spPostsAboveTitleSZ']);
		update_option('spPostsAboveTitleAL', $_POST['spPostsAboveTitleAL']);
		

		update_option('spPostsBelowTitle', $_POST['spPostsBelowTitle']);
		update_option('spPostsBelowTitleSZ', $_POST['spPostsBelowTitleSZ']);
		update_option('spPostsBelowTitleAL', $_POST['spPostsBelowTitleAL']);			
				
		update_option('spPostsAboveComments', $_POST['spPostsAboveComments']);
		update_option('spPostsAboveCommentsSZ', $_POST['spPostsAboveCommentsSZ']);
		update_option('spPostsAboveCommentsAL', $_POST['spPostsAboveCommentsAL']);		
		
		update_option('spPostsBelowComments', $_POST['spPostsBelowComments']);
		update_option('spPostsBelowCommentsSZ', $_POST['spPostsBelowCommentsSZ']);
		update_option('spPostsBelowCommentsAL', $_POST['spPostsBelowCommentsAL']);
		
		update_option('spPostsWithinContent', $_POST['spPostsWithinContent']);
		update_option('spPostsWithinContentAL', $_POST['spPostsWithinContentAL']);
		update_option('spPostsWithinContentSZ', $_POST['spPostsWithinContentSZ']);	

		/** SEARCH **/
		$spStr = 'spSearches';
		update_option($spStr.'AboveSite', $_POST[$spStr.'AboveSite']);
		update_option($spStr.'AboveSite'.'SZ', $_POST[$spStr.'AboveSite'.'SZ']);
		update_option($spStr.'AboveSite'.'AL', $_POST[$spStr.'AboveSite'.'AL']);
		
		update_option($spStr.'BelowSite', $_POST[$spStr.'BelowSite']);
		update_option($spStr.'BelowSite'.'SZ', $_POST[$spStr.'BelowSite'.'SZ']);
		update_option($spStr.'BelowSite'.'AL', $_POST[$spStr.'BelowSite'.'AL']);
		
		update_option($spStr.'AboveMenu', $_POST[$spStr.'AboveMenu']);
		update_option($spStr.'AboveMenu'.'SZ', $_POST[$spStr.'AboveMenu'.'SZ']);
		update_option($spStr.'AboveMenu'.'AL', $_POST[$spStr.'AboveMenu'.'AL']);
		
		update_option('spSearchesAboveContent', $_POST['spSearchesAboveContent']);
		update_option('spSearchesAboveContentSZ', $_POST['spSearchesAboveContentSZ']);
		update_option('spSearchesAboveContentAL', $_POST['spSearchesAboveContentAL']);	
		
		update_option('spSearchesBelowContent', $_POST['spSearchesBelowContent']);
		update_option('spSearchesBelowContentSZ', $_POST['spSearchesBelowContentSZ']);
		update_option('spSearchesBelowContentAL', $_POST['spSearchesBelowContentAL']);	
		
		/** CATEGORY **/
		$spStr = 'spCategory';
		update_option($spStr.'AboveSite', $_POST[$spStr.'AboveSite']);
		update_option($spStr.'AboveSite'.'SZ', $_POST[$spStr.'AboveSite'.'SZ']);
		update_option($spStr.'AboveSite'.'AL', $_POST[$spStr.'AboveSite'.'AL']);
		
		update_option($spStr.'BelowSite', $_POST[$spStr.'BelowSite']);
		update_option($spStr.'BelowSite'.'SZ', $_POST[$spStr.'BelowSite'.'SZ']);
		update_option($spStr.'BelowSite'.'AL', $_POST[$spStr.'BelowSite'.'AL']);
		
		update_option($spStr.'AboveMenu', $_POST[$spStr.'AboveMenu']);
		update_option($spStr.'AboveMenu'.'SZ', $_POST[$spStr.'AboveMenu'.'SZ']);
		update_option($spStr.'AboveMenu'.'AL', $_POST[$spStr.'AboveMenu'.'AL']);
		
		update_option('spCategoryAboveContent', $_POST['spCategoryAboveContent']);
		update_option('spCategoryAboveContentSZ', $_POST['spCategoryAboveContentSZ']);
		update_option('spCategoryAboveContentAL', $_POST['spCategoryAboveContentAL']);	
		
		update_option('spCategoryBelowContent', $_POST['spCategoryBelowContent']);
		update_option('spCategoryBelowContentSZ', $_POST['spCategoryBelowContentSZ']);
		update_option('spCategoryBelowContentAL', $_POST['spCategoryBelowContentAL']);	
		
		update_option('spCategoryAboveFpost', $_POST['spCategoryAboveFpost']);
		update_option('spCategoryAboveFpostSZ', $_POST['spCategoryAboveFpostSZ']);
		update_option('spCategoryAboveFpostAL', $_POST['spCategoryAboveFpostAL']);	
		
		update_option('spCategoryBelowFpost', $_POST['spCategoryBelowFpost']);
		update_option('spCategoryBelowFpostSZ', $_POST['spCategoryBelowFpostSZ']);
		update_option('spCategoryBelowFpostAL', $_POST['spCategoryBelowFpostAL']);	
		
		
		update_option('spCategoryBelowSpost', $_POST['spCategoryBelowSpost']);
		update_option('spCategoryBelowSpostSZ', $_POST['spCategoryBelowSpostSZ']);
		update_option('spCategoryBelowSpostAL', $_POST['spCategoryBelowSpostAL']);	
		
		update_option('spCategoryBelowTpost', $_POST['spCategoryBelowTpost']);
		update_option('spCategoryBelowTpostSZ', $_POST['spCategoryBelowTpostSZ']);
		update_option('spCategoryBelowTpostAL', $_POST['spCategoryBelowTpostAL']);	
		
		update_option('spCategoryBelowFrpost', $_POST['spCategoryBelowFrpost']);
		update_option('spCategoryBelowFrpostSZ', $_POST['spCategoryBelowFrpostSZ']);
		update_option('spCategoryBelowFrpostAL', $_POST['spCategoryBelowFrpostAL']);	
		
		update_option('spCategoryBelowFvpost', $_POST['spCategoryBelowFvpost']);
		update_option('spCategoryBelowFvpostSZ', $_POST['spCategoryBelowFvpostSZ']);
		update_option('spCategoryBelowFvpostAL', $_POST['spCategoryBelowFvpostAL']);	
		
		update_option('spCategoryBelowLpost', $_POST['spCategoryBelowLpost']);
		update_option('spCategoryBelowLpostSZ', $_POST['spCategoryBelowLpostSZ']);
		update_option('spCategoryBelowLpostAL', $_POST['spCategoryBelowLpostAL']);			
		

		/** AUTHOR **/
		$spStr = 'spAuthor';
		update_option($spStr.'AboveSite', $_POST[$spStr.'AboveSite']);
		update_option($spStr.'AboveSite'.'SZ', $_POST[$spStr.'AboveSite'.'SZ']);
		update_option($spStr.'AboveSite'.'AL', $_POST[$spStr.'AboveSite'.'AL']);
		
		update_option($spStr.'BelowSite', $_POST[$spStr.'BelowSite']);
		update_option($spStr.'BelowSite'.'SZ', $_POST[$spStr.'BelowSite'.'SZ']);
		update_option($spStr.'BelowSite'.'AL', $_POST[$spStr.'BelowSite'.'AL']);
		
		update_option($spStr.'AboveMenu', $_POST[$spStr.'AboveMenu']);
		update_option($spStr.'AboveMenu'.'SZ', $_POST[$spStr.'AboveMenu'.'SZ']);
		update_option($spStr.'AboveMenu'.'AL', $_POST[$spStr.'AboveMenu'.'AL']);
		
		update_option('spAuthorAboveContent', $_POST['spAuthorAboveContent']);
		update_option('spAuthorAboveContentSZ', $_POST['spAuthorAboveContentSZ']);
		update_option('spAuthorAboveContentAL', $_POST['spAuthorAboveContentAL']);
		
		update_option('spAuthorBelowContent', $_POST['spAuthorBelowContent']);
		update_option('spAuthorBelowContentSZ', $_POST['spAuthorBelowContentSZ']);
		update_option('spAuthorBelowContentAL', $_POST['spAuthorBelowContentAL']);
		
		
		$spRefer = $_POST['spRefer'];	
		$spWebsites = $_POST['spWebsites'];		
		$spProd1 = $_POST['spProd1'];		
		$spProd2 = $_POST['spProd2'];		
		$spProd3 = $_POST['spProd3'];		
		$spProd4 = $_POST['spProd4'];
		$spProd5 = $_POST['spProd5'];				
		
		$spHomepage = $_POST['spHomepage'];		
		$spPages = $_POST['spPages'];
		$spCategory = $_POST['spCategory'];		
		$spSearches = $_POST['spSearches'];
		$spPosts = $_POST['spPosts'];		
		$spAuthor = $_POST['spAuthor'];
		$spComments = $_POST['spComments'];
		$spExcludeDisplay =$_POST['spExcludeDisplay'];
		$spExcludeExit =$_POST['spExcludeExit'];
		$spExcludeFooter =$_POST['spExcludeFooter'];
		$spExcludeTower =$_POST['spExcludeTower'];
		$spExcludeStitial =$_POST['spExcludeStitial'];
		
		
		
		$spHomepageAboveSite =$_POST['spHomepageAboveSite'];
		$spHomepageAboveSiteAL =$_POST['spHomepageAboveSiteAL'];
		$spHomepageAboveSiteAZ =$_POST['spHomepageAboveSiteSZ'];
		
		$spHomepageBelowSite =$_POST['spHomepageBelowSite'];
		$spHomepageBelowSiteSZ =$_POST['spHomepageBelowSiteSZ'];
		$spHomepageBelowSiteAL =$_POST['spHomepageBelowSiteAL'];	
		
		$spHomepageAboveMenu =$_POST['spHomepageAboveMenu'];
		$spHomepageAboveMenuSZ =$_POST['spHomepageAboveMenuSZ'];
		$spHomepageAboveMenuAL =$_POST['spHomepageAboveMenuAL'];

		$spHomepageBelowMenu =$_POST['spHomepageBelowMenu'];
		$spHomepageBelowMenuSZ =$_POST['spHomepageBelowMenuSZ'];
		$spHomepageBelowMenuAL =$_POST['spHomepageBelowMenuAL'];		
		
		$spHomepageAboveFirstPost =$_POST['spHomepageAboveFirstPost'];
		$spHomepageAboveFirstPostSZ =$_POST['spHomepageAboveFirstPostSZ'];
		$spHomepageAboveFirstPostAL =$_POST['spHomepageAboveFirstPostAL'];
		
		$spHomepageBelowFirstPost =$_POST['spHomepageBelowFirstPost'];
		$spHomepageBelowFirstPostSZ =$_POST['spHomepageBelowFirstPostSZ'];
		$spHomepageBelowFirstPostAL =$_POST['spHomepageBelowFirstPostAL'];		
		
		$spHomepageBelowSecondPost =$_POST['spHomepageBelowSecondPost'];
		$spHomepageBelowSecondPostSZ =$_POST['spHomepageBelowSecondPostSZ'];
		$spHomepageBelowSecondPostAL =$_POST['spHomepageBelowSecondPostAL'];				
		
		$spHomepageBelowThirdPost =$_POST['spHomepageBelowThirdPost'];
		$spHomepageBelowThirdPostSZ =$_POST['spHomepageBelowThirdPostSZ'];
		$spHomepageBelowThirdPostAL =$_POST['spHomepageBelowThirdPostAL'];				

		$spHomepageBelowFourthPost =$_POST['spHomepageBelowFourthPost'];
		$spHomepageBelowFourthPostSZ =$_POST['spHomepageBelowFourthPostSZ'];
		$spHomepageBelowFourthPostAL =$_POST['spHomepageBelowFourthPostAL'];				
		
		$spHomepageBelowFifthPost =$_POST['spHomepageBelowFifthPost'];
		$spHomepageBelowFifthPostSZ =$_POST['spHomepageBelowFifthPostSZ'];
		$spHomepageBelowFifthPostAL =$_POST['spHomepageBelowFifthPostAL'];						
		
		
		$spHomepageBelowLastPost = $_POST['spHomepageBelowLastPost'];
		$spHomepageBelowLastPostSZ =$_POST['spHomepageBelowLastPostSZ'];
		$spHomepageBelowLastPostAL =$_POST['spHomepageBelowLastPostAL'];


		
		/** PAGES **/
		
		$spPageAboveSite = $_POST['spPageAboveSite'];
		$spPageAboveSiteSZ = $_POST['spPageAboveSiteSZ'];
		$spPageAboveSiteAL = $_POST['spPageAboveSiteAL'];
		
		$spPagesBelowSite = $_POST['spPagesBelowSite'];
		$spPagesBelowSiteSZ = $_POST['spPagesBelowSiteSZ'];
		$spPagesBelowSiteAL = $_POST['spPagesBelowSiteAL'];
		
		$spStr='spPages';
		$spPagesAboveMenu = $_POST[$spStr.'AboveMenu'];
		$spPagesAboveMenuSZ = $_POST[$spStr.'AboveMenu'.'SZ'];
		$spPagesAboveMenuAL = $_POST[$spStr.'AboveMenu'.'AL'];

		
		$spPagesAboveContent = $_POST['spPagesAboveContent'];
		$spPagesAboveContentSZ = $_POST['spPagesAboveContentSZ'];
		$spPagesAboveContentAL = $_POST['spPagesAboveContentAL'];
		
		$spPagesBelowContent = $_POST['spPagesBelowContent'];
		$spPagesBelowContentSZ = $_POST['spPagesBelowContentSZ'];
		$spPagesBelowContentAL = $_POST['spPagesBelowContentAL'];
		
		
		$spPagesWithinContent = $_POST['spPagesWithinContent'];
		$spPagesWithinContentSZ = $_POST['spPagesWithinContentSZ'];
		$spPagesWithinContentAL = $_POST['spPagesWithinContentAL'];
		
		
		
		/** POSTS **/
		$spPostsAboveSite = $_POST['spPostsAboveSite'];
		$spPostsAboveSiteSZ = $_POST['spPostsAboveSiteSZ'];
		$spPostsAboveSiteAL = $_POST['spPostsAboveSiteAL'];
		
		$spPostsBelowSite = $_POST['spPostsBelowSite'];
		$spPostsBelowSiteSZ = $_POST['spPostsBelowSiteSZ'];
		$spPostsBelowSiteAL = $_POST['spPostsBelowSiteAL'];
		
		$spStr='spPosts';
		$spPostsBelowMenu = $_POST[$spStr.'AboveMenu'];
		$spPostsBelowMenuSZ = $_POST[$spStr.'AboveMenu'.'SZ'];
		$spPostsBelowMenuAL = $_POST[$spStr.'AboveMenu'.'AL'];
		
		$spPostsAbovePost = $_POST['spPostsAbovePost'];
		$spPostsAbovePostSZ = $_POST['spPostsAbovePostSZ'];
		$spPostsAbovePostAL = $_POST['spPostsAbovePostAL'];
		
		$spPostsBelowPost = $_POST['spPostsBelowPost'];
		$spPostsBelowPostSZ = $_POST['spPostsBelowPostSZ'];
		$spPostsBelowPostAL = $_POST['spPostsBelowPostAL'];
		
		$spPostsAboveTitle = $_POST['spPostsAboveTitle'];
		$spPostsAboveTitleSZ = $_POST['spPostsAboveTitleSZ'];
		$spPostsAboveTitleAL = $_POST['spPostsAboveTitleAL'];

		$spPostsBelowTitle = $_POST['spPostsBelowTitle'];
		$spPostsBelowTitleSZ = $_POST['spPostsBelowTitleSZ'];
		$spPostsBelowTitleAL = $_POST['spPostsBelowTitleAL'];
				
		$spPostsAboveComments = $_POST['spPostsAboveComments'];
		$spPostsAboveCommentsSZ = $_POST['spPostsAboveCommentsSZ'];
		$spPostsAboveCommentsAL = $_POST['spPostsAboveCommentsAL'];
		
		$spPostsBelowComments = $_POST['spPostsBelowComments'];
		$spPostsBelowCommentsSZ =$_POST['spPostsBelowCommentsSZ'];
		$spPostsBelowCommentsAL = $_POST['spPostsBelowCommentsAL'];
		
		$spPostsWithinContent =$_POST['spPostsWithinContent']; 
		$spPostsWithinContentSZ = $_POST['spPostsWithinContentSZ'];
		$spPostsWithinContentAL = $_POST['spPostsWithinContentAL'];
		
		/** SEARCH **/
		$spStr='spSearches';
		$spSearchesAboveSite = $_POST[$spStr.'AboveSite'];
		$spSearchesAboveSiteSZ = $_POST[$spStr.'AboveSite'.'SZ'];
		$spSearchesAboveSiteAL = $_POST[$spStr.'AboveSite'.'AL'];
		
		$spSearchesBelowSite = $_POST[$spStr.'BelowSite'];
		$spSearchesBelowSiteSZ = $_POST[$spStr.'BelowSite'.'SZ'];
		$spSearchesBelowSiteAL = $_POST[$spStr.'BelowSite'.'AL'];
		
		$spSearchesAboveMenu = $_POST[$spStr.'AboveMenu'];
		$spSearchesAboveMenuSZ = $_POST[$spStr.'AboveMenu'.'SZ'];
		$spSearchesAboveMenuAL = $_POST[$spStr.'AboveMenu'.'AL'];
		
		$spSearchesAboveContent = $_POST['spSearchesAboveContent'];
		$spSearchesAboveContentSZ =$_POST['spSearchesAboveContentSZ'];
		$spSearchesAboveContentAL = $_POST['spSearchesAboveContentAL'];
		
		$spSearchesBelowContent = $_POST['spSearchesBelowContent'];
		$spSearchesBelowContentSZ = $_POST['spSearchesBelowContentSZ'];
		$spSearchesBelowContentAL = $_POST['spSearchesBelowContentAL'];
		
		/** CATEGORY **/
		$spStr='spSearches';
		$spCategoryAboveSite = $_POST[$spStr.'AboveSite'];
		$spCategoryAboveSiteSZ = $_POST[$spStr.'AboveSite'.'SZ'];
		$spCategoryAboveSiteAL = $_POST[$spStr.'AboveSite'.'AL'];
		
		$spCategoryBelowSite = $_POST[$spStr.'BelowSite'];
		$spCategoryBelowSiteSZ = $_POST[$spStr.'BelowSite'.'SZ'];
		$spCategoryBelowSiteAL = $_POST[$spStr.'BelowSite'.'AL'];
		
		$spCategoryAboveMenu = $_POST[$spStr.'AboveMenu'];
		$spCategoryAboveMenuSZ = $_POST[$spStr.'AboveMenu'.'SZ'];
		$spCategoryAboveMenuAL = $_POST[$spStr.'AboveMenu'.'AL'];
		
		$spCategoryAboveContent = $_POST['spCategoryAboveContent'];
		$spCategoryAboveContentSZ = $_POST['spCategoryAboveContentSZ'];
		$spCategoryAboveContentAL = $_POST['spCategoryAboveContentAL'];
		
		$spCategoryBelowContent = $_POST['spCategoryBelowContent'];
		$spCategoryBelowContentSZ = $_POST['spCategoryBelowContentSZ'];
		$spCategoryBelowContentAL = $_POST['spCategoryBelowContentAL'];
		
		$spCategoryAboveFpost = $_POST['spCategoryAboveFpost'];
		$spCategoryAboveFpostSZ =$_POST['spCategoryAboveFpostSZ']; 
		$spCategoryAboveFpostAL = $_POST['spCategoryAboveFpostAL'];
		
		$spCategoryBelowFpost =$_POST['spCategoryBelowFpost'];
		$spCategoryBelowFpostSZ = $_POST['spCategoryBelowFpostSZ'];
		$spCategoryBelowFpostAL = $_POST['spCategoryBelowFpostAL'];
		
		$spCategoryBelowSpost =$_POST['spCategoryBelowSpost'];
		$spCategoryBelowSpostSZ = $_POST['spCategoryBelowSpostSZ'];
		$spCategoryBelowSpostAL = $_POST['spCategoryBelowSpostAL'];
		
		$spCategoryBelowTpost =$_POST['spCategoryBelowTpost'];
		$spCategoryBelowTpostSZ = $_POST['spCategoryBelowTpostSZ'];
		$spCategoryBelowTpostAL = $_POST['spCategoryBelowTpostAL'];
		
		$spCategoryBelowFrpost =$_POST['spCategoryBelowFrpost'];
		$spCategoryBelowFrpostSZ = $_POST['spCategoryBelowFrpostSZ'];
		$spCategoryBelowFrpostAL = $_POST['spCategoryBelowFrpostAL'];
		
		$spCategoryBelowFvpost =$_POST['spCategoryBelowFvpost'];
		$spCategoryBelowFvpostSZ = $_POST['spCategoryBelowFvpostSZ'];
		$spCategoryBelowFvpostAL = $_POST['spCategoryBelowFvpostAL'];
		
		$spCategoryBelowLpost = $_POST['spCategoryBelowLpost'];
		$spCategoryBelowLpostSZ = $_POST['spCategoryBelowLpostSZ'];
		$spCategoryBelowLpostAL = $_POST['spCategoryBelowLpostAL'];

		/** AUTHOR **/
		$spStr='spSearches';
		$spAuthorAboveSite = $_POST[$spStr.'AboveSite'];
		$spAuthorAboveSiteSZ = $_POST[$spStr.'AboveSite'.'SZ'];
		$spAuthorAboveSiteAL = $_POST[$spStr.'AboveSite'.'AL'];
		
		$spAuthorBelowSite = $_POST[$spStr.'BelowSite'];
		$spAuthorBelowSiteSZ = $_POST[$spStr.'BelowSite'.'SZ'];
		$spAuthorBelowSiteAL = $_POST[$spStr.'BelowSite'.'AL'];
		
		$spAuthorAboveMenu = $_POST[$spStr.'AboveMenu'];
		$spAuthorAboveMenuSZ = $_POST[$spStr.'AboveMenu'.'SZ'];
		$spAuthorAboveMenuAL = $_POST[$spStr.'AboveMenu'.'AL'];
		
		$spAuthorAboveContent =$_POST['spAuthorAboveContent'];
		$spAuthorAboveContentSZ = $_POST['spAuthorAboveContentSZ'];
		$spAuthorAboveContentAL = $_POST['spAuthorAboveContentAL'];
		
		$spAuthorBelowContent = $_POST['spAuthorBelowContent'];
		$spAuthorBelowContentSZ = $_POST['spAuthorBelowContentSZ'];
		$spAuthorBelowContentAL =$_POST['spAuthorBelowContentAL'];
		

		//echo $spHomepageAboveSite. ' b:'. $spHomepageAboveSiteAL. ' c:'. $spHomepageAboveSiteSZ;
		
		//header('Location: ' . plugins_url() .'superlinks-ads/sp_frame.php' );
		$purl = plugins_url(); 
		
	if(isset($_POST['SaveBtn'])){ $saved = '1';} else {
		
	?>
	<script type="text/javascript">
	<!--
	window.location = "widgets.php";
	//-->
	</script>
	<?		
	}
}
?>
<style>
body{
	background:#fff !important;
}
.wrap { width:60%;}
.left-container { min-width:40%; float:left; font-size:13px;}
.right-container { min-width:250px; width:18%; float:right;}
.tbl-wrap { display:table; margin:25px 0 0 0;}
.tbl-row { display:table-row; }
.tbl-cell { display:table-cell;  padding-top:0; }
.tbl-caption { display:table-caption; }
.tbl-label { width:150px;}

.displayOptions .tbl-label { width:200px; }
.tbl-label2 { min-width:150px; }

.save-button {
    background-image: none !important;
	background-color: #FC604A  !important;
    border: medium none  !important;
    border-radius: 5px  !important;
    color: #FFFFFF  !important;
    cursor: pointer  !important;
    display: block  !important;
    font-size: 17px  !important;
    height: 35px  !important;
    margin: 0  !important;
    padding: 0 12px !important;
    text-decoration: none !important;
	float:left  !important;
	margin-right:10px  !important;
	text-shadow: none !important;
}

.red-button {
	background-image: none !important;
    background-color: #FC604A !important;
    border: medium none  !important;
    border-radius: 5px  !important;
    color: #FFFFFF  !important;
    cursor: pointer  !important;
    display: block  !important;
    font-size: 17px  !important;
    height: 35px  !important;
    margin: 0  !important;
    padding: 0 12px  !important;
    text-decoration: none !important;
	text-shadow: none !important;
}

iframe.fancybox-iframe {
    overflow:hidden !important;
}




/* Style for Usual tabs */
.usual {
  background:none;
  color:#111;
  padding:15px 0px 0 0;
  width:100%;
  border:none;
  margin:8px auto;
}
.usual li { list-style:none; float:left;margin-bottom:0px; }
.usual ul a {
  display:block;
  padding:6px 10px;
  text-decoration:none!important;
  margin:1px;
  margin-left:0;
  color:#FFF;
  background:#444;
}
.usual ul a:hover {
  color:#FFF;
  background:#111;
  }
.usual ul a.selected {
  margin-bottom:0;
  color:#000;
  background:#EEE;
  border-bottom:1px solid #CCC;
  cursor:default;
  }
.usual div {
  padding:10px 10px 8px 0px;
  *padding-top:3px;
  *margin-top:-15px;
  clear:left;
  background:#FFF;
}
.usual div a { color:#21759b; font-weight:normal !important; }


.tbl-placement { padding-left:50px;}
.tbl-placement td{ padding:4px 30px; width:145px; }

a.toggle-head { color:#000 !important; display: block; font-weight: normal !important; padding: 10px 0 10px 5px; text-decoration: none; }


input[type="checkbox"], input[type="radio"] {
    margin: 0 5px !important;
}

p.submit {
	margin-top:0 !important;
	padding-top:0 !important;
}

#tbl-homepage table { display:block; }
#tab1 h3 {
	margin-left:4px;
}
#tbl-pages table { display:none; }
#tbl-posts table { display:none; }
#tbl-searches table { display:none; }
#tbl-category table { display:none; }
#tbl-author table { display:none; }

select { min-width:100px;}

select.dimension{
	width:160px !important;
}

.toggle-head span {
	margin-right: 4px !important;
}

.toggle-head2 span {
	margin-right: 4px !important;
}

</style>

<form name="sp_form1" id="sp_form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<div class="wrap">
	
	<?php if($saved=='1') {?><div style="background-color:#dff0d8 !important; padding:6px 4px;margin:10px 0;">Settings successfully saved!</div><? } ?>

	<div class="left-container">
    <?php    
	echo '<img src="' . plugins_url( 'images/sl-logo-small.png' , __FILE__ ) . '" alt="Superlinks" >';
	?>
     
	 <? if($n != '1') { ?>
    	
        <input type="hidden" name="sp_hidden" value="Y">
        
        <div class="tbl-wrap">
        <div class="tbl-row">
            <div class="tbl-cell tbl-label">
              <?php _e("Superlinks username: " ); ?>
            </div>
                <div style="display: table-cell;">
              <input type="text" name="sp_username" value="<?php echo $spuser; ?>" size="20">
            </div>
          </div>
          <div class="tbl-row">
            <div class="tbl-cell tbl-label">
              <?php _e("Password: " ); ?>
            </div>
                <div style="display: table-cell;">
              <input type="password" name="sp_passwd" value="" size="20">
            </div>
          </div>
        </div>        
        
         <p class="submit">
        <input type="submit" name="Submit" class="save-button" value="<?php _e('Log In', 'sp_trdom' ) ?>" />
        </p>
	    
		<? } ?>
		
		<? if($n == '1') { ?>
		
		<div style="margin-top:26px"> 
		<?php _e("Superlinks Pub ID:  " ); ?> <?='P-'.$sp_userid?><br>
		 <?php _e("Superlinks Username: " ); ?> <?=$sp_username?><br>
		</div>


	<? } ?>

</div>

<div class="right-container">
	<div align="right">
<? /* ?>    	<a href="#" target="_blank">Help</a> | <? */ ?>
		<a href="http://support.superlinks.com/" target="_blank">Support</a> | 
    	<a href="http://superlinks.com/publisher/login.php" target="_blank">Log into Superlinks</a><? if($n == '1') { ?> | 
    	<a href="admin.php?page=superlinks-ads/superlinks-plugin.php&logout=1">Sign out</a><? } ?>                
    </div>
    <? if($n == '1') { ?><br /><br />
	<div align="right">
    	Monthly Earnings: <?=$monthly_earning?><br />
    	All-Time Earnings: <?=$total_earning?>
    </div><? } ?>
    
    <? if($n != '1') { ?><br /><br />
	<div style="text-align:center;padding:0;">
    	<a href="http://superlinks.com/signup.php" target="_blank">
		<? echo '<img src="' . plugins_url( 'images/signup.jpg' , __FILE__ ) . '" alt="Don\'t have an account? Click here to Sign Uperlinks" >'; ?>
		</a>
    </div>
	<? } ?>
	
    <?
	 if($spSiteGStatus == 'APPROVED'){ ?>
	<br />
    <div align="right">
    	    <a href="#">
			<? echo '<img src="' . plugins_url( 'images/googleadex.jpg' , __FILE__ ) . '" alt="Click here to sign up for Google Ad Exchange" >'; ?>
			</a>
    </div>
	<? } ?>

        
</div>
<!-- right container -->
<div style="clear:both"></div>
</div>
<!-- wrap -->		
        
        
    <? if($n == '1') { 
	
	$plus_icon = "<img src=\"" . plugins_url( 'images/plus-icon.gif' , __FILE__ ) . "\" >";
	$minus_icon = "<img src=\"" . plugins_url( 'images/minus-icon.gif' , __FILE__ ) . "\" style=\"width:11px;height:11px\" >";
	
	?>
        <input type="hidden" name="spOptionHidden" value="Y">   



        <hr />   
		
<script type="text/javascript" src="<? echo  plugins_url( 'js/jquery.idTabs.min.js' , __FILE__ ) ?>"></script>

<Script language="JavaScript">


jQuery(document).ready(function(){
		jQuery('.dimension').on('change', function () {
			 var $this = jQuery(this);
			 if($this.val() == 'add'){
				$this.prop('selectedIndex',0);
				window.open('http://superlinks.com/publisher/widget-setup-selection.php','_blank');
			 }
		 });

		jQuery('#homepage-plus').html("<?=addslashes($minus_icon)?>");

		jQuery('#tab1 #toggle-head1').click(function(e){
			e.preventDefault();
			jQuery('#tbl-homepage').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-homepage').children('table').is(':visible')) { 
			jQuery('#homepage-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#homepage-plus').html("<?=addslashes($plus_icon)?>"); }
			});
		});
		
		jQuery('#tab1 #toggle-head2').click(function(e){
			e.preventDefault();
			jQuery('#tbl-pages').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-pages').children('table').is(':visible')) { 
			jQuery('#pages-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#pages-plus').html("<?=addslashes($plus_icon)?>"); }
			});
		});			

		jQuery('#tab1 #toggle-head4').click(function(e){
			e.preventDefault();
			jQuery('#tbl-posts').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-posts').children('table').is(':visible')) { 
			jQuery('#posts-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#posts-plus').html("<?=addslashes($plus_icon)?>"); }
			});
			
		});		
		
		jQuery('#tab1 #toggle-head5').click(function(e){
			e.preventDefault();
			jQuery('#tbl-searches').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-searches').children('table').is(':visible')) { 
			jQuery('#searches-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#searches-plus').html("<?=addslashes($plus_icon)?>"); }
			});
		});						
		
		jQuery('#tab1 #toggle-head6').click(function(e){
			e.preventDefault();
			jQuery('#tbl-category').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-category').children('table').is(':visible')) { 
			jQuery('#category-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#category-plus').html("<?=addslashes($plus_icon)?>"); }
			});
		});								
		
		jQuery('#tab1 #toggle-head7').click(function(e){
			e.preventDefault();
			jQuery('#tbl-author').children('table').not(':animated').slideToggle(function(){
				
			if(jQuery('#tbl-author').children('table').is(':visible')) { 
			jQuery('#author-plus').html("<?=addslashes($minus_icon)?>"); } 
			else 
			{ jQuery('#author-plus').html("<?=addslashes($plus_icon)?>"); }
			});
		});										
															

});

</script>

<div id="usual1" class="usual"> 
  <ul> 
    <li><a href="#tab1" class="selected">Super Display Ads</a></li> 
    <li><a href="#tab2">Super Exit Links</a></li> 
    <li><a href="#tab3">Super Footer</a></li> 
	<li><a href="#tab4">Super Tower</a></li> 
	<li><a href="#tab5">Super Interstitial</a></li> 
  </ul> 
  <div id="tab1">
  	
	<?php    echo "<h3>" . __( 'Super Display Ads Options', 'sp_trdom' ) . "</h3>"; ?>

<?php if($superdisplayApproved == '1'){ ?>
	
	<div style="margin-top:0px;"><input name="spProd1" type="checkbox" value="1" <?php if($spProd1=='1')echo 'checked="checked"'; ?> />Enable Super Display Ads on your website</div>   
	
	
    <div style="margin-left:5px;">To learn more about Super Display, please see the <a href="http://superlinks.com/super-display-ads.php" target="_blank">Product Overview</a></div>
    
    <div style="color:red;margin-left:5px;">NOTE: You can easily insert banners into your sidebar or footer by going to Appearance then <a href="widgets.php">Widgets</a>, by dragging the widget Superlinks Display Ads to the widgets area.</div>
    
<table border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td id="tbl-homepage">
        <h3>Placement</h3>				
        
        <div style="margin-top:0px;"><input name="spHomepage" type="checkbox" value="1" <?php if($spHomepage=='1')echo 'checked="checked"'; ?> />Home Page</div>     
        
			<a href='#' id="toggle-head1" class='toggle-head'><span id="homepage-plus"><?=$plus_icon?></span> Enable custom Home Page placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
                <tr>
                    <td>				  	
                    <input name="spHomepageAboveSite" type="checkbox" value="1" <?php if($spHomepageAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spHomepageAboveSiteSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>
                	<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageAboveSiteAL">               
                    <option value="left" <?php if($spHomepageAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageBelowSite" type="checkbox" value="1" <?php if($spHomepageBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spHomepageBelowSiteSZ" class="dimension">
                    <option>Select Dimension</option>   
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowSiteAL">
                    <option value="left" <?php if($spHomepageBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageAboveMenu" type="checkbox" value="1" <?php if($spHomepageAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spHomepageAboveMenuSZ" class="dimension">
                    <option>Select Dimension</option> 
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
					<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageAboveMenuAL">
                    
                    <option value="left" <?php if($spHomepageAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageAboveFirstPost" type="checkbox" value="1" <?php if($spHomepageAboveFirstPost=='1')echo 'checked="checked"'; ?> /> Above First Post</td>
                    <td>
                    <select name="spHomepageAboveFirstPostSZ" class="dimension">
                    <option>Select Dimension</option>  
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageAboveFirstPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageAboveFirstPostAL">                 
                    <option value="left" <?php if($spHomepageAboveFirstPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageAboveFirstPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageAboveFirstPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageBelowFirstPost" type="checkbox" value="1" <?php if($spHomepageBelowFirstPost=='1')echo 'checked="checked"'; ?> /> Below First Post</td>
                    <td>
                    <select name="spHomepageBelowFirstPostSZ" class="dimension">
                    <option>Select Dimension</option> 
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowFirstPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowFirstPostAL">                   
                    <option value="left" <?php if($spHomepageBelowFirstPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowFirstPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowFirstPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageBelowSecondPost" type="checkbox" value="1" <?php if($spHomepageBelowSecondPost=='1')echo 'checked="checked"'; ?> /> Below Second Post</td>
                    <td>
                    <select name="spHomepageBelowSecondPostSZ" class="dimension">
                    <option>Select Dimension</option>     
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowSecondPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>						
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowSecondPostAL">
                    <option value="left" <?php if($spHomepageBelowSecondPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowSecondPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowSecondPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageBelowThirdPost" type="checkbox" value="1" <?php if($spHomepageBelowThirdPost=='1')echo 'checked="checked"'; ?> /> Below Third Post</td>
                    <td>
                    <select name="spHomepageBelowThirdPostSZ" class="dimension">
                    <option>Select Dimension</option>   
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowThirdPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowThirdPostAL">                  
                    <option value="left" <?php if($spHomepageBelowThirdPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowThirdPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowThirdPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spHomepageBelowFourthPost" type="checkbox" value="1" <?php if($spHomepageBelowFourthPost=='1')echo 'checked="checked"'; ?> /> Below Fourth Post</td>
                    <td>
                    <select name="spHomepageBelowFourthPostSZ" class="dimension">
                    <option>Select Dimension</option>  
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowFourthPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowFourthPostAL">                  
                    <option value="left" <?php if($spHomepageBelowFourthPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowFourthPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowFourthPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spHomepageBelowFifthPost" type="checkbox" value="1" <?php if($spHomepageBelowFifthPost=='1')echo 'checked="checked"'; ?> /> Below Fifth Post</td>
                    <td>
                    <select name="spHomepageBelowFifthPostSZ" class="dimension">
                    <option>Select Dimension</option> 
					<?php
						
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowFifthPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowFifthPostAL">                  
                    <option value="left" <?php if($spHomepageBelowFifthPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowFifthPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowFifthPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spHomepageBelowLastPost" type="checkbox" value="1" <?php if($spHomepageBelowLastPost=='1')echo 'checked="checked"'; ?> /> Above Last Post</td>
                    <td>
                    <select name="spHomepageBelowLastPostSZ" class="dimension">
                    <option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spHomepageBelowLastPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>						
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spHomepageBelowLastPostAL">
                    <option value="left" <?php if($spHomepageBelowLastPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spHomepageBelowLastPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spHomepageBelowLastPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                
            </table>        
        </td>
    </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td id="tbl-pages">				
        
        <div style="margin-top:0px;"><input name="spPages" type="checkbox" value="1" <?php if($spPages=='1')echo 'checked="checked"'; ?> />Pages</div>     
        
			<a href='#' id="toggle-head2" class='toggle-head'><span id="pages-plus"><?=$plus_icon?></span> Enable custom Pages placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
			<tr>
                    <td>				  	
                    <input name="spPageAboveSite" type="checkbox" value="1" <?php if($spPageAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spPageAboveSiteSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPageAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spPageAboveSiteAL">               
                    <option value="left" <?php if($spPageAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPageAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPageAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPagesBelowSite" type="checkbox" value="1" <?php if($spPagesBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spPagesBelowSiteSZ" class="dimension">
                    <option>Select Dimension</option>  
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPagesBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPagesBelowSiteAL">
                    <option value="left" <?php if($spPagesBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPagesBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPagesBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				
				<tr>
                    <td>				  	
                    <input name="spPagesAboveMenu" type="checkbox" value="1" <?php if($spPagesAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spPagesAboveMenuSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPagesAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>
                	<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spPagesAboveMenuAL">               
                    <option value="left" <?php if($spPagesAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPagesAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPagesAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPagesAboveContent" type="checkbox" value="1" <?php if($spPagesAboveContent=='1')echo 'checked="checked"'; ?> /> Above Content</td>
                    <td>
                    <select name="spPagesAboveContentSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPagesAboveContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?>                    
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPagesAboveContentAL">
                    
                    <option value="left" <?php if($spPagesAboveContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPagesAboveContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPagesAboveContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPagesBelowContent" type="checkbox" value="1" <?php if($spPagesBelowContent=='1')echo 'checked="checked"'; ?> /> Below Content</td>
                    <td>
                    <select name="spPagesBelowContentSZ" class="dimension">
                    <option>Select Dimension</option>   
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPagesBelowContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPagesBelowContentAL">
                    
                    <option value="left" <?php if($spPagesBelowContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPagesBelowContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPagesBelowContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				
				 <tr>
                    <td>				  	
                    <input name="spPagesWithinContent" type="checkbox" value="1" <?php if($spPagesWithinContent=='1')echo 'checked="checked"'; ?> /> Within Content</td>
                    <td>
                    <select name="spPagesWithinContentSZ" class="dimension">
                    <option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPagesWithinContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPagesWithinContentAL">
                    
                    <option value="left" <?php if($spPagesWithinContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPagesWithinContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPagesWithinContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
</table>


<table border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td id="tbl-posts">
			
        
        <div style="margin-top:0px;"><input name="spPosts" type="checkbox" value="1" <?php if($spPosts=='1')echo 'checked="checked"'; ?> />Posts</div>     
        
			<a href='#' id="toggle-head4" class='toggle-head'><span id="posts-plus"><?=$plus_icon?></span> Enable custom Posts placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
			<tr>
                    <td>				  	
                    <input name="spPostsAboveSite" type="checkbox" value="1" <?php if($spPostsAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spPostsAboveSiteSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>
                    </select>
                    </td>
                    <td>
                    <select name="spPostsAboveSiteAL">               
                    <option value="left" <?php if($spPostsAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPostsBelowSite" type="checkbox" value="1" <?php if($spPostsBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spPostsBelowSiteSZ" class="dimension">
                    <option>Select Dimension</option>     
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsBelowSiteAL">
                    <option value="left" <?php if($spPostsBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				
				<tr>
                    <td>				  	
                    <input name="spPostsAboveMenu" type="checkbox" value="1" <?php if($spPostsAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spPostsAboveMenuSZ" class="dimension">
                    <option>Select Dimension</option>         
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsAboveMenuAL">
                    <option value="left" <?php if($spPostsAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
			
                <tr>
                    <td>				  	
                    <input name="spPostsAbovePost" type="checkbox" value="1" <?php if($spPostsAbovePost=='1')echo 'checked="checked"'; ?> /> Above Post</td>
                    <td>
                    <select name="spPostsAbovePostSZ" class="dimension">
                    <option>Select Dimension</option>        
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsAbovePostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsAbovePostAL">
                    
                    <option value="left" <?php if($spPostsAbovePostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsAbovePostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsAbovePostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPostsBelowPost" type="checkbox" value="1" <?php if($spPostsBelowPost=='1')echo 'checked="checked"'; ?> /> Below Post</td>
                    <td>
                    <select name="spPostsBelowPostSZ" class="dimension">
                    <option>Select Dimension</option>      
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsBelowPostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsBelowPostAL">
                    
                    <option value="left" <?php if($spPostsBelowPostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsBelowPostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsBelowPostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPostsAboveComments" type="checkbox" value="1" <?php if($spPostsAboveComments=='1')echo 'checked="checked"'; ?> /> Above Comments</td>
                    <td>
                    <select name="spPostsAboveCommentsSZ" class="dimension">
                    <option>Select Dimension</option>      
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsAboveCommentsSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsAboveCommentsAL">
                    
                    <option value="left" <?php if($spPostsAboveCommentsAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsAboveCommentsAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsAboveCommentsAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPostsBelowComments" type="checkbox" value="1" <?php if($spPostsBelowComments=='1')echo 'checked="checked"'; ?> /> Below Comments</td>
                    <td>
                    <select name="spPostsBelowCommentsSZ" class="dimension">
                    <option>Select Dimension</option>   
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsBelowCommentsSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsBelowCommentsAL">
                    
                    <option value="left" <?php if($spPostsBelowCommentsAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsBelowCommentsAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsBelowCommentsAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spPostsWithinContent" type="checkbox" value="1" <?php if($spPostsWithinContent=='1')echo 'checked="checked"'; ?> /> Within Content</td>
                    <td>
                    <select name="spPostsWithinContentSZ" class="dimension">
                    <option>Select Dimension</option>
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spPostsWithinContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spPostsWithinContentAL">
                    
                    <option value="left" <?php if($spPostsWithinContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spPostsWithinContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spPostsWithinContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td id="tbl-searches">
        
        <div style="margin-top:0px;"><input name="spSearches" type="checkbox" value="1" <?php if($spSearches=='1')echo 'checked="checked"'; ?> />Search</div>     
        
			<a href='#' id="toggle-head5" class='toggle-head'><span id="searches-plus"><?=$plus_icon?></span> Enable custom Search placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
                <tr>
                    <td>				  	
                    <input name="spSearchesAboveSite" type="checkbox" value="1" <?php if($spSearchesAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spSearchesAboveSiteSZ" class="dimension">
                    <option>Select Dimension</option>     
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesAboveSiteAL">
                    
                    <option value="left" <?php if($spSearchesAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spSearchesBelowSite" type="checkbox" value="1" <?php if($spSearchesBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spSearchesBelowSiteSZ" class="dimension">
                    <option>Select Dimension</option>      
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesBelowSiteAL">
                    
                    <option value="left" <?php if($spSearchesBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spSearchesAboveMenu" type="checkbox" value="1" <?php if($spSearchesAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spSearchesAboveMenuSZ" class="dimension">
                    <option>Select Dimension</option>      
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesAboveMenuAL">
                    
                    <option value="left" <?php if($spSearchesAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spSearchesAboveContent" type="checkbox" value="1" <?php if($spSearchesAboveContent=='1')echo 'checked="checked"'; ?> /> Above Content</td>
                    <td>
                    <select name="spSearchesAboveContentSZ" class="dimension">
                    <option>Select Dimension</option>                  
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesAboveContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesAboveContentAL">
                    
                    <option value="left" <?php if($spSearchesAboveContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesAboveContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesAboveContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spSearchesAboveContent" type="checkbox" value="1" <?php if($spSearchesAboveContent=='1')echo 'checked="checked"'; ?> /> Above Content</td>
                    <td>
                    <select name="spSearchesAboveContentSZ" class="dimension">
                    <option>Select Dimension</option>           
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesAboveContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesAboveContentAL">
                    
                    <option value="left" <?php if($spSearchesAboveContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesAboveContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesAboveContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spSearchesBelowContent" type="checkbox" value="1" <?php if($spSearchesBelowContent=='1')echo 'checked="checked"'; ?> /> Below Content</td>
                    <td>
                    <select name="spSearchesBelowContentSZ" class="dimension">
                    <option>Select Dimension</option>                  
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spSearchesBelowContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spSearchesBelowContentAL">
                    
                    <option value="left" <?php if($spSearchesBelowContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spSearchesBelowContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spSearchesBelowContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td id="tbl-category">
        
        
        <div style="margin-top:0px;"><input name="spCategory" type="checkbox" value="1" <?php if($spCategory=='1')echo 'checked="checked"'; ?> />Category</div>     
        
			<a href='#' id="toggle-head6" class='toggle-head'><span id="category-plus"><?=$plus_icon?></span> Enable custom Category placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
                <tr>
                    <td>				  	
                    <input name="spCategoryAboveSite" type="checkbox" value="1" <?php if($spCategoryAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spCategoryAboveSiteSZ" class="dimension">
					<option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryAboveSiteAL">
                    
                    <option value="left" <?php if($spCategoryAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spCategoryBelowSite" type="checkbox" value="1" <?php if($spCategoryBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spCategoryBelowSiteSZ" class="dimension">
					<option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowSiteAL">
                    
                    <option value="left" <?php if($spCategoryBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spCategoryAboveMenu" type="checkbox" value="1" <?php if($spCategoryAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spCategoryAboveMenuSZ" class="dimension">
					<option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryAboveMenuAL">
                    
                    <option value="left" <?php if($spCategoryAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryAboveContent" type="checkbox" value="1" <?php if($spCategoryAboveContent=='1')echo 'checked="checked"'; ?> /> Above Content</td>
                    <td>
                    <select name="spCategoryAboveContentSZ" class="dimension">
					<option>Select Dimension</option> 
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryAboveContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryAboveContentAL">
                    
                    <option value="left" <?php if($spCategoryAboveContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryAboveContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryAboveContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryBelowContent" type="checkbox" value="1" <?php if($spCategoryBelowContent=='1')echo 'checked="checked"'; ?> /> Below Content</td>
                    <td>
                    <select name="spCategoryBelowContentSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowContentAL">
                    
                    <option value="left" <?php if($spCategoryBelowContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryAboveFpost" type="checkbox" value="1" <?php if($spCategoryAboveFpost=='1')echo 'checked="checked"'; ?> /> Above First Post</td>
                    <td>
                    <select name="spCategoryAboveFpostSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryAboveFpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryAboveFpostAL">
                    
                    <option value="left" <?php if($spCategoryAboveFpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryAboveFpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryAboveFpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryBelowFpost" type="checkbox" value="1" <?php if($spCategoryBelowFpost=='1')echo 'checked="checked"'; ?> /> Below First Post</td>
                    <td>
                    <select name="spCategoryBelowFpostSZ" class="dimension">
                    <option>Select Dimension</option>  
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowFpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowFpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowFpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowFpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowFpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryBelowSpost" type="checkbox" value="1" <?php if($spCategoryBelowSpost=='1')echo 'checked="checked"'; ?> /> Below Second Post</td>
                    <td>
                    <select name="spCategoryBelowSpostSZ" class="dimension">
                    <option>Select Dimension</option>                  
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowSpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowSpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowSpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowSpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowSpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spCategoryBelowTpost" type="checkbox" value="1" <?php if($spCategoryBelowTpost=='1')echo 'checked="checked"'; ?> /> Below Third Post</td>
                    <td>
                    <select name="spCategoryBelowTpostSZ" class="dimension">
                    <option>Select Dimension</option>              
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowTpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowTpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowTpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowTpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowTpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spCategoryBelowFrpost" type="checkbox" value="1" <?php if($spCategoryBelowFrpost=='1')echo 'checked="checked"'; ?> /> Below Fourth Post</td>
                    <td>
                    <select name="spCategoryBelowFrpostSZ" class="dimension">
                    <option>Select Dimension</option>          
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowFrpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowFrpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowFrpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowFrpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowFrpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spCategoryBelowFvpostAL" type="checkbox" value="1" <?php if($spCategoryBelowFvpostAL=='1')echo 'checked="checked"'; ?> /> Below Fifth Post</td>
                    <td>
                    <select name="spCategoryBelowFvpostSZ" class="dimension">
                    <option>Select Dimension</option>           
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowFvpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowFvpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowFvpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowFvpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowFvpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spCategoryBelowLpost" type="checkbox" value="1" <?php if($spCategoryBelowLpost=='1')echo 'checked="checked"'; ?> /> Above Last Post</td>
                    <td>
                    <select name="spCategoryBelowLpostSZ" class="dimension">
                    <option>Select Dimension</option>            
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spCategoryBelowLpostSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spCategoryBelowLpostAL">
                    
                    <option value="left" <?php if($spCategoryBelowLpostAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spCategoryBelowLpostAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spCategoryBelowLpostAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
              
            </table>        
        </td>
    </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td id="tbl-author">				
        
        <div style="margin-top:0px;"><input name="spAuthor" type="checkbox" value="1" <?php if($spAuthor=='1')echo 'checked="checked"'; ?> />Author</div>     
        
			<a href='#' id="toggle-head7" class='toggle-head'><span id="author-plus"><?=$plus_icon?></span> Enable custom Author placements</a>
        
            <table border="0" cellspacing="0" cellpadding="0" class="tbl-placement">
                <tr>
                    <td>				  	
                    <input name="spAuthorAboveSite" type="checkbox" value="1" <?php if($spAuthorAboveSite=='1')echo 'checked="checked"'; ?> /> Above Site</td>
                    <td>
                    <select name="spAuthorAboveSiteSZ" class="dimension">
                    <option>Select Dimension</option>                   
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spAuthorAboveSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spAuthorAboveSiteAL">
                    
                    <option value="left" <?php if($spAuthorAboveSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spAuthorAboveSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spAuthorAboveSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spAuthorBelowSite" type="checkbox" value="1" <?php if($spAuthorBelowSite=='1')echo 'checked="checked"'; ?> /> Below Site</td>
                    <td>
                    <select name="spAuthorBelowSiteSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spAuthorBelowSiteSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spAuthorBelowSiteAL">
                    
                    <option value="left" <?php if($spAuthorBelowSiteAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spAuthorBelowSiteAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spAuthorBelowSiteAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spAuthorAboveMenu" type="checkbox" value="1" <?php if($spAuthorAboveMenu=='1')echo 'checked="checked"'; ?> /> Above Menu</td>
                    <td>
                    <select name="spAuthorAboveMenuSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spAuthorAboveMenuSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spAuthorAboveMenuAL">
                    
                    <option value="left" <?php if($spAuthorAboveMenuAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spAuthorAboveMenuAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spAuthorAboveMenuAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
				<tr>
                    <td>				  	
                    <input name="spAuthorAboveContent" type="checkbox" value="1" <?php if($spAuthorAboveContent=='1')echo 'checked="checked"'; ?> /> Above Content</td>
                    <td>
                    <select name="spAuthorAboveContentSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spAuthorAboveContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spAuthorAboveContentAL">
                    
                    <option value="left" <?php if($spAuthorAboveContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spAuthorAboveContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spAuthorAboveContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>				  	
                    <input name="spAuthorBelowContent" type="checkbox" value="1" <?php if($spAuthorBelowContent=='1')echo 'checked="checked"'; ?> /> Below Content</td>
                    <td>
                    <select name="spAuthorBelowContentSZ" class="dimension">
                    <option>Select Dimension</option>                    
					<?php
						foreach($DisplayBannerSize as $bannerSize) {
						?>
						<option value="<?php echo $bannerSize; ?>" <?php if($spAuthorBelowContentSZ==$bannerSize)echo 'selected="selected"'; ?>><?php echo $bannerSize; ?></option>
						<?						
						}
					?> 					
                	<option value="add">Add New</option>                    
                    </select>
                    </td>
                    <td>
                    <select name="spAuthorBelowContentAL">
                    
                    <option value="left" <?php if($spAuthorBelowContentAL=='left')echo 'selected="selected"'; ?>>Left</option>
                    <option value="center" <?php if($spAuthorBelowContentAL=='center')echo 'selected="selected"'; ?>>Center</option>
                    <option value="right" <?php if($spAuthorBelowContentAL=='right')echo 'selected="selected"'; ?>>Right</option>
                    </select>
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
</table>

	  
        
		<div style="margin-top:0px;margin-left:5px;">
        	Exclude from the following pages &nbsp; <input type="text" name="spExcludeDisplay" value="<?php echo $spExcludeDisplay; ?>" size="20">
            <div style="font-size:12px;color:#AAA;padding:3px 0 0 0;">(Please enter the page numbers by commas where you do not wish to have the ads on. Example: 1,5,4)</div>
        </div>
	<?php } else {?>
		
		<div style="margin-top:15px;padding-left:0px">
        	<p style="text-align:left;margin-top:0px; padding-bottom:3px;">
            There are currently no Super Display Ads widgets active in your account.<br />
            <a href="http://superlinks.com/publisher/widget-setup-selection.php" target="_blank">Click here</a> to create a widget and request your ad tag.<br><br>
			Explore our <a href="http://superlinks.com/super-display-ads.php" target="_blank">Product Overview</a> page to learn more.
            </p>
        </div>
		
	<? } ?>
	
	<hr />
        
		<div style="margin-top:15px;">
        To view or edit your Super Display Ads settings for this website, please <a href="http://superlinks.com/login.php" target="_blank">click here</a>. If you are having difficulty with this plugin, please email <a href="mailto:support@superlinks.com">support@superlinks.com</a> to submit a trouble ticket or visit our <a href="http://superlinks.com/super-links-faq.php" target="_blank">FAQ</a>.
        </div>
		
  </div> 
  <div id="tab2">
	
	<?php    echo "<h3>" . __( 'Super Exit Links Options', 'sp_trdom' ) . "</h3>"; ?>
	
    <?php if($superlinksApproved == '1'){ ?>
	  <div style="margin-top:0px;"><input name="spProd2" type="checkbox" value="1" <?php if($spProd2=='1')echo 'checked="checked"'; ?> />Enable Super Exit Links on your website</div>  
        
		<div style="margin-top:0px;margin-left:5px;">
        	Exclude from the following pages &nbsp; <input type="text" name="spExcludeExit" value="<?php echo $spExcludeExit; ?>" size="20">
            <div style="font-size:12px;color:#AAA;padding:3px 0 0 0;">(Please enter the page numbers by commas where you do not wish to have the ads on. Example: 1,5,4)</div>
        </div>
    <?php }else{ ?>
    
    	<div style="margin-top:0px;padding-left:0px">
        	<p style="text-align:left;margin-top:0px; padding-bottom:3px;">
            There are currently no Super Exit Links widgets active in your account.<br />
            <a href="http://superlinks.com/publisher/widget-setup-selection.php" target="_blank">Click here</a> to create a widget and request your ad tag.<br><br>
			Explore our <a href="http://superlinks.com/super-links-interstitial-ads.php" target="_blank">Product Overview</a> page to learn more.
            </p>
        </div>
    
    <?php } ?>
           
	<hr />
        
		<div style="margin-top:15px;">
        To view or edit your Super Exit Links settings for this website, please <a href="http://superlinks.com/login.php" target="_blank">click here</a>. If you are having difficulty with this plugin, please email <a href="mailto:support@superlinks.com">support@superlinks.com</a> to submit a trouble ticket or visit our <a href="http://superlinks.com/super-links-faq.php" target="_blank">FAQ</a>.
        </div>
	
  </div> 
  <div id="tab3">
  	
	<?php    echo "<h3>" . __( 'Super Footer Options', 'sp_trdom' ) . "</h3>"; ?>
	
    <?php if($superfooterApproved == '1'){ ?>
	 <div style="margin-top:0px;"><input name="spProd3" type="checkbox" value="1" <?php if($spProd3=='1')echo 'checked="checked"'; ?> />Enable Super Footer on your website</div>  
        
		<div style="margin-top:0px;margin-left:5px;">
        	Exclude from the following pages &nbsp; <input type="text" name="spExcludeFooter" value="<?php echo $spExcludeFooter; ?>" size="20">
            <div style="font-size:12px;color:#AAA;padding:3px 0 0 0;">(Please enter the page numbers by commas where you do not wish to have the ads on. Example: 1,5,4)</div>
        </div>
        
	 <?php }else{ ?>
    
    	<div style="margin-top:0px;padding-left:0px">
        	<p style="text-align:left;margin-top:0px; padding-bottom:3px;">
            There are currently no Super Footer widgets active in your account.<br />
            <a href="http://superlinks.com/publisher/widget-setup-selection.php" target="_blank">Click here</a> to create a widget and request your ad tag.<br><br>
			Explore our <a href="http://superlinks.com/super-footer-display-ad-banners.php" target="_blank">Product Overview</a> page to learn more.
            </p>
        </div>
    
    <?php } ?>        
	
	
	<hr />
        
		<div style="margin-top:15px;">
        To view or edit your Super Footer settings for this website, please <a href="http://superlinks.com/login.php" target="_blank">click here</a>. If you are having difficulty with this plugin, please email <a href="mailto:support@superlinks.com">support@superlinks.com</a> to submit a trouble ticket or visit our <a href="http://superlinks.com/super-links-faq.php" target="_blank">FAQ</a>.
        </div>
		
  </div> 
  <div id="tab4">
  	
	<?php    echo "<h3>" . __( 'Super Tower Options', 'sp_trdom' ) . "</h3>"; ?>
    <?php if($supertowerApproved == '1'){ ?>
    	
	 <div style="margin-top:0px;"><input name="spProd4" type="checkbox" value="1" <?php if($spProd4=='1')echo 'checked="checked"'; ?> />Enable Super Tower on your website</div>  

            
		<div style="margin-top:0px;margin-left:5px;">
        	Exclude from the following pages &nbsp; <input type="text" name="spExcludeTower" value="<?php echo $spExcludeTower; ?>" size="20">
            <div style="font-size:12px;color:#AAA;padding:3px 0 0 0;">(Please enter the page numbers by commas where you do not wish to have the ads on. Example: 1,5,4)</div>
        </div>
        
	 <?php }else{ ?>
    
    	<div style="margin-top:0px;padding-left:0px">
        	<p style="text-align:left;margin-top:0px; padding-bottom:3px;">
             There are currently no Super Tower widgets active in your account.<br />
            <a href="http://superlinks.com/publisher/widget-setup-selection.php" target="_blank">Click here</a> to create a widget and request your ad tag.<br><br>
			Explore our <a href="http://superlinks.com/super-tower-display-ad-banners.php" target="_blank">Product Overview</a> page to learn more.
            </p>
        </div>
    
    <?php } ?>          
	
	<hr />
        
		<div style="margin-top:15px;">
        To view or edit your Super Tower settings for this website, please <a href="http://superlinks.com/login.php" target="_blank">click here</a>. If you are having difficulty with this plugin, please email <a href="mailto:support@superlinks.com">support@superlinks.com</a> to submit a trouble ticket or visit our <a href="http://superlinks.com/super-links-faq.php" target="_blank">FAQ</a>.
        </div>
	
  </div> 
  <div id="tab5">
  	
	<?php    echo "<h3>" . __( 'Super Interstitial Options', 'sp_trdom' ) . "</h3>"; ?>
    <?php if($superinterstitialApproved == '1'){ ?>
    	
	  <div style="margin-top:0px;"><input name="spProd5" type="checkbox" value="1" <?php if($spProd5=='1')echo 'checked="checked"'; ?> />Enable Super Interstitial on your website</div>  
        
		<div style="margin-top:0px;margin-left:5px;">
        	Exclude from the following pages &nbsp; <input type="text" name="spExcludeStitial" value="<?php echo $spExcludeStitial; ?>" size="20">
            <div style="font-size:12px;color:#AAA;padding:3px 0 0 0;">(Please enter the page numbers by commas where you do not wish to have the ads on. Example: 1,5,4)</div>
        </div>
        
	 <?php }else{ ?>
    
    	<div style="margin-top:0px;padding-left:0px">
        	<p style="text-align:left;margin-top:0px; padding-bottom:3px;">
            There are currently no Super Interstitial widgets active in your account.<br />
            <a href="http://superlinks.com/publisher/widget-setup-selection.php" target="_blank">Click here</a> to create a widget and request your ad tag.<br><br>
			Explore our <a href="http://superlinks.com/super-stitial-interstitial-ads.php" target="_blank">Product Overview</a> page to learn more.
            </p>
        </div>
    
    <?php } ?>    

		<hr />
        
		<div style="margin-top:15px;">
        To view or edit your Super Interstitial settings for this website, please <a href="http://superlinks.com/login.php" target="_blank">click here</a>. If you are having difficulty with this plugin, please email <a href="mailto:support@superlinks.com">support@superlinks.com</a> to submit a trouble ticket or visit our <a href="http://superlinks.com/super-links-faq.php" target="_blank">FAQ</a>.
        </div>	
  </div> 
</div> 
 
<script type="text/javascript"> 
  jQuery("#usual1 ul").idTabs(); 
</script>
         
     
        <p class="submit">
        <input type="submit" name="SaveBtn" class="save-button" value="<?php _e('Save', 'sp_trdom' ) ?>" /> <input type="submit" name="Submit" class="red-button" value="<?php _e('Save & Proceed To Widget Area', 'sp_trdom' ) ?>" />
        </p>
   		<? } ?> 

<!-- left container -->

</form>
<? echo "<img style=\"display:none;\" src=\"" . plugins_url( 'images/minus-icon.gif' , __FILE__ ) . "\" >"; ?>