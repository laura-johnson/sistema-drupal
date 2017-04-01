<?php
namespace Drupal\sistema_custom\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use \Drupal\node\Entity\Node;


/**
 * Access check for user registration routes.
 */

class AccessSistemaStudent implements AccessInterface {

  public function access(Route $route, AccountInterface $account, $arg = NULL) {

    $access = AccessResult::forbidden();

    $student = Node::load($arg);
    if ($account) {
      if ($student->field_student_parent_uid->target_id > 0 && $student->field_student_parent_uid->target_id == $account->id()) {
        $access = AccessResult::allowed();
      }
      elseif ($arg == 'new' && $account->hasPermission('create application content')) {
        $access = AccessResult::allowed();
      }
    }

    return $access;

   }

}
?>
