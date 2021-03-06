<?php
// $Id: rate-widget.tpl.php,v 1.5 2010/09/27 19:39:14 mauritsl Exp $

/**
 * @file
 * Rate widget theme
 *
 * This is the default template for rate widgets. See section 3 of the README
 * file for information on theming widgets.
 */

foreach ($links as $link) {
  print theme('rate_button', $link['text'], $link['href']);
}

print t('Total votes: !count', array('!count' => $results['count'])); ?>