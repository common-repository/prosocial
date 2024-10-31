<?php
		/*
		Plugin Name: ProSocial
		Plugin URI: http://www.digitalshift.me/prosocial
		Description: Plugin for displaying social media accounts next to authors' names
		Author: F.Bacon
		Version: 0.5
		Author URI: http://www.andeggs.me
		*/

//A Library for recaptcha-ing email addresses
require_once ("recaptchalib.php");
			
// Add the settings

$pluginlocation = plugins_url( '', dirname(__FILE__) ) . '/prosocial' ;

$barcolours=array(
	array('Yellow', 'yellow'),
	array('Red', 'red'),
	array('Green', 'green'),
	array('Blue', 'blue'),
	array('White', 'white'),
	array('Black', 'black'),
);

$instantmessaging=array(
	array('AIM', 'aim'),
	array('Yahoo IM', 'yim'),
	array('Jabber / Google Talk', 'jabber')
	);

$socialnetworks=array(
	array('500px', '500px', '500px.png'),
	array('8tracks', '8tracks', '8tracks.png'),
	array('Bandcamp', 'bandcamp', 'bandcamp.png'),
	array('Bebo', 'bebo', 'bebo.png'),
	array('Behance', 'behance', 'behance.png'),
	array('Blogger', 'blogger', 'blogger.png'),
	array('Delicious', 'delicious', 'delicious.png'),
	array('Digg', 'digg', 'digg.png'),
	array('Dribbble', 'dribbble', 'dribbble.png'),
	array('Etsy', 'etsy', 'etsy.png'),
	array('Evernote', 'evernote', 'evernote.png'),
	array('Facebook', 'facebook', 'facebook.png'),
	array('Flickr', 'flickr', 'flickr.png'),
	array('Formspring', 'formspring', 'formspring.png'),
	array('Foursquare', 'foursquare', 'foursquare.png'),
	array('Friendfeed', 'friendfeed', 'friendfeed.png'),
	array('Github', 'github', 'github.png'),
	array('Goodreads', 'goodreads', 'goodreads.png'),
	array('Google', 'google', 'google.png'),
	array('Gowalla', 'gowalla', 'gowalla.png'),
	array('Hypemachine', 'hypemachine', 'hypemachine.png'),
	array('Instagram', 'instagram', 'instagram.png'),
	array('Last.fm', 'lastfm', 'lastfm.png'),
	array('Linkedin', 'linkedin', 'linkedin.png'),
	array('Myspace', 'myspace', 'myspace.png'),
	array('Netflix', 'netflix', 'netflix.png'),
	array('Orkut', 'orkut', 'orkut.png'),
	array('Picasa', 'picasa', 'picasa.png'),
	array('Posterous', 'posterous', 'posterous.png'),
	array('Rdio', 'rdio', 'rdio.png'),
	array('Reddit', 'reddit', 'reddit.png'),
	array('Skype', 'skype', 'skype.png'),
	array('Songkick', 'songkick', 'songkick.png'),
	array('Soundcloud', 'soundcloud', 'soundcloud.png'),
	array('Stumbleupon', 'stumbleupon', 'stumbleupon.png'),
	array('Tripit', 'tripit', 'tripit.png'),
	array('Tumblr', 'tumblr', 'tumblr.png'),
	array('Twitter', 'twitter', 'twitter.png'),
	array('Typepad', 'typepad', 'typepad.png'),
	array('Vimeo', 'vimeo', 'vimeo.png'),
	array('Wordpress', 'wordpress', 'wordpress.png'),
	array('Yahoo', 'yahoo', 'yahoo.png'),
	array('Youtube', 'youtube', 'youtube.png')
	);

