<?php

namespace Drupal\tripticket_dashboard\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Tripticket Dashboard routes.
 */
class TripticketDashboardController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {


    if (!$this -> currentUser()->hasPermission('administer tripticket_dashboard configuration')){
      return new RedirectResponse('/');
    }

    $build = [];
    $build['#theme'] = 'tripticket_dashboard';


    return $build;
  }

}
