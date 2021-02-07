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
    $auth_token = is_null($_) ? '' : $_;

    if (\Drupal::currentUser()->isAnonymous() && $auth_token != '') {
      $uid = \Drupal::entityQuery('user')
        ->condition('field_auth_token', $auth_token)
        ->execute();
      $uid = reset($uid);
      if ($uid && $uid != 0) {
        $user = User::load($uid);
        if ($user) {
          user_login_finalize($user);
          db_update('users_field_data')
            ->fields(array('login' => time()))
            ->condition('uid', $uid)
            ->execute();
        }
      }
      else {
        $message = t('Wrong token');
        \Drupal::messenger()->addStatus($message);
      }

      $url = Url::fromRoute('<front>');
      $response = new RedirectResponse($url->toString());
      $response->send();
    }

    $event->stopPropagation();
  }

}

// EOF
