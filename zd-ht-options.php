<div class="wrap">
  <h2><?php echo $plugin_info['name']; ?></h2>
  <div id="poststuff" class="mainblock">
    <!--Admin Page Right Column //start-->
    <div id="plugin-right">
      <!--Information Box //start-->
      <div class="stuffbox">
        <h3>Information:</h3>
        <div class="inside">
          <ul>
            <li><strong>Version:&nbsp;</strong><?php echo $plugin_info['version']; ?></li>
            <li><strong>Release Date:&nbsp;</strong><?php echo $plugin_info['date']; ?></li>
            <li><a href="<?php echo $plugin_info['pluginhome']; ?>" target="_blank">Plugin Homepage</a></li>
            <li><a href="<?php echo $plugin_info['rateplugin']; ?>" target="_blank">Rate this plugin</a></li>
            <li><a href="<?php echo $plugin_info['support']; ?>">Support and Help</a></li>
            <li><a href="<?php echo $plugin_info['authorhome']; ?>" target="_blank">Author Homepage</a></li>
            <li><a href="<?php echo $plugin_info['more']; ?>" target="_blank">More WordPress Plugins</a></li>
          </ul>
        </div>
      </div>
      <!--Information Box //end-->
      <!--Follow me on Box //start-->
      <div class="stuffbox">
        <h3>Follow me on:</h3>
        <div class="inside">
          <ul class="zdinfo">
            <li class="fb"><a href="http://www.facebook.com/people/Proloy-Chakroborty/1424058392" title="Follow me on Facebook" target="_blank">Facebook</a></li>
            <li class="ms"><a href="http://www.myspace.com/proloy" title="Follow me on MySpace" target="_blank">MySpace</a></li>
            <li class="tw"><a href="http://twitter.com/proloyc" title="Follow me on Twitter" target="_blank">Twitter</a></li>
            <li class="lin"><a href="http://www.linkedin.com/in/proloy" title="Follow me on LinkedIn" target="_blank">LinkedIn</a></li>
            <li class="plx"><a href="http://proloy.myplaxo.com/" title="Follow me on Plaxo" target="_blank">Plaxo</a></li>
          </ul>
        </div>
      </div>
      <!--Follow me on Box //end-->
      <!--Donate Box //start-->
      <div class="stuffbox">
        <h3>Donate:</h3>
        <div class="inside">
          <p>Please support me by donating as such as you can, so that I get motivation to develop this plugin and more plugins.</p>
          <p align="center"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8831827" target="_blank"><img src="http://images.proloy.me/wp/paypal.gif" alt="Donate to Support Me" /></a></p>
        </div>
      </div>
      <!--Donate Box //end-->
    </div>
  <!--Admin Page Right Column //end-->

  <!--Admin Page Left Column //start-->
  <div id="plugin-left">
      <form action="<?php echo $action_url ?>" method="post" name="ZDHeaderTags" id="ZDHeaderTags">
        <input type="hidden" name="submitted" value="1" />
        <?php wp_nonce_field('zdheadertags-nonce'); ?>
        <!--Options //start-->
        <div class="stuffbox">
          <h3>Home Page Title:</h3>
          <div class="inside">
          	<label for="home_title"><input type="text" name="home_title" id="home_title" style="width:98%;" value="<?php echo stripslashes(html_entity_decode($options['home_title'], ENT_QUOTES)); ?>" /></label>
            <p><em>This Title will be inserted within &lt;title&gt; and &lt;/title&gt; of your home page. Make sure your theme's header.php have &lt;title&gt;wp_title()&lt;/title&gt;. There may be some other codes along with wp_title(), which may not provide you desired result.<br />
            <strong>You May Use: &lt;title&gt;&lt;?php wp_title('', 'false'); ?&gt;&lt;?php bloginfo('name'); ?&gt;&lt;/title&gt;</strong></em></p>
          </div>
        </div>
        		
        <div class="stuffbox">
          <h3>Home Page Keywords:</h3>
          <div class="inside">
          	<label for="home_keyword"><input type="text" name="home_keyword" id="home_keyword" style="width:98%;" value="<?php echo stripslashes(html_entity_decode($options['home_keyword'], ENT_QUOTES)); ?>" /></label>
            <p><em>This keywords will be used for your blog home page.</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>Home Page Description:</h3>
          <div class="inside">
          	<label for="home_description"><input type="text" name="home_description" id="home_description" style="width:98%;" value="<?php echo stripslashes(html_entity_decode($options['home_description'], ENT_QUOTES)); ?>" /></label>
            <p><em>This description will be used for your blog home page.</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>Title Separator:</h3>
          <div class="inside">
          	<label for="title_sep"><input name="title_sep" type="text" id="title_sep" size="75" value="<?php echo stripslashes(html_entity_decode($options['title_sep'], ENT_QUOTES)); ?>" /></label>
            <br />
            <p><label for="title_seplocation"><strong>Separator Location</strong>&nbsp;
            <select name="title_seplocation" id="title_seplocation" style="width:100px;">
            <?php if($options['title_seplocation'] == "yes") { ?>
                <option value="left" selected="selected">Left</option>
            <?php }else { ?>
                <option value="left">Left</option>
            <?php } ?>
            <?php if($options['title_seplocation'] == "right") { ?>
                <option value="right" selected="selected">Right</option>
            <?php }else { ?>
                <option value="right">Right</option>
            <?php } ?>
            </select>
            </label></p>
            <p><em>This Separator will be used for all &lt;title&gt; outputs</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>CSS Sytle Sheet &lt;link&gt;:</h3>
          <div class="inside">
          	<label for="styles"><textarea name="styles" cols="70" rows="8" id="styles" style="width:100%;"><?php echo stripslashes(html_entity_decode($options['styles'], ENT_QUOTES)); ?></textarea></label>
            <p><em>Paste you style HTML here. Example: &lt;link href="style.css" rel="stylesheet" type="text/css" media="screen" /&gt;. Enter each tag in separate line.</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>Meta Tag &lt;meta&gt;:</h3>
          <div class="inside">
          	<label for="smetas"><textarea name="metas" cols="70" rows="8" id="metas" style="width:100%;"><?php echo stripslashes(html_entity_decode($options['metas'], ENT_QUOTES)); ?></textarea></label>
            <p><em>Paste you meta HTML here. Example: &lt;meta name='robots' content='noindex,nofollow' /&gt;. Enter each tag in separate line.</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>JavaScript &lt;script&gt;:</h3>
          <div class="inside">
          	<label for="javascripts"><textarea name="javascripts" cols="70" rows="8" id="javascripts" style="width:100%;"><?php echo stripslashes(html_entity_decode($options['javascripts'], ENT_QUOTES)); ?></textarea></label>
            <p><em>Paste you script HTML here. Example: &lt;script type='text/javascript' src='jquery.js'&gt;&lt;/script&gt;. Enter each tag in separate line.</em></p>
          </div>
        </div>
        
        <div class="stuffbox">
          <h3>Options:</h3>
          <div class="inside">
          	 <label for="use-default"><strong>Do you want to use Home Keywords and Home Description as default?</strong>&nbsp;
                <select name="use-default" id="use-default" style="width:70px;">
				<?php if($options['use-default'] == "yes") { ?>
                    <option value="yes" selected="selected">Yes</option>
                <?php }else { ?>
                    <option value="yes">Yes</option>
                <?php } ?>
                <?php if($options['use-default'] == "no") { ?>
                    <option value="no" selected="selected">No</option>
                <?php }else { ?>
                    <option value="no">No</option>
                <?php } ?>          
                </select>
                </label>
                <p><em>When on post/page/category/archive keywords and description is not available, home keywords and description will be inserted into meta.</em></p>
                
                <label for="disable-custom-field"><strong>Do you want to disable Custom Fields Box from Post/Page Writer Screen?</strong>&nbsp;
                <select name="disable-custom-field" id="disable-custom-field" style="width:70px;">
				<?php if($options['disable-custom-field'] == "yes") { ?>
                    <option value="yes" selected="selected">Yes</option>
                <?php }else { ?>
                    <option value="yes">Yes</option>
                <?php } ?>
                <?php if($options['disable-custom-field'] == "no") { ?>
                    <option value="no" selected="selected">No</option>
                <?php }else { ?>
                    <option value="no">No</option>
                <?php } ?>          
                </select>
                </label>
                
                <label for="use-excerpt"><strong>Do you want to use Post/Page Excerpt as Meta Description?</strong>&nbsp;
                <select name="use-excerpt" id="use-excerpt" style="width:70px;">
				<?php if($options['use-excerpt'] == "yes") { ?>
                    <option value="yes" selected="selected">Yes</option>
                <?php }else { ?>
                    <option value="yes">Yes</option>
                <?php } ?>
                <?php if($options['use-excerpt'] == "no") { ?>
                    <option value="no" selected="selected">No</option>
                <?php }else { ?>
                    <option value="no">No</option>
                <?php } ?>          
                </select>
                </label>
                
                <label for="excerpt-count"><strong>How many words of content you want use as Meta Desciption?</strong>&nbsp;
                <input type="text" name="excerpt-count" id="excerpt-count" style="width:50px;" value="<?php echo $options['excerpt-count']; ?>" />
                </label>
                
                <label for="description-excerpt"><strong>If a Post aslo have Descrition? Use:</strong>&nbsp;
                <select name="description-excerpt" id="description-excerpt" style="width:100px;">
				<?php if($options['description-excerpt'] == "both") { ?>
                    <option value="description" selected="selected">Description</option>
                <?php }else { ?>
                    <option value="description">Description</option>
                <?php } ?>
                <?php if($options['description-excerpt'] == "keywords") { ?>
                    <option value="excerpt" selected="selected">Excerpt</option>
                <?php }else { ?>
                    <option value="excerpt">Excerpt</option>
                <?php } ?>
                </select>
                </label>
                
                <label for="use-post-tags"><strong>Do you want to use Post Tags as Meta Keywords?</strong>&nbsp;
                <select name="use-post-tags" id="use-post-tags" style="width:70px;">
				<?php if($options['use-post-tags'] == "yes") { ?>
                    <option value="yes" selected="selected">Yes</option>
                <?php }else { ?>
                    <option value="yes">Yes</option>
                <?php } ?>
                <?php if($options['use-post-tags'] == "no") { ?>
                    <option value="no" selected="selected">No</option>
                <?php }else { ?>
                    <option value="no">No</option>
                <?php } ?>          
                </select>
                </label>
                
                <label for="keywords-post-tags"><strong>If both Keywords and Post Tags are available? Use:</strong>&nbsp;
                <select name="keywords-post-tags" id="keywords-post-tags" style="width:70px;">
				<?php if($options['keywords-post-tags'] == "both") { ?>
                    <option value="both" selected="selected">Both</option>
                <?php }else { ?>
                    <option value="both">Both</option>
                <?php } ?>
                <?php if($options['keywords-post-tags'] == "keywords") { ?>
                    <option value="keywords" selected="selected">Keywords</option>
                <?php }else { ?>
                    <option value="keywords">Keywords</option>
                <?php } ?>
                <?php if($options['keywords-post-tags'] == "posttags") { ?>
                    <option value="posttags" selected="selected">Post Tags</option>
                <?php }else { ?>
                    <option value="posttags">Post Tags</option>
                <?php } ?>
                </select>
                </label>                
          </div>
        </div>
        <!--Options //end-->
        <div class="submit">
          <input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
        </div>
      </form>
    <h5>WordPress plugin by: <a href="http://www.proloy.me/" target="_blank">Proloy Chakroborty</a></h5>
  </div>
  <!--Admin Page Left Column //end-->
</div>
</div>
