<?php
/**
 * Data list
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class DataList
{
    protected $data;
    protected $data_as_model = null;
    protected $page_size = null;
    protected $page = null;
    protected $page_count = null;
    protected $total_count = 0;
    protected $query = null;
    protected $search_query = null;
    protected $search_performed = false;

    /**
     * Initialize
     */
    public function __construct()
    {
    }


    /**
     * Get page count
     * @return int 
     */
    public function getPageCount()
    {
        return $this->page_count;
    }


    /**
     * Set page count
     * @param int|null $count
     */
    protected function setPageCount($count)
    {
        $this->page_count = $count;
        return $this;
    }


    /**
     * Get total count of results
     * @return [type] [description]
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }


    protected function setTotalCount($count)
    {
        $this->total_count = (int)$count > 0 ? (int)$count : 0;
        return $this;
    }


    /**
     * Set current page
     * @param int $page Page number
     */
    public function setPage($page)
    {
        $this->page = (int)$page > 0 ? (int)$page : 1;
        return $this;
    }


    /**
     * Get current page number
     * @return int 
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * Set page size
     * @param integer $page_size 
     */
    public function setPageSize($page_size = 20)
    {
        if (is_null($page_size) || (is_int($page_size) && $page_size > 0)) {
            $this->page_size = $page_size;
        }

        return $this;
    }

    /**
     * Get page size
     * @return int|null 
     */
    public function getPageSize()
    {
        return $this->page_size;
    }


    /**
     * Get query
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * Set query
     */
    protected function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }



    /**
     * Paginate 
     * @return self
     */
    public function paginate()
    {
        $this->setTotalCount($this->getQuery()->count());
        
        if ($this->getPageSize() > 0) {
            $this->setPageCount(ceil($this->getTotalCount() / $this->getPageSize()));

            if ($this->getPage() > $this->getPageCount()) {
                $this->setPage($this->getPageCount());
            }

            if ($this->getPage() < 1) {
                $this->setPage(1);
            }

            $this->getQuery()
                ->limit($this->getPageSize())
                ->offset(($this->getPage() - 1) * $this->getPageSize());
        } else {
            $this->setPage(null);
            $this->setPageCount(null);
        }

        return $this;
    }


    /**
     * Perform a search
     * @param  [string] $q Search query
     * @return self 
     */
    public function search($search_query)
    {
        $search_query = trim((string)$search_query);
        if ($search_query) {
            $this->setSearchQuery($search_query);   
            $this->searchPerformed(true);
        }


        return $this;
    }


    /**
     * Set search query string (after processing it in the self::search() method)
     * Query validation is in responsibility of the self::serch() method
     * @param [type] $q [description]
     */
    protected function setSearchQuery($search_query) 
    {
        $this->search_query = $search_query;
    }


    /**
     * Get processed search query string
     * @return string|null 
     */
    public function getSearchQuery()
    {
        return $this->search_query;
    }


    /**
     * Set the status of the search_performed property
     * @param  boolean $status 
     * @return self          
     */
    public function searchPerformed($status = true)
    {
        $this->search_performed = (bool)$status;
        return $this;
    }


    /**
     * Get the status of serch_performed
     * @return boolean 
     */
    public function isSearchPerformed()
    {
        return $this->search_performed;
    }


    /**
     * Request data from database
     * @return self
     */
    public function fetchData()
    {
        $this->paginate();
        $this->getQuery()->select("*");
        $this->data = $this->getQuery()->get();
        return $this;
    }


    /**
     * Get data
     * @return [type] [description]
     */
    public function getData()
    {
        return $this->data;
    }


    public function getDataAs($modelname, $force_new = false)
    {
        if (is_null($this->data_as_model) || $force_new) {
            $data = array();

            foreach ($this->data as $r) {
                $model = Controller::model($modelname);
                foreach ($r as $key => $value) {
                    $model->set($key, $value);
                }

                $model->markAsAvailable();
                $data[] = $model;
            }

            $this->data_as_model = $data;
        }

        return $this->data_as_model;
    }


    /**
     * Check if inaccessible method is a accessible method in query object
     * @param  string $method Case sensitive name of method
     * @param  mixed $args   
     * @return DataList|Exception
     */
    public function __call($method, $args)
    {
        if (method_exists($this->getQuery(), $method)) {
            call_user_func_array(array($this->getQuery(), $method), $args);
            return $this;
        } else {
            throw new Exception('Undefined method: '.$method);
        }
    }
}