//Add the settings area when the administration interface loads
add_action('admin_init', 'prosocial_admin_init');
function prosocial_admin_init(){
	register_setting( 'prosocial_main', 'prosocial_networks');
	register_setting( 'prosocial_main', 'prosocial_instant');
	register_setting( 'prosocial_main', 'prosocial_hover');
	register_setting( 'prosocial_main', 'prosocial_emailwebsite');
	register_setting( 'prosocial_main', 'prosocial_colour');
	register_setting( 'prosocial_main', 'prosocial_analytics');
	add_settings_section('prosocial_section_1', 'Social media accounts', 'prosocial_section_1_callback', 'prosocial');
	add_settings_section('prosocial_section_2', 'Instant messaging services', 'prosocial_section_2_callback', 'prosocial');
	add_settings_section('prosocial_section_4', 'Email and website links', 'prosocial_section_4_callback', 'prosocial');
	add_settings_section('prosocial_section_3', 'Hover links', 'prosocial_section_3_callback', 'prosocial');
	add_settings_section('prosocial_section_5', 'Pop-up bar colour', 'prosocial_section_5_callback', 'prosocial');
	add_settings_section('prosocial_section_6', 'Google Analytics', 'prosocial_section_6_callback', 'prosocial');
	add_settings_section('prosocial_section_7', 'Credits', 'prosocial_section_7_callback', 'prosocial');
	add_settings_field('prosocial_networks', 'Social networks', 'prosocial_SocialNetwork_callback', 'prosocial', 'prosocial_section_1');
	add_settings_field('prosocial_instant', 'Instant messaging', 'prosocial_InstantMessaging_callback', 'prosocial', 'prosocial_section_2');
	add_settings_field('prosocial_hover', '', 'prosocial_Hover_callback', 'prosocial', 'prosocial_section_3');
	add_settings_field('prosocial_emailwebsite', '', 'prosocial_EmailWebsite_callback', 'prosocial', 'prosocial_section_4');
	add_settings_field('prosocial_colour', '', 'prosocial_Colour_callback', 'prosocial', 'prosocial_section_5');
	add_settings_field('prosocial_analytics', '', 'prosocial_Analytics_callback', 'prosocial', 'prosocial_section_6');
}
		
// Add the settings page to the menu
add_action('admin_menu', 'prosocial_admin_menu');
function prosocial_admin_menu() {
	add_options_page('ProSocial', 'ProSocial', 'manage_options', 'prosocial', 'prosocial_admin_callback');
}

// Display the settings page
function prosocial_admin_callback() {
	?>
		<div class="wrap">
		<h2>ProSocial</h2>
		<form action="options.php" method="post">
			<p>The <em>ProSocial</em> plugin makes it easier for your readers to connect with your blog authors.<br/>It shows a pop-up bar with links to your authors' social media accounts next to their name.</p>
			<p>Your authors can add the links to their accounts through their Wordpress user profile.<br/>Please make sure they use full URLs when entering these (e.g. 'http://twitter.com/andeggs')</p>
			<?php settings_fields('prosocial_main'); ?>
			<fieldset><?php do_settings_sections('prosocial'); ?></fieldset>
			<input name="Submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form></div>
	<?php
}

function prosocial_section_1_callback() {
	echo '<p>Select the social media accounts that you want to use.<br/>Your authors will only be able to add account details for those that are ticked.</p>';
}

function prosocial_section_2_callback() {
	echo '<p>By default Wordpress includes three instant messaging services in user profiles. Untick the checkboxes to remove the fields from the user profile. Please note that these services are not displayed in the ProSocial pop-up bar.</p>';
}

function prosocial_section_3_callback() {
	echo '<p>By default ProSocial displays the pop-up bar over links to authors\' main Wordpress pages.<br/>However some themes display links to authors\' websites (as entered in their user profile) instead. If this applies to you then check the box below.</p>';
	}
	
function prosocial_section_4_callback() {
	echo '<p>The ProSocial pop-up bar can also display a link to each authors\' website and email address (as entered in their user profile). Note that users have to complete a captcha in order to access the email addresses.</p>';
	}
	
function prosocial_section_5_callback() {
	echo '<p>The ProSocial pop-up bar can have one of several background colours.</p>';
	}
	
function prosocial_section_6_callback() {
	echo '<p>If you have Google Analytics installed, ProSocial can record clicks on each of the icons with <a href="https://developers.google.com/analytics/devguides/collection/gajs/eventTrackerGuide">event tracking</a>.</p>';
	}

function prosocial_section_7_callback() {
	echo '<p><a href="http://digitalshift.me/prosocial">ProSocial</a> was created by <a href="http://andeggs.me">Francis Bacon</a>. Please get in touch if you have suggestions for improvement.</p><p>The pop-up style is based on <a href="http://tutorialzine.com/2010/07/colortips-jquery-tooltip-plugin/">ColorTip</a> by <a href="http://tutorialzine.com">Tutorialzine</a>. Most of the icons are from <a href="http://www.komodomedia.com">Komodo Media</a> and <a href="http://www.famfamfam.com/">FamFamFam</a>. We also use Google\'s <a href="http://www.google.com/recaptcha/mailhide/">Recaptcha Mailhide</a>.</p>';
	}

