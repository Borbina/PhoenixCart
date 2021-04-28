<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
 */


class PUREHTML_CSOB {

    public $_code = 'csob';
    public $_title = 'CSOB App';
    public $_version;
    public $_api_version = '204';
    public $_identifier = 'osCommerce_PPapp_v5';
    public $_definitions = [];

    function log($module, $action, $result, $request, $response, $server, $is_ipn = false) {
        $do_log = false;

        if (in_array(PUREHTML_APP_CSOB_LOG_TRANSACTIONS, ['1', '0'])) {
            $do_log = true;

            if ((PUREHTML_APP_CSOB_LOG_TRANSACTIONS == '0') && ($result === 1)) {
                $do_log = false;
            }
        }

        if ($do_log !== true) {
            return false;
        }

        $filter = ['ACCT', 'CVV2', 'ISSUENUMBER'];

        $request_string = '';

        if (is_array($request)) {
            foreach ($request as $key => $value) {
                if ((strpos($key, '_nh-dns') !== false) || in_array($key, $filter)) {
                    $value = '**********';
                }

                $request_string .= $key . ': ' . $value . "\n";
            }
        } else {
            $request_string = $request;
        }

        $response_string = '';

        if (is_array($response)) {
            foreach ($response as $key => $value) {
                if (is_array($value)) {
                    $value = http_build_query($value);
                } elseif ((strpos($key, '_nh-dns') !== false) || in_array($key, $filter)) {
                    $value = '**********';
                }

                $response_string .= $key . ': ' . $value . "\n";
            }
        } else {
            $response_string = $response;
        }

        $data = [
            'customers_id' => ($_SESSION['customer_id'] ?? 0),
            'module' => $module,
            'action' => $action . (($is_ipn === true) ? ' [IPN]' : ''),
            'result' => $result,
            'server' => ($server == 'live') ? 1 : -1,
            'request' => trim($request_string),
            'response' => trim($response_string),
            'ip_address' => sprintf('%u', ip2long(tep_get_ip_address())),
            'date_added' => 'NOW()',
        ];

        tep_db_perform('PUREHTML_APP_CSOB_log', $data);
    }

    function migrate() {
        $migrated = false;

        foreach ($this->getModules() as $module) {
            if (!defined('PUREHTML_APP_CSOB_' . $module . '_STATUS')) {
                $this->saveParameter('PUREHTML_APP_CSOB_' . $module . '_STATUS', '');

                $class = 'OSCOM_CSOB_' . $module;

                if (!class_exists($class)) {
                    $this->loadLanguageFile("modules/$module/$module.php");

                    include(DIR_FS_CATALOG . "includes/apps/csob/modules/$module/$module.php");
                }

                $m = new $class();

                if (method_exists($m, 'canMigrate') && $m->canMigrate()) {
                    $m->migrate($this);

                    if ($migrated === false) {
                        $migrated = true;
                    }
                }
            }
        }

        return $migrated;
    }

    function getModules() {
        static $result;

        if (!isset($result)) {
            $result = [];

            $d = DIR_FS_CATALOG . 'includes/apps/csob/modules/';
            if ($dir = @dir($d)) {
                while ($file = $dir->read()) {
                    if (in_array($file, ['.', '..'])) {
                        continue;
                    }

                    if (is_dir("$d/$file") && file_exists("$d/$file/$file.php")) {
                        $sort_order = $this->getModuleInfo($file, 'sort_order');

                        if (is_numeric($sort_order)) {
                            $counter = (int) $sort_order;
                        } else {
                            $counter = count($result);
                        }

                        while (true) {
                            if (isset($result[$counter])) {
                                $counter++;

                                continue;
                            }

                            $result[$counter] = $file;

                            break;
                        }
                    }
                }

                ksort($result, SORT_NUMERIC);
            }
        }

        return $result;
    }

    function isInstalled($module) {
        if (file_exists(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . basename($module) . '/' . basename($module) . '.php')) {
            return defined('PUREHTML_APP_CSOB_' . basename($module) . '_STATUS') && tep_not_null(constant('PUREHTML_APP_CSOB_' . basename($module) . '_STATUS'));
        }

        return false;
    }

