<?php
// $Id: finder.form.inc,v 1.1.2.28 2009/07/22 03:02:51 danielb Exp $

/**
 * @file
 * The finder form.
 */

/**
 * Function to create finder form.
 */
function finder_form(&$form_state, $finder) {

  $form = array();

  // see if there is already a form state we should be using.
  $finder_form_state = finder_form_state($finder->finder_id);
  if (is_array($finder_form_state)) {
    $form_state = array_merge($form_state, $finder_form_state);
  }

  finder_invoke_finderapi($finder, 'finder_form', $form, $form_state);

  $form['finder_id'] = array (
    '#type' => 'value',
    '#value' => $finder->finder_id,
  );

  $form['#action'] = url($finder->path);
  $form['finder_form'] = array(
    '#weight' => 0,
    '#prefix' => '<div class="finder-form finder-'. $finder->finder_id .'">',
    '#suffix' => '</div>',
  );
  $form['finder_form']['#prefix'] .= $finder->settings['form']['prefix'] ? '<div class="prefix">'. $finder->settings['form']['prefix'] .'</div>' : '';
  $max_weight = 0;

  foreach ((array)$finder->elements as $element) {
    $max_weight = max($max_weight, $element->weight);
    if (isset($form_state['values'][$element->finder_element_id])) {
      $element_default = $form_state['values'][$element->finder_element_id];
    }
    else {
      $element_default = $element->settings['form']['default_value'];
    }
    $form['finder_form'][$element->finder_element_id] = array(
      '#title' => $element->title,
      '#weight' => $element->weight,
      '#description' => $element->description,
      '#prefix' => $element->settings['form']['prefix'],
      '#suffix' => $element->settings['form']['suffix'],
      '#default_value' => $element_default,
      '#required' => $element->settings['form']['required'],
      '#executes_submit_callback' => TRUE,
    );
    $module = &$element->element_handler['#module'];
    // module_invoke doesn't seem to handle references.
    $handler_function = $module .'_finder_element';
    if (function_exists($handler_function)) {
      $handler_function($element, $form['finder_form'][$element->finder_element_id]);
    }
  }

  if ($finder->settings['advanced']['submission']) {

    $form['finder_form']['submit'] = array(
      '#type' => 'submit',
      '#name' => 'find',
      '#value' => $finder->settings['form']['button_text'],
      '#weight' => $max_weight + 1000,
    );

    if ($finder->settings['advanced']['ahah']) {
      $form['finder_form']['submit']['#ahah'] = array(
        'path' => 'finder/finder_ahah/'. $finder->finder_id,
        'wrapper' => $finder->finder_view_build_id,
        'method' => 'replace',
        'effect' => $finder->settings['advanced']['ahah_effect'],
      );
    }

    if ($finder->settings['advanced']['goto'] == 'go') {
      $form['finder_form']['submit']['#prefix'] = '<div class="container-inline finder-buttons">';
      $form['finder_form']['go'] = array(
        '#type' => 'submit',
        '#name' => 'go',
        '#value' => $finder->settings['form']['go_text'],
        '#weight' => $max_weight + 1010,
        '#suffix' => '</div>',
      );
    }

  }

  $form['finder_form']['#suffix'] = ($finder->settings['form']['suffix'] ? '<div class="suffix">'. $finder->settings['form']['suffix'] .'</div>' : '') . $form['finder_form']['#suffix'];
  return $form;
}

/**
 * Submit function for finder form.
 */
function finder_form_submit($form, &$form_state) {
  $finder_id = $form_state['values']['finder_id'];
  $finder = finder_load($finder_id);
  $form_state['storage']['finder'] = $finder;
  $form_state['storage']['values'] = $form_state['values'];
  $form_state['storage']['finished'] = TRUE;
  finder_form_state($finder->finder_id, $form_state);
}

/**
 * Helper function for finder form to pass form state to results page.
 */