function prosocial_SocialNetwork_callback() {
	global $socialnetworks;
	global $pluginlocation;
	$options_networks = get_option('prosocial_networks');
	$i = 0;
	for ($i = 0; $i < count($socialnetworks); $i++) {
		echo '<img src="'.$pluginlocation. '/img/' . $socialnetworks[$i][2] . '"/>&nbsp;&nbsp;<input name="prosocial_networks['.$i.']" type="checkbox" value="1" class="code" ' . checked( 1, $options_networks[$i], false ) . ' /> '. $socialnetworks[$i][0].'<br/>';
	}
}

function prosocial_InstantMessaging_callback() {
	global $instantmessaging;
	$options_instant = get_option('prosocial_instant');
	$i = 0;
	for ($i = 0; $i < count($instantmessaging); $i++) {
		echo '<input name="prosocial_instant['.$i.']" type="checkbox" value="1" class="code" ' . checked( 1, $options_instant[$i], false ) . ' /> '. $instantmessaging[$i][0].'<br/>';
	}
}

function prosocial_EmailWebsite_callback() {
	$options_emailwebsite = get_option('prosocial_emailwebsite');
	echo '<input name="prosocial_emailwebsite[1]" type="checkbox" value="1" class="code" ' . checked( 1, $options_emailwebsite[1], false ) . ' /> Display website link in the pop-up bar<br/>';
	echo '<input name="prosocial_emailwebsite[0]" type="checkbox" value="1" class="code" ' . checked( 1, $options_emailwebsite[0], false ) . ' /> Display authors\' email in the pop-up bar<br/>';
}

function prosocial_Hover_callback() {
	$options_hover = get_option('prosocial_hover');
	echo '<input name="prosocial_hover" type="checkbox" value="1" class="code" ' . checked( 1, $options_hover, false ) . ' /> Display pop-up bar on links to authors\' websites<br/>';
}

function prosocial_Colour_callback() {
	global $barcolours;
	$options_colour = get_option('prosocial_colour');
	
	echo '<select name="prosocial_colour">';
	$i = 0;
	for ($i = 0; $i < count($barcolours); $i++) {
		echo '<option value="'.$barcolours[$i][1].'" ' . selected( $barcolours[$i][1], $options_colour ) . '>'.$barcolours[$i][0].'</option>';
	}

}

function prosocial_Analytics_callback() {
	$options_analytics = get_option('prosocial_analytics');
	echo '<input name="prosocial_analytics" type="checkbox" value="1" class="code" ' . checked( 1, $options_analytics, false ) . ' /> Use Google Analytics Event tracking<br/>';
}

/* User profile page */
add_filter('user_contactmethods','add_contactmethods',10,1);
function add_contactmethods( $contactmethods ) {
	global $socialnetworks;
	global $instantmessaging;
	
	//remove instant messaging accounts as needed
	$options_instant = get_option('prosocial_instant');
	$i = 0;
	for ($i = 0; $i < count($instantmessaging); $i++) {
		if ($options_instant[$i]==0) {
			unset($contactmethods[$instantmessaging[$i][1]]);
		}
	}
	
	//add social networks as needed
	$options_networks = get_option('prosocial_networks');
	$i = 0;
	for ($i = 0; $i < count($socialnetworks); $i++) {
		if ($options_networks[$i]==1) {	
			$contactmethods[$socialnetworks[$i][1]] = $socialnetworks[$i][0];
		}
	}	
	return $contactmethods;
}

