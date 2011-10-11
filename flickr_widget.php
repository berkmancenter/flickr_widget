<?php
/*
Plugin Name: Flickr Widget
Plugin URI: http://donncha.wordpress.com/flickr-widget/
Description: A widget which will display your latest Flickr photos.
Author: Donncha O Caoimh
Version: 0.1
Author URI: http://inphotos.org/

Installing
1. Make sure you have the Widget plugin available at http://automattic.com/code/widgets/
1. Copy flickr_widget.php to your plugins folder, /wp-content/plugins/widgets/
2. Activate it through the plugin management screen.
3. Go to Themes->Sidebar Widgets and drag and drop the widget to wherever you want to show it.

Changelog
0.1 = First public release.
*/

function widget_flickr($args) {
	if( file_exists( ABSPATH . WPINC . '/rss.php') ) {
		require_once(ABSPATH . WPINC . '/rss.php');
	} else {
		require_once(ABSPATH . WPINC . '/rss-functions.php');
	}
	extract($args);

	$options = get_option('widget_flickr');
	if( $options == false ) {
		$options[ 'title' ] = 'Flickr Photos';
		$options[ 'items' ] = 3;
	}
	$title = empty($options['title']) ? __('Flickr Photos') : $options['title'];
	$items = $options[ 'items' ];
	$flickr_rss_url = empty($options['flickr_rss_url']) ? __('http://flickr.com/services/feeds/photos_public.gne?id=78656712@N00&format=rss_200') : $options['flickr_rss_url'];
	if ( empty($items) || $items < 1 || $items > 10 ) $items = 3;
	
	$rss = fetch_feed( $flickr_rss_url );
	if(!is_wp_error( $rss ) ) {
		$out = '';
		$items = $rss->get_items(0, $items);
		foreach ($items as $item) {
            $photo_thumbnail = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'thumbnail');
            $photo_thumbnail_url = $photo_thumbnail[0]['attribs']['']['url'];
            $photo_content = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'content');
            $photo_content_url = str_replace('_b.jpg', '_z.jpg', $photo_content[0]['attribs']['']['url']);

			$out .= '<div class="flickr_image"><a href="' . esc_url($photo_content_url) . '"><img alt="'.esc_attr( $item->get_title() ).'" title="'.esc_attr( $item->get_title() ).'" src="' . esc_url($photo_thumbnail_url) . '"></a></div>';
		}
		$flickr_home = $rss->get_link();
		$flickr_more_title = $rss->get_title();
	}
	?>
	<?php echo $before_widget; ?>
	<?php echo $before_title . $title . $after_title; ?>
<!-- Start of Flickr Badge -->
<div class="flickr_badge_wrapper">
<?php echo $out; ?>
<a href="<?php echo esc_url( $flickr_home ) ?>">Flickr Home</a>
</div>
<!-- End of Flickr Badge -->

		<?php echo $after_widget; ?>
<?php
}

function widget_flickr_control() {
	$options = $newoptions = get_option('widget_flickr');
	if( $options == false ) {
		$newoptions[ 'title' ] = 'Flickr Photos';
	}
	if ( $_POST["flickr-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["flickr-title"]));
		$newoptions['items'] = strip_tags(stripslashes($_POST["rss-items"]));
		$newoptions['flickr_rss_url'] = strip_tags(stripslashes($_POST["flickr-rss-url"]));
        $newoptions['lightbox'] = isset($_POST['flickr-lightbox']); 
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_flickr', $options);
	}
	$title = wp_specialchars($options['title']);
	$items = wp_specialchars($options['items']);
	if ( empty($items) || $items < 1 ) $items = 3;
	$flickr_rss_url = wp_specialchars($options['flickr_rss_url']);

	?>
	<p><label for="flickr-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="flickr-title" name="flickr-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="flickr-rss-url"><?php _e('Flickr RSS URL:'); ?> <input style="width: 250px;" id="flickr-title" name="flickr-rss-url" type="text" value="<?php echo $flickr_rss_url; ?>" /></label></p>
	<p style="text-align:center; line-height: 30px;"><?php _e('How many photos  would you like to display?'); ?> <select id="rss-items" name="rss-items"><?php for ( $i = 1; $i <= 10; ++$i ) echo "<option value='$i' ".($items==$i ? "selected='selected'" : '').">$i</option>"; ?></select></p>
	<p align='left'>
	* Your RSS feed can be found on your Flickr homepage. Scroll down to the bottom of the page until you see the <em>Feed</em> link and copy that into the box above.<br />
	<br clear='all'></p>
    <p><label for="flickr-lightbox"><?php _e('Use jQuery Lightbox plugin:'); ?></label> <input type="checkbox" name="flickr-lightbox" id="flickr-lightbox" value="1" checked="<?php echo $options['lightbox'] ? 'checked' : ''; ?> /></p>
	<p>Leave the Flickr RSS URL blank to display <a href="http://inphotos.org/">Donncha's</a> Flickr photos.</p>
	<input type="hidden" id="flickr-submit" name="flickr-submit" value="1" />
	<?php
}


function flickr_widgets_init() {
	$options = get_option('widget_flickr');
	register_widget_control('Flickr', 'widget_flickr_control', 500, 250);
	register_sidebar_widget('Flickr', 'widget_flickr');
    if ( $options['lightbox'] ) {
        wp_register_script('lightbox', plugins_url('/js/jquery.lightbox-0.5.pack.js', __FILE__), array('jquery'));
        wp_register_script('flickr-widget', plugins_url('/js/flickr_widget.js', __FILE__), array('jquery'));
        wp_enqueue_script('lightbox');
        wp_enqueue_script('flickr-widget');
        wp_enqueue_style('lightbox', plugins_url( '/css/jquery.lightbox-0.5.css', __FILE__ ));
    }
    wp_enqueue_style('flickr-widget', plugins_url( '/css/flickr.css', __FILE__ ));
}
add_action( "init", "flickr_widgets_init" );

?>
