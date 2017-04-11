<?php

$settings = array(
  array(
    'type'       => 'colorpicker',
    'label'      => __('Thumbnail Border Color'),
    'id'         => 'thumbnail-border-color',
    'value'      => '#dadada',
    'pro'        => FALSE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-thumbnail', 'attribute' => 'borderColor'),
      )
  ),
  array(
    'type'       => 'colorpicker',
    'label'      => __('Video Title Color'),
    'id'         => 'video-title-color',
    'value'      => '#565656',
    'pro'        => FALSE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-data h1.vimeography-title', 'attribute' => 'color'),
      )
  ),
  array(
    'type'       => 'colorpicker',
    'label'      => __('Video Description Color'),
    'id'         => 'video-description-color',
    'value'      => '#555555',
    'pro'        => FALSE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-data p.vimeography-description', 'attribute' => 'color'),
      )
  ),
  array(
    'type'       => 'colorpicker',
    'label'      => __('Paging Loader Color'),
    'id'         => 'loader-color',
    'value'      => '#000000',
    'pro'        => TRUE,
    'namespace'  => TRUE,
    'important'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-spinner div div', 'attribute' => 'backgroundColor'),
      )
  ),
  array(
    'type'       => 'colorpicker',
    'label'      => __('Paging Title Color'),
    'id'         => 'paging-title-color',
    'value'      => '#000000',
    'pro'        => TRUE,
    'namespace'  => TRUE,
    'important'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-paging', 'attribute' => 'color'),
      )
  ),
  array(
    'type'       => 'slider',
    'label'      => __('Thumbnail Width'),
    'id'         => 'thumbnail-width',
    'value'      => '200',
    'min'        => '150',
    'max'        => '500',
    'step'       => '10',
    'pro'        => TRUE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-thumbnail-container', 'attribute' => 'maxWidth'),
      )
  ),
  array(
    'type'       => 'slider',
    'label'      => __('Thumbnail Height'),
    'id'         => 'thumbnail-height',
    'value'      => '200',
    'min'        => '150',
    'max'        => '300',
    'step'       => '10',
    'pro'        => TRUE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-thumbnail-container', 'attribute' => 'height'),
      )
  ),
  array(
    'type'       => 'numeric',
    'label'      => __('Thumbnail Spacing'),
    'id'         => 'thumbnail-spacing',
    'value'      => '5',
    'min'        => '5',
    'max'        => '20',
    'step'       => '1',
    'pro'        => TRUE,
    'namespace'  => TRUE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-thumbnail-container', 'attribute' => 'margin'),
      )
  ),
  array(
    'type'       => 'visibility',
    'label'      => __('Show Video Playcount'),
    'id'         => 'video-playcount-visibility',
    'value'      => 'block',
    'pro'        => TRUE,
    'namespace'  => FALSE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge.vimeography-template span.vimeography-plays', 'attribute' => 'display'),
      )
  ),
  array(
    'type'       => 'visibility',
    'label'      => __('Show Tags'),
    'id'         => 'video-tags-visibility',
    'value'      => 'block',
    'pro'        => TRUE,
    'namespace'  => FALSE,
    'properties' =>
      array(
        array('target' => '.vimeography-bridge .vimeography-video .vimeography-tags', 'attribute' => 'display'),
      )
  ),
);