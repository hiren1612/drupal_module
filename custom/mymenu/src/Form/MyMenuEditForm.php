<?php

namespace Drupal\mymenu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class MyMenuEditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mymenu_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $connection = Database::getConnection();
    $record = $connection->select('mymenu_data', 'm')
      ->fields('m')
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$record) {
      \Drupal::messenger()->addMessage($this->t('Invalid Property ID.'), 'error');
      return new RedirectResponse(Url::fromRoute('mymenu.dummy')->toString());
    }

    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $record['id'],
    ];
    $form['property_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Property Name'),
      '#default_value' => $record['property_name'],
      '#required' => TRUE,
    ];
    $form['property_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Property Type'),
      '#options' => [
        'apartment' => $this->t('Apartment'),
        'house' => $this->t('House'),
        'villa' => $this->t('Villa'),
        'commercial' => $this->t('Commercial'),
      ],
      '#default_value' => $record['property_type'],
      '#required' => TRUE,
    ];
    $form['price'] = [
      '#type' => 'number',
      '#title' => $this->t('Price ($)'),
      '#default_value' => $record['price'],
      '#required' => TRUE,
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $record['city'],
      '#required' => TRUE,
    ];
    $form['state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('State'),
      '#default_value' => $record['state'],
      '#required' => TRUE,
    ];
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $record['country'],
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $connection = Database::getConnection();
    $connection->update('mymenu_data')
      ->fields([
        'property_name' => $values['property_name'],
        'property_type' => $values['property_type'],
        'price' => $values['price'],
        'city' => $values['city'],
        'state' => $values['state'],
        'country' => $values['country'],
      ])
      ->condition('id', $values['id'])
      ->execute();

    \Drupal::messenger()->addMessage($this->t('Property details updated successfully!'));

    $form_state->setRedirect('mymenu.dummy');
  }
}
