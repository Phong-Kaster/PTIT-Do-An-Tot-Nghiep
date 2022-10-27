<?php 
/**
 * Proxies model
 *
 * @version 1.0
 * @author Onelab <hello@onelab.co> 
 * 
 */
class ProxiesModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_PROXIES));
	}


    /**
     * Get best proxy according to country code
     * @param  array        $countries Array f ISO Aplha-2 Codes countries
     *                                 First value is main country
     * @return string|null             Proxy or null (if not found)
     */
    public static function getBestProxy($countries = [])
    {
        $proxy = null;

        $query = DB::table(TABLE_PREFIX.TABLE_PROXIES);
        if ($countries) {
            $query->where("country_code", "=", $countries[0]);
        }

        $query->orderBy("use_count","ASC")
              ->limit(1)
              ->select("*");
        $resp = $query->get();

        if (count($resp) != 1 && count($countries) > 1) {
            // Not found country proxy
            // Select in neighbour countries
            $query = DB::table(TABLE_PREFIX.TABLE_PROXIES);
            $query->whereIn("country_code", $countries);
            $query->orderBy("use_count","ASC")
                  ->limit(1)
                  ->select("*");
                  
            $resp = $query->get();
        }

        if (count($resp) != 1) {
            // Still not found the proxy
            // Select randomly
            $query = DB::table(TABLE_PREFIX.TABLE_PROXIES);
            $query->orderBy("use_count","ASC")
                  ->limit(1)
                  ->select("*");
                  
            $resp = $query->get();
        }

        if (count($resp) == 1) {
            $r = $resp[0];


            if (isValidProxy($r->proxy)) {
                $proxy = $r->proxy;
            }
        }

        return $proxy;
    }
}
