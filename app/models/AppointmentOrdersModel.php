<?php 
    Class AppointmentOrdersModel extends DataList
    {
        public function __construct()
        {
            $this->setQuery(DB::table(TABLE_PREFIX.TABLE_APPOINTMENT_ORDERS));
        }
    }
?>