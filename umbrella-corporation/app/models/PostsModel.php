<?php 
/**
 * Posts model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class PostsModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_POSTS));
	}

    public function fetchData()
    {
        $this->getQuery()
             ->leftJoin(TABLE_PREFIX.TABLE_USERS,
                        TABLE_PREFIX.TABLE_POSTS.".user_id",
                        "=",
                        TABLE_PREFIX.TABLE_USERS.".id")
             ->leftJoin(TABLE_PREFIX.TABLE_ACCOUNTS,
                        TABLE_PREFIX.TABLE_POSTS.".account_id",
                        "=",
                        TABLE_PREFIX.TABLE_ACCOUNTS.".id");
        $this->paginate();

        $this->getQuery()
             ->select(TABLE_PREFIX.TABLE_POSTS.".*")
             ->select(TABLE_PREFIX.TABLE_USERS.".firstname")
             ->select(TABLE_PREFIX.TABLE_USERS.".lastname")
             ->select(TABLE_PREFIX.TABLE_ACCOUNTS.".username");
        $this->data = $this->getQuery()->get();
        return $this;
    }
}
