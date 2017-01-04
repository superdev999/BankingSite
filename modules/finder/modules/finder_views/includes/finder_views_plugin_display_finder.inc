<?php
// $Id: finder_views_plugin_display_finder.inc,v 1.1.2.31 2009/07/22 03:02:53 danielb Exp $

/**
 * @file
 * Handler for 'finder_views' display.
 */
class finder_views_plugin_display_finder extends views_plugin_display {

  function execute() {
    return $this->view->render($this->display->id);
  }

  function render() {
    return !empty($this->view->result) || !empty($this->view->style_plugin->definition['even empty']) ? $this->view->style_plugin->render($this->view->result) : '';
  }

  function query() {
    $options = $this->get_option('finder_views_options');

    $match = $options['mode'] == 'results' ? $options['results_match'] : $options['match'];
    if (!empty($options['keywords'])) {
      $this->view->query->set_where_group($options['combination'], 'finder');
      foreach ($options['keywords'] as $feid => $keyword_array) {
        $keyword_array = (array)$keyword_array;

        if (!empty($keyword_array)) {
          $clauses = array();
          $clause_args = array();

          $alias = $this->view->query->ensure_table($options['field_info'][$feid]['table']);

          if (!$alias) {
            // This is a hack to get fields present via a relationship to work.
            // Doesn't work if you haven't added the field to the view via UI though.
            $field_info = $this->view->field[$options['field_info'][$feid]['field']];
            $table = $field_info->definition['table'];
            $alias = $field_info->table_alias;

            // Alternate idea that might work without adding the field to the view:
            // $table_data = views_fetch_data($options['field_info'][$feid]['table']);
          }
          else {
            $join_data = $this->view->query->get_join_data($alias, $options['base_table']);
            $table = $join_data->table ? $join_data->table : $options['field_info'][$feid]['table'];
          }
          // need a way to get the
          $match = finder_placeholder($match, $table, $options['field_info'][$feid]['field']);
          foreach ($keyword_array as $keyword) {
            $clauses[] = $alias .'.'. $options['field_info'][$feid]['field'] . $match;
            $clause_args[] = $keyword;
          }
          if (!empty($clauses)) {
            $this->view->query->add_where('finder', '('. implode(' OR ', $clauses) .')', $clause_args);
          }
        }

      }
    }
  }

}

