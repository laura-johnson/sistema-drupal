<?php
/**
 * @file
 * Contains \Drupal\sistema_custom\Controller\ApplicationPage.
 */

namespace Drupal\sistema_custom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse; 
use Drupal\Core\Ajax\OpenModalDialogCommand;
use \Drupal\node\Entity\Node;
	

/**
 * Provides route responses for the SistemaCustom module.
 */
class ApplicationPage extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function appPage($nid = NULL) {

    $node = Node::load($nid);
    $title = $node->title->value;
    $content = $node->body->value;
    
    return array (
      '#type' => 'markup',
      '#markup' => $content,
      '#title' =>  $title,
      '#attached' => array( 
	'library' => array( 
	   array('system', 'drupal.ajax'),
         ), 
      ), 
    );    
  }
 public function modal() { 

  $response = new AjaxResponse();
  $response->addCommand(new OpenModalDialogCommand());
  return $response;
  }
}

?>
