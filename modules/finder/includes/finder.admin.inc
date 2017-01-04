<?php
// $Id: finder.admin.inc,v 1.1.2.21 2009/07/22 03:02:51 danielb Exp $

/**
 * @file
 * The finder admin screens.
 */

/**
 * Admin finder list page redirect.
 */
function finder_admin_list_redirect() {
  drupal_goto('admin/build/finder');
}

/**
 * Admin finder list page.
 */
function finder_admin_list() {
  $output = '';
  $finders = finder_load_multiple();

  if (!$finders) {
    $output .= t('There are currently no finders configured.');
  }
  else {
    foreach ((array)$finders as $finder) {
      // allow modules to change the finder here
      finder_invoke_finderapi($finder, 'finder_admin_list');

      $rows = array();
      $rows[] = array(
        array(
          'data' => '<strong>'. check_plain($finder->title) .'</strong>',
          'class' => 'finder-title',
        ),
        array(
          'data' => l(t('Edit'), 'admin/build/finder/'. $finder->finder_id .'/edit')
            . t(' | ') . l(t('Delete'), 'admin/build/finder/'. $finder->finder_id .'/delete'),
          'class' => 'finder-ops',
          'align' => 'right',
        ),
      );
      $rows[] = array(
        array(
          'data' => '
            <div class="type">'. t('Type') .'<span class="finder-colon">:</span> <span>'. t($finder->base_handler['#title']) .'</span></div>
            <div class="path">'. t('Path') .'<span class="finder-colon">:</span> <span>'. l($finder->path, $finder->path) .'</span></div>
            ',
          'class' => 'finder-summary',
        ),
        array(
          'data' =>  '<div class="description">'. check_markup($finder->description) .'</div>',
          'class' => 'finder-desc description',
        ),
      );
      $output .= theme('table', array(), $rows, array('class' => 'finder-table finder-'. $finder->finder_id));
    }
  }

  $output .= drupal_get_form('finder_admin_add_form');

  return $output;

}

/**
 * Admin finder add form.
 */
function finder_admin_add_form($form_state) {
  $form = array();

  $form['base'] = array(
    '#type' => 'select',
    '#title' => t('Add a new finder'),
    '#default_value' => '',
    '#options' => finder_admin_get_base_options(),
    '#description' => t('Choose what kind of finder to create.', array('!save_button' => t('Save finder'))),
    '#required' => TRUE,
    '#weight' => 20,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Add finder'),
    '#weight' => 22,
  );

  return $form;

}

/**
 * Submit function for admin finder add form.
 */
function finder_admin_add_form_submit($form, &$form_state) {
  $values = &$form_state['values'];
  $finder = (object)array('base' => $values['base']);
  $finder = finder_save($finder);
  $finder->title = t('Finder !1', array('!1' => $finder->finder_id));
  $finder->path = 'finder/'. $finder->finder_id;
  $finder = finder_save($finder);
  drupal_set_message(t('A finder was added.  Configure the finder below.'));
  menu_rebuild();
  drupal_goto('admin/build/finder/'. $finder->finder_id .'/edit');
}

/**
 * Admin finder edit page.
 */
