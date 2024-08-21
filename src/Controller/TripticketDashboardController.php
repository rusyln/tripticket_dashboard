<?php

namespace Drupal\tripticket_dashboard\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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

    $build = array(
      'page' => array(
        '#theme' => 'tripticket_dashboard',
        '#content' => $content,
      ),
    );
    $html = \Drupal::service('renderer')->renderRoot($build);
    $response = new Response();
    $response->setContent($html);
  
    return $response;
  }

}
