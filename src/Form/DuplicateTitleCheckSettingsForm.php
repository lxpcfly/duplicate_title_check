<?php

namespace Drupal\duplicate_title_check\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

final class DuplicateTitleCheckSettingsForm extends ConfigFormBase {

  public function getFormId(): string {
    return 'duplicate_title_check_settings_form';
  }

  protected function getEditableConfigNames(): array {
    return ['duplicate_title_check.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config  = $this->config('duplicate_title_check.settings');
    $enabled = $config->get('enabled_bundles') ?? [];

    /** @var \Drupal\node\NodeTypeInterface[] $types */
    $types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    $options = [];
    foreach ($types as $type) {
      $options[$type->id()] = $type->label();
    }

    $form['help'] = [
      '#markup' => $this->t('Select the content types that should warn editors when a <strong>published</strong> node already uses the same title. If none are selected, the module treats it as <strong>all types enabled</strong>.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];

    $form['enabled_bundles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Enabled content types'),
      '#options' => $options,
      '#default_value' => $enabled,
      '#description' => $this->t('Leave all unchecked to apply to all content types.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values   = $form_state->getValue('enabled_bundles') ?? [];
    $selected = array_values(array_filter($values, static fn($v) => !empty($v)));

    $this->config('duplicate_title_check.settings')
      ->set('enabled_bundles', $selected)
      ->save();

    parent::submitForm($form, $form_state);
  }
}