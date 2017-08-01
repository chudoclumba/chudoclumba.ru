<?php
//if ($_SERVER['REMOTE_ADDR']=='62.140.235.202') define('DEBUG',1); else define('DEBUG',0);
//define('SQLLOG',0);
define('START_TIME',microtime(true));
if(!function_exists('date_default_timezone_set'))
{
	header('HTTP/1.1 503 Service temporary down');
	echo 'Для работы сайта необходим PHP 5.2.* или выше (текущая версия '.PHP_VERSION.')';
	exit;
}

date_default_timezone_set('Europe/Moscow');

include 'dbconf.php';
include 'dbtables.php';
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'].CAT_DIR);
define('SITE_NAME',$_SERVER['HTTP_HOST']);
define('SITE_URL','https://'.$_SERVER['HTTP_HOST'].CAT_DIR);
define('MODULES_PATH', 'modules/');
define('ISHOP_IMAGES', ROOT_DIR.'data/ishop/');
define('INC_DIR', ROOT_DIR.'includes/'); // TODO remove
define('UPLOAD_PRICE_IN_CAT', 1);
define('ISHOP_PRICE_LIST', 1);
define('ISHOP_CURRENCY', 1);
define('EDIT_URL_PAGES', 1);
define('ISHOP_GALLERY', 0);
define('USER_SITEMENU', 1);
define('NEWS_PDP', 0);

define('ROOT_PREF', ' clumba/'); //TODO change to ' /'

ini_set('mysql.connect_timeout', 5);

$sitename = $_SERVER['HTTP_HOST'];
$sitename_mail = $_SERVER['HTTP_HOST'];


$tex_email 	= 'info@chudoclumba.ru';	// E-Mail технической поддержки
$tex_email2 	= 'info@chudoclumba.ru';	// E-Mail технической поддержки
$tech_name 		= '';	// Название сайта технической поддержки
$tech_url 		= '';	// URL сайта технической поддержки
$menu = array(
	"sitemenu"	=> 	"Меню сайта",
	"ishop"		=> 	"Интернет магазин",
	"options"	=> 	"Настройки",
	"trash"		=> 	"Корзина",
	"editboxes" =>	"Редактируемые поля",
	"faq"		=>  "Вопрос-ответ",
	"files"		=> 	"Загрузка файлов",
	"users"		=> 	"Пользователи",
	"news"		=> 	"Новости",
	"templates"	=>	"Настройка шаблона"
);


$mes = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
$mes2 = array('Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря');

$id_undel = array();

$edit_boxes = array(
	"id_main"=>"Главная страница",
	"id_feedback"=>"Раздел с формой обратной связи",
	"id_proizv"=>"Раздел с производителями",
	"icq"=>"ICQ",
	//"skype"=>"Skype",
	"phone"=>"Телефон",
	"yandex_meta"=>"Яндекс метатег",
	//"phone_mob"=>"Телефон моб",
	"email" => "Ваш Email",
	"email_su" => "Email для уведомлений о входе в СУ",
	'adr' => 'Адрес',
	'top_text' => 'Текст',
	'spdst' => 'Способы доставки',
	'spopl' => 'Способ оплаты'
);


	