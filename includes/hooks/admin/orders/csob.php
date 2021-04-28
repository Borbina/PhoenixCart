<?php

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
 */

class hook_admin_orders_csob {

    function listen_orderAction() {
        if (!class_exists('csob_hook_admin_orders_action')) {
            include(DIR_FS_CATALOG . 'includes/apps/csob/hooks/admin/orders/action.php');
        }

        $hook = new csob_hook_admin_orders_action();

        return $hook->execute();
    }

    function listen_orderTab() {
        if (!class_exists('csob_hook_admin_orders_tab')) {
            include(DIR_FS_CATALOG . 'includes/apps/csob/hooks/admin/orders/tab.php');
        }

        $hook = new csob_hook_admin_orders_tab();

        return $hook->execute();
    }

}

?>
