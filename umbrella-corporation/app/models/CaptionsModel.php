<?php 
/**
 * Captions model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class CaptionsModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_CAPTIONS));
	}
}
