<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce
  
  Edited by 2014 Newburns Design and Technology
  *************************************************
  ************ New addon definitions **************
  ************        Below          **************
  *************************************************
  SEO Header Tags Reloaded added -- http://addons.oscommerce.com/info/8864
  Credit Class, Gift Vouchers & Discount Coupons osC2.3.3.4 (CCGV) added -- http://addons.oscommerce.com/info/9020
  Mail Manager added -- http://addons.oscommerce.com/info/9133/v,23
  
  Released under the GNU General Public License
*/

@setlocale(LC_ALL, array('cs_CZ.utf8', 'cs_CZ.UTF-8'));

const DATE_FORMAT_SHORT = '%d.%m.%Y';
const DATE_FORMAT_LONG = '%A %d %B, %Y';
const DATE_FORMAT = 'd.m.Y';
// define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
const JQUERY_DATEPICKER_I18N_CODE = 'cs';
const JQUERY_DATEPICKER_FORMAT = 'mm.dd.yy';

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
const LANGUAGE_CURRENCY = 'CZK';

// Global entries for the <html> tag
const HTML_PARAMS = 'dir="ltr" lang="cs"';

// charset for web pages and emails
const CHARSET = 'utf-8';

// page title
// define('TITLE', STORE_NAME);

// header text in includes/header.php
const HEADER_TITLE_CREATE_ACCOUNT = 'Založit účet';
const HEADER_TITLE_MY_ACCOUNT = 'můj účet';
const HEADER_TITLE_CART_CONTENTS = 'košík';
const HEADER_TITLE_CHECKOUT = 'pokladna';
const HEADER_TITLE_TOP = '<i class="glyphicon glyphicon-home"><span class="sr-only">shop-name.domain</span></i>';
const HEADER_TITLE_CATALOG = 'Home';
const HEADER_TITLE_LOGOFF = 'Odhlásit';
const HEADER_TITLE_LOGIN = 'Přihlásit';

// text for gender
const MALE = 'M<span class="hidden-xs">už</span>';
const FEMALE = 'Ž<span class="hidden-xs">ena</span>';
const MALE_ADDRESS = 'Pan';
const FEMALE_ADDRESS = 'Paní';

// text for date of birth example
const DOB_FORMAT_STRING = 'mm/dd/yyyy';

// checkout procedure text
const CHECKOUT_BAR_DELIVERY = 'Dodání';
const CHECKOUT_BAR_PAYMENT = 'Platba';
const CHECKOUT_BAR_CONFIRMATION = 'Potvrdit';
const CHECKOUT_BAR_FINISHED = 'Dokončeno!';

// pull down default text
const PULL_DOWN_DEFAULT = 'vyberte';
const TYPE_BELOW = 'Napsat níže';

// javascript messages
const JS_ERROR = 'Ve formuláři je chyba.\n\nOpravte následující:\n\n';

// define('JS_REVIEW_TEXT', '* The \'Review Text\' musí mít nejméně ' . REVIEW_TEXT_MIN_LENGTH . ' znaků.\n');
const JS_REVIEW_RATING = '* Přepočítat zboží\n';

const JS_ERROR_NO_PAYMENT_MODULE_SELECTED = '* Prosíme vyberte typ platby.\n';

const JS_ERROR_SUBMITTED = 'Formulář může být odeslán. Zmáčkněte Ok a vyčkejte.';

const ERROR_NO_PAYMENT_MODULE_SELECTED = 'Vyberte způsob platby Vaší objednávky.';

const CATEGORY_COMPANY = 'Společnost';
const CATEGORY_PERSONAL = 'Osobní';
const CATEGORY_ADDRESS = 'Adresa';
const CATEGORY_CONTACT = 'Napište nám';
const CATEGORY_OPTIONS = 'Nastavení';
const CATEGORY_PASSWORD = 'Heslo';

