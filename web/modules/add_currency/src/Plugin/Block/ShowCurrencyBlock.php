<?php

namespace Drupal\add_currency\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "add_currency_example",
 *   admin_label = @Translation("add_currency"),
 *   category = @Translation("add_currency")
 * )
 */
class ShowCurrencyBlock extends BlockBase
{

    /**
     * {@inheritdoc}
     */

    public function build()
    {
        $service = \Drupal::service('add_currency.currency');

        $build['content'] = [
            '#theme' => 'currency_display',
            '#curs_usd' => $service->getCurs('USD'),
            '#curs_eur' => $service->getCurs('EUR'),
            '#curs_pln' => $service->getCurs('PLN'),
            '#curs_gbp' => $service->getCurs('GBP'),
        ];
        return $build;
    }

}


