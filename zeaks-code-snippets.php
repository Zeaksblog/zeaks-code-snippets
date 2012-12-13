<?php
/*
Plugin Name: Zeaks Code snippets
Plugin URI: http://zeaks.org
Description: Add code snippets to your posts.
Version: 2.1.1
Author: Zeaks
Author URI: http://zeaks.org
License: GPL2
*/

// Enqueue Scripts

  function sunprettify_script_loader() {
        wp_enqueue_script('zeaks_code_highlight', plugins_url( 'js/highlight.pack.js', __FILE__ ), array('jquery'),'', false );
      }
   add_action('wp_enqueue_scripts', 'sunprettify_script_loader');


// Enqueue Styles

  function sunprettify_style_loader() {
    wp_enqueue_style('zeaks_code_style', plugins_url( 'css/style.css', __FILE__ ), true ,'1.0', 'all' );
      }
  add_action('wp_enqueue_scripts', 'sunprettify_style_loader', 50 );


// Loading the needed prettyprint() function in the body

  function sunprettify_auto_loading() { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
   jQuery('pre code').each(function(i, e) {
    hljs.highlightBlock(e, '    ')
   });
});
</script>
<?php }
add_action('wp_footer', 'sunprettify_auto_loading');


// Necessary to display the shortcode in comments
		add_filter( 'get_comment_text', 'do_shortcode' ); //Warning, doing it this way adds ALL shortcodes to forum topics
		add_filter( 'bbp_get_reply_content', 'do_shortcode' ); //Warning, doing it this way adds ALL shortcodes to forum replies
		add_filter( 'bp_get_activity_content_body', 'do_shortcode' ); //Warning, doing it this way adds ALL shortcodes to forum replies
		add_filter( 'bp_get_the_topic_post_content', 'do_shortcode' ); //Warning, doing it this way adds ALL shortcodes to forum replies
	
class zeaks_code_snippets {

	function __construct()
{
		remove_filter('the_content','wpautop');
		add_filter('the_content','wpautop',99);
		add_shortcode('code', array(&$this,'replace_code'));
		add_filter('the_excerpt_rss',array(&$this,'strip_shortcodes'));
		add_filter('the_content_rss',array(&$this,'strip_shortcodes'));
		add_filter('the_excerpt',array(&$this,'strip_shortcodes'),1);
		add_filter('the_content',array(&$this,'strip_shortcodes'),99);
		
	if(is_admin()) {
		$plugin = plugin_basename(__FILE__); 
	}
}
	
	function replace_code($atts,$content)
	{
		if(version_compare(PHP_VERSION,'5.2.3')== -1) {
			$content ='<pre>'.htmlspecialchars($content,ENT_NOQUOTES,'UTF-8').'</pre>';
		} else {
			$content ='<pre><code>'.htmlspecialchars($content,ENT_NOQUOTES,'UTF-8',false).'</code></pre>';
		}
		
		return $content;
	}

	function strip_shortcodes($content)
	{
		$content=str_replace('[code]','<pre><code>',$content);
		$content=str_replace('[/code]','</pre></code>',$content);
		return $content;
	}

}

$zeaks_code_snippets = new zeaks_code_snippets();
?>