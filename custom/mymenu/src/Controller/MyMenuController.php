<?php

namespace Drupal\mymenu\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MyMenuController extends ControllerBase {

  public function showName() {
    $submenu_items = [
      [
        'title' => $this->t('Property Form'),
        'description' => $this->t('Create properties.'),
        'link' => Url::fromRoute('mymenu.form'),
      ],
      [
        'title' => $this->t('Property Data'),
        'description' => $this->t('View and manage property data.'),
        'link' => Url::fromRoute('mymenu.dummy'),
      ],
    ];

    $items = [];
    foreach ($submenu_items as $item) {
      $items[] = [
        '#type' => 'markup',
        '#markup' => '<div class="admin-menu-item">
                        <a href="' . $item['link']->toString() . '">
                          <span class="admin-menu-title">' . $item['title'] . '</span>
                        </a>
                        <div class="admin-menu-description">' . $item['description'] . '</div>
                      </div>',
        '#allowed_tags' => ['div', 'a', 'span'],
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
      '#attributes' => ['class' => ['admin-menu-list']],
    ];
  }

  public function dummyContent() {
    $connection = Database::getConnection();
    $request = \Drupal::request();
    $search = $request->query->get('search', '');

    // Get total count of records with search filter
    $query = $connection->select('mymenu_data', 'm')
      ->fields('m', ['id', 'property_name', 'property_type', 'price', 'city', 'state', 'country']);

    if (!empty($search)) {
      $query->condition('property_name', '%' . $search . '%', 'LIKE');
    }

    $total = $query->countQuery()->execute()->fetchField();

    // Pagination setup
    $limit = 10;
    $current_page = \Drupal::service('pager.manager')->createPager($total, $limit)->getCurrentPage();
    $offset = $current_page * $limit;

    // Fetch paginated data
    $query->range($offset, $limit);
    $result = $query->execute();

    $rows = [];
    foreach ($result as $record) {
      $edit_link = Link::fromTextAndUrl('Edit', Url::fromRoute('mymenu.edit', ['id' => $record->id]))->toString();
      $delete_link = Link::fromTextAndUrl('Delete', Url::fromRoute('mymenu.delete', ['id' => $record->id]))->toString();

      $rows[] = [
        'data' => [
          $record->property_name,
          $record->property_type,
          '$' . number_format($record->price, 2),
          $record->city . ', ' . $record->state . ', ' . $record->country,
          Markup::create($edit_link . ' | ' . $delete_link),
        ],
      ];
    }

    // Define table headers
    $header = [
      ['data' => $this->t('Property Name')],
      ['data' => $this->t('Property Type')],
      ['data' => $this->t('Price')],
      ['data' => $this->t('Location')],
      ['data' => $this->t('Actions')],
    ];

    // Build search form
    $form = [
      '#type' => 'container',
      'search_form' => [
        '#type' => 'markup',
        '#markup' => '<form method="get" action="' . Url::fromRoute('mymenu.dummy')->toString() . '">
                        <input type="text" name="search" placeholder="Search property..." value="' . htmlspecialchars($search) . '">
                        <button type="submit">Search</button>
                      </form>',
        '#allowed_tags' => ['form', 'input', 'button'],
      ],
    ];

    // Build render array
    $build = [
      'search' => $form,
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No properties found.'),
      ],
      'pager' => [
        '#type' => 'pager',
      ],
    ];

    return $build;
  }

  public function deleteProperty($id) {
    $connection = Database::getConnection();
    $connection->delete('mymenu_data')
      ->condition('id', $id)
      ->execute();

    \Drupal::messenger()->addMessage($this->t('Property deleted successfully!'));

    return new RedirectResponse(Url::fromRoute('mymenu.dummy')->toString());
  }
}
