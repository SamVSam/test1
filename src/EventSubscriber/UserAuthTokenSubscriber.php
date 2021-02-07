<?php

namespace Drupal\user_auth_token\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

class UserAuthTokenSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkAuth');
    return $events;
  }

  /**
   * Method to check token and authenticate user.
   */
  public function checkAuth($event) {

    $request = \Drupal::request();

    $_ = $request->query->get('authtoken');
    $auth_token = is_null($_) ? 'token_not_set' : $_;

    if ($auth_token != '' && $auth_token == 'qwe123') {



// TODO
// - query field for token
// - handle user/0
// - make autologin
//     $db_connection->query('
//        SELECT uid
//        FROM {user__field_auth_token}
//        WHERE field_auth_token_value = @_1
//     ', ['@_1' => $auth_token])
//     ->fetchField();
// ^^^^ MAKE IT FIELD QUERY ^^^^^^^^^^^^^^^
//     $user = User::load($uid);
//     user_login_finalize($user);
//
// - Find how to solve existing destination:
//     local.dept/user/69/edit?destination=/admin/people



      $destination = trim(str_replace($request->getQueryString(), '', $request->getRequestUri()), '?');
      $response = new RedirectResponse($destination);
      $response->send();
    }

    drupal_set_message(t('@_1', ['@_1' => $auth_token]), 'error');

    $event->stopPropagation();
  }

}

// EOF
