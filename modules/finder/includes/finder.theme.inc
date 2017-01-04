<?php
// $Id: finder.theme.inc,v 1.1.2.29 2009/07/22 03:02:51 danielb Exp $

/**
 * @file
 * Theme functions for the finder module.
 */

/**
 * Theme the admin table of draggable elements.
 */
function theme_finder_admin_edit_elements_table($form) {
  finder_css();
  $children = element_children($form);
  if (!empty($children)) {
    $css_id = 'finder-admin-edit-elements-table';
    $css_class = 'finder-admin-edit-elements-table-order';
    drupal_add_tabledrag($css_id, 'order', 'sibling', $css_class);
    $rows = array();
    $headers = array(t('Element'), t('Weight'), t('Operations'));
    foreach ((array)$children as $key) {
      $value = &$form[$key];
      $value['weight']['#attributes']['class'] = $css_class;
      $rows[] = array(
        'data' => array(
          drupal_render($value['value']),
          drupal_render($value['weight']),
          drupal_render($value['ops']),
        ),
        'class' => 'draggable',
      );
    }
    $output = theme('table', $headers, $rows, array('id' => $css_id));
  }
  else {
    $output = t('There are no items to display');
  }
  return theme('item', array('#title' => t('Elements'), '#value' => $output));
}

/**
 * Theme the finder admin links.
 */
function theme_finder_admin_links($finder) {
  $output = '';

  $links = array();
  foreach ($finder->admin_links as $path => $title) {
    // don't show this link if the current path starts with $path
    if (strpos($_GET['q'], $path) !== 0) {
      $links[] = l($title, $path);
    }
  }
  if (!empty($links)) {
    $output .= '<div id="finder-admin-links-'. $finder->finder_id .'" 
              class="finder-admin-links" 
              style="float:right;">';
    $output .= theme('item_list', $links, NULL, 'ul', array('class' => 'links'));
    $output .= '</div>';
  }

  return $output;
}

/**
 * Theme the finder links.
 */
function theme_finder_links($finder) {
  $output = '';

  $links = array();
  foreach ($finder->links as $path => $title) {
    // don't show this link if the current path starts with $path
    if (strpos($_GET['q'], $path) !== 0) {
      $links[] = l($title, $path);
    }
  }
  if (!empty($links)) {
    $output .= '<div id="finder-links-'. $finder->finder_id .'" 
              class="finder-links">';
    $output .= theme('item_list', $links, NULL, 'ul', array('class' => 'links'));
    $output .= '</div>';
  }

  return $output;
}

/**
 * Theme the finder page.
 */
function theme_finder_page($finder) {
  $output = '<div id="finder-page-'. $finder->finder_id .'" class="finder-page">';
  $output .= finder_view($finder, 'page');
  $output .= '</div>';
  return $output;
}

/**
 * Theme the finder block.
 */
function theme_finder_block($finder) {
  $output = '<div id="finder-block-'. $finder->finder_id .'" class="finder-block">';
  $output .= finder_view($finder, 'block');
  $output .= '</div>';
  return $output;
}

/**
 * Theme the finder view.
 */
function theme_finder_view($finder, $output_array) {
  $output = '<div class="finder-view-'. $finder->finder_id .' finder-view">';
  $output .= implode('', $output_array);
  $output .= '</div>';
  return $output;
}

/**
 * Theme the finder results.
 */
function theme_finder_results($results, $finder, $keywords, $per_page, $params, $form_state) {
  $output = '';
  //$output .= '<h3 class="finder-results">'. t('Results') .'</h3>';
  $output .= '<div class="finder-results">';
  if ($results) {
    $output .= $results;
    if ($per_page) {
      $output .= theme('pager', NULL, $per_page, 0, $params);
    }
  }
  else {
    $output .= t('There are no results to display');
  }
  $output .= '</div>';
  return $output;
}