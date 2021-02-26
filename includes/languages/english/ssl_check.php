<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Security Check');
define('HEADING_TITLE', 'Security Check');

define('TEXT_INFORMATION', 'We have detected that your browser has generated a different SSL Session ID used throughout our secure pages.<br><br>For security measures you will need to logon to your account again to continue shopping online.<br><br>Some browsers such as Konqueror 3.1 does not have the capability of generating a secure SSL Session ID automatically which we require. If you use such a browser, we recommend switching to another browser such as <a class="btn btn-light btn-sm" role="button" href="http://www.microsoft.com/ie/" target="_blank" rel="noreferrer">Microsoft Internet Explorer</a> or <a class="btn btn-light btn-sm" role="button" href="http://channels.netscape.com/ns/browsers/download_other.jsp" target="_blank" rel="noreferrer">Netscape</a> or <a class="btn btn-light btn-sm" role="button" href="http://www.mozilla.org/releases/" target="_blank" rel="noreferrer">Mozilla</a> to continue your online shopping experience.<br><br>We have taken this measurement of security for your benefit, and apologize upfront if any inconveniences are caused.<br><br>Please <a class="btn btn-success btn-sm" role="button" href="' . tep_href_link('contact_us.php') . '">contact the store owner</a> if you have any questions relating to this requirement, or to continue purchasing products offline.');

define('BOX_INFORMATION_HEADING', 'Privacy and Security');
define('BOX_INFORMATION', 'We validate the SSL Session ID automatically generated by your browser on every secure page request made to this server.<br><br>This validation assures that it is you who is navigating on this site with your account and not somebody else.');
