<?php // $Id: page.tpl.php,v 1.17 2009/05/07 17:00:40 jmburnz Exp $
/**
 * @file
 *  page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
//require_once($_SERVER['DOCUMENT_ROOT']."/".path_to_theme()."/autoload.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>">
<head>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <link rel="publisher" href="https://plus.google.com/115413476422580977786" />
  <?php print $styles; ?>
  <!--[if IE]>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print $base_path . $directory; ?>/ie.css" >
  <![endif]-->
      <script type="text/javascript" src="/<?php echo drupal_get_path('theme', 'pixture_reloaded'); ?>/lib/jquery-1.8.1.js"></script>
  <script type='text/javascript'>
 var $jq = jQuery.noConflict();
</script>
<?php if ($_REQUEST['action']=='bewertung-zeigen' or $_REQUEST['action']=='mailApproved') {?>
 <style type='text/css'>
#content-area-right{
display:none;
}

#content-area-left{
width: 943px;
}

<?php
global $user;

if ( !$user->uid and $_REQUEST['action']!=='mailApproved' ) {
echo
	".messages{
	background-color: #FBE3E4;
	border: 1px solid #D77;
	}";
}
?>

<?php
if ( $_REQUEST['action']=='mailApproved' ) {
echo
	"#content-area #node-4038{
		position: relative;
		margin-top: 110px;
		}
		.reg_teaser{
		position:absolute;
		top: -120px;
		}

		#block-block-4{
		border:none;
		}

		#block-block-4 li{
		display:block;
		float:left;
		width: 180px;
		}

		.reg_button{
		margin:0;
		position:absolute;
		top: -68px;
		right: -3px;
		}
		#block-block-4 p.blue{
		color: #045493;
		}

		";
}


?>


.messages{
font-weight: normal;
color: #535353;
}

.messages p{
font-weight: normal;
color: #535353;
margin-top: 0px;
margin-bottom: 0px;
line-height: 1.6;
font-weight: bold;
}

.messages strong{
font-weight: bold;
}

#content-area-left div.node-type-page{
background: none !important;
border: 2px solid #045493;
}

</style>
<?php } elseif ($_REQUEST['action']=='mailApproved') {?>
<style type='text/css'>
#content-area-right{
display:none;
}
#content-area-left{
width: 943px;
}
#content-area-left div.node-type-page{
background: none !important;
}
</style>
<?php } ?>

<?php print $scripts;  ?>

<?php

if($_REQUEST['app']=='true') {


$left = false;
$right = false;
$content_left=false;
$content_right=false;
$content_bottom=false;
$footer=false;
$header=false;
$closure_region=false;
$primary_menu =false;
$superfish = false;

?>
     <meta name="viewport" content="width=480px,target-densitydpi=device-dpi,user-scalable=yes">

<style type="text/css">

#header, #footer, #logo, #bewertungsfinanzheader {
  display: none;
}

div.description {
  width: 50px !important;
}

#content-parts, html body #content-inner , .node-full-view.sidebar-right #content-inner, body .sidebar-right #content-inner {
  padding: 0;
  padding-right: 0 !important;
}

#pixture-reloaded {
  background: none;
}

#page {
width: 480px !important;
min-width: 320px;
}

#content-area .block-content-inner {
  margin: 0;
  }

 #content-area  div.block {
  clear: both;
 }

#pixture-reloaded {
  font-size: 15px;
}

html.js .resizable-textarea textarea {
  width: 100% !important;
  font-size: 16px;
}

body div#block-block-23 {
  width: 100%;
  height: 12px;
}

body div#block-block-23 div.block-inner button, body div#block-block-23 div.block-inner input {
  display: block;
  float: none;
  margin-bottom: 10px;

}

body .blueButton, body div#block-block-23 div.block-inner button {
  height: 55px;
  background-image: none !important;
  background-color: #2677b8;
  -webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
font-size: 16px;
width: 95% !important;
margin-left: 40px;
}

body div#block-block-23 div.block-inner div.block-content {
  width: 80%;
}


body table#bewertung td {
  display: block;
  width: 100% !important;
  clear: both;
}

body table#bewertung div.siegel {
  float: none !important;
}

body #ratings-2 div.single_rating {
  width: 384px;
}

body #ratings-2 {
  margin-top: 20px;
}

body .commenttext.short {
  display: none !important;
}

body .commenttext.long {
position: initial;
top: 0 !important;
  width: auto !important;
  border: none !important;
  padding: 0 !important;
  margin-bottom: 25px;
}
body .commenttext.long div {
  display: none !important;

}

body a#weiterlesen {
  display: none !important;
}

body .rat_abs {
  position: initial;

}


</style>

<?php
}

?>
</head>
<?php
  $pixture_width = "1060px";//theme_get_setting('pixture_width');
  $pixture_width = pixture_validate_page_width($pixture_width);
?>
<body id="pixture-reloaded" class="<?php print $body_classes; ?> <?php print $logo ? 'with-logo' : 'no-logo' ; ?>">

  <div id="skip-to-content">
    <a href="#main-content"><?php print t('Skip to main content'); ?></a>
  </div>

    <div id="page" style="width: <?php print $pixture_width; ?>;">
	  <div id="uber-header">
	  	<? print ($super_header) ?>
	  </div>
      <div id="header">

        <?php if ($site_logo): ?>
          <div id="logo"><?php print $site_logo; ?></div>
        <?php endif; ?>

        <div id="head-elements">

          <?php if ($search_box): ?>
            <div id="search-box">
              <?php print $search_box; ?>
            </div> <!-- /#search-box -->
          <?php endif; ?>

          <div id="branding">
            <?php if ($site_name): ?>
              <?php if ($title): ?>
                <div id="site-name"><strong><?php print $site_name; ?></strong></div>
              <?php else: /* Use h1 when the content title is empty */ ?>
                <h1 id="site-name"><?php print $site_name; ?></h1>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($site_slogan): ?>
              <div id="site-slogan"><em><?php print $site_slogan; ?></em></div>
            <?php endif; ?>
          </div> <!-- /#branding -->

        </div> <!-- /#head-elements -->

        <?php if ($primary_menu || $superfish): ?>
          <!-- Primary || Superfish -->
          <div id="<?php print $primary_menu ? 'primary' : 'superfish' ; ?>">
            <div id="<?php print $primary_menu ? 'primary' : 'superfish' ; ?>-inner">
			<div id="pokal"><div id="pokal_inner"></div></div>
              <?php if ($primary_menu): ?>
                <?php print $primary_menu; ?>
              <?php elseif ($superfish): ?>
                <?php print $superfish; ?>

              <?php endif; ?>
            </div> <!-- /inner -->
          </div> <!-- /primary || superfish -->
        <?php endif; ?>

    </div> <!--/#header -->

    <?php if ($header): ?>
      <div id="header-blocks" class="region region-header">
        <?php print $header; ?>
      </div> <!-- /#header-blocks -->
    <?php endif; ?>

    <div id="main" class="clear-block <?php print $header ? 'with-header-blocks' : 'no-header-blocks' ; ?>">

      <div id="content">
      	<div id="content-inner">
      	  <div id="content-parts">
        <?php if ($mission): ?>
          <div id="mission"><?php print $mission; ?></div>
        <?php endif; ?>

        <?php if ($content_top): ?>
          <div id="content-top" class="region region-content_top">
            <?php print $content_top; ?>
          </div> <!-- /#content-top -->
        <?php endif; ?>

        <div id="content-header" class="clearfix">
          <?php // if ($breadcrumb): print $breadcrumb; endif; ?>
          <a name="main-content" id="main-content"></a>
          <?php if ($title): ?>
					<!--<h1 class="title"><?php //print $title; ?></h1>-->
					<?php endif; ?>
          <?php if ($tabs): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>
          <?php if ($messages): print $messages; endif; ?>
          <?php if ($help): print $help; endif; ?>
        </div> <!-- /#content-header -->

        <div id="content-area">
          <?php
			if($content_right) {
				?>
				<div id="content-area-left">
					<?php print $content; ?>
				</div>
				<div id="content-area-right">
					<?php print $content_right; ?>
				</div>
				<br class="clear" />
				<?php
			} else {
				print $content;
			}
          ?>
        </div>

        <?php if ($content_bottom): ?>
          <div id="content-bottom" class="region region-content_bottom">
            <?php print $content_bottom; ?>
          </div> <!-- /#content-bottom -->
        <?php endif; ?>


      </div></div></div> <!-- /#content-inner, /#content -->

      <?php if ($left): ?>
        <div id="sidebar-left" class="region region-left">
          <?php print $left; ?>
        </div> <!-- /#sidebar-left -->
      <?php endif; ?>

      <?php if ($right): ?>
        <div id="sidebar-right" class="region region-right">
          <?php print $right; ?>
        </div> <!-- /#sidebar-right -->
      <?php endif; ?>

    </div> <!-- #main -->


  </div> <!--/#page -->
    <div id="footer" class="region region-footer" style="min-width: <?php print $pixture_width; ?>;">
      <?php if ($footer): print $footer; endif; ?>
        <div id="footer-message">
          <?php print $footer_message; ?>
        </div> <!-- /#footer-message -->
    </div> <!-- /#footer -->

  <?php if ($closure_region): ?>
    <div id="closure-blocks" class="region region-closure">
      <?php print $closure_region; ?>
    </div>
  <?php endif; ?>

  <?php print $closure; ?>

<!--kupona-->
<?php include("kupona.php"); ?>
  
</body>
</html>
