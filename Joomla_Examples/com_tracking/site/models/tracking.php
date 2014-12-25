<?php
defined('_JEXEC') or die('404');

jimport('joomla.application.component.modelitem');

class TrackingModelTracking extends JModelItem
{
	/**
	 * @var string msg
	 */
	protected $msg;
 
	/**
	 * Here we can load anything from DBO...
         * @return string The message to be displayed to the user
	 */
	public function getInfo() 
	{
		if (!isset($this->msg)) 
		{
			$this->msg = 'Message from front model. Add access to DB here.';
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('extension_id,name');
		$query->from('#__extensions');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->id, $message->greeting);
			}
		}
		var_dump($options);
		return $this->msg;
	}

}
