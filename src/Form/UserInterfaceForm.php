<?php

namespace Drupal\archive\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UserInterfaceForm.
 */
class UserInterfaceForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'archive.userinterface',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_interface_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('archive.userinterface');
    $form['archive_type'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types available in archive'),
      '#description' => $this->t('Posts of these types will be displayed in the archive.'),
      '#default_value' => $config->get('archive_type'),
      '#options' => contentTypeList(),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('archive.userinterface')
      ->set('archive_type', $form_state->getValue('archive_type'))
      ->save();
  }

}