function finder_admin_edit($form_state, $finder) {
  // allow modules to change the finder here
  finder_invoke_finderapi($finder, 'finder_admin_edit', $form, $form_state);

  $form_state['storage']['finder'] = $finder;

  // build the form
  $form = array();
  $form['#tree'] = TRUE;
  $form['finder_id'] = array(
    '#type' => 'value',
    '#value' => $finder->finder_id ? $finder->finder_id : 'add',
    '#weight' => 0,
  );

  $form['links'] = array(
    '#type' => 'markup',
    '#value' => theme('finder_admin_links', $finder),
    '#weight' => 0,
  );

  $form['base'] = array(
    '#type' => 'select',
    '#title' => t('Finder type'),
    '#default_value' => $finder->base,
    '#options' => finder_admin_get_base_options(),
    '#description' => t('You cannot change this.'),
    '#required' => TRUE,
    '#disabled' => TRUE,
    '#weight' => 5,
  );
  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $finder->title,
    '#size' => 40,
    '#maxlength' => 255,
    '#required' => TRUE,
    '#weight' => 10,
  );
  $form['description'] = array(
    '#type' => 'textfield',
    '#title' => t('Description'),
    '#default_value' => $finder->description,
    '#size' => 80,
    '#description' => t('This will appear on the finder administration page.'),
    '#weight' => 20,
  );
  $form['path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path'),
    '#default_value' => $finder->path,
    '#description' => t('Finder needs a URL path for some functionality.  No leading or trailing slash.'),
    '#size' => 30,
    '#maxlength' => 128,
    '#required' => TRUE,
    '#weight' => 40,
  );
  $form['block'] = array(
    '#type' => 'checkbox',
    '#title' => t('Provide block'),
    '#default_value' => $finder->block,
    '#weight' => 50,
    '#description' => t('Create a block for the finder form.'),
  );

  $form['settings'] = array(
    '#weight' => 60,
  );

  $form['settings']['form_on_page'] = array(
    '#type' => 'checkbox',
    '#title' => t('Form on page'),
    '#default_value' => $finder->settings['form_on_page'],
    '#weight' => 10,
    '#description' => t('Put a finder form on the path given above.'),
  );

  $form['settings']['form'] = array(
    '#type' => 'fieldset',
    '#title' => t('Finder form settings'),
    '#weight' => 100,
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['settings']['form']['prefix'] = array(
    '#type' => 'textarea',
    '#title' => t('Prefix'),
    '#default_value' => isset($finder->settings['form']['prefix']) ? $finder->settings['form']['prefix'] : '',
    '#weight' => 10,
    '#description' => t('Displayed before the form.'),
  );
  $form['settings']['form']['suffix'] = array(
    '#type' => 'textarea',
    '#title' => t('Suffix'),
    '#default_value' => isset($finder->settings['form']['suffix']) ? $finder->settings['form']['suffix'] : '',
    '#weight' => 20,
    '#description' => t('Displayed after the form.'),
  );
  $form['settings']['form']['button_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Button text'),
    '#default_value' => isset($finder->settings['form']['button_text']) ? $finder->settings['form']['button_text'] : t('Find'),
    '#weight' => 100,
    '#description' => t('Text to display on the submit button.'),
  );
  $form['settings']['form']['go_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Go text'),
    '#default_value' => isset($finder->settings['form']['go_text']) ? $finder->settings['form']['go_text'] : t('Go'),
    '#weight' => 120,
    '#description' => t('Text to display on the go button. Only used with <em>redirect to result</em> "go" button option under <em>Advanced settings</em>.'),
  );
  $form['settings']['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced settings'),
    '#weight' => 200,
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['settings']['advanced']['filter'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show results on results page even before the form has been completed'),
    '#default_value' => isset($finder->settings['advanced']['filter']) ? $finder->settings['advanced']['filter'] : 0,
    '#weight' => 10,
    '#description' => t('Allows this finder to behave more as a filter.'),
  );

  $form['settings']['advanced']['submission'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use submit button'),
    '#default_value' => isset($finder->settings['advanced']['submission']) ? $finder->settings['advanced']['submission'] : 1,
    '#weight' => 20,
    '#description' => t('Gives the finder a submit button.'),
  );

  $form['settings']['advanced']['ahah'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use AHAH'),
    '#default_value' => isset($finder->settings['advanced']['ahah']) ? $finder->settings['advanced']['ahah'] : 0,
    '#weight' => 30,
    '#description' => t('Dynamically update the form and results with <a href="http://en.wikipedia.org/wiki/AHAH">AHAH javascript replacement</a> instead of loading a whole new page.'),
  );

  $form['settings']['advanced']['ahah_effect'] = array(
    '#type' => 'radios',
    '#title' => t('AHAH effect'),
    '#default_value' => isset($finder->settings['advanced']['ahah_effect']) ? $finder->settings['advanced']['ahah_effect'] : 'none',
    '#weight' => 32,
    '#description' => t('If using AHAH, this will be the transition effect when the update occurs.'),
    '#options' => array(
      'none' => t('None'),
      'fade' => t('Fade'),
      'slide' => t('Slide'),
    ),
  );

  $form['settings']['advanced']['element_combination'] = array(
    '#type' => 'radios',
    '#title' => t('Multiple element result combination'),
    '#default_value' => isset($finder->settings['advanced']['union']) ? $finder->settings['advanced']['union'] : 0,
    '#weight' => 40,
    '#description' => t('With multiple elements, how should the results of each element be combined on the results page?'),
    '#options' => array(
      0 => t('Intersect - Results must be present from <em>all</em> elements to appear.'),
      1 => t('Union - Results can be present in <em>any</em> element to appear.'),
    ),
  );

  $form['settings']['advanced']['pager'] = array(
    '#type' => 'textfield',
    '#title' => t('Pager'),
    '#default_value' => isset($finder->settings['advanced']['pager']) ? $finder->settings['advanced']['pager'] : '10',
    '#description' => t('Number of items to show per results page.  Set to 0 to disable pager.'),
    '#size' => 5,
    '#maxlength' => 8,
    '#weight' => 50,
  );

  $operators = finder_match_operator();
  foreach ($operators as $k => $v) {
    $operators[$k] = $v['description'];
  }

  $form['settings']['advanced']['match'] = array(
    '#type' => 'radios',
    '#title' => t('Result matching'),
    '#default_value' => isset($finder->settings['advanced']['match']) ? $finder->settings['advanced']['match'] : 'equals',
    '#options' => $operators,
    '#weight' => 60,
    '#description' => t('"Equals" is the most common result matching method.'),
  );

  $form['settings']['advanced']['goto'] = array(
    '#type' => 'radios',
    '#title' => t('Redirect to result'),
    '#default_value' => $finder->settings['advanced']['goto'] ? $finder->settings['advanced']['goto'] : '',
    '#weight' => 80,
    '#options' => array(
      '' => t('Never redirect to result, always use results page.'),
      'best' => t('Redirect to the only result, or show results page if there are multiple results.'),
      'always' => t('Always redirect to the first result.'),
      'go' => t('Use a seperate "go" button for the first result, otherwise use results page.'),  // 'separate' is intentionally written as 'seperate' here.
    ),
  );

  $form['settings']['advanced']['hide_args'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide URL arguments on results page'),
    '#default_value' => isset($finder->settings['advanced']['hide_args']) ? $finder->settings['advanced']['hide_args'] : 0,
    '#weight' => 160,
    '#description' => t("Use the user's session to store submitted values across multiple pages.  Also creates a token for multipage results to identify the stored submission."),
  );

  $form['settings']['advanced']['arg_sep'] = array(
    '#type' => 'textfield',
    '#title' => t('Multiple value URL arguments separator'),
    '#default_value' => isset($finder->settings['advanced']['arg_sep']) ? $finder->settings['advanced']['arg_sep'] : ',',
    '#weight' => 170,
    '#description' => t('Must contain at least one non-alphanumeric character other than \'/\', \'_\', and \'-\'.  If this string exists within a keyword, the keyword must be enclosed in double quotes to prevent parsing issues.'),
    '#size' => 5,
    '#required' => TRUE,
  );

  $form['settings']['advanced']['show_links'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show finder links'),
    '#default_value' => isset($finder->settings['advanced']['show_links']) ? $finder->settings['advanced']['show_links'] : 1,
    '#weight' => 180,
    '#description' => t('Show the <em>finder links</em>.'),
  );

  $form['settings']['advanced']['show_admin_links'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show finder admin links'),
    '#default_value' => isset($finder->settings['advanced']['show_admin_links']) ? $finder->settings['advanced']['show_admin_links'] : 1,
    '#weight' => 182,
    '#description' => t('Show the <em>finder admin links</em>.'),
  );

  if (!empty($finder->elements)) {
    $form['elements'] = array(
      '#title' => t('Elements'),
      '#weight' => 2000,
      '#theme' => 'finder_admin_edit_elements_table',
    );
    foreach ($finder->elements as $key => $value) {
      $element_output = '<div class="finder-element">';
      $element_output .= '<div class="finder-element-title"><strong>'. $value->title .'</strong></div>';
      $element_output .= '<div class="finder-element-element">'.t('Element').'<span class="finder-colon">:</span> <span>'. $value->element_handler['#title'] .'</span></div>';
      $element_output .= '</div>';
      $form['elements'][$value->finder_element_id]['value']['#value'] = $element_output;
      $form['elements'][$value->finder_element_id]['ops']['#value'] =
        l(t('Edit'), 'admin/build/finder/'. $finder->finder_id .'/edit/'. $value->finder_element_id .'/edit')
          . t(' | ') . l(t('Delete'), 'admin/build/finder/'. $finder->finder_id .'/edit/'. $value->finder_element_id .'/delete');
      $form['elements'][$value->finder_element_id]['weight'] = array(
        '#type' => 'weight',
        '#delta' => count($finder->elements),
        '#default_value' => $key,
      );
    }
  }
  else {
    $form['no_elements'] = array(
      '#value' => '
        <div id="edit-no-elements-wrapper" class="form-item">
        <label for="edit-path">'. t('Elements') .'<span class="finder-colon">:</span></label>'
        . t('There are currently no elements configured for this finder.')
        .'</div>
      ',
      '#weight' => 100,
    );
  }

  $form['add_element'] = array(
    '#type' => 'select',
    '#title' => t('Add element'),
    '#default_value' => '',
    '#options' => finder_admin_get_element_options(),
    '#description' => t('Choose the form element to use and press %save_button.', array('%save_button' => t('Save finder'))),
    '#weight' => 10000,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save finder'),
    '#weight' => 10010,
  );
  $form['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete finder'),
    '#weight' => 10020,
  );
  return $form;
}

