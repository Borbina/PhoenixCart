<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  function tep_validate_password($plain, $encrypted) {
    trigger_error('The tep_validate_password function has been deprecated.', E_USER_DEPRECATED);
    return Password::validate($plain, $encrypted);
  }

  function tep_validate_old_password($plain, $encrypted) {
    trigger_error('The tep_validate_old_password function has been deprecated.', E_USER_DEPRECATED);
    return old_password::validate($plain, $encrypted);
  }

  function tep_encrypt_password($plain) {
    trigger_error('The tep_encrypt_password function has been deprecated.', E_USER_DEPRECATED);
    return Password::hash($plain);
  }

  function tep_encrypt_old_password($plain) {
    trigger_error('The tep_encrypt_old_password function has been deprecated.', E_USER_DEPRECATED);
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= mt_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }

  function tep_password_type($encrypted) {
    trigger_error('The tep_password_type function has been deprecated.', E_USER_DEPRECATED);
    return Password::type($encrypted);
  }