const ENTRY_COMPANY = 'Společnost:';
const ENTRY_COMPANY_TEXT = '';
const ENTRY_GENDER = 'Pohlaví:';
const ENTRY_GENDER_ERROR = 'Vyberte pohlaví';
const ENTRY_GENDER_TEXT = '';
//const ENTRY_FIRST_NAME = 'Jméno:';
// define('ENTRY_FIRST_NAME_ERROR', 'Vaše jméno musí mít nejméně ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' znaků.');
//const ENTRY_FIRST_NAME_TEXT = '';
//const ENTRY_LAST_NAME_TEXT = '';
const ENTRY_DATE_OF_BIRTH = 'Datum narození:';
const ENTRY_DATE_OF_BIRTH_ERROR = 'Datum narození ve tvaru: MM/DD/YYYY (eg 05/21/1970)';
const ENTRY_DATE_OF_BIRTH_TEXT = '* (např. 05/21/1970)';
//const ENTRY_EMAIL_ADDRESS = 'E-Mail:';
// define('ENTRY_EMAIL_ADDRESS_ERROR', 'E-Mail musí mít nejméně ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' znaků.');
// const ENTRY_EMAIL_ADDRESS_CHECK_ERROR = 'E-Mail je špatně, opravte jej.';
//const ENTRY_STREET_ADDRESS = 'Ulice:';
// define('ENTRY_STREET_ADDRESS_ERROR', 'Ulice musí mít nejméně ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' znaků.');
// define('ENTRY_CITY_ERROR', 'Město musí mít nejméně ' . ENTRY_CITY_MIN_LENGTH . ' znaků.');
// define('ENTRY_STATE_ERROR', 'Stát musí mít nejméně ' . ENTRY_STATE_MIN_LENGTH . ' znaků.');
const ENTRY_TELEPHONE_NUMBER = 'Telefon:';
// define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Telefonní číslo musí mít nejméně ' . ENTRY_TELEPHONE_MIN_LENGTH . ' znaků.');
const ENTRY_TELEPHONE_NUMBER_TEXT = '';
const ENTRY_FAX_NUMBER = 'Fax:';
const ENTRY_FAX_NUMBER_TEXT = '';
// define('ENTRY_PASSWORD_ERROR', 'Vaše heslo musí mít nejméně ' . ENTRY_PASSWORD_MIN_LENGTH . ' znaků.');
const PASSWORD_HIDDEN = '--HIDDEN--';

// constants for use in tep_prev_next_display function
const TEXT_RESULT_PAGE = 'Celkem stránek:';
const TEXT_DISPLAY_NUMBER_OF_PRODUCTS = 'Zobrazeno <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> produktů)';
const TEXT_DISPLAY_NUMBER_OF_ORDERS = 'Zobrazeno <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> objednávek)';
const TEXT_DISPLAY_NUMBER_OF_REVIEWS = 'Zobrazeno <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> hodnocení)';
const TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW = 'Zobrazeno <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> novinek)';
const TEXT_DISPLAY_NUMBER_OF_SPECIALS = 'Zobrazeno <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> slev)';

const PREVNEXT_TITLE_FIRST_PAGE = 'První stránka';
const PREVNEXT_TITLE_PREVIOUS_PAGE = 'Předchozí stránka';
const PREVNEXT_TITLE_NEXT_PAGE = 'Další stránka';
const PREVNEXT_TITLE_LAST_PAGE = 'Poslední stránka';
const PREVNEXT_TITLE_PAGE_NO = 'Stránka %d';
const PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE = 'Předcházejících %d stránek';
const PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE = 'Dalších %d stránek';
const PREVNEXT_BUTTON_FIRST = '&lt;&lt;první';
const PREVNEXT_BUTTON_PREV = '[&lt;&lt;&nbsp;předchozí]';
const PREVNEXT_BUTTON_NEXT = '[další&nbsp;&gt;&gt;]';
const PREVNEXT_BUTTON_LAST = 'poslední&gt;&gt;';

