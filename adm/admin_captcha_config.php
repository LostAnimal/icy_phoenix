<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* AmigaLink
*
*/

define('IN_ICYPHOENIX', true);

// First we do the setmodules stuff for the admin cp.
if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['145_Captcha_Config'] = $filename;

	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Pull config data
$sql = "SELECT * FROM " . CAPTCHA_CONFIG_TABLE;
$result = $db->sql_query($sql);
while($row = $db->sql_fetchrow($result))
{
	$config_name = $row['config_name'];
	$config_value = $row['config_value'];
	$default_config[$config_name] = $config_value;

	$new[$config_name] = request_post_var($config_name, $default_config[$config_name]);

	if(isset($_POST['submit']))
	{
		$sql = "UPDATE " . CAPTCHA_CONFIG_TABLE . " SET
			config_value = '" . $db->sql_escape($new[$config_name]) . "'
			WHERE config_name = '$config_name'";
		$db->sql_query($sql);
	}
}

if(isset($_POST['submit']))
{
	$message = $lang['captcha_config_updated'] . '<br />' . sprintf($lang['Click_return_captcha_config'], '<a href="' . append_sid("admin_captcha_config." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>') . '<br /><br />';

	message_die(GENERAL_MESSAGE, $message);
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_captcha_config.tpl'));

$template->assign_vars(array(
	'L_CAPTCHA_CONFIGURATION' => $lang['VC_Captcha_Config'],
	'L_CAPTCHA_CONFIGURATION_EXPLAIN' => $lang['captcha_config_explain'],
	'L_VC_ACTIVE' => ($config['enable_confirm']) ? $lang['VC_active'] : $lang['VC_inactive'],
	'L_BACKGROUND_CONFIG' => $lang['background_configs'],
	'L_RANDOM' => $lang['Random'],
	'L_DISABLED' => $lang['Disabled'],
	'L_ENABLED' => $lang['Enabled'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_WIDTH' => $lang['CAPTCHA_width'],
	'L_HEIGHT' => $lang['CAPTCHA_height'],
	'L_BACKGROUND_COLOR' => $lang['background_color'],
	'L_BACKGROUND_COLOR_EXPLAIN' => $lang['background_color_explain'],
	'L_PRE_LETTERS' => $lang['pre_letters'],
	'L_PRE_LETTERS_EXPLAIN' => $lang['pre_letters_explain'],
	'L_GREAT_PRE_LETTERS' => $lang['great_pre_letters'],
	'L_GREAT_PRE_LETTERS_EXPLAIN' => $lang['great_pre_letters_explain'],
	'L_RND_FONT_PER_LETTER' => $lang['random_font_per_letter'],
	'L_RND_FONT_PER_LETTER_EXPLAIN' => $lang['random_font_per_letter_explain'],
	'L_ALLOW_CHESS' => $lang['back_chess'],
	'L_ALLOW_CHESS_EXPLAIN' => $lang['back_chess_explain'],
	'L_ALLOW_ELLIPSES' => $lang['back_ellipses'],
	'L_ALLOW_ARCS' => $lang['back_arcs'],
	'L_ALLOW_LINES' => $lang['back_lines'],
	'L_ALLOW_IMAGE' => $lang['back_image'],
	'L_ALLOW_IMAGE_EXPLAIN' => $lang['back_image_explain'],
	'L_FOREGROUND_LATTICE' => $lang['foreground_lattice'],
	'L_FOREGROUND_LATTICE_EXPLAIN' => $lang['foreground_lattice_explain'],
	'L_FOREGROUND_LATTICE_COLOR' => $lang['foreground_lattice_color'],
	'L_FOREGROUND_LATTICE_COLOR_EXPLAIN' => $lang['foreground_lattice_color_explain'],
	'L_GAMMACORRECT' => $lang['gammacorrect'],
	'L_GAMMACORRECT_EXPAIN' => $lang['gammacorrect_axplain'],
	'L_GENERATE_JPEG' => $lang['generate_jpeg'],
	'L_GENERATE_JPEG_EXPAIN' => $lang['generate_jpeg_explain'],
	'L_JPEG_QUALITY' => $lang['generate_jpeg_quality'],

	'WIDTH' => $new['width'],
	'HEIGHT' => $new['height'],
	'BACKGROUND_COLOR' => $new['background_color'],
	'PRE_LETTERS' => $new['pre_letters'],
	'LATTICE_X_LINES' => $new['foreground_lattice_x'],
	'LATTICE_Y_LINES' => $new['foreground_lattice_y'],
	'LATTICE_COLOR' => $new['lattice_color'],
	'GAMMACORRECT' => $new['gammacorrect'],
	'JPEG_QUALITY' => $new['jpeg_quality'],

	'CAPTCHA_IMG' => '<img src="' . append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=confirm&amp;id=Admin') . '" alt="" />',

	'S_GREAT_PRE_LETTERS_YES' => ($new['pre_letters_great'] == 1) ? 'checked="checked"' : '',
	'S_GREAT_PRE_LETTERS_NO' => ($new['pre_letters_great'] == 0) ? 'checked="checked"' : '',
	'S_RND_FONT_PER_LETTER_YES' => ($new['font'] == 1) ? 'checked="checked"' : '',
	'S_RND_FONT_PER_LETTER_NO' => ($new['font'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_YES' => ($new['chess'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_NO' => ($new['chess'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_RND' => ($new['chess'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_YES' => ($new['ellipses'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_NO' => ($new['ellipses'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_RND' => ($new['ellipses'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_YES' => ($new['arcs'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_NO' => ($new['arcs'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_RND' => ($new['arcs'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_YES' => ($new['lines'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_NO' => ($new['lines'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_RND' => ($new['lines'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_IMAGE_YES' => ($new['image'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_IMAGE_NO' => ($new['image'] == 0) ? 'checked="checked"' : '',
	'S_JPEG_IMAGE_YES' => ($new['jpeg'] == 1) ? 'checked="checked"' : '',
	'S_JPEG_IMAGE_NO' => ($new['jpeg'] == 0) ? 'checked="checked"' : '',

	'S_HIDDEN_FIELDS' => '',
	'S_CAPTCHA_CONFIG_ACTION' => append_sid('admin_captcha_config.' . PHP_EXT)
	)
);

$template->pparse('body');

echo '<div align="center"><span class="copyright">Advanced Visual Confirmation &copy; 2006 <a href="http://www.amigalink.de" target="_blank">AmigaLink</a></span></div>';
include('./page_footer_admin.' . PHP_EXT);

?>