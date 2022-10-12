<?php 
    Class ClinicsModel extends DataList
    {
        public function __construct()
        {
            $this->setQuery(DB::table(TABLE_PREFIX.TABLE_CLINICS));
        }
    }
?>