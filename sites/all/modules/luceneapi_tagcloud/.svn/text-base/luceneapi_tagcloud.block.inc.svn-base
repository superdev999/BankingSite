<?php
// $Id: luceneapi_tagcloud.block.inc,v 1.4 2010/01/26 04:39:21 cpliakas Exp $

/**
 * @file
 * Renders the Search Lucene Tagcloud facet blocks.
 *
 * @ingroup luceneapi_tagcloud
 */

/**
 * Themes the luceneapi tagcloud facet block.
 *
 * @param $items
 *   An array of items as returned by luceneapi_tagcloud_weights_get().
 * @param $type
 *   A string containing the type of the search, i.e. luceneapi_node.
 * @return
 *   A string containing the themed list.
 */
function theme_luceneapi_tagcloud_facets($items, $type) {
  drupal_add_css(drupal_get_path('module', 'luceneapi_tagcloud') .'/luceneapi_tagcloud.css');
  $output = '';
  foreach ($items as $name => $item) {
    $output .= theme(
      'luceneapi_tagcloud_facet', check_plain($item['title']), $item['items'], $type
    );
  }
  return $output;
}

/**
 * Process variables for luceneapi_tagcloud-facet.tpl.php.
 *
 * @param &$variables
 *   An array containing the template variables.
 * @return
 *   NULL
 * @see luceneapi_tagcloud-facet.tpl.php
 */
function template_preprocess_luceneapi_tagcloud_facet(&$variables) {
  $links = array();
  foreach ($variables['facets'] as $id => $args) {
    $links[] = theme($args['function'], $args['text'], $args['path'], $args['options']);
  }
  $variables['facets'] = join(' ', $links);
  $variables['template_files'][] = 'luceneapi_tagcloud-facet-'. $variables['type'];
}

/**
 * Calculates tagcloud weights for an item.
 *
 * @param $items
 *   An array of items as returned by luceneapi_facet_block_realm_render().
 *   Specifically, $items is the 'items' key for the facet.
 * @param $levels
 *   An integer containing the number of levels.
 * @return
 *   A sorted array of items with tagcloud weights.
 * @see tagadelic_build_weighted_tags()
 */
function luceneapi_tagcloud_weights_get($items, $levels) {
  // NOTE: The weight calculation was completely stolen from the Tagadelic
  // project at http://drupal.org/project/tagcloud. The comments say that the
  // mathematics are credited to Steven Wittens, aka UnConeD.
  $min = 1e9;
  $max = -1e9;
  foreach ($items as $id => $item) {
    $log = log($item['count']);
    $items[$id]['count'] = $log;
    $min = min($min, $log);
    $max = max($max, $log);
  }

  // Note: we need to ensure the range is slightly too large to make sure even
  // the largest element is rounded down.
  $range = max(.01, $max - $min) * 1.0001;

  // adds tagcloud weights for the facet items
  foreach ($items as $id => $item) {
    $weight = 1 + floor((int)$levels * ($item['count'] - $min) / $range);
    $class = sprintf('level%d', $weight);
    if (empty($items[$id]['options']['attributes']['class'])) {
      $items[$id]['options']['attributes']['class'] = $class;
    }
    else {
      $items[$id]['options']['attributes']['class'] .= ' '. $class;
    }
  }

  return $items;
}

/**
 * Returns the themed content for hook_block('view') implementations of Search
 * Lucene Tagcloud blocks.
 *
 * @param $module
 *   A string containing the module handling the search.
 * @return
 *   A string containing the themed facets.
 */
function luceneapi_tagcloud_block_view($module) {
  // gets items, calculates facet counts, uses Search Lucene Content's
  // hook_luceneapi_facet_postrender_alter() implementation to convert
  // values such as taxonomy term ID's to display names.
  $type = luceneapi_index_type_get($module);
  if ($items = luceneapi_facet_realm_render('tagcloud', $module, $type)) {
    luceneapi_node_luceneapi_facet_postrender_alter($items, 'block', $module, $type);
  }
  else {
    $items = array();
  }

  // gets number of levels from settings
  $variable = sprintf('luceneapi_facet:%s:tagcloud:levels', $module);
  $levels = variable_get($variable, 6);

  // gets the facet limit
  $variable = sprintf('luceneapi_facet:%s:tagcloud:limit', $module);
  $limit = variable_get($variable, 20);

  // adds tagcloud weights
  foreach ($items as $name => $item) {

    // limits the number of facets if a limit is set
    if ($limit) {
      arsort($items[$name]['count']);
      $items[$name]['count'] = array_slice($items[$name]['count'], 0, $limit, TRUE);
      $items[$name]['items'] = array_intersect_key($items[$name]['items'], $items[$name]['count']);
    }

    // assigns levels, or weights to the facet
    $items[$name]['items'] = luceneapi_tagcloud_weights_get(
      $items[$name]['items'], $levels
    );
  }

  // if we have items, return block content
  return (!empty($items)) ? theme('luceneapi_tagcloud_facets', $items, $module) : '';
}