const IMAGE_BUTTON_ADD_ADDRESS = 'Přidat adresu';
const IMAGE_BUTTON_ADDRESS_BOOK = 'Adresář';
const IMAGE_BUTTON_BACK = 'Zpět';
const IMAGE_BUTTON_BUY_NOW = 'objednat';
const IMAGE_BUTTON_CHANGE_ADDRESS = 'Změnit adresu';
const IMAGE_BUTTON_CHECKOUT = 'Pokladna';
const IMAGE_BUTTON_CONFIRM_ORDER = 'Potvrdit závaznou objednávku';
const IMAGE_BUTTON_CONTINUE = 'pokračovat';
const IMAGE_BUTTON_CONTINUE_SHOPPING = 'Pokračovat v nákupu';
const IMAGE_BUTTON_DELETE = 'Smazat';
const IMAGE_BUTTON_EDIT_ACCOUNT = 'Upravit účet';
const IMAGE_BUTTON_HISTORY = 'Historie objednávek';
const IMAGE_BUTTON_LOGIN = 'přihlásit';
const IMAGE_BUTTON_IN_CART = 'Koupit';
const IMAGE_BUTTON_NOTIFICATIONS = 'Zpráva';
const IMAGE_BUTTON_QUICK_FIND = 'Rychlé hledání';
const IMAGE_BUTTON_REMOVE_NOTIFICATIONS = 'Smazat zprávu';
const IMAGE_BUTTON_REVIEWS = 'Hodnocení';
const IMAGE_BUTTON_SEARCH = 'Vyhledat';
const IMAGE_BUTTON_SHIPPING_OPTIONS = 'Doprava';
const IMAGE_BUTTON_TELL_A_FRIEND = 'Dejte vědět příteli';
const IMAGE_BUTTON_UPDATE = 'obnovit';
const IMAGE_BUTTON_UPDATE_CART = 'Obnovit košík';
const IMAGE_BUTTON_WRITE_REVIEW = 'Zapsat hodnocení';
const IMAGE_BUTTON_UPDATE_PREFERENCES = 'Obnov preference';

const SMALL_IMAGE_BUTTON_DELETE = 'Smazat';
const SMALL_IMAGE_BUTTON_EDIT = 'Upravit';
const SMALL_IMAGE_BUTTON_VIEW = 'Zobrazit';
const SMALL_IMAGE_BUTTON_BUY = 'Koupit';

const ICON_ARROW_RIGHT = 'dále';
const ICON_CART = 'v košíku';
const ICON_ERROR = 'chyba';
const ICON_SUCCESS = 'správně';
const ICON_WARNING = 'Pozor';

const TEXT_GREETING_PERSONAL = 'Vítejte zpět <span class="greetUser">%s!</span> Chcete se podívat jaké máme <a href="%s"><u>novinky</u></a> od Vašeho posledního nákupu?';
const TEXT_GREETING_PERSONAL_RELOGON = '<small>Pokud ne, %s, prosíme <a href="%s"><u>přihlaste se</u></a> na váš účet.</small>';
const TEXT_GREETING_GUEST = 'Vítejte <span class="greetUser">návštěvníku!</span> Chcete se  <a href="%s"><u>přihlásit</u></a>? nebo teprve <a href="%s"><u>zaregistrovat</u></a>?';

const TEXT_SORT_PRODUCTS = 'seřadit';
const TEXT_DESCENDINGLY = 'sestupně';
const TEXT_ASCENDINGLY = 'vzestupně';
const TEXT_BY = ' by ';
// grid/list
const TEXT_SORT_BY = 'seřadit podle';

const TEXT_REVIEW_BY = 'od %s';
const TEXT_REVIEW_WORD_COUNT = '%s slov';
const TEXT_REVIEW_RATING = 'hodnocení: %s [%s]';
const TEXT_REVIEW_DATE_ADDED = 'přidáno: %s';
const TEXT_NO_REVIEWS = 'žádná nová hodnocení.';

const TEXT_NO_NEW_PRODUCTS = 'žádné nové produkty.';

const TEXT_UNKNOWN_TAX_RATE = 'Unknown tax rate';

const TEXT_REQUIRED = '<span class="errorText">je nutné vyplnit</span>';

const ERROR_TEP_MAIL = '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</strong></font>';

