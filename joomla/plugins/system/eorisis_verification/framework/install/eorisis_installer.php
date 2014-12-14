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

class plgSystemEorisis_VerificationInstallerScript
{
	protected $xml;
	protected $attributes;
	protected $client_id;
	protected $install_type;
	protected $version_upgrade;

	protected $element;
	protected $app_title;
	protected $app_title_full;
	protected $app_type;
	protected $app_group;
	protected $app_path;
	protected $media_dir;
	protected $release_date;
	protected $app_version_new = '';
	protected $app_version_old = '';
	protected $min_joomla_version = '';
	protected $min_php_version = '';
	protected $jversion = '';

	protected $manifest_cache;
	protected $html;
	protected $short_url;
	protected $thanks;
	protected $copyright;

	protected $new_jversion;
	protected $db;

	//	--------------------------------------------------

	function preflight($type, $parent)
	{
		//	Preflight is executed prior to any Joomla install, update or discover_install actions (or uninstall for plugins).
		$type = strtolower($type); // important

		//	Install or Update
		if (($type == 'install') or
			($type == 'update'))
		{
			if ($this->get_extension_details($parent))
			{
				//	Minimum PHP Version Check
				if (version_compare(PHP_VERSION, $this->min_php_version, '<'))
				{
					$warning  = 'Your PHP Version is '.PHP_VERSION;
					$warning .= ' - <b>'.$this->app_title.'</b> requires PHP Version '.$this->min_php_version.' or higher in order to work properly.';
					$warning .= ' Please upgrade your PHP';
					$this->warning($warning);
				}

				//	Minimum Joomla Version Check
				if (version_compare($this->jversion, $this->min_joomla_version, '<'))
				{
					$warning  = 'Your Joomla Version is too old. You are running Joomla! '.$this->jversion;
					$warning .= ' - The minimum Joomla Version for this '.$this->app_type.' is '.$this->min_joomla_version.'. Please upgrade your Joomla!';
					$this->warning($warning);
					return false;
				}

				//	--------------------------------------------------

				$this->new_jversion = version_compare($this->jversion, '1.7.0', '>=');
				$this->install_type = $this->install_type();

				//	--------------------------------------------------

				//	Clean first
				$this->clean_dir_contents($this->app_path.'framework');

				//	--------------------------------------------------

				$go_to_app = 'You may now go to your <strong>Extensions > '.$this->app_type.' Manager > '.$this->app_title_full.'</strong>';

				//	Update
				if ($type == 'update')
				{
					$setup_text = 'Update';
					$this->app_version_old = preg_replace('/[^0-9a-zA-Z.-]+/', '', $this->manifest_cache('version'));
					$method = $this->install_type.'.';

					//	Upgrade to a newer version
					if (version_compare($this->app_version_new, $this->app_version_old, '>'))
					{
						$this->version_upgrade = true;
						$version_text  = 'You have upgraded from version <strong>'.$this->app_version_old.'</strong> to version <strong>'.$this->app_version_new.'</strong>';
						$version_text .= $this->release_date;
					}

					//	Downgrade
					elseif (version_compare($this->app_version_new, $this->app_version_old, '<'))
					{
						$version_text  = 'You have downgraded from version <strong>'.$this->app_version_old.'</strong> to version <strong>'.$this->app_version_new.'</strong>';
						$version_text .= $this->release_date.'<br />';
						$version_text .= 'This is not recommended, you may have lost your '.$this->app_type.' settings.';
					}

					//	Re-installed the same version
					else
					{
						$version_text = 'You have re-installed version: <strong>'.$this->app_version_old.'</strong>'.$this->release_date;
					}

					$enable_app = '';
				}

				//	Install
				else
				{
					$setup_text = 'Installation';
					$version_text = 'Version Installed: <strong>'.$this->app_version_new.'</strong>'.$this->release_date;
					$method = $this->install_type.' - Fresh Install.';
					$enable_app = 'Remember to <strong>enable</strong> the '.$this->app_type.'.<br />';
				}

				//	--------------------------------------------------

				$app_link = '<a href="http://eorisis.com/'.$this->short_url.'" target="_blank" title="'.JText::_('JBROWSERTARGET_NEW').'">eorisis.com/'.$this->short_url.'</a>';
				$this->thanks = 'Thanks for choosing eorisis.';

				$html  = '<div class="eo_wrap">';
				$html .= '	<div class="alert alert-info">';
				$html .= '		<p>';
				$html .= '			<strong>Software '.$setup_text.' Complete.</strong><br />';
				$html .=			$version_text.'<br />';
				$html .= '			Method Used: '.$method.'<br />';
				$html .= '			Changelog: <strong>'.$app_link.'</strong><br />';
				$html .= '			<br />';
				$html .=			$go_to_app.'<br />';
				$html .=			$enable_app;
				$html .= '			<br />';
				$html .=			$this->thanks.'<br />';
				$html .=			$this->copyright;
				$html .= '		</p>';
				$html .= '	</div>';
				$html .= '</div>';

				$this->html = $this->minify($html);
			}
			else
			{
				$this->warning('Installation aborted due to a package problem. Please report this to info@eorisis.com');
				return false;
			}
		}
		elseif ($type != 'uninstall')
		{
			$this->warning('You cannot use this Install Type');
			return false;
		}
	}

