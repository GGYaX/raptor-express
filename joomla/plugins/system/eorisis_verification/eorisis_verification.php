<?php
defined('_JEXEC') or die;

/**
 * -------------------------------------------------------------------
 * Software:			eorisis Verification
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

if (!version_compare(JVERSION, 3, '>=')) { jimport('joomla.plugin.plugin'); }

//	--------------------------------------------------

class plgSystemEorisis_Verification extends JPlugin
{
	function onAfterDispatch()
	{
		if (JFactory::getApplication()->isAdmin()) { return; }
		$doc = JFactory::getDocument();
		if ($doc->getType() != 'html') { return; }

		//	--------------------------------------------------
		//	Google

		if ($this->params->get('google_verify_meta', 1) and
			($google_id = $this->params->get('google_id')) and
			($google_id = $this->clean_field($google_id)))
		{
			$doc->setMetaData('google-site-verification', $google_id);
		}

		if ($this->params->get('google_verify_file', 0) and
			($google_file_id = $this->params->get('google_file_id')) and
			($google_file_id = trim($google_file_id, ' ')))
		{
			if ((strpos($google_file_id, 'google') !== false) or
				(strpos($google_file_id, '.html') !== false))
			{
				$google_file_id = str_replace(array('google', '.html'), '', $google_file_id);
			}

			if ($google_file_id = $this->clean_field($google_file_id))
			{
				$google_file = 'google'.$google_file_id.'.html';
				$this->store_file(JPATH_SITE.'/'.$google_file, 'google-site-verification: '.$google_file);
			}
		}

		//	--------------------------------------------------
		//	Bing

		if (($bing_id = $this->params->get('bing_id')) and
			($bing_id = $this->clean_field($bing_id)))
		{
			if ($this->params->get('bing_verify_meta', 1))
			{
				$doc->setMetaData('msvalidate.01', $bing_id);
			}

			if ($this->params->get('bing_verify_file', 0))
			{
				$xml  = '<?xml version="1.0"?>';
				$xml .= '<users>';
				$xml .= '<user>'.$bing_id.'</user>';
				$xml .= '</users>';

				$this->store_file(JPATH_SITE.'/BingSiteAuth.xml', $xml);
			}
		}

		//	--------------------------------------------------
		//	Yandex

		if (($yandex_id = $this->params->get('yandex_id')) and
			($yandex_id = $this->clean_field($yandex_id)))
		{
			if ($this->params->get('yandex_verify_meta', 1))
			{
				$doc->setMetaData('yandex-verification', $yandex_id);
			}

			if ($this->params->get('yandex_verify_file_html', 0))
			{
				$html  = '<!DOCTYPE html>';
				$html .= '<html>';
				$html .= '<head>';
				$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
				$html .= '</head>';
				$html .= '<body>Verification: '.$yandex_id.'</body>';
				$html .= '</html>';

				$this->store_file(JPATH_SITE.'/yandex_'.$yandex_id.'.html', $html);
			}

			if ($this->params->get('yandex_verify_file_txt', 0))
			{
				$this->store_file(JPATH_SITE.'/yandex_'.$yandex_id.'.txt', '');
			}
		}

		//	--------------------------------------------------
		//	Baidu

		if (($baidu_id = $this->params->get('baidu_id')) and
			($baidu_id = $this->clean_field($baidu_id)))
		{
			if ($this->params->get('baidu_verify_meta', 1))
			{
				$doc->setMetaData('baidu-site-verification', $baidu_id);
			}

			if ($this->params->get('baidu_verify_file', 0))
			{
				$this->store_file(JPATH_SITE.'/baidu_verify_'.$baidu_id.'.html', $baidu_id);
			}
		}

		//	--------------------------------------------------
		//	Alexa

		if (($alexa_id = $this->params->get('alexa_id')) and
			($alexa_id = $this->clean_field($alexa_id)))
		{
			$name = 'alexaverifyid'; // alexaVerifyID

			if ($this->params->get('alexa_verify_meta', 1))
			{
				$doc->setMetaData($name, $alexa_id);
			}

			if ($this->params->get('alexa_verify_file', 0))
			{
				$html  = '<!DOCTYPE html>';
				$html .= '<html>';
				$html .= '<head>';
				$html .= '<meta name="'.$name.'" content="'.$alexa_id.'" />';
				$html .= '</head>';
				$html .= '<body>';
				$html .= '<p>Alexa File Verification Ready.</p>';
				$html .= '</body>';
				$html .= '</html>';

				$this->store_file(JPATH_SITE.'/'.$alexa_id.'.html', $html);
			}
		}

		//	--------------------------------------------------
		//	WOT (Web of Trust)

		if (($wot_id = $this->params->get('wot_id')) and
			($wot_id = $this->clean_field($wot_id)))
		{
			if ($this->params->get('wot_verify_meta', 1))
			{
				$doc->setMetaData('wot-verification', $wot_id);
			}

			if ($this->params->get('wot_verify_file_html', 0) and
				($wot_file_id = $this->params->get('wot_file_id')) and
				($wot_file_id = trim($wot_file_id, ' ')))
			{
				if ((strpos($wot_file_id, 'mywot') !== false) or
					(strpos($wot_file_id, '.html') !== false))
				{
					$wot_file_id = str_replace(array('mywot', '.html'), '', $wot_file_id);
				}

				if ($wot_file_id = $this->clean_field($wot_file_id))
				{
					$this->store_file(JPATH_SITE.'/mywot'.$wot_id.'.html', $wot_file_id);
				}
			}
		}

		//	--------------------------------------------------
		//	Norton Safe Web

		if (($norton_safeweb_id = $this->params->get('norton_safeweb_id')) and
			($norton_safeweb_id = $this->clean_field($norton_safeweb_id)))
		{
			if ($this->params->get('norton_safeweb_verify_meta', 1))
			{
				$doc->setMetaData('norton-safeweb-site-verification', $norton_safeweb_id);
			}

			if ($this->params->get('norton_safeweb_verify_file_html', 0) and
				($norton_safeweb_file_id = $this->params->get('norton_safeweb_file_id')) and
				($norton_safeweb_file_id = trim($norton_safeweb_file_id, ' ')))
			{
				if ((strpos($norton_safeweb_file_id, 'nortonsw_') !== false) or
					(strpos($norton_safeweb_file_id, '.html') !== false))
				{
					$norton_safeweb_file_id = str_replace(array('nortonsw_', '.html'), '', $norton_safeweb_file_id);
				}

				if ($norton_safeweb_file_id = $this->clean_field($norton_safeweb_file_id))
				{
					$this->store_file(JPATH_SITE.'/nortonsw_'.$norton_safeweb_file_id.'.html', $norton_safeweb_id);
				}
			}
		}

		//	--------------------------------------------------
		//	Pinterest

		if (($pinterest_id = $this->params->get('pinterest_id')) and
			($pinterest_id = $this->clean_field($pinterest_id)))
		{
			$name = 'p:domain_verify';

			if ($this->params->get('pinterest_verify_meta', 1))
			{
				$doc->setMetaData($name, $pinterest_id);
			}

			if ($this->params->get('pinterest_verify_file_html', 0))
			{
				$html  = '<!DOCTYPE html>';
				$html .= '<html>';
				$html .= '<head>';
				$html .= '<meta name="'.$name.'" content="'.$pinterest_id.'" />';
				$html .= '</head>';
				$html .= '<body>';
				$html .= '<p>Pinterest File Verification Ready.</p>';
				$html .= '</body>';
				$html .= '</html>';

				$this->store_file(JPATH_SITE.'/pinterest-'.substr($pinterest_id, 0, 5).'.html', $html);
			}
		}
	}

	//	/onAfterDispatch
	//	--------------------------------------------------

	protected function clean($data)
	{
		return filter_var($data, FILTER_SANITIZE_STRING);
	}

	//	--------------------------------------------------

	protected function clean_field($data)
	{
		if (str_replace(' ', '', $data) != '')
		{
			$data = str_replace(array(
			'	',
			' ',
			'.',
			'/',
			'\\'
			), '', $data);

			return $this->clean($data);
		}
	}

	//	--------------------------------------------------

	protected function store_file($file, $data)
	{
		if (!is_file($file))
		{
			if (file_put_contents($file, $data))
			{
				if ($this->params->get('file_permissions', 0))
				{
					chmod($file, 0644);
				}
			}
		}
	}
}