const TEXT_CCVAL_ERROR_INVALID_DATE = 'The expiry date entered for the credit card is invalid. Please check the date and try again.';
const TEXT_CCVAL_ERROR_INVALID_NUMBER = 'The credit card number entered is invalid. Please check the number and try again.';
const TEXT_CCVAL_ERROR_UNKNOWN_CARD = 'The first four digits of the number entered are: %s. If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.';

// category views
const TEXT_VIEW = 'Zobrazit: ';
const TEXT_VIEW_LIST = ' Seznam';
const TEXT_VIEW_GRID = ' Grid';

// search placeholder
const TEXT_SEARCH_PLACEHOLDER = 'Vyhledávání';

// message for required inputs
const FORM_REQUIRED_INFORMATION = 'Nutno vyplnit';
const FORM_REQUIRED_INPUT = '<span><span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span></span>';

// reviews
const REVIEWS_TEXT_RATED = 'Hodnoceno %s s <cite title="%s" itemprop="recenzent">%s</cite>';
const REVIEWS_TEXT_AVERAGE = 'Hodnocení <span itemprop="počet">%s</span> recenzí %s';
const REVIEWS_TEXT_TITLE = 'Co říkají naši zákazníci...';

// grid/list

// moved from index
const TABLE_HEADING_IMAGE = '';
const TABLE_HEADING_MODEL = 'Model';
const TABLE_HEADING_PRODUCTS = 'Název zboží';
const TABLE_HEADING_MANUFACTURER = 'Výrobce';
const TABLE_HEADING_QUANTITY = 'Množství';
const TABLE_HEADING_PRICE = 'Cena';
const TABLE_HEADING_WEIGHT = 'Váha';
const TABLE_HEADING_BUY_NOW = 'Koupit';
const TABLE_HEADING_LATEST_ADDED = 'Poslední zboží';
const TABLE_HEADING_DATE_AVAILABLE = 'Nejnovější zboží';
const TABLE_HEADING_CUSTOM_DATE = 'Podle data';
const TABLE_HEADING_SORT_ORDER = 'Pořadí';
const TABLE_HEADING_ORDERED = 'Nejprodávanější';

// product notifications
const PRODUCT_SUBSCRIBED = '%s bylo přidána do vašeho seznamu';
const PRODUCT_UNSUBSCRIBED = '%s bylo odebráno z vašeho seznamu';
const PRODUCT_ADDED = '%s přidáno do vašeho košíku';
const PRODUCT_REMOVED = '%s odebráno z vašeho košíku';

// bootstrap helper
const MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION = '';

// sorting product_listing module
// sitewide product listing
const LISTING_SORT_DOWN = '<i class="fas fa-level-down-alt text-primary"></i>';
const LISTING_SORT_UP = '<i class="fas fa-level-up-alt text-primary"></i>';
const LISTING_SORT_UNSELECTED = '<i class="fas fa-level-up-alt text-black-50"></i>';

// for new style internal pages
const LINK_TEXT_EDIT = '<small><a class="%s" href="%s">Edit</a></small>';
const SHIPPING_FA_ICON = '<i class="fas fa-shipping-fast fa-fw fa-3x float-right text-black-50"></i>';
const PAYMENT_FA_ICON = '<i class="fas fa-file-invoice-dollar fa-fw fa-3x float-right text-black-50"></i>';

const ENTRY_COMMENTS = 'Měli bychom něco vědět?';
const ENTRY_COMMENTS_PLACEHOLDER = 'Přidejte komentář';
const TABLE_HEADING_OR = '-nebo-';


/*
************************************************************************
************** Custom Filenames can be // defined below here **************
**************               Raymond Burns                **************
************************************************************************
*/
// SEO Header Tags Reloaded
  //header titles
