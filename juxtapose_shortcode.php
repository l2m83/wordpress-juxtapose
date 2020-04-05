<?php
/*
Plugin Name: Juxtapose
Plugin URI: http://wordpress.org/plugins/juxtaposejs/
Description: Adds a [juxtapose] shortcode to embed Northwestern University Knight Lab's JuxtaposeJS frame comparisons.
Author: Federico Cingolani
Version: 1.1
Author URI: http://fcingolani.com.ar/
*/

class Juxtapose {

  static $shortcode_rendered;

  static function initialize() {
    self::$shortcode_rendered = false;

    add_shortcode('juxtapose', array(__CLASS__, 'shortcode'));

    add_action('init',      array(__CLASS__, 'init'));
    add_action('wp_footer', array(__CLASS__, 'wp_footer'));
  }

  // TODO: Sanitize attributes
  static function shortcode( $atts ) {
    self::$shortcode_rendered = true;

    wp_enqueue_script('juxtapose');

    $a = shortcode_atts( array(
        'startingposition'  => 50,
        'showlabels'        => true,
        'showcredits'       => true,
        'animate'           => true,
        'mode'              => 'horizontal',

        'leftsrc'           => '',
        'leftlabel'         => '',
        'leftcredit'        => '',
        'leftlink'           => '',

        'rightsrc'          => '',
        'rightlabel'        => '',
        'rightcredit'       => '',
        'rightlink'       => '',
    ), $atts );
    $a['modalLink']=plugins_url( 'compare.php?left='.urlencode($a['leftsrc']).'&right='.urlencode($a['rightsrc']).'&leftcredit='.urlencode($a['leftcredit']).'&rightcredit='.urlencode($a['rightcredit']), __FILE__ );
    $a['image']=plugins_url( 'compare2.png', __FILE__ );
	$leftCredit = $a['leftcredit'];
	  if ($a['leftlink']!='') {
		  $leftCredit = '<a href=&quot;'.$a['leftlink'].'&quot; target=&quot;_blank&quot;>'.$leftCredit.'</a>';
	  }
	$rightCredit = $a['rightcredit'];
	  if ($a['rightlink']!='') {
		  $rightCredit = '<a href=&quot;'.$a['rightlink'].'&quot; target=&quot;_blank&quot;>'.$rightCredit.'</a>';
	  }

    return <<<EOT
<div class="juxtapose" data-startingposition="{$a['startingposition']}" data-showlabels="{$a['showlabels']}" data-showcredits="{$a['showcredits']}" data-animate="{$a['animate']}" data-mode="{$a['mode']}">
  <img src="{$a['leftsrc']}" data-label="{$a['leftlabel']}" data-credit="{$leftCredit}">
  <img src="{$a['rightsrc']}" data-label="{$a['rightlabel']}" data-credit="{$rightCredit}">
</div>
<!-- Links -->
<div align="right"><a rel="modal:open" href="{$a['modalLink']}"><img src="{$a['image']}" title="Comparer côte à côte" style="max-width: 30px; height: auto; "></a></div>
EOT;

  }

  static function init() {
    wp_register_script( 'juxtapose',  plugins_url( 'juxtapose.js', __FILE__ ), null, null, true );
    /*
        wp_register_script( 'juxtapose',  "//s3.amazonaws.com/cdn.knightlab.com/libs/juxtapose/latest/js/juxtapose.js", null, null, true );
    */

  }

  static function wp_footer() {
    // Should be using wp_enqueue_style, but it can't be used to add styles to the footer.
    // Yeah i know, <link> inside <body> is not valid HTML.
    // But i don't want to load this css when there's not need to.
    // SUE ME.
    if(self::$shortcode_rendered){
      echo '<link rel="stylesheet" href="'.plugins_url( 'juxtapose.css', __FILE__ ).'">';
      echo '<link rel="stylesheet" href="'.plugins_url( 'jquery-modal-master/jquery.modal.css', __FILE__ ).'">';
      echo '<script src="'.plugins_url( 'jquery-modal-master/jquery.modal.js', __FILE__ ).'"type="text/javascript" charset="utf-8"></script>';
      //echo '<div id="photoModal" class="modal">
        //      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        //    </div>';
  //  echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    //echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>'
    // echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
      /*
       echo '<link rel="stylesheet" href="//s3.amazonaws.com/cdn.knightlab.com/libs/juxtapose/latest/css/juxtapose.css">';
      */
    }
  }

}

Juxtapose::initialize();