/**
 * Validate function for admin finder add/edit page.
 */
function finder_admin_edit_validate($form, &$form_state) {
  $values = &$form_state['values'];
  $arg_sep = preg_replace("/[a-zA-Z0-9\-\_\s]/", "", $values['settings']['advanced']['arg_sep']);
  if (!$arg_sep) {
    form_set_error('arg_sep', t('Multiple value URL arguments separator must contain at least one non-alphanumeric character that is not \'/\', \'-\', or \'_\'.'));
  }
}

/**
 * Submit function for admin finder add/edit page.
 */
function finder_admin_edit_submit($form, &$form_state) {
  $values = &$form_state['values'];
  if ($form_state['clicked_button']['#id'] == 'edit-delete') {
    drupal_goto('admin/build/finder/'. $values['finder_id'] .'/delete');
  }
  else {
    $finder = (object)array(
      'finder_id' => $values['finder_id'],
      'base' => $values['base'],
      'title' => $values['title'],
      'description' => $values['description'],
      'path' => $values['path'],
      'block' => $values['block'],
      'settings' => $values['settings'],
    );
    $finder = finder_save($finder);
    if (is_array($values['elements'])) {
      foreach ($values['elements'] as $finder_element_id => $value) {
        $finder_element = (object)array(
          'finder_element_id' => $finder_element_id,
          'weight' => $value['weight'],
        );
        $finder_element = finder_element_save($finder_element);
      }
    }
    drupal_set_message('The finder was saved.');
    menu_rebuild();
    if ($values['add_element']) {
      $finder_element = (object)array(
        'finder_id' => $values['finder_id'],
        'element' => $values['add_element'],
      );
      $finder_element = finder_element_save($finder_element);
      $finder_element->title = t('Finder element !1', array('!1' => $finder_element->finder_element_id));
      $finder_element = finder_element_save($finder_element);
      drupal_set_message(t('A finder element was added.  Configure the element below.'));
      drupal_goto('admin/build/finder/'. $values['finder_id'] .'/edit/'. $finder_element->finder_element_id .'/edit/');
    }
    else {
      drupal_goto('admin/build/finder/'. $values['finder_id'] .'/edit/');
    }
  }
}

