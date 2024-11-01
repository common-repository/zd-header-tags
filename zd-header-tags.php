<?php
/*
Plugin Name: ZD Header Tags
Plugin URI: http://www.proloy.me/projects/wordpress-plugins/zd-header-tags/
Description: This plugin helps you to insert separate title, meta keyword, meta description for each post or page. You will also be able to insert style sheets, javascript files and other meta tags with admin option page. Its a great tool of SEO.
Author: Proloy Chakroborty
Version: 2.1
Author URI: http://www.proloy.me/
*/

 
/*Copyright (c) 2008, Proloy Chakroborty
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of Proloy Chakroborty nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY Proloy Chakroborty ''AS IS'' AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL Proloy Chakroborty BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

////////////////////////////////////////////////////////////////////////////////////////////////////
// Filename: zd-header-tags.php
// Creation date: 30 January 2009
// Last Modification date: 15 October 2009
// Version history:
//	1.0.0 - 30 January 2009: Beta Release
//	1.0.1 - 31 January 2009: Empty Keyword and Description Bug Fixed
//	1.1.0 - 31 January 2009: Home Keywords and Description added. Also use home page keywords and descrition as default.
//	2.0   - 15 October 2009: Meta Box in Writer Screen. Enhanced Option Page. Title Editing
//	2.1   - 16 October 2009: Bug Fixing - Home Page title now don't have double separators
////////////////////////////////////////////////////////////////////////////////////////////////////

//Check minimum required WordPress Version
global $wp_version;
$exit_msg = '\'ZD Header Tags\' require WordPress 2.7 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
if (version_compare($wp_version, "2.7", "<")) {
	exit($exit_msg);
}

//Make sure we're running an up-to-date version of PHP
$phpVersion = phpversion();
$verArray = explode('.', $phpVersion);
$error_msg = "'ZD Header Tags' requires PHP version 5 or newer.<br>Your server is running version $phpVersion<br>";
if( (int)$verArray[0] < 5 ) {
	exit($error_msg);
}

class ZDHeaderTags {
	//Name for our options in the DB
 	private $db_option = 'ZDHeaderTags_options';
	private $plugin_url;
	private $plugin_dir;
	private $plugin_info = array('name'=>'ZD Header Tags',
							 'version'=>'2.1',
							 'date'=>'2009-10-16',
							 'pluginhome'=>'http://www.proloy.me/projects/wordpress-plugins/zd-youtube-flv-player/',
							 'authorhome'=>'http://www.proloy.me/',
							 'rateplugin'=>'http://www.proloy.me/projects/wordpress-plugins/zd-header-tags/',
							 'support'=>'mailto:support@proloy.me',
							 'more'=>'http://www.proloy.me/projects/wordpress-plugins/');
		
	//Initialize WordPress hooks
 	public function __construct() {
 		$this->plugin_dir = dirname(__FILE__);
		$this->plugin_url = defined('WP_PLUGIN_URL') ? WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 
		
		//Add Meta Tags, Style Tags and Script Tags
		add_action('wp_head', array(&$this, 'updateHeaderTags'));
		
		//Modifiy Page Title
		add_filter('wp_title', array(&$this, 'updateTitle'));
		
		//Add Options Page
		add_action('admin_menu', array(&$this, 'addAdminMenu'));
		
		//Add Meta Box on Post/Page Writer
		add_action('admin_menu', array(&$this, 'addMetabox'));
		
		//Save Meta Data
		add_action('save_post', array(&$this, 'saveMeta'));
    }
    
    //Set-up DB Variables
	public function install() {
		$options = array('home_title' => '', 'home_keyword' => '', 'home_description' => '', 'use-default' => 'yes', 'styles' => '', 'javascripts' => '', 'metas' => '', 'title_sep' => '', 'title_seplocation' => 'left', 'disable-custom-field' => 'no', 'use-excerpt' => 'no', 'use-post-tags' => 'no', 'keywords-post-tags' => 'both', 'excerpt-count' => '50', 'description-excerpt' => 'description');
              
		update_option($this->db_option, $options);
	}
    
 	//Add Admin Options page
	public function addAdminMenu() {
		$plugin_page = add_options_page('ZD Header Tags Options', 'ZD Header Tags', 10, basename(__FILE__), array(&$this, 'handleAdminOptions'));
		add_action('admin_head-'. $plugin_page, array(&$this, 'myplugin_admin_header'));		
	}
	//Add Javascript to Admin <header></header>
	public function myplugin_admin_header(){
		wp_enqueue_script('jquery');
		echo '<script type="text/javascript" src="'.$this->plugin_url.'/js/header.js'.'"></script>'."\n";
		echo '<link href="'.$this->plugin_url.'/zdstyle.css'.'" rel="stylesheet" type="text/css" />'."\n";
	}
	
	//Add Meta Box
	public function addMetabox() {
		$options = get_option($this->db_option);
		
		if( function_exists('add_meta_box')) {
		    add_meta_box('zdheadertag', 'ZD Header Tags', array(&$this, 'metaBoxHTML'), 'post', 'side','high');
		    add_meta_box('zdheadertag', 'ZD Header Tags', array(&$this, 'metaBoxHTML'), 'page', 'side','high');
  		}
  		if( function_exists('remove_meta_box')) {
  			if($options['disable-custom-field'] == "yes") {
  				remove_meta_box('postcustom', 'post', 'normal');
				remove_meta_box('pagecustomdiv', 'page', 'normal');
  			}  			
		}
	}
	//Meta Box HTML
	public function metaBoxHTML() {
		global $post;
        
        //DB Plugin Options
		$options = get_option($this->db_option);
        
        //Post Meta
		$meta_keywords = get_post_meta($post->ID, 'keywords', true);
		$meta_title = get_post_meta($post->ID, 'title', true);
        $meta_description = get_post_meta($post->ID, 'description', true);
                
		echo '<input type="hidden" name="zdheadertag_noncename" id="zdheadertag_noncename" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />';
		echo '<p><label for="meta_title"><strong>Title:</strong><br /><input type="text" name="meta_title" id="meta_title" style="width:100%;" value="'.$meta_title.'" /></label></p>';
 		echo '<p><label for="meta_keyword"><strong>Keyword:</strong><br /><input type="text" name="meta_keyword" id="meta_keyword" style="width:100%;" value="'.$meta_keywords.'" /></label></p>';
        echo '<p><label for="meta_description"><strong>Description:</strong><br /><input type="text" name="meta_description" id="meta_description" style="width:100%;" value="'.$meta_description.'" /></label></p>';
             
        
	}
	//Save Post Meta
	public function saveMeta($post_id) {
        //DB Plugin Options
		$options = get_option($this->db_option);
       
		if(!wp_verify_nonce($_POST['zdheadertag_noncename'], plugin_basename(__FILE__))) {
		    return $post_id;
  		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return $post_id;		
		}
		if ('page' == $_POST['post_type']) {
		    if (!current_user_can('edit_page', $post_id)){
		    	return $post_id;
		    }		      
		} else {
		    if (!current_user_can('edit_post', $post_id)) {
		    	return $post_id;
		    }
		}
		
		update_post_meta($post_id, 'title', $_POST['meta_title']);
		update_post_meta($post_id, 'keywords', $_POST['meta_keyword']);
        update_post_meta($post_id, 'description', $_POST['meta_description']);
	}
	
    //Add <meta>, <style> and <script> Tags
	public function updateHeaderTags() {
		global $wp_query;
		
		//DB Plugin Options
		$options = get_option($this->db_option);
		
		//Post Metas
		$meta_keywords = get_post_meta($wp_query->post->ID, 'keywords', true);
		$meta_description = get_post_meta($wp_query->post->ID, 'description', true);
		
        //Excerpts
        if(is_single()) {
            $current_post = get_post($wp_query->post->ID);
        }else if(is_page()){
            $current_post = get_page($wp_query->post->ID);
        }
        
        $excerpt = $current_post->post_content;
        $excerpt = $this->getExcerpt($excerpt,$options['excerpt-count']);
        
        //Variables for Keywords and Description
        $keywords = "";
        $description = "";
        
        //Create Excerpt
        if(empty($meta_description)) {
            if($options['use-excerpt'] == 'yes') {
                $description = $excerpt;
            }else {
                $description = $meta_description;
            }
        }else {
            if($options['description-excerpt'] == "excerpt") {
                $description = $excerpt;
            }else {
                $description = $meta_description;
            }            
        }        
        
        //Contruct Keywords from tags
        $keywords = $meta_keywords;  
        if($options['keywords-post-tags'] == 'both') {
            $tags = wp_get_post_tags($wp_query->post->ID);
        	if($tags) {
        		$tag_keywords = array();
        		$y = 0;
        		foreach($tags as $tag) {
        			$tag_keywords[$y] = $tag->name;
        			$y++;
        		}
                if(!empty($keywords)) {
                    $keywords .= ", ";
                }                                
        		$keywords .= implode(", ", $tag_keywords);
        	}
        }else if($options['keywords-post-tags'] == 'posttags') {
            $tags = wp_get_post_tags($wp_query->post->ID);
        	if($tags) {
        		$tag_keywords = array();
        		$y = 0;
        		foreach($tags as $tag) {
        			$tag_keywords[$y] = $tag->name;
        			$y++;
        		}                
        		$keywords = implode(",", $tag_keywords);
        	}
        }
               
		//<meta> Keyword and Description of Home/Front Page
		if(is_home() or is_front_page()) {
			if(!empty($options["home_description"])) {
				echo '<meta name="description" content="'.$options["home_description"].'" />'."\n";
			}
			if(!empty($options["home_keyword"])) {
				echo '<meta name="keywords" content="'.$options["home_keyword"].'" />'."\n";
			}
		}else if (is_single() or is_page()) {
			if(!empty($description)) {
				echo '<meta name="description" content="'.$description.'" />'."\n";
			}else if($options["use-default"] == "yes") {
				if(!empty($options["home_description"])) {
					echo '<meta name="description" content="'.$options["home_description"].'" />'."\n";
				}
			}
			if(!empty($keywords)) {
				echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
			}else if($options["use-default"] == "yes") {
				if(!empty($options["home_keyword"])) {
					echo '<meta name="keywords" content="'.$options["home_keyword"].'" />'."\n";
				}
			}
		} else {
			if($options["use-default"] == "yes") {
				if(!empty($options["home_description"])) {
					echo '<meta name="description" content="'.$options["home_description"].'" />'."\n";
				}
				if(!empty($options["home_keyword"])) {
					echo '<meta name="keywords" content="'.$options["home_keyword"].'" />'."\n";
				}
			}
		}
		
		//Other <meta> Tags
		if(!empty($options["metas"])) {
			echo stripslashes(html_entity_decode($options["metas"], ENT_QUOTES))."\n";
		}
			
		//<style> Tags
		if(!empty($options["styles"])) {
			echo stripslashes(html_entity_decode($options["styles"], ENT_QUOTES))."\n";
		}
			
		//<script> Tags
		if(!empty($options["javascripts"])) {
			echo stripslashes(html_entity_decode($options["javascripts"], ENT_QUOTES))."\n";
		}
	}
	
	//Update <title> Tag
	public function updateTitle($title) {
		global $wp_query;
		
		//DB Plugin Options
		$options = get_option($this->db_option);
		
		//Post Metas
		$meta_title = get_post_meta($wp_query->post->ID, 'title', true);

		//<title> of Home Page
		if(is_home() or is_front_page()) {
			if(!empty($options["home_title"])) {
				$title = stripslashes(html_entity_decode($options["home_title"]));				
			}else {
				$title = stripslashes(html_entity_decode($title));							
			}								
		}
		
		//<title> of Post/Page
		if (is_single() or is_page()) {
			if(!empty($meta_title)) {
				if($options["title_seplocation"] == "left") {
					$title = stripslashes(html_entity_decode($options["title_sep"].$meta_title));
				}else if($options["title_seplocation"] == "right") {
					$title = stripslashes(html_entity_decode($meta_title.$options["title_sep"]));
				}
			}else {
				if($options["title_seplocation"] == "left") {
					$title = stripslashes(html_entity_decode($options["title_sep"].$title));
				}else if($options["title_seplocation"] == "right") {
					$title = stripslashes(html_entity_decode($title.$options["title_sep"]));
				}			
			}
		}else {
			if($options["title_seplocation"] == "left") {
				$title = stripslashes(html_entity_decode($options["title_sep"].$title));
			}else if($options["title_seplocation"] == "right") {
				$title = stripslashes(html_entity_decode($title.$options["title_sep"]));
			}
		}
		
		//Return Modified Title
		return $title;
	}
	
	//Handles Admin Page Options
	public function handleAdminOptions() {
		//Plugin Information
		$plugin_info = $this->plugin_info;
		
		//DB Plugin Options
		$options = get_option($this->db_option);
		
		//Form Action URL
		$action_url = $_SERVER['REQUEST_URI'];
              
		if (isset($_POST['submitted'])) {
			//check security
			check_admin_referer('zdheadertags-nonce');
	
			$options['home_title'] = stripslashes(htmlentities($_POST['home_title'], ENT_QUOTES));
			$options['home_keyword'] = stripslashes(htmlentities($_POST['home_keyword'], ENT_QUOTES));
			$options['home_description'] = stripslashes(htmlentities($_POST['home_description'], ENT_QUOTES));
			$options['use-default'] = stripslashes(htmlentities($_POST['use-default'], ENT_QUOTES));
			$options['styles'] = stripslashes(htmlentities($_POST['styles'], ENT_QUOTES));
			$options['javascripts'] = stripslashes(htmlentities($_POST['javascripts'], ENT_QUOTES));
			$options['metas'] = stripslashes(htmlentities($_POST['metas'], ENT_QUOTES));
			$options['title_sep'] = stripslashes(htmlentities($_POST['title_sep'], ENT_QUOTES));
			$options['title_seplocation'] = stripslashes(htmlentities($_POST['title_seplocation'], ENT_QUOTES));
			$options['disable-custom-field'] = stripslashes(htmlentities($_POST['disable-custom-field'], ENT_QUOTES));
            $options['use-excerpt'] = stripslashes(htmlentities($_POST['use-excerpt'], ENT_QUOTES));
            $options['use-post-tags'] = stripslashes(htmlentities($_POST['use-post-tags'], ENT_QUOTES));
            $options['keywords-post-tags'] = stripslashes(htmlentities($_POST['keywords-post-tags'], ENT_QUOTES));
            $options['excerpt-count'] = stripslashes(htmlentities($_POST['excerpt-count'], ENT_QUOTES));
            $options['description-excerpt'] = stripslashes(htmlentities($_POST['description-excerpt'], ENT_QUOTES));
	
			update_option($this->db_option, $options);
				
			echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
		}		
			
		include('zd-ht-options.php');
	}
    
    //Return formatted Description
    private function getExcerpt($content, $length) {
        $content = stripslashes(html_entity_decode($content));
        $content = strip_tags($content);
        $content = strip_shortcodes($content);
        
        for($i=0; $i<100; $i++) {
            $content = str_replace("\n"," ",$content);
            $content = str_replace("\t","",$content);
            $content = str_replace("\n\t","",$content);
            $content = str_replace("\0","",$content);
            $content = str_replace("\x0B","",$content);
            $content = str_replace("\r","",$content);
            $content = str_replace("/","",$content);
            $content = str_replace("\"","",$content);
            $content = str_replace("'","",$content);
            $content = str_replace(":","",$content);
            $content = str_replace("(","",$content);
            $content = str_replace(")","",$content);
            $content = str_replace("{","",$content);
            $content = str_replace("}","",$content);
            $content = str_replace("]","",$content);
            $content = str_replace("[","",$content);
        }
                  
        $excerpt = explode(" ",$content);
        if(count($excerpt) > 1) {
            for($x=0; $x<count($excerpt); $x++) {
               if($excerpt[$x] != ""){
                   $new_excerpt[] = $excerpt[$x];
               }
            }
            $new_excerpt = array_slice($new_excerpt,0,$length);
            $description = implode(" ",$new_excerpt);
        }else {
            $description = $content;
        }
        return $description; 
    }
}

//Initialize Plugin
if (class_exists('ZDHeaderTags')) {
	$ZDHeaderTags = new ZDHeaderTags();
	if (isset($ZDHeaderTags)) {
		register_activation_hook(__FILE__, array(&$ZDHeaderTags, 'install'));
	}
}
?>