//const HEADER_CART_CONTENTS = '<i class="glyphicon fa-shopping-cart"></i> %s kusů<span class="caret"></span>';
//const HEADER_CART_NO_CONTENTS = '<i class="glyphicon fa-shopping-cart"></i><span class="hidden-lg hidden-md hidden-sm"> košík</span><span class="caret"></span>';
//const HEADER_ACCOUNT_LOGGED_OUT = '<span class="hidden-sm"> přihlásit se/registrace</span> <span class="caret"></span>';
//const HEADER_ACCOUNT_LOGGED_IN = '<i class="glyphicon glyphicon-user"></i> %s <span class="caret"></span>';
//const HEADER_SITE_SETTINGS = '<i class="glyphicon glyphicon-cog"></i><span class="hidden-sm"> Site Settings</span> <span class="caret"></span>';
//const HEADER_TOGGLE_NAV = 'Toggle Navigation';
//const HEADER_HOME = '<i class="glyphicon glyphicon-home"></i><span class="hidden-sm"> Home</span>';
//const HEADER_WHATS_NEW = '<i class="glyphicon glyphicon-certificate"></i><span class="hidden-sm">  Nové zboží</span>';
//const HEADER_SPECIALS = '<i class="glyphicon glyphicon-fire"></i><span class="hidden-sm"> Speciální nabídky</span>';
//const HEADER_REVIEWS = '<i class="glyphicon glyphicon-comment"></i><span class="hidden-sm"> Hodnocení</span>';
// header dropdowns
//const HEADER_ACCOUNT_LOGIN = '<i class="glyphicon glyphicon-log-in"></i> Přihlásit';
//const HEADER_ACCOUNT_LOGOFF = '<i class="glyphicon glyphicon-log-out"></i> Odhlásit';
//const HEADER_ACCOUNT = 'Můj účet';
//const HEADER_ACCOUNT_HISTORY = 'Moje objednávky';
//const HEADER_ACCOUNT_EDIT = 'Moje údaje';
//const HEADER_ACCOUNT_ADDRESS_BOOK = 'Můj Adresář';
//const HEADER_ACCOUNT_PASSWORD = 'Heslo';
//const HEADER_ACCOUNT_REGISTER = '<i class="glyphicon glyphicon-pencil"></i> Registrace';
//const HEADER_CART_HAS_CONTENTS = '%s item(s), %s';
//const HEADER_CART_VIEW_CART = 'Košík';
//const HEADER_CART_CHECKOUT = '<i class="glyphicon glyphicon-chevron-right"></i> Pokladna';
//const USER_LOCALIZATION = '<abbr title="Vybraný jazyk">L:</abbr> %s <abbr title="Vybraná měna">C:</abbr> %s';

// CCGV
const VOUCHER_BALANCE = 'Voucher Balanc';
const BOX_HEADING_GIFT_VOUCHER = 'Dárkový poukaz účet';
const GV_FAQ = 'Gift Voucher FAQ';
const IMAGE_REDEEM_VOUCHER = 'Redeem';
const ERROR_REDEEMED_AMOUNT = 'Congratulations, you have redeemed ';
const ERROR_NO_REDEEM_CODE = 'You did not enter a redeem code.';
const ERROR_NO_INVALID_REDEEM_GV = 'nesprávný kód';
const TABLE_HEADING_CREDIT = '&nbsp;';
const GV_HAS_VOUCHERA = 'Máte finanční prostředky na vašem účtu dárkového poukazu. If you want <br>                           you can send those funds by <a class="pageResults" href="';
const GV_HAS_VOUCHERB = '"><b>email</b></a> to someone';
const ENTRY_AMOUNT_CHECK_ERROR = 'Nemáte dostatek finančních prostředků.';
const BOX_SEND_TO_FRIEND = 'Zaslat dárkový poukaz';
const VOUCHER_REDEEMED = 'Voucher Redeemed';
const CART_COUPON = 'Kupon :';
const CART_COUPON_INFO = 'další info';
// MailManager
const BOX_HEADING_MAIL_MANAGER = 'Mail Manager';
const BOX_MM_BULKMAIL = 'BulkMail Manager';
const BOX_MM_TEMPLATES = 'Template Manager';
const BOX_MM_EMAIL = 'Zaslat e-mail';
const BOX_MM_RESPONSEMAIL = 'Response Mail';
//pure:new link to advanced search
const IMAGE_BUTTON_ADVANCED_SEARCH_LINK = 'podrobné';
//VAT numbber
const ENTRY_VAT_NUMBER_TEXT_2 = '';
const ENTRY_COMPANY_NUMBER_TEXT_2 = '';