    function getModuleInfo($module, $info) {
        $class = 'OSCOM_CSOB_' . $module;

        if (!class_exists($class)) {
            $this->loadLanguageFile("modules/$module/$module.php");

            include DIR_FS_CATALOG . "includes/apps/csob/modules/$module/$module.php";
        }

        $m = new $class();

        return $m->{'_' . $info};
    }

    function hasCredentials($module, $type = null) {
        if (!defined('PUREHTML_APP_CSOB_' . $module . '_STATUS')) {
            return false;
        }

        $server = constant('PUREHTML_APP_CSOB_' . $module . '_STATUS');

        if (!in_array($server, ['1', '0'])) {
            return false;
        }

        $server = ($server == '1') ? 'LIVE' : 'SANDBOX';

        if ($type == 'email') {
            $creds = ['PUREHTML_APP_CSOB_' . $server . '_SELLER_EMAIL'];
        } elseif (substr($type, 0, 7) == 'payflow') {
            if (strlen($type) > 7) {
                $creds = ['PUREHTML_APP_CSOB_PF_' . $server . '_' . strtoupper(substr($type, 8))];
            } else {
                $creds = [
                    'PUREHTML_APP_CSOB_PF_' . $server . '_VENDOR',
                    'PUREHTML_APP_CSOB_PF_' . $server . '_PASSWORD',
                    'PUREHTML_APP_CSOB_PF_' . $server . '_PARTNER',
                ];
            }
        } else {
            $creds = [
                'PUREHTML_APP_CSOB_' . $server . '_API_USERNAME',
                'PUREHTML_APP_CSOB_' . $server . '_API_PASSWORD',
                'PUREHTML_APP_CSOB_' . $server . '_API_SIGNATURE',
            ];
        }

        foreach ($creds as $c) {
            if (!defined($c) || (strlen(trim(constant($c))) < 1)) {
                return false;
            }
        }

        return true;
    }

    function getCredentials($module, $type) {
        if (constant('PUREHTML_APP_CSOB_' . $module . '_STATUS') == '1') {
            if ($type == 'email') {
                return constant('PUREHTML_APP_CSOB_LIVE_SELLER_EMAIL');
            } elseif ($type == 'email_primary') {
                return constant('PUREHTML_APP_CSOB_LIVE_SELLER_EMAIL_PRIMARY');
            } elseif (substr($type, 0, 7) == 'payflow') {
                return constant('PUREHTML_APP_CSOB_PF_LIVE_' . strtoupper(substr($type, 8)));
            } else {
                return constant('PUREHTML_APP_CSOB_LIVE_API_' . strtoupper($type));
            }
        }

        if ($type == 'email') {
            return constant('PUREHTML_APP_CSOB_SANDBOX_SELLER_EMAIL');
        } elseif ($type == 'email_primary') {
            return constant('PUREHTML_APP_CSOB_SANDBOX_SELLER_EMAIL_PRIMARY');
        } elseif (substr($type, 0, 7) == 'payflow') {
            return constant('PUREHTML_APP_CSOB_PF_SANDBOX_' . strtoupper(substr($type, 8)));
        } else {
            return constant('PUREHTML_APP_CSOB_SANDBOX_API_' . strtoupper($type));
        }
    }

    function hasApiCredentials($server, $type = null) {
        $server = ($server == 'live') ? 'LIVE' : 'SANDBOX';

        if ($type == 'email') {
            $creds = ['PUREHTML_APP_CSOB_' . $server . '_SELLER_EMAIL'];
        } elseif (substr($type, 0, 7) == 'payflow') {
            $creds = ['PUREHTML_APP_CSOB_PF_' . $server . '_' . strtoupper(substr($type, 8))];
        } else {
            $creds = [
                'PUREHTML_APP_CSOB_' . $server . '_API_USERNAME',
                'PUREHTML_APP_CSOB_' . $server . '_API_PASSWORD',
                'PUREHTML_APP_CSOB_' . $server . '_API_SIGNATURE',
            ];
        }

        foreach ($creds as $c) {
            if (!defined($c) || (strlen(trim(constant($c))) < 1)) {
                return false;
            }
        }

        return true;
    }

