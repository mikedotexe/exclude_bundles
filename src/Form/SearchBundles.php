<?php

/**
 * @file
 * Contains \Drupal\exclude_bundles\Form\SearchBundles.
 */

namespace Drupal\exclude_bundles\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SearchBundles.
 *
 * @package Drupal\exclude_bundles\Form
 */
class SearchBundles extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_bundles';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'exclude_bundles.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('exclude_bundles.settings');
    $configured_bundles = $config->get('bundles') ? $config->get('bundles') : [];

    // get list of current Content Types
    $node_bundles = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();

    $options = [];
    foreach ($node_bundles as $bundle) {
      $options[$bundle->id()] = $bundle->label();
    }

    $form['bundles'] = array(
      '#type' => 'checkboxes',
      '#options' => $options,
      '#title' => t('Content types to exclude from search'),
      '#default_value' => $configured_bundles,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('exclude_bundles.settings')
      ->set('bundles', $form_state->getValue('bundles'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