/**
 * Admin finder delete page.
 */
function finder_admin_delete($form_state, $finder) {
  // allow modules to change the finder here
  finder_invoke_finderapi($finder, 'finder_admin_delete');

  $form_state['storage']['finder'] = $finder;
  // build the form
  $form = array();
  $form['#tree'] = TRUE;
  $form['finder_id'] = array(
    '#type' => 'value',
    '#value' => $finder->finder_id,
  );
  return confirm_form($form,
    t('Are you sure you want to delete the %ft finder?', array('%ft' => $finder->title)),
    $_GET['destination'] ? $_GET['destination'] : 'admin/build/finder',
    t('This action cannot be undone.'),
    t('Delete'), t('Cancel'));
}

/**
 * Submit function for admin finder delete page.
 */
function finder_admin_delete_submit($form, &$form_state) {
  $finder_id = &$form_state['values']['finder_id'];
  finder_delete($finder_id);
  menu_rebuild();
  drupal_goto('admin/build/finder');
}

/**
 * Admin finder element add/edit page.
 * Must have a Finder object at this point.
 */
function finder_admin_element_edit($form_state, $finder, $finder_element_id) {
  $element = &finder_element($finder, $finder_element_id);

  // allow modules to change the element here
  finder_invoke_finderapi($element, 'finder_admin_element_edit');

  $module = &$finder->base_handler['#module'];
  $fields = module_invoke($module, 'finder_fields', $finder, $finder_element_id);

  $form_state['storage']['finder'] = $finder;
  $form_state['storage']['finder_element_id'] = $finder_element_id;
  $form_state['storage']['finder_element_defaults'] = $element;


  $form = array();

  $form['#tree'] = TRUE;

  $form['finder_id'] = array(
    '#type' => 'value',
    '#value' => $finder->finder_id,
    '#weight' => 0,
  );

  $form['finder_element_id'] = array(
    '#type' => 'value',
    '#value' => $finder_element_id,
    '#weight' => 2,
  );

  $form['element'] = array(
    '#type' => 'select',
    '#title' => t('Element'),
    '#default_value' => $element->element ? $element->element : '',
    '#options' => finder_admin_get_element_options(),
    '#description' => t('The form element to use.'),
    '#required' => TRUE,
    '#weight' => 10,
  );

  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $element->title ? $element->title : '',
    '#size' => 40,
    '#maxlength' => 255,
    '#required' => TRUE,
    '#weight' => 20,
    '#description' => t('The label on the form element.'),
  );

  $form['settings'] = array(
    '#weight' => 30,
  );

  $form['settings']['form'] = array(
    '#type' => 'fieldset',
    '#title' => t('Finder form element settings'),
    '#weight' => 0,
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['settings']['form']['description'] = array(
    '#type' => 'textfield',
    '#title' => t('Description'),
    '#default_value' => isset($element->settings['form']['description']) ? $element->settings['form']['description'] : '',
    '#weight' => 10,
    '#description' => t('The description or help message to display with the form element.'),
  );

  $form['settings']['form']['prefix'] = array(
    '#type' => 'textarea',
    '#title' => t('Prefix'),
    '#default_value' => isset($element->settings['form']['prefix']) ? $element->settings['form']['prefix'] : '',
    '#weight' => 20,
    '#description' => t('Displayed before the form element.'),
  );

  $form['settings']['form']['suffix'] = array(
    '#type' => 'textarea',
    '#title' => t('Suffix'),
    '#default_value' => isset($element->settings['form']['suffix']) ? $element->settings['form']['suffix'] : '',
    '#weight' => 30,
    '#description' => t('Displayed after the form element.'),
  );

  $form['settings']['form']['default_value'] = array(
    '#type' => 'textfield',
    '#title' => t('Default value'),
    '#default_value' => isset($element->settings['form']['default_value']) ? $element->settings['form']['default_value'] : '',
    '#weight' => 40,
    '#description' => t('Default value of the form element.'),
  );

  $form['settings']['form']['required'] = array(
    '#type' => 'checkbox',
    '#title' => t('Required'),
    '#default_value' => isset($element->settings['form']['required']) ? $element->settings['form']['required'] : 0,
    '#weight' => 50,
    '#description' => t('Enforce user input in this field.'),
  );

  $form['settings']['choices'] = array(
    '#type' => 'fieldset',
    '#title' => t('Possible choices'),
    '#weight' => 60,
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  if ($fields) {
    $form['settings']['choices']['field'] = array(
      '#type' => 'select',
      '#title' => t('Find items by this field'),
      '#default_value' => $element->settings['choices']['field'],
      '#options' => $fields,
      '#description' => t('This is the field that will be searched on.'),
      '#weight' => 20,
      '#required' => TRUE,
    );
  }
  else {
    $form['settings']['choices']['no_field'] = array(
      '#value' => t("<em>There are currently no fields to choose from.</em>"),
      '#weight' => 20,
    );
  }

  $form['settings']['choices']['sort'] = array(
    '#type' => 'checkbox',
    '#title' => t('Sort options'),
    '#default_value' => $element->settings['choices']['sort'],
    '#weight' => 100,
    '#description' => t('Will put the list into order for easier scanning.'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save finder element'),
    '#weight' => 10000,
  );

  $form['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete finder element'),
    '#weight' => 10020,
  );

  return $form;
}

/**
 * Submit function for admin finder add/edit page.
 */
function finder_admin_element_edit_submit($form, &$form_state) {
  $values = &$form_state['values'];
  if ($form_state['clicked_button']['#id'] == 'edit-delete') {
    drupal_goto('admin/build/finder/'. $values['finder_id'] .'/edit/'. $values['finder_element_id'] .'/delete');
  }
  else {
    $finder_element = (object)array(
      'finder_element_id' => $values['finder_element_id'],
      'finder_id' => $values['finder_id'],
      'element' => $values['element'],
      'title' => $values['title'],
      'weight' => $values['weight'],
      'settings' => $values['settings'],
    );
    $finder_element = finder_element_save($finder_element);

    drupal_set_message(t('The finder element was saved.'));
    drupal_goto('admin/build/finder/'. $values['finder_id'] .'/edit');
  }
}

/**
 * Admin finder delete page
 */
function finder_admin_element_delete($form_state, $finder, $finder_element_id) {
  $element = &finder_element($finder, $finder_element_id);

  // allow modules to change the element here
  finder_invoke_finderapi($element, 'finder_admin_element_delete');

  $form = array();
  $form['#tree'] = TRUE;
  $form['finder_element_id'] = array(
    '#type' => 'value',
    '#value' => $finder_element_id,
  );
  $form['finder_id'] = array(
    '#type' => 'value',
    '#value' => $finder->finder_id,
  );
  return confirm_form($form,
    t('Are you sure you want to delete the finder element %fet ?', array('%fet' => $element->title)),
    $_GET['destination'] ? $_GET['destination'] : 'admin/build/finder/'. $finder->finder_id .'/edit',
    t('This action cannot be undone.'),
    t('Delete'), t('Cancel'));
}

/**
 * Submit function for admin finder delete page.
 */
function finder_admin_element_delete_submit($form, &$form_state) {
  $finder_element_id = &$form_state['values']['finder_element_id'];
  $finder_id = &$form_state['values']['finder_id'];
  finder_element_delete($finder_element_id);
  drupal_goto('admin/build/finder/'. $finder_id .'/edit');
}

/**
 * Get base handlers and convert to options array.
 */
function finder_admin_get_base_options() {
  $bases = finder_base_handlers();
  $base_options = array('' => t('- Select a finder type -'));
  if (!empty($bases)) {
    foreach ($bases as $base_key => $base_data) {
      $base_options[$base_key] = $base_data['#title'];
    }
  }
  else {
    drupal_set_message(t('Error: You must activate a finder base handler module such as Finder Views or Finder Node to begin.'), 'error');
    return array('' => t('- No finder types to choose from -'));
  }
  return $base_options;
}

/**
 * Get element handlers and convert to options array.
 */
function finder_admin_get_element_options() {
  $elements = finder_element_handlers();
  $element_options = array('' => t('- Select an element type -'));
  if (!empty($elements)) {
    foreach ($elements as $element_key => $element_data) {
      $element_options[$element_key] = t($element_data['#title']);
    }
  }
  else {
    drupal_set_message(t('Error: You must activate a finder element handler module such as Finder Autocomplete or Finder Optionwidgets to begin.'), 'error');
    return array('' => t('- No element types to choose from -'));
  }
  return $element_options;
}