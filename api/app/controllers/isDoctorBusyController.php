<?php
    /**
     * @author Phong-Kaster
     * @since 19-12-2022

     */
    class isDoctorBusyController extends Controller
    {
        public function process()
        {
            $AuthUser = $this->getVariable("AuthUser");
            if (!$AuthUser)
            {
                header("Location: ".APPURL."/login");
                exit;
            }

            $request_method = Input::method();
            if($request_method === 'GET')
            {
                $this->isDoctorBusy();
            }
        }

        /**
         * @since 19-12-2022
         * kiểm tra xem bác sĩ với id hiện tại có đang bận không
         * 
         * Bước 1: lấy ra toàn bộ bác sĩ cùng chuyên khoa với bác sĩ đang được kiểm tra
         * Bước 2: tìm số lượng bệnh nhân của mỗi bác sĩ trong ngày hôm đó
         * Bước 3: tính trung bình cộng mà lấy tất cả số bệnh nhân của mọi bác sĩ cộng lại
         * Bước 4: nếu số lượng bệnh nhân của bác sĩ hiện tại lớn hơn trung bình cộng thì 
         * bác sĩ này được coi là bận.
         */
        private function isDoctorBusy()
        {
            /**Step 0: kiểm tra dữ liệu đầu vào */
            $this->resp->result = 0;
            $Route = $this->getVariable("Route");

            if( !isset($Route->params->id) )
            {
                $this->resp->msg = "Doctor ID is required !";
                $this->jsonecho();
            }

            $id = $Route->params->id;
            $Doctor = Controller::model("Doctor", $id);
            if( !$Doctor->isAvailable() )
            {
                $this->resp->msg = "Doctor is not available";
                $this->jsonecho();
            }

            
            $specialityId = $Doctor->get("speciality_id");
            $averageQuantity = $this->getAverageAppointmentWithSpecialityId($specialityId);
            $doctorQuantity = $this->getCurrentAppointmentQuantityByDoctorId($id);

            if( $doctorQuantity > $averageQuantity )
            {
                $this->resp->msg = "Bác sĩ ".$Doctor->get("name")." hiện tại đang có rất nhiều bệnh nhân. Hãy cân nhắc chuyển qua bác sĩ khác";
                $this->jsonecho();
            }

            $this->resp->result = 1;
            $this->resp->msg = "Bác sĩ ".$Doctor->get("name")." hiện tại sẵn sàng tiếp nhận bệnh nhân. Hãy tiếp tục !";
            $this->jsonecho();

        }

        /**
         * @since 18-12-2022
         * truyền vào một service id và tìm ra số lượng khám bệnh nhân khám trung bình với service đấy
         * khám nhất ở thời điểm.
         * Kết quả trả về là ID của bác sĩ đó
         */
        private function getAverageAppointmentWithSpecialityId($specialityId)
        {
            /**Step 1 - khai báo cú pháp */
            $doctorWithQuantity = [];
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)

                    ->leftJoin(TABLE_PREFIX.TABLE_SPECIALITIES,
                                TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", TABLE_PREFIX.TABLE_DOCTORS.".speciality_id")

                    ->where(TABLE_PREFIX.TABLE_SPECIALITIES.".id", "=", $specialityId)
                    ->orderBy(TABLE_PREFIX.TABLE_DOCTORS.".id", "asc")
                    ->select(
                        DB::raw(TABLE_PREFIX.TABLE_DOCTORS.".id as doctor_id")
                    );
            
            $result = $query->get();
            
            $doctorQuantity = count($result);
            $appointmentQuantity = 0;

            /**Step 2 - tìm các doctorId với số lượng bệnh nhân hiện khám trong ngày */
            foreach($result as $element)
            {
                $appointmentQuantity += (int)$this->getCurrentAppointmentQuantityByDoctorId($element->doctor_id);
            }

            /**Step 3 - tính trung bình cộng */
            $averageQuantity = ceil($appointmentQuantity / $doctorQuantity);

            $this->resp->appointmentQuantity = $appointmentQuantity;
            $this->resp->doctorQuantity = $doctorQuantity;
            $this->resp->averageQuantity = $averageQuantity;

            return $averageQuantity;
        }

        /**
         * @author Phong-Kaster
         * @since 19-12-2022
         * hàm này sẽ tìm ra số lượng bệnh nhân hiện tại của bác sĩ trong 
         * ngày hôm nay và lượt khám đang là processing
         * trả về số lượng
         */
        private function getCurrentAppointmentQuantityByDoctorId($doctorId)
        {
            $today = Date("Y-m-d");// is used for storing the day when this appointment is created
            $query = DB::table(TABLE_PREFIX.TABLE_DOCTORS)

                    ->leftJoin(TABLE_PREFIX.TABLE_APPOINTMENTS,
                                TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", TABLE_PREFIX.TABLE_DOCTORS.".id")

                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".doctor_id", "=", $doctorId)
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".status", "=", "processing")
                    ->where(TABLE_PREFIX.TABLE_APPOINTMENTS.".date", "=", $today)
                    ->select(
                        DB::raw("COUNT(".TABLE_PREFIX.TABLE_APPOINTMENTS.".id) as quantity")
                    );
            $result = $query->get();
            $output = $result[0]->quantity;
            return $output;  
        }
    }
?>