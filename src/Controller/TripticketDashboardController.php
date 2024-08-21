<?php

namespace Drupal\tripticket_dashboard\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\BareHtmlPageRenderer;
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

    $attachments = \Drupal::service('html_response.attachments_processor');
    $renderer = \Drupal::service('renderer');
    
    $bareHtmlPageRenderer = new BareHtmlPageRenderer($renderer, $attachments);
    
    $response = $bareHtmlPageRenderer->renderBarePage($build, 'Page Title', 'markup');
    return $response;
  }

}