/**** BEGIN ARTICLE MANAGER ****/
const BOX_HEADING_ARTICLES = 'Články';
const BOX_ALL_ARTICLES = 'Všechny články';
const BOX_ALL_BLOG_ARTICLES = 'Všechny blogy';
const BOX_ARTICLE_SUBMIT = 'Odeslat článek';
const BOX_ARTICLE_TOPICS = 'Všechny kategorie';
const BOX_NEW_ARTICLES = 'Nový článek';
const TEXT_ARTICLE_SEARCH = 'Vyhledávání v článcích';
const TEXT_ARTICLE_SEARCH_STRING = 'hledat článek';
const TEXT_DISPLAY_NUMBER_OF_ARTICLES = 'Zobrazuji <b>%d</b> až <b>%d</b> (z <b>%d</b> článků)';
const TEXT_DISPLAY_NUMBER_OF_ARTICLES_NEW = 'Zobrazuji <b>%d</b> až <b>%d</b> (z <b>%d</b> nových článků)';
const TEXT_ARTICLES = 'Níže je seznam všech článků od nejnovějších k nejstarším.';
const TABLE_HEADING_AUTHOR = 'Autor';
const TABLE_HEADING_ABSTRACT = 'Shrnutí';
const TEXT_PXSELL_ARTICLES = 'Příbuzné články';
const BOX_HEADING_AUTHORS = 'Články podle autora';
const BOX_ARTICLES_BLOG_COMMENTS = 'Diskuse k článkům';
const NAVBAR_TITLE_DEFAULT = 'Články';
const BOX_RSS_ARTICLES = 'RSS Feed k článkům';
const BOX_UPCOMING_ARTICLES = 'Připravované články';
const BOX_HEADING_TELL_A_FRIEND = 'Poslat na e-mail';
/**** END ARTICLE MANAGER ****/

/*** Begin Header Tags SEO ***/
const BOX_HEADING_HEADERTAGS_TAGCLOUD = 'Populární vyhledávání';
const TEXT_SEE_MORE = 'více';
const TEXT_SEE_MORE_FULL = 'více o %s';
const HTS_OG_AVAILABLE_STOCK = 'Sostupnost skladem';
const HTS_OG_PRICE = 'Cena';
/*** End Header Tags SEO ***/

//pure
const HEADER_AUTHORS = 'AUTOŘI';
const HEADER_NEWS = 'AKTUALITY';
const HEADER_ABOUT_US = 'O NÁS';
const XHEADER_CONTACT_US = 'KONTAKTY';
const HEADER_ADVANCED_SEARCH = 'podrobné vyhledávání';
const SUPPORT = 'Podpora';
const CART = 'Košík';
const CHECKOUT = 'Pokladna';
const ACCOUNT = 'Můj účet';

// noscript helper
const TEXT_NOSCRIPT = <<<'EOT'
<p><strong>Zdá se že máte JavaScript vypnuty ve svém prohlížeč.</strong></p>
<p>Musíte mít JavaScript ve svém prohlížeči to utilize the functionality of this website.<br>
<a class="alert-link" href="https://www.enable-javascript.com/" target="_blank" rel="noreferrer">Click here for instructions on enabling javascript in your browser</a>.</p>
EOT;


// sitewide is-product
const IS_PRODUCT_SHOW_PRICE = '%s';
const IS_PRODUCT_SHOW_PRICE_SPECIAL = '<del>%s</del> <span class="text-danger">now %s</span>';
const IS_PRODUCT_BUTTON_BUY = '<i class="fas fa-shopping-cart"></i>';
const IS_PRODUCT_BUTTON_VIEW = '<i class="fas fa-eye"></i> View';



//static pages
const PRODUCTS_NEW_PAGE = 'novinky';
const QUICK_SHOP_PAGE = 'quick-shop';

const PASSWORD = 'Heslo';
const LOGIN = 'Přihlášení';