    function getApiCredentials($server, $type) {
        if (($server == 'live') && defined('PUREHTML_APP_CSOB_LIVE_API_' . strtoupper($type))) {
            return constant('PUREHTML_APP_CSOB_LIVE_API_' . strtoupper($type));
        } elseif (defined('PUREHTML_APP_CSOB_SANDBOX_API_' . strtoupper($type))) {
            return constant('PUREHTML_APP_CSOB_SANDBOX_API_' . strtoupper($type));
        }
    }

    function getParameters($module) {
        $result = [];

        if ($module == 'G') {
            if ($dir = @dir(DIR_FS_CATALOG . 'includes/apps/csob/cfg_params/')) {
                while ($file = $dir->read()) {
                    if (!is_dir(DIR_FS_CATALOG . 'includes/apps/csob/cfg_params/' . $file) && (substr($file, strrpos($file, '.')) == '.php')) {
                        $result[] = 'PUREHTML_APP_CSOB_' . strtoupper(substr($file, 0, strrpos($file, '.')));
                    }
                }
            }
        } else {
            if ($dir = @dir(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/cfg_params/')) {
                while ($file = $dir->read()) {
                    if (!is_dir(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/cfg_params/' . $file) && (substr($file, strrpos($file, '.')) == '.php')) {
                        $result[] = 'PUREHTML_APP_CSOB_' . $module . '_' . strtoupper(substr($file, 0, strrpos($file, '.')));
                    }
                }
            }
        }

        return $result;
    }

    function getInputParameters($module) {
        $result = [];

        if ($module == 'G') {
            $cut = 'PUREHTML_APP_CSOB_';
        } else {
            $cut = 'PUREHTML_APP_CSOB_' . $module . '_';
        }

        $cut_length = strlen($cut);

        foreach ($this->getParameters($module) as $key) {
            $p = strtolower(substr($key, $cut_length));

            if ($module == 'G') {
                $cfg_class = 'OSCOM_CSOB_Cfg_' . $p;

                if (!class_exists($cfg_class)) {
                    $this->loadLanguageFile('cfg_params/' . $p . '.php');

                    include(DIR_FS_CATALOG . 'includes/apps/csob/cfg_params/' . $p . '.php');
                }
            } else {
                $cfg_class = 'OSCOM_CSOB_' . $module . '_Cfg_' . $p;

                if (!class_exists($cfg_class)) {
                    $this->loadLanguageFile('modules/' . $module . '/cfg_params/' . $p . '.php');

                    include(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/cfg_params/' . $p . '.php');
                }
            }

            $cfg = new $cfg_class();

            if (!defined($key)) {
                $this->saveParameter($key, $cfg->default, ($cfg->title ?? null), ($cfg->description ?? null), ($cfg->set_func ?? null));
            }

            if (!isset($cfg->app_configured) || ($cfg->app_configured !== false)) {
                if (isset($cfg->sort_order) && is_numeric($cfg->sort_order)) {
                    $counter = (int) $cfg->sort_order;
                } else {
                    $counter = count($result);
                }

                while (true) {
                    if (isset($result[$counter])) {
                        $counter++;

                        continue;
                    }

                    $set_field = $cfg->getSetField();

                    if (!empty($set_field)) {
                        $result[$counter] = $set_field;
                    }

                    break;
                }
            }
        }

        ksort($result, SORT_NUMERIC);

        return $result;
    }

// APP calls require $server to be "live" or "sandbox"
    function getApiResult($module, $call, $extra_params = null, $server = null, $is_ipn = false) {
        if ($module == 'APP') {
            $function = 'OSCOM_CSOB_Api_' . $call;

            if (!function_exists($function)) {
                require DIR_FS_CATALOG . 'includes/apps/csob/api/' . $call . '.php';
            }
        } else {
            if (!isset($server)) {
                $server = (constant('PUREHTML_APP_CSOB_' . $module . '_STATUS') == '1') ? 'live' : 'sandbox';
            }

            $function = 'OSCOM_CSOB_' . $module . '_Api_' . $call;

            if (!function_exists($function)) {
                include(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/api/' . $call . '.php');
            }
        }

        $result = $function($this, $server, $extra_params);

        $this->log($module, $call, ($result['success'] === true) ? 1 : -1, $result['req'], $result['res'], $server, $is_ipn);

        return $result['res'];
    }

    function makeApiCall($url, $parameters = null, $headers = null, $opts = null) {
        $server = parse_url($url);

        if (!isset($server['port'])) {
            $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
        }

        if (!isset($server['path'])) {
            $server['path'] = '/';
        }

        $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
        curl_setopt($curl, CURLOPT_PORT, $server['port']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_ENCODING, ''); // disable gzip

        if (isset($parameters)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        if (isset($headers) && is_array($headers) && !empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (isset($server['user']) && isset($server['pass'])) {
            curl_setopt($curl, CURLOPT_USERPWD, $server['user'] . ':' . $server['pass']);
        }

        if (defined('PUREHTML_APP_CSOB_VERIFY_SSL') && (PUREHTML_APP_CSOB_VERIFY_SSL == '1')) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

            if ((substr($server['host'], -10) == 'csob.com') && file_exists(DIR_FS_CATALOG . 'ext/modules/payment/csob/csob.com.crt')) {
                curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'ext/modules/payment/csob/csob.com.crt');
            } elseif (file_exists(DIR_FS_CATALOG . 'includes/cacert.pem')) {
                curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'includes/cacert.pem');
            }
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (substr($server['host'], -10) == 'csob.com') {
            $ssl_version = 0;

            if (defined('PUREHTML_APP_CSOB_SSL_VERSION') && (PUREHTML_APP_CSOB_SSL_VERSION == '1')) {
                $ssl_version = 6;
            }

            if (isset($opts['sslVersion']) && is_int($opts['sslVersion'])) {
                $ssl_version = $opts['sslVersion'];
            }

            if ($ssl_version !== 0) {
                curl_setopt($curl, CURLOPT_SSLVERSION, $ssl_version);
            }
        }

        if (defined('PUREHTML_APP_CSOB_PROXY') && tep_not_null(PUREHTML_APP_CSOB_PROXY)) {
            curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($curl, CURLOPT_PROXY, PUREHTML_APP_CSOB_PROXY);
        }

        $result = curl_exec($curl);

        if (isset($opts['returnFull']) && ($opts['returnFull'] === true)) {
            $result = [
                'response' => $result,
                'error' => curl_error($curl),
                'info' => curl_getinfo($curl),
            ];
        }

        curl_close($curl);

        return $result;
    }

    function drawButton($title = null, $link = null, $type = null, $params = null, $force_css = false) {
        $colours = [
            'success' => 'success',
            'error' => 'danger',
            'warning' => 'warning',
            'info' => 'info',
            'primary' => 'primary',
        ];

        if (!isset($type) || !in_array($type, array_keys($colours))) {
            $type = 'info';
        }

        $button = '';

        if (isset($link)) {
            $button .= '<a href="' . $link . '" class="btn btn-' . $colours[$type] . ' pp-button';

            if (isset($type)) {
                $button .= ' pp-button-' . $type;
            }

            $button .= '"';

            if (isset($params)) {
                $button .= ' ' . $params;
            }

            //if ( $force_css == true ) {
            //$button .= ' style="' . $css . '"';
            //}

            $button .= '>' . $title . '</a>';
        } else {
            $button .= '<button type="submit" class="btn btn-' . $colours[$type] . ' pp-button';

            if (isset($type)) {
                $button .= ' pp-button-' . $type;
            }

            $button .= '"';

            if (isset($params)) {
                $button .= ' ' . $params;
            }

            //if ( $force_css == true ) {
            //  $button .= ' style="' . $css . '"';
            //}

            $button .= '>' . $title . '</button>';
        }

        return $button;
    }

    function createRandomValue($length, $type = 'mixed') {
        if (($type != 'mixed') && ($type != 'chars') && ($type != 'digits'))
            $type = 'mixed';

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';

        $base = '';

        if (($type == 'mixed') || ($type == 'chars')) {
            $base .= $chars;
        }

        if (($type == 'mixed') || ($type == 'digits')) {
            $base .= $digits;
        }

        $value = '';

        $hasher = new PasswordHash(10, true);

        do {
            $random = base64_encode($hasher->get_random_bytes($length));

            for ($i = 0, $n = strlen($random); $i < $n; $i++) {
                $char = substr($random, $i, 1);

                if (strpos($base, $char) !== false) {
                    $value .= $char;
                }
            }
        } while (strlen($value) < $length);

        if (strlen($value) > $length) {
            $value = substr($value, 0, $length);
        }

        return $value;
    }

    function saveParameter($key, $value, $title = null, $description = null, $set_func = null) {
        if (defined($key)) {
            tep_db_query("UPDATE configuration SET configuration_value = '" . tep_db_input($value) . "' WHERE configuration_key = '" . tep_db_input($key) . "'");
        } else {
            if (!isset($title)) {
                $title = 'CSOB App Parameter';
            }

            if (!isset($description)) {
                $description = 'A parameter for the CSOB Application.';
            }

            $sql = 'INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, ';
            if (isset($set_func)) {
                $sql .= 'set_function, ';
            }
            $sql .= "date_added) VALUES ('" . tep_db_input($title) . "', '" . tep_db_input($key) . "', '" . tep_db_input($value) . "', '" . tep_db_input($description) . "', 6, 0, ";
            if (isset($set_func)) {
                $sql .= "'" . tep_db_input($set_func) . "', ";
            }
            $sql .= 'NOW())';

            tep_db_query($sql);

            define($key, $value);
        }
    }

    function deleteParameter($key) {
        tep_db_query("DELETE FROM configuration WHERE configuration_key = '" . tep_db_input($key) . "'");
    }

    function formatCurrencyRaw($total, $currency_code = null, $currency_value = null) {
        global $currencies;

        if (!isset($currency_code)) {
            $currency_code = $_SESSION['currency'] ?? DEFAULT_CURRENCY;
        }

        if (!isset($currency_value) || !is_numeric($currency_value)) {
            $currency_value = $currencies->currencies[$currency_code]['value'];
        }

        return number_format(tep_round($total * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

    function getCode() {
        return $this->_code;
    }

    function getTitle() {
        return $this->_title;
    }

    function getVersion() {
        if (!isset($this->_version)) {
            $version = trim(file_get_contents(DIR_FS_CATALOG . 'includes/apps/csob/version.txt'));

            if (is_numeric($version)) {
                $this->_version = $version;
            } else {
                trigger_error('APP [CSOB]: Could not read App version number.');
            }
        }

        return $this->_version;
    }

    function getApiVersion() {
        return $this->_api_version;
    }

    function getIdentifier() {
        return $this->_identifier;
    }

    function hasAlert() {
        return isset($_SESSION['OSCOM_CSOB_Alerts']);
    }

    function addAlert($message, $type) {
        if (in_array($type, ['error', 'warning', 'success'])) {
            if (!isset($_SESSION['OSCOM_CSOB_Alerts'])) {
                $_SESSION['OSCOM_CSOB_Alerts'] = [];
            }

            $_SESSION['OSCOM_CSOB_Alerts'][$type][] = $message;
        }
    }

    function getAlerts() {
        $output = '';

        if (!empty($_SESSION['OSCOM_CSOB_Alerts'])) {
            $result = [];

            foreach ($_SESSION['OSCOM_CSOB_Alerts'] as $type => $messages) {
                if (in_array($type, ['error', 'warning', 'success'])) {
                    $m = null;

                    foreach ($messages as $message) {
                        $m .= '<div class="alert alert-' . $type . '">';
                        $m .= htmlspecialchars($message);
                        $m .= '</div>';
                    }

                    $result[] = $m;
                }
            }

            if (!empty($result)) {
                $output .= '<div class="pp-alerts">' . implode("\n", $result) . '</div>';
            }
        }

        unset($_SESSION['OSCOM_CSOB_Alerts']);

        return $output;
    }

    function install($module) {
        $cut_length = strlen('PUREHTML_APP_CSOB_' . $module . '_');

        foreach ($this->getParameters($module) as $key) {
            $p = strtolower(substr($key, $cut_length));

            $cfg_class = 'OSCOM_CSOB_' . $module . '_Cfg_' . $p;

            if (!class_exists($cfg_class)) {
                $this->loadLanguageFile('modules/' . $module . '/cfg_params/' . $p . '.php');

                include(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/cfg_params/' . $p . '.php');
            }

            $cfg = new $cfg_class();

            $this->saveParameter($key, $cfg->default, ($cfg->title ?? null), ($cfg->description ?? null), ($cfg->set_func ?? null));
        }

        $m_class = 'OSCOM_CSOB_' . $module;

        if (!class_exists($m_class)) {
            $this->loadLanguageFile('modules/' . $module . '/' . $module . '.php');

            include(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/' . $module . '.php');
        }

        $m = new $m_class();

        if (method_exists($m, 'install')) {
            $m->install($this);
        }
    }

    function uninstall($module) {
        tep_db_query("delete from configuration where configuration_key like 'PUREHTML_APP_CSOB_" . tep_db_input($module) . "_%'");

        $m_class = 'OSCOM_CSOB_' . $module;

        if (!class_exists($m_class)) {
            $this->loadLanguageFile('modules/' . $module . '/' . $module . '.php');

            include(DIR_FS_CATALOG . 'includes/apps/csob/modules/' . $module . '/' . $module . '.php');
        }

        $m = new $m_class();

        if (method_exists($m, 'uninstall')) {
            $m->uninstall($this);
        }
    }

    function logUpdate($message, $version) {
        if (is_writable(DIR_FS_CATALOG . 'includes/apps/csob/work')) {
            file_put_contents(DIR_FS_CATALOG . 'includes/apps/csob/work/update_log-' . $version . '.php', '[' . date('d-M-Y H:i:s') . '] ' . $message . "\n", FILE_APPEND);
        }
    }

    public function loadLanguageFile($filename, $lang = null) {
        $lang = basename($lang ?? $_SESSION['language']);

        if ($lang != 'english') {
            $this->loadLanguageFile($filename, 'english');
        }

        $pathname = DIR_FS_CATALOG . 'includes/apps/csob/languages/' . $lang . '/' . $filename;

        if (file_exists($pathname)) {
            $contents = file($pathname);

            $ini_array = [];

            foreach ($contents as $line) {
                $line = trim($line);

                if (!empty($line) && (substr($line, 0, 1) != '#')) {
                    $delimiter = strpos($line, '=');

                    if (($delimiter !== false) && (preg_match('/^[A-Za-z0-9_-]/', substr($line, 0, $delimiter)) === 1) && (substr_count(substr($line, 0, $delimiter), ' ') == 1)) {
                        $key = trim(substr($line, 0, $delimiter));
                        $value = trim(substr($line, $delimiter + 1));

                        $ini_array[$key] = $value;
                    } elseif (isset($key)) {
                        $ini_array[$key] .= "\n" . $line;
                    }
                }
            }

            unset($contents);

            $this->_definitions = array_merge($this->_definitions, $ini_array);

            unset($ini_array);
        }
    }

    function getDef($key, $values = null) {
        $def = $this->_definitions[$key] ?? $key;

        if (is_array($values)) {
            $keys = array_keys($values);

            foreach ($keys as &$k) {
                $k = ':' . $k;
            }

            $def = str_replace($keys, array_values($values), $def);
        }

        return $def;
    }

    function getDirectoryContents($base, &$result = []) {
        foreach (scandir($base) as $file) {
            if (($file == '.') || ($file == '..')) {
                continue;
            }

            $pathname = $base . '/' . $file;

            if (is_dir($pathname)) {
                $this->getDirectoryContents($pathname, $result);
            } else {
                $result[] = str_replace('\\', '/', $pathname); // Unix style directory separator "/"
            }
        }

        return $result;
    }

    function isWritable($location) {
        if (!file_exists($location)) {
            while (true) {
                $location = dirname($location);

                if (file_exists($location)) {
                    break;
                }
            }
        }

        return is_writable($location);
    }

    function rmdir($dir) {
        foreach (scandir($dir) as $file) {
            if (!in_array($file, ['.', '..'])) {
                if (is_dir($dir . '/' . $file)) {
                    $this->rmdir($dir . '/' . $file);
                } else {
                    unlink($dir . '/' . $file);
                }
            }
        }

        return rmdir($dir);
    }

    function displayPath($pathname) {
        if (DIRECTORY_SEPARATOR == '/') {
            return $pathname;
        }

        return str_replace('/', DIRECTORY_SEPARATOR, $pathname);
    }

}
