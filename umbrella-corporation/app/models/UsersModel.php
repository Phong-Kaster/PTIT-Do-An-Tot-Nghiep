<?php 
/**
 * Users model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class UsersModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_USERS));
        $this->getQuery()
             ->leftJoin(
                    TABLE_PREFIX.TABLE_PACKAGES,
                    TABLE_PREFIX.TABLE_USERS.".package_id",
                    "=",
                    TABLE_PREFIX.TABLE_PACKAGES.".id"
                );
	}

    public function search($search_query)
    {
        parent::search($search_query);
        $search_query = $this->getSearchQuery();

        if (!$search_query) {
            return $this;
        }

        $query = $this->getQuery();
        $search_strings = array_unique((explode(" ", $search_query)));
        foreach ($search_strings as $sq) {
            $sq = trim($sq);

            if (!$sq) {
                continue;
            }

            $query->where(function($q) use($sq) {
                $q->where(TABLE_PREFIX.TABLE_USERS.".email", "LIKE", $sq."%");
                $q->orWhere(TABLE_PREFIX.TABLE_USERS.".firstname", "LIKE", $sq."%");
                $q->orWhere(TABLE_PREFIX.TABLE_USERS.".lastname", "LIKE", $sq."%");
                $q->orWhere(TABLE_PREFIX.TABLE_PACKAGES.".title", "LIKE", $sq."%");
            });
        }

        return $this;
    }

    public function fetchData()
    {
        $this->paginate();

        $this->getQuery()
             ->select(TABLE_PREFIX.TABLE_USERS.".*")
             ->select(TABLE_PREFIX.TABLE_PACKAGES.".title");
        $this->data = $this->getQuery()->get();
        return $this;
    }
}
