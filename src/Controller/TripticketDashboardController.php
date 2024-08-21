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

    // Retrieve the attachments directly from the response.
    $attachments = $response->getAttachments();

    // Filter out theme libraries.
    if (isset($attachments['library'])) {
      $attachments['library'] = array_filter($attachments['library'], function ($library) {
        // Keep only the libraries from your module.
        return strpos($library, 'tripticket_dashboard/') === 0;
      });
    }

    // Log all head tags.
    if (isset($attachments['html_head'])) {
      foreach ($attachments['html_head'] as $head_tag) {
        // Convert the head tag to a string for logging.
        $head_tag_string = \Drupal::service('renderer')->renderPlain($head_tag[0]);
        \Drupal::logger('tripticket_dashboard')->info('Head Tag: <pre>@tag</pre>', ['@tag' => htmlspecialchars($head_tag_string)]);
      }
    }

    return $response;
  }

}
