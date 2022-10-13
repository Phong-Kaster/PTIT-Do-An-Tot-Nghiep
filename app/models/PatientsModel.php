<?php 
    Class PatientsModel extends DataList
    {
        public function __construct()
        {
            $this->setQuery(DB::table(TABLE_PREFIX.TABLE_PATIENTS));
        }
    }
?>