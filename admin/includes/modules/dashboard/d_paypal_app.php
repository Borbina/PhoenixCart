<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( !class_exists('OSCOM_PayPal') ) {
    include(DIR_FS_CATALOG . 'includes/apps/paypal/OSCOM_PayPal.php');
  }

  class d_paypal_app {
    var $code = 'd_paypal_app';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $content_width = 6;

    function __construct() {
      $this->_app = new OSCOM_PayPal();
      $this->_app->loadLanguageFile('admin/balance.php');
      $this->_app->loadLanguageFile('admin/modules/dashboard/d_paypal_app.php');

      $this->title = $this->_app->getDef('module_admin_dashboard_title');
      $this->description = $this->_app->getDef('module_admin_dashboard_description');

      if ( defined('MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER;
        $this->enabled = true;
        if (defined('MODULE_ADMIN_DASHBOARD_PAYPAL_APP_CONTENT_WIDTH')) {
          $this->content_width = (int)MODULE_ADMIN_DASHBOARD_PAYPAL_APP_CONTENT_WIDTH;
        }
      }
    }

    function getOutput() {
      $version = $this->_app->getVersion();
      $version_check_result = defined('OSCOM_APP_PAYPAL_VERSION_CHECK') ? '"' . OSCOM_APP_PAYPAL_VERSION_CHECK . '"' : 'undefined';
      $can_apply_online_updates = class_exists('ZipArchive') && function_exists('json_encode') && function_exists('openssl_verify') ? 'true' : 'false';
      $has_live_account = ($this->_app->hasApiCredentials('live') === true) ? 'true' : 'false';
      $has_sandbox_account = ($this->_app->hasApiCredentials('sandbox') === true) ? 'true' : 'false';
      $version_check_url = tep_href_link('paypal.php', 'action=checkVersion');
      $new_update_notice = $this->_app->getDef('update_available_body', array('button_view_update' => $this->_app->drawButton($this->_app->getDef('button_view_update'), tep_href_link('paypal.php', 'action=update'), 'success', null, true)));
      $heading_live_account = $this->_app->getDef('heading_live_account', array('account' => str_replace('_api1.', '@', $this->_app->getApiCredentials('live', 'username'))));
      $heading_sandbox_account = $this->_app->getDef('heading_sandbox_account', array('account' => str_replace('_api1.', '@', $this->_app->getApiCredentials('sandbox', 'username'))));
      $receiving_balance_progress = $this->_app->getDef('retrieving_balance_progress');
      $app_get_started = $this->_app->drawButton($this->_app->getDef('button_app_get_started'), tep_href_link('paypal.php'), 'warning', null, true);
      $error_balance_retrieval = addslashes($this->_app->getDef('error_balance_retrieval'));
      $get_balance_url = tep_href_link('paypal.php', 'action=balance&subaction=retrieve&type=PPTYPE');

      $output = <<<EOD
<script>
if ( typeof jQuery == 'undefined' ) {
  document.write('<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></scr' + 'ipt>');
}
</script>
<script>
var OSCOM = {
  dateNow: new Date(),
  htmlSpecialChars: function(string) {
    if ( string == null ) {
      string = '';
    }

    return $('<span />').text(string).html();
  },
  nl2br: function(string) {
    return string.replace(/\\n/g, '<br />');
  },
  APP: {
    PAYPAL: {
      version: '{$version}',
      versionCheckResult: {$version_check_result},
      doOnlineVersionCheck: false,
      canApplyOnlineUpdates: false,
      accountTypes: {
        live: {$has_live_account},
        sandbox: {$has_sandbox_account}
      }
    }
  }
};
</script>

<div class="pp-container">
  <div class="card" id="ppAccountBalanceLive">
    <div class="card-header">
      {$heading_live_account}
    </div>
    <div class="card-body">
      <div id="ppBalanceLiveInfo">
        <p>{$receiving_balance_progress}</p>
      </div>
    </div>
  </div>
  
  <div class="card" id="ppAccountBalanceSandbox">
    <div class="card-header">
      {$heading_sandbox_account}
    </div>
    <div class="card-body">
      <div id="ppBalanceLiveInfo">
        <p>{$receiving_balance_progress}</p>
      </div>
    </div>
  </div>

  <div class="card" id="ppAccountBalanceNone" style="display: none;">
    <div class="card-body">
      <p>{$app_get_started}</p>
    </div>
  </div>

</div>

<script>
OSCOM.APP.PAYPAL.getBalance = function(type) {
  var def = {
    'error_balance_retrieval': '{$error_balance_retrieval}'
  };

  var divId = 'ppBalance' + type.charAt(0).toUpperCase() + type.slice(1) + 'Info';

  $.get('{$get_balance_url}'.replace('PPTYPE', type), function (data) {
    var balance = {};

    $('#' + divId).empty();

    try {
      data = $.parseJSON(data);
    } catch (ex) {
    }

    if ( (typeof data == 'object') && ('rpcStatus' in data) && (data['rpcStatus'] == 1) ) {
      if ( ('balance' in data) && (typeof data['balance'] == 'object') ) {
        balance = data['balance'];
      }
    } else if ( (typeof data == 'string') && (data.indexOf('rpcStatus') > -1) ) {
      var result = data.split("\\n", 1);

      if ( result.length == 1 ) {
        var rpcStatus = result[0].split('=', 2);

        if ( rpcStatus[1] == 1 ) {
          var entries = data.split("\\n");

          for ( var i = 0; i < entries.length; i++ ) {
            var entry = entries[i].split('=', 2);

            if ( (entry.length == 2) && (entry[0] != 'rpcStatus') ) {
              balance[entry[0]] = entry[1];
            }
          }
        }
      }
    }

    var pass = false;

    for ( var key in balance ) {
      pass = true;

      $('#' + divId).append('<p><strong>' + OSCOM.htmlSpecialChars(key) + ':</strong> ' + OSCOM.htmlSpecialChars(balance[key]) + '</p>');
    }

    if ( pass == false ) {
      $('#' + divId).append('<p>' + def['error_balance_retrieval'] + '</p>');
    }
  }).fail(function() {
    $('#' + divId).empty().append('<p>' + def['error_balance_retrieval'] + '</p>');
  });
};

$(function() {
  (function() {
    var pass = false;

    if ( OSCOM.APP.PAYPAL.accountTypes['live'] == true ) {
      pass = true;

      $('#ppAccountBalanceSandbox').hide();

      OSCOM.APP.PAYPAL.getBalance('live');
    } else {
      $('#ppAccountBalanceLive').hide();

      if ( OSCOM.APP.PAYPAL.accountTypes['sandbox'] == true ) {
        pass = true;

        OSCOM.APP.PAYPAL.getBalance('sandbox');
      } else {
        $('#ppAccountBalanceSandbox').hide();
      }
    }

    if ( pass == false ) {
      $('#ppAccountBalanceNone').show();
    }
  })();
});
</script>

EOD;

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER', '1300', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_PAYPAL_APP_CONTENT_WIDTH', 'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER');
    }
  }