	//	--------------------------------------------------

	function install($parent)
	{
		echo $this->html;
	}

	//	--------------------------------------------------

	function update($parent)
	{
		$notice = "After a ".$this->app_type." Update, clear your Joomla! and browser's cache.";
		JFactory::getApplication()->enqueueMessage($notice, 'notice');
		echo $this->html;
	}

	//	--------------------------------------------------

	function postflight($type, $parent)
	{
		//	Executed after Install or Update. Not after uninstall.
//		$type = strtolower($type); // important

		//	--------------------------------------------------

		if ($this->version_upgrade)
		{
			//	Changes in version 1.1.1 :: Set new param names
			if (version_compare($this->app_version_old, '1.1.1', '<'))
			{
				if (($params = $this->get_params()) and is_object($params))
				{
					foreach ($params as $name => $value)
					{
						switch ($name)
						{
							case 'google_meta_tag'	: unset($params->$name); $params->google_id = (string)$value; break;
							case 'bing_meta_tag'	: unset($params->$name); $params->bing_id = (string)$value; break;
							case 'baidu_meta_tag'	: unset($params->$name); $params->baidu_id = (string)$value; break;
							case 'alexa_meta_tag'	: unset($params->$name); $params->alexa_id = (string)$value; break;
						}
					}

					$this->set_params(json_encode($params));
				}
			}
		}
	}

	//	--------------------------------------------------

	protected function get_extension_details($parent)
	{
		$this->xml = $parent->get('manifest');
		$this->element = (string)$this->xml->name;

		if ($this->xml and $this->element)
		{
			$this->attributes = $this->xml->attributes();
			$this->app_type = (string)$this->attributes->type;

			if (defined('JPATH_SITE') and
				defined('JPATH_ADMINISTRATOR'))
			{
				if (($this->app_type == 'template') or
					($this->app_type == 'module'))
				{
					$type_dir = '/'.$this->app_type.'s/';
					switch ((string)$this->attributes->client)
					{
						case 'site'			: $this->client_id = '0'; $root_path = JPATH_SITE.$type_dir; break;
						case 'administrator': $this->client_id = '1'; $root_path = JPATH_ADMINISTRATOR.$type_dir; break;
					}
				}
				elseif ($this->app_type == 'plugin')
				{
					$this->app_group = (string)$this->attributes->group;
					$this->app_title_full = $this->app_title_full();
					$root_path = JPATH_SITE.'/plugins/'.$this->app_group.'/';
				}
				else
				{
					return false;
				}

				$this->media_dir = $this->media_destination();
				$this->app_path = $root_path.$this->element.'/';
				$this->app_type = ucfirst($this->app_type);
				$this->app_title = (string)$this->xml->title;
				$this->jversion = $this->version_correction(JVERSION);
				$this->min_joomla_version = (string)$this->attributes->version;
				$this->min_php_version = (string)$this->xml->min_php_version;
				$this->app_version_new = (string)$this->xml->version;
				$this->release_date = ' - Released on '.(string)$this->xml->creationDate.'.';
				$this->short_url = (string)$this->xml->short_url;
				$this->copyright = str_replace('(C)', '&copy', (string)$this->xml->copyright);

				return true;
			}
		}
	}

	//	--------------------------------------------------

	protected function media_destination()
	{
		if ($media_dir = (string)$this->xml->media['destination'])
		{
			$media_dir = preg_replace('/[^a-zA-Z_-]+/', '', $media_dir);

			if (strlen($media_dir))
			{
				return JPATH_SITE.'/media/'.$media_dir.'/';
			}
		}
	}

	//	--------------------------------------------------

	protected function install_type()
	{
		$install_type = 'unknown';

		if (isset($_POST['task']))
		{
			if (version_compare(JVERSION, 3, '>='))
			{
				if (($_POST['task'] == 'install.install') and isset($_POST['installtype']))
				{
					$install_type = preg_replace('/[^a-z]+/', '', $_POST['installtype']); // upload, folder, url
				}
				elseif (($_POST['task'] == 'update.update') and isset($_POST['boxchecked']) and ($_POST['boxchecked'] == 1))
				{
					$install_type = 'update';
				}
			}
			elseif (isset($_POST['option']) and ($_POST['option'] == 'com_installer'))
			{
				if (($_POST['task'] == 'install') and isset($_POST['installtype']))
				{
					$install_type = preg_replace('/[^a-z]+/', '', $_POST['installtype']); // upload, folder, url
				}
				elseif (($_POST['task'] == 'update') and isset($_POST['boxchecked']) and ($_POST['boxchecked'] == 1))
				{
					$install_type = 'update';
				}
			}
		}

		//	TODO: Install From Web and discover_install
		//	discover_install is not firing the intaller possibly due to a Joomla bug
		switch ($install_type)
		{
			case 'update'	: return 'Joomla Update';
			case 'upload'	: return 'Upload Package File (File Upload)';
			case 'folder'	: return 'Install from Directory';
			case 'url'		: return 'Install from URL';
			case 'unknown'	: return 'Unknown';
		}
	}

