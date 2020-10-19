<?php

namespace Drupal\add_currency\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for add_currency routes.
 */
class AddCurrencyController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
