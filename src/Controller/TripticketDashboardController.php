<?php

namespace Drupal\tripticket_dashboard\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\BareHtmlPageRenderer;
use Drupal\Core\Html\HtmlResponseAttachmentsProcessorInterface;

/**
 * Returns responses for Tripticket Dashboard routes.
 */
class TripticketDashboardController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    // Check if the user has the necessary permission.
    if (!$this->currentUser()->hasPermission('administer tripticket_dashboard configuration')) {
      return new RedirectResponse('/');
    }

    // Build the content for the page.
    $content = [
      '#theme' => 'tripticket_dashboard',
      '#content' => $content,
      '#attached' => [
        'library' => [
          'tripticket_dashboard/tripticket_dashboard',  // Attach your custom library here.
        ],
      ],
    ];

    // Use Drupal services to render the page.
    $bareHtmlPageRenderer = new BareHtmlPageRenderer(\Drupal::service('renderer'), \Drupal::service('html_response.attachments_processor'));

    // Render the bare HTML page.
   

    // Attach head tags using HtmlResponseAttachmentsProcessorInterface.
    $attachmentsProcessor = \Drupal::service('html_response.attachments_processor');
    if ($attachmentsProcessor instanceof HtmlResponseAttachmentsProcessorInterface) {
      $attachmentsProcessor->processAttachments($response);
    }

    // Retrieve the attachments directly from the response.
    $attachments = $response->getAttachments();

    // Filter out all libraries except the ones from your custom module.
    if (isset($attachments['library'])) {
      $attachments['library'] = array_filter($attachments['library'], function ($library) {
        // Keep only the libraries from your module.
        return strpos($library, 'tripticket_dashboard/') === 0;
      });
    }

    // Re-process the attachments to ensure only your module's assets are attached.
    $response->setAttachments($attachments);

    return $response;
  }

}

