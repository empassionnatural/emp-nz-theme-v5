<?php

add_filter( 'rwmb_meta_boxes', 'prefix_register_meta_boxes' );
function prefix_register_meta_boxes( $meta_boxes ) {
    $prefix = 'field_prefix_';
    $meta_boxes[] = array(
        'id'         => 'ingredients',
        'title'      => 'Ingredients Information',
        'post_types' => 'product',
        'context'    => 'normal',
        'priority'   => 'high',

        'fields' => array(
            array(
                'name'  => 'Ingredients',
                'desc'  => 'Ingredients Description',
                'id'    => $prefix . 'name',
                'type'  => 'wysiwyg',
            ),
        )
    );

    // Add more meta boxes if you want
    // $meta_boxes[] = ...

    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'prefix_usage_meta_boxes' );
function prefix_usage_meta_boxes( $meta_boxes ) {
    $prefix = 'field_prefix_';
    $meta_boxes[] = array(
        'id'         => 'usage',
        'title'      => 'Usage Information',
        'post_types' => 'product',
        'context'    => 'normal',
        'priority'   => 'high',

        'fields' => array(
            array(
                'name'  => 'usage',
                'desc'  => 'Usage Description',
                'id'    => $prefix . 'usage',
                'type'  => 'wysiwyg',
            ),
        )
    );

    // Add more meta boxes if you want
    // $meta_boxes[] = ...

    return $meta_boxes;
}

