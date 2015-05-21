<?php

    $taxonomies = array();

    // Cover Tax
    $taxonomies[] = array(
        'cover_categories' => array(
            'label' => 'Cover Categories',
            'rewrite' => 'cover_categories',
            'hierarchical' => TRUE
        ),
        'cover_tags' => array(
            'label' => 'Cover Tags',
            'rewrite' => 'cover_tags',
            'hierarchical' => FALSE
        ),
    );