	//	--------------------------------------------------

	protected function clean_dir_contents($path, $exceptions = false)
	{
		if (is_dir($path) === true)
		{
			$exceptions_default = array(
				'.',
				'..',
				'.htaccess',
				'.htpasswd',
				'.ftpquota'
			);

			if ($exceptions)
			{
				if (is_array($exceptions))
				{
					if (!empty($exceptions))
					{
						$exceptions = array_merge($exceptions_default, $exceptions);
						$exceptions = array_unique($exceptions);
					}
				}
				elseif (is_string($exceptions))
				{
					$exceptions_default[] = (string)$exceptions;
					$exceptions = $exceptions_default;
				}
			}
			else
			{
				$exceptions = $exceptions_default;
			}

			$contents = array_diff(scandir($path), $exceptions);

			//	--------------------------------------------------

			foreach ($contents as $item)
			{
				$this->delete(realpath($path).'/'.$item);
			}

			return true;
		}
	}

	//	--------------------------------------------------

	protected function delete($path)
	{
		if (is_dir($path) === true)
		{
			$contents = array_diff(scandir($path), array('.', '..'));

			foreach ($contents as $item)
			{
				$this->delete(realpath($path).'/'.$item);
			}

			return rmdir($path);
		}
		elseif (is_file($path) === true)
		{
			return unlink($path);
		}

		return false;
	}

	//	--------------------------------------------------

	protected function warning($warning)
	{
		Jerror::raiseWarning(null, $warning);
	}

	//	--------------------------------------------------

	protected function minify($data)
	{
		return str_replace('	', '', $data);
	}

	//	--------------------------------------------------

	protected function version_correction($version)
	{
		if (strlen($version) == 3)
		{
			$version = $version.'.0';
		}

		return $version;
	}

	//	--------------------------------------------------

	protected function quote_name($name)
	{
		if ($this->new_jversion)
		{
			return $this->db->quoteName($name);
		}
		else
		{
			return $this->db->nameQuote($name);
		}
	}

	//	--------------------------------------------------

	protected function manifest_cache($value = false)
	{
		if (!$this->manifest_cache)
		{
			$db = JFactory::getDbo();
			$this->db = $db;
			$query = $db->getQuery(true);

			if (isset($this->client_id)) // template, module
			{
				$condition = $this->quote_name('client_id').'='.$db->quote($this->client_id);
			}
			elseif (isset($this->app_group)) // plugin
			{
				$condition = $this->quote_name('folder').'='.$db->quote($this->app_group);
			}

			$query->select($this->quote_name('manifest_cache'));
			$query->from($this->quote_name('#__extensions'));
			$query->where(array(
				$this->quote_name('type').'='.$db->quote(strtolower($this->app_type)),
				$this->quote_name('element').'='.$db->quote($this->element),
				$condition
			));
			$db->setQuery($query);
			$this->manifest_cache = json_decode($db->loadResult());
		}

		if ($this->manifest_cache)
		{
			if ($value)
			{
				return (string)$this->manifest_cache->$value;
			}
			else
			{
				return $this->manifest_cache;
			}
		}
	}

	//	--------------------------------------------------

	protected function app_title_full()
	{
		$current_path = str_replace('framework'.DIRECTORY_SEPARATOR.'install', '', dirname(__FILE__));

		if ($ini = $current_path.'language/'.(string)$this->xml->languages->language[1])
		{
			$ini = parse_ini_file($ini);
			return $ini[strtoupper($this->element)];
		}
	}

	//	--------------------------------------------------

	protected function get_params()
	{
		$db = JFactory::getDbo();
		$this->db = $db;
		$query = $db->getQuery(true);
		$query->select($this->quote_name('params'));
		$query->from($this->quote_name('#__extensions'));
		$query->where(array(
			$this->quote_name('type').'='.$db->quote('plugin'),
			$this->quote_name('folder').'='.$db->quote('system'),
			$this->quote_name('element').'='.$db->quote($this->element)
		));
		$db->setQuery($query);

		return json_decode($db->loadResult());
	}

	//	--------------------------------------------------

	protected function set_params($params)
	{
		$db = JFactory::getDbo();
		$this->db = $db;
		$query = $db->getQuery(true);
		$query->update($this->quote_name('#__extensions'));
		$query->set($this->quote_name('params').'='.$db->quote($params));
		$query->where(array(
			$this->quote_name('type').'='.$db->quote('plugin'),
			$this->quote_name('folder').'='.$db->quote('system'),
			$this->quote_name('element').'='.$db->quote($this->element)
		));
		$db->setQuery($query);
		$db->query();
	}
}
