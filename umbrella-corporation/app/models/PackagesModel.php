<?php 
/**
 * Packages model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class PackagesModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_PACKAGES));
	}

    /**
     * Perform a search if $searh_query provided
     * @param  string $search_query 
     * @return self               
     */
    public function search($search_query)
    {
        parent::search($search_query);
        $search_query = $this->getSearchQuery();

        if (!$search_query) {
            return $this;
        }

        $query = $this->getQuery();
        $query->where(TABLE_PREFIX.TABLE_PACKAGES.".title", "LIKE", $search_query."%");

        return $this;
    }
}
