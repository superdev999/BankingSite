<?php
// $Id: finder_views_plugin_style_php_array_finder.inc,v 1.1.2.20 2009/07/22 03:02:53 danielb Exp $

/**
 * @file
 * Handler for 'finder_views_php_array_finder' style.
 */
class finder_views_plugin_style_php_array_finder extends views_plugin_style {
  function render() {
    $results = array();

    // Group the rows according to the grouping field, if specified.
    $sets = $this->render_grouping($this->view->result, $this->options['grouping']);
    $base_field = $this->view->base_field;
    $field = $this->display->display_options['finder_views_field'];
    if ($field) {
      $field_alias = $this->view->field[$field]->field_alias;
    }
    $options = $this->display->display_options['finder_views_options'];

    foreach ($sets as $title => $records) {
      foreach ($records as $label => $row) {
        $result = array();
        $result['base_table'] = $options['base_table'];
        $result['base_field'] = $base_field;
        $result[$base_field] = $row->$base_field;
        $result['set'] = $title;
        if ($options['mode'] == 'choices') {
          $result[$field] = $row->$field_alias;
          $result['field_name'] = $field;
        }
        elseif ($options['mode'] == 'results') {
          $result['rendered'] = $this->row_plugin->render($row);
          foreach ($options['field_info'] as $feid => $info) {
            $alias = $this->view->field[$info['field']]->field_alias;
            $result[$info['field']] = $row->$alias;
            $result['field_names'][$feid] = $info['field'];
          }
        }
        $results[] = (object)$result;
      }
    }

    return $results;
  }
}