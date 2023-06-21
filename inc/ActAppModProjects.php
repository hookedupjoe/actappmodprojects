<?php
/*
Common access points for this plugin
*/

/* package: actappmodprojects */

class ActAppModProjects {
	private static $instance;
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new ActAppModProjects();
		}
		return self::$instance;
	}

	public static function init() {
		//self::do_something_on_startup();
	}

	public static function pluginDir() {
		return ACTAPP_PROJECTS_PLUGIN_DIR;
	}

	//Common function to be moved to common builder class later
	public static function getCardUI($theDoc) {
		$tmpURL = $theDoc["url"];
		$tmpRet = '<a class="card" href="'.$tmpURL.'">';
		$tmpFeaturedURL = $theDoc["thumbnail"];
		if( $tmpFeaturedURL != ""){
			$tmpRet .= '<div class="image">
			<img style="min-height:125px;max-height:125px;object-fit: cover;" src="'.$tmpFeaturedURL.'">
		</div>';
		}

		$tmpRet .= '<div class="content"><div class="header">'.$theDoc["title"].'</div>';
	  
		$tmpExcerpt = $theDoc["excerpt"];
		if( $tmpExcerpt != ""){
			$tmpRet .= '<div class="description">
			'.$tmpExcerpt.'
			</div>';
		}
		$tmpRet .= '</div>';

		
		$tmpRet .= '</a>';
		return $tmpRet;
	}

	public static function getProjectCard($theDoc) {
		return self::getCardUI($theDoc);
	}
	
	public static function processSidebarForCategory() {
		$tmpCats = get_the_category();
		foreach ($tmpCats as $aCat) {
			echo self::getCategoryLink($aCat);
		}
		echo self::getFullListLink();
		return true;
	}
	
	public static function getCategoryLink($theCat){
		$tmpRet = '';
		$tmpURL = site_url( '/projects/?category_name='.$theCat->slug);
		$tmpRet .= '<div class="ui list"><div class="item"><a href="'.$tmpURL.'" class="ui button fluid circular black rounded basic">More '.$theCat->name.'</a></div></div>';
		return $tmpRet;
	}

	public static function getFullListLink(){
		$tmpRet = '';
		$tmpURL = site_url( '/projects');
		$tmpRet .= '<div class="ui list"><div class="item"><a href="'.$tmpURL.'" class="ui button fluid circular black rounded basic">See all projects</a></div></div>';
		return $tmpRet;
	}
	
	public static function getNothingFound(){
		return ('<div class="ui message orange large">Nothing found</div>');
	}

	public static function processArchivePosts($theShowCount = 0) {
		$tmpRet = '';
		$tmpSummary = [];

		while ( have_posts() ) :
			the_post();
			
			$tmpCat = get_the_category();
			
			foreach ($tmpCat as $aCat) {
				$tmpCatName = $aCat->name;
				$tmpCatSlug = $aCat->slug;
				$tmpCurr = $tmpSummary[$tmpCatSlug];

				$tmpRec = array(
					"id"=>get_the_ID(),
					"title"=>get_the_title(),
					"excerpt"=>get_the_excerpt(),
					"url"=>get_the_permalink(),
					"thumbnail"=>get_the_post_thumbnail_url(),
				);
				
				if( $tmpCurr == null){
					$tmpCurr = array(
						"name"=>$tmpCatName, 
						"slug"=>$tmpCatSlug
					);
					$tmpCurr["posts"] = array();
					
				}
				array_push($tmpCurr["posts"], $tmpRec);
				$tmpSummary[$tmpCatSlug] = $tmpCurr;
			}
		endwhile;
		$tmpShowMax = 3;
		if( $theShowCount == 0){
			$tmpShowMax = 999;
		}
		$tmpCardsDiv = "three";
		foreach($tmpSummary as $aKey => $tmpCurr) {
			$tmpURL = site_url( '/projects/?category_name='.$tmpCurr["slug"]);
			$tmpCards = array();
			$tmpPosts = $tmpCurr["posts"];
			$tmpCount = sizeof($tmpPosts);
			$tmpShowCount = $tmpCount;
			$tmpShowMore = false;
			if( $tmpShowCount > $tmpShowMax ){
				$tmpShowMore = true;
				$tmpShowCount = $tmpShowMax;
			}

			if( $theShowCount > 0){
				echo ('<div class="ui header blue medium">');
				echo $tmpPrefix = 'Latest '.$tmpCurr["name"];
				echo ('</div>');
			}

			echo ('<div class="ui cards stackable '.$tmpCardsDiv.'"><div></div>'); //--- Extra div to fix first child oddity on stackable cards

			for( $i = 0 ; $i < $tmpShowCount ; $i++){
				echo self::getProjectCard($tmpPosts[$i]);
			}
			echo('</div>');
			if( $tmpShowMore == true ){
				echo('<a class="ui fluid basic blue circular small button" href="'.$tmpURL.'">See all '.$tmpCurr["name"].'</a>');
			}
		}
		  
		
		return $tmpRet;

	}
	
}

add_action( 'init', array( 'ActAppModProjects', 'init' ) );