function finder_form_state($finder_id, $form_state = NULL) {
  static $finder_form_state = NULL;

  if ($form_state) { // we are setting the form_state in a submit.
    // last chance for modules to intefere before potential redirect.
    drupal_alter('finder_form_state', $form_state, $finder_id);
    $finder_form_state[$finder_id] = $form_state;

    // handle URL stuff.
    $finder = finder_load($finder_id);
    if (!$finder->settings['advanced']['hide_args']) {
      $sep = &$finder->settings['advanced']['arg_sep'];
      $query = array();
      foreach ($finder->elements as $element) {
        $keyword = array();
        if (!is_null($form_state['values'][$element->finder_element_id])) {
          $keyword = (array)$form_state['values'][$element->finder_element_id];
          foreach($keyword as $k => $v) {
             if (strpos($v, $sep) !== FALSE) {
               $v = '"'. $v .'"';
             }
             $keyword[$k] = $v ? trim($v) : ' ';
          }
        }
        $keywords[$element->finder_element_id] = implode(',', $keyword);
      }
      if (!$form_state['storage']['finished']) {
        $query['finished'] = '0';
      }
      if ($form_state['clicked_button']['#name'] == 'go' && $form_state['storage']['finished']) {
        $query['go'] = '1';
      }
      finder_form_goto($sep, $finder->path .'/'. implode('/', $keywords), $query);
    }

  }
  elseif (!isset($finder_form_state[$finder_id])) {
    $finder = finder_load($finder_id);
    if ($finder->settings['advanced']['hide_args'] && isset($_GET['finder'])) {  // check the session
      $finder_form_state[$finder_id] = $_SESSION['finder'][$_GET['finder']];
    }
    elseif (!isset($_GET['finder']) && 
            strlen($finder->path) < strlen($_GET['q']) && 
            stripos($_GET['q'], $finder->path) === 0) { // check the URL
      $args = explode('/', str_replace($finder->path .'/', '', $_GET['q']));  
      $form_state['storage']['finished'] = TRUE;
      $sep = &$finder->settings['advanced']['arg_sep'];
      $csv_regex = "/". $sep ."(?!(?:[^\\\"". $sep ."]|[^\\\"]". $sep ."[^\\\"])+\\\")/";
      foreach ($finder->elements as $key => $element) {
        $keywords = preg_split($csv_regex, $args[$key]); 
        foreach ($keywords as $k => $v) {
          $v = str_replace(urlencode($sep), $sep, trim($v));
          if (strpos($v, $sep) !== FALSE && $v[0] == '"' && $v[strlen($v)-1] == '"') {
             $v = substr($v, 1, strlen($v)-2);
          }
          unset($keywords[$k]);
          if ($v) {
            $keywords[$v] = $v;
          }
        }
        if (count($keywords) === 1) {
          $keywords = current($keywords);
        }
        elseif (!count($keywords)) {
          $keywords = NULL;
        }
        $form_state['values'][$element->finder_element_id] = $keywords;
      }
      $finder_form_state[$finder_id] = $form_state;
    }

  }
  return $finder_form_state[$finder_id];
}

/**
 * Custom version of drupal_goto().
 */
function finder_form_goto($sep, $path = '', $query = NULL, $fragment = NULL, $http_response_code = 302) {

  if (isset($_REQUEST['destination'])) {
    extract(parse_url(urldecode($_REQUEST['destination'])));
  }
  else if (isset($_REQUEST['edit']['destination'])) {
    extract(parse_url(urldecode($_REQUEST['edit']['destination'])));
  }

  $url = url($path, array('query' => $query, 'fragment' => $fragment, 'absolute' => TRUE));

  // custom changes - undo separator encoding
  $url = str_replace(urlencode($sep), $sep, $url);
  
  // Remove newlines from the URL to avoid header injection attacks.
  $url = str_replace(array("\n", "\r"), '', $url);

  // If there is a space on the end, add another slash.
  //if (substr($url, -3, 3) == '%20' || substr($url, -1, 1) == ' ') {
  //  $url .= '/';
  //}

  // Allow modules to react to the end of the page request before redirecting.
  // We do not want this while running update.php.
  if (!defined('MAINTENANCE_MODE') || MAINTENANCE_MODE != 'update') {
    module_invoke_all('exit', $url);
  }

  // Even though session_write_close() is registered as a shutdown function, we
  // need all session data written to the database before redirecting.
  session_write_close();

  header('Location: '. $url, TRUE, $http_response_code);

  // The "Location" header sends a redirect status code to the HTTP daemon. In
  // some cases this can be wrong, so we make sure none of the code below the
  // drupal_goto() call gets executed upon redirection.
  exit();
}