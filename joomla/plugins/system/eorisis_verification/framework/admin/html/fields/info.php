<?php
defined('_JEXEC') or die;

/**
 * -------------------------------------------------------------------
 * Software Name:	eorisis Verification
 * Software Type:	Joomla! System Plug-in
 * 
 * @author		eorisis http://eorisis.com
 * @copyright	Copyright (C) 2012-2014 eorisis. All Rights Reserved.
 * @license		GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * 'eorisis Verification' is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * See /misc/licence.txt
 * -------------------------------------------------------------------
**/

jimport('joomla.form.formfield');

//	The class name must always be the same as the filename (in camel case)
class JFormFieldInfo extends JFormField
{
	//	The field class must know its own type through the variable $type.
	protected $type = 'info';

	//	--------------------------------------------------

	public function getLabel() {}

	//	--------------------------------------------------

	protected function getInput()
	{
		if (!JFactory::getApplication()->isAdmin()) { return; }

		//	--------------------------------------------------

		$sef_url = 'verification';
		$app_dir = 'eorisis_verification';
		$app_url = 'eorisis-verification';
		$app_type = 'plugin';

		//	--------------------------------------------------

		JFactory::getLanguage()->load('plg_system_'.$app_dir, JPATH_ADMINISTRATOR, 'en-GB', true);
		$doc = JFactory::getDocument();
		$app_path = JPATH_SITE.'/plugins/system/'.$app_dir.'/';
		$framework_admin = $app_path.'framework/admin/';

		//	--------------------------------------------------

		$doc->addStyleSheet('//fonts.googleapis.com/css?family=Open+Sans:300', 'text/css', 'all');
		$css = $this->read_file($framework_admin.'css/styles.css');

		if (version_compare(JVERSION, 3, '>='))
		{ $css .= '.eo_hr { width:220px;border-top:1px solid #eee; }'; }
		else
		{ $css .= '.eo_hr { display:block;color:#ddd;background:#eee;border:0; }'; }

		$css .= '.eo_hr { clear:both;height:1px;font-size:0;margin:5px 0; }';
		$css .= '.eo_help label { color:#ec8824; }';
		$doc->addStyleDeclaration($css);

		//	--------------------------------------------------

		if ($xml_file = $this->read_file($app_path.$app_dir.'.xml', true))
		{
			$xml = new SimpleXMLElement($xml_file);

			$js = $this->read_file($framework_admin.'js/script.js');
			$js = str_replace('*app_url*', $app_url, $js);
			$js = str_replace('*app_type*', $app_type, $js);
			$js = str_replace('*update_site_url*', (string)$xml->authorUrl.'/updates', $js);
			$doc->addScriptDeclaration($js);

			$more_info_url = (string)$xml->authorUrl.'/'.$sef_url;

			//	--------------------------------------------------

			$html  = '<script type="text/javascript">var installed_version = "'.(string)$xml->version.'"</script>';
			$html .= '<div class="eo_info">';

			$html .= '	<h1>'.(string)$xml->title.'</h1>';
			$html .= '	<div id="eo_update_check">';
			$html .= '		<a href="#" title="'.JText::_('EO_UPDATE_CHECK_TITLE').'"><h3>'.JText::_('EO_UPDATE_CHECK').'</a></h3>';
			$html .= '	</div>';

			$html .= '	<div id="server_version" class="ver">';
			$html .= '		<h3><span id="eo_version_no"></span></h3>';
			$html .= '		<span id="eo_latest"></span>';
			$html .= '		<span id="eo_dl_update"></span>';
			$html .= '	</div>';

			$html .= '	<div id="eo_release">';
			$html .= '		<span id="eo_release_date"></span>';
			$html .= '		<span id="eo_release_notes"></span>';
			$html .= '		<span id="eo_infourl"></span>';
			$html .= '	</div>';
			$html .= '	<hr>';

			$html .= '	<span class="group">';
			$html .=		JText::_('EO_VERSION').': '.(string)$xml->version.' ('.(string)$xml->creationDate.')<br />';
			$html .=		JText::_('EO_COMPATIBILITY').': '.(string)$xml->compatibility;
			$html .= '	</span>';

			$html .= '	<span class="group">';
			$html .=		JText::_('EO_MORE_INFO').': <a href="'.$more_info_url.'" target="_blank" title="'.JText::_('JBROWSERTARGET_NEW').'">'.(string)$xml->author.'/'.$sef_url.'</a><br />';
			$html .=		JText::_('EO_SUPPORT').': <a href="mailto:'.(string)$xml->authorEmail.'" target="_top">'.(string)$xml->authorEmail.'</a><br />';
			$html .=		JText::_('EO_VOTE').' <a href="'.$more_info_url.'-jed" target="_blank" title="'.JText::_('JBROWSERTARGET_NEW').'">'.JText::_('EO_JED').'</a><br />';
			$html .=		JText::_('EO_DONATE').': <a href="'.$more_info_url.'#donate" target="_blank" title="'.JText::_('JBROWSERTARGET_NEW').'">'.JText::_('EO_DONATION').'</a><br />';
			$html .= '		<br />';
			$html .=		str_replace('(C)', '&copy', (string)$xml->copyright);
			$html .= '	</span>';
			$html .= '</div>';

			return str_replace('	', '', $html);
		}
	}

	//	--------------------------------------------------

	protected function read_file($file, $check = false)
	{
		if (!$check)
		{
			return file_get_contents($file);
		}
		elseif (is_file($file) === true)
		{
			return file_get_contents($file);
		}
	}
}