/*Filter for showing the information on the front-end*/
add_filter('the_content','filter_the_content');
add_filter('the_excerpt','filter_the_content');
function filter_the_content($content) {
	//for therecaptcha service
	$mailhide_pubkey = '01YDIxRbqBgzGtEVNYBUqpyg==';
	$mailhide_privkey = 'aa0f3c45e024debd2558e5aae78875f4';
	
	global $socialnetworks;
	global $pluginlocation;
	
	//get various options from the Settings
	$options_networks = get_option('prosocial_networks');
	$options_hover = get_option('prosocial_hover');
	$options_emailwebsite = get_option('prosocial_emailwebsite');
	$options_analytics = get_option('prosocial_analytics');
	
	$i = 0;
	
	//For each social network
	for ($i = 0; $i < count($socialnetworks); $i++) {
		//If that network is turned on and this user has an entry
		if ($options_networks[$i]==1 && get_the_author_meta($socialnetworks[$i][1])<>'') {	
			//If Google Analytics tracking is turned on
			if($options_analytics==1) {
				$ga_tracker = '\" onclick="_gaq.push([\\\'_trackEvent\\\', \\\'prosocial\\\', \\\'Click\\\', \\\''.$socialnetworks[$i][0] .'\\\'])"><img src="';
			} else {
				$ga_tracker = '\"><img src="';
			}
			//Append to the string of icon code				
			$icons .= '<a title="'.$socialnetworks[$i][0].' (via ProSocial)" href="' . get_the_author_meta($socialnetworks[$i][1]) . $ga_tracker . $pluginlocation. '/img/' . $socialnetworks[$i][2] . '"/></a>&nbsp;';
		}
	}
	
	//add website icon 
	if($options_emailwebsite[1]==1 && get_the_author_meta('user_url')<>'') {
		if($options_analytics==1) {
			$ga_tracker = '\" onclick="_gaq.push([\\\'_trackEvent\\\', \\\'prosocial\\\', \\\'Click\\\', \\\'Website\\\'])"><img src="';
		} else {
			$ga_tracker = '\"><img src="';
		}		
		$icons .= '<a title="Website (via ProSocial)" href="' . get_the_author_meta('user_url') . $ga_tracker . $pluginlocation. '/img/world.png' .'"/></a>&nbsp;';
	}
	
	//add email icon 
	if($options_emailwebsite[0]==1 && get_the_author_meta('user_email')<>'') {
		if($options_analytics==1) {
			$ga_tracker = '\" onclick="_gaq.push([\\\'_trackEvent\\\', \\\'prosocial\\\', \\\'Click\\\', \\\'Email\\\'])"><img src="';
		} else {
			$ga_tracker = '\"><img src="';
		}
		
		$icons .= '<a title="Email (via ProSocial)" target="_blank" href="' . recaptcha_mailhide_url ($mailhide_pubkey, $mailhide_privkey, get_the_author_meta('user_email')) . $ga_tracker .$pluginlocation. '/img/email.png' .'"/></a>&nbsp;';
	}
	
	
	//Use Jquery to find and amend <a> tags which point to author pages
	$script = '<script>jQuery(document).ready(function(){jQuery("a[href=\'' . addslashes(get_author_posts_url(get_the_author_meta('ID'))) . '\']").attr(\'title\', \''. $icons .'\');jQuery("a[href=\'' . addslashes(get_author_posts_url(get_the_author_meta('ID'))) . '\']").addClass(\'prosocial_author\')})</script>';
	
	//Use Jquery to find and amend <a> tags which point to authors' websites
	if($options_hover==1) {
		$script .= '<script>jQuery(document).ready(function(){jQuery("a[href=\'' . addslashes(get_the_author_meta('user_url')) . '\']").attr(\'title\', \''. $icons .'\');jQuery("a[href=\'' . addslashes(get_the_author_meta('user_url')) . '\']").addClass(\'prosocial_author\')});</script>';
	}
	
	$content = $script. $content; 
	return $content;	
}

/*Action for including javascript in the header*/
add_action('wp_head', 'action_wp_head');
function action_wp_head() {
	global $pluginlocation;
    echo '<link rel="stylesheet" type="text/css" href="'.$pluginlocation.'/css/colortip-1.0-jquery.css"/><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>';
}

/*Action for including javascript in the footer*/
add_action('wp_footer', 'action_wp_footer');
function action_wp_footer() {
	global $pluginlocation;
	$options_colour = get_option('prosocial_colour');
    echo '<script type="text/javascript" src="'.$pluginlocation.'/js/colortip-1.0-jquery.js"></script>';

	/* Adding a colortip to any tag with a title attribute: */
	echo '<script>jQuery(document).ready(function(){	jQuery(\'a[title]\').colorTip({color:\''.$options_colour.'\'});});</script>';
}
?>