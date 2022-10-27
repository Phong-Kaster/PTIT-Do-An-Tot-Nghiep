<?php 
/**
 * Plugins model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class PluginsModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_PLUGINS));
	}
}
