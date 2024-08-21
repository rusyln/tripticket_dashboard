<?php

namespace Drupal\tripticket_dashboard\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\BareHtmlPageRenderer;
use Drupal\Core\Html\HtmlResponseAttachmentsProcessorInterface;
use Drupal\Core\Asset\AttachedAssets;

/**
 * Returns responses for Tripticket Dashboard routes.
 */
class TripticketDashboardController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    // Check if the user has the necessary permission.
    if (!$this->currentUser()->hasPermission('administer tripticket_dashboard configuration')){
      return new RedirectResponse('/');
    }

    // Build the content for the page.
    $content = [
      '#theme' => 'tripticket_dashboard',
      '#content' => $content,
      '#attached' => [
        'library' => [
          'tripticket_dashboard/custom_library',  // Attach your custom library here.
        ],
      ],
    ];

    // Use Drupal services to render the page.
    $bareHtmlPageRenderer = new BareHtmlPageRenderer(\Drupal::service('renderer'), \Drupal::service('html_response.attachments_processor'));
    
    // Render the bare HTML page.
    $response = $bareHtmlPageRenderer->renderBarePage($content, 'Page Title', 'markup');
    
    // Attach head tags using HtmlResponseAttachmentsProcessorInterface.
    $attachmentsProcessor = \Drupal::service('html_response.attachments_processor');
    if ($attachmentsProcessor instanceof HtmlResponseAttachmentsProcessorInterface) {
      $attachmentsProcessor->processAttachments($response);
    }
    
    // Get the attached assets (libraries, styles, scripts, etc.).
    $assets = $response->getAttachedAssets();

    // Debug and print all the head tags.
    dpm($assets->getHead());

    return $response;
  }

}
