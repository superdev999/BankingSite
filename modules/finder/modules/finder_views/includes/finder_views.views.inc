<?php
// $Id: finder_views.views.inc,v 1.1.2.12 2009/05/02 07:49:18 danielb Exp $

/**
 * @file
 * Interface between finder_views.module and views.module.
 */

/**
 * Implementation of hook_views_plugins().
 */
function finder_views_views_plugins() {
  $plugins = array(
    'module' => 'finder_views', // This just tells our themes are elsewhere.
    'display' => array(
      'finder_views' => array(
        'path' => drupal_get_path('module', 'finder_views') .'/includes',
        'title' => 'Finder',
        'help' => 'Destination-agnostic display. Mostly useful for programmatic views.',
        'handler' => 'finder_views_plugin_display_finder',
        'no ui' => TRUE, // Programmatic use only.
        'uses hook menu' => FALSE,
        'use ajax' => FALSE,
        'use pager' => TRUE,
        'accept attachments' => FALSE,
      ),
    ),
    'style' => array(
      'finder_views_php_array_finder' => array(
        'path' => drupal_get_path('module', 'finder_views') .'/includes',
        'title' => 'Results array (with title)',
        'help' => 'Returns the view as a PHP array.',
        'handler' => 'finder_views_plugin_style_php_array_finder',
        'no ui' => TRUE, // Programmatic use only.
        'uses row plugin' => TRUE,
        'uses fields' => TRUE,
        'type' => 'normal',
        'even empty' => TRUE,
      ),
    ),
  );
  return $plugins;
}