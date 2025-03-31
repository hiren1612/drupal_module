<?php

namespace Drupal\mymenu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class MyMenuForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mymenu_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['property_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Property Name'),
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
      '#required' => TRUE,
    ];
    $form['price'] = [
      '#type' => 'number',
      '#title' => $this->t('Price ($)'),
      '#required' => TRUE,
    ];
    $form['bedrooms'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Bedrooms'),
      '#required' => TRUE,
    ];
    $form['bathrooms'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Bathrooms'),
      '#required' => TRUE,
    ];
    $form['square_feet'] = [
      '#type' => 'number',
      '#title' => $this->t('Square Feet'),
      '#required' => TRUE,
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
    ];
    $form['state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('State'),
      '#required' => TRUE,
    ];
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#required' => TRUE,
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Property Description'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $connection = Database::getConnection();
    $connection->insert('mymenu_data')
      ->fields([
        'property_name' => $values['property_name'],
        'property_type' => $values['property_type'],
        'price' => $values['price'],
        'bedrooms' => $values['bedrooms'],
        'bathrooms' => $values['bathrooms'],
        'square_feet' => $values['square_feet'],
        'city' => $values['city'],
        'state' => $values['state'],
        'country' => $values['country'],
        'description' => $values['description'],
      ])
      ->execute();
    \Drupal::messenger()->addMessage($this->t('Property details saved successfully!'));
  }
}