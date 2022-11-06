<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Sửa lịch khám</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            
                                <div class="row mb-4"><!--1. SERVICE | BOOKING NAME | BOOKING PHONE-->
                                    <div class="col-md-4"><!-- 1.1 SPECIALITY -->
                                        <label class="form-label" for="service">Lựa chọn nhu cầu</label>
                                        <select class="form-select" id="service" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <!-- <option value="id">ID</option> -->
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.1 SPECIALITY -->

                                    <div class="col-md-4"><!--2.1 LENGTH -->
                                        <div class="mb-3">
                                            <label class="form-label" for="booking-name">Tên người đặt</label>
                                            <input class="form-control" id="booking-name" type="text" placeholder="">
                                        </div>
                                    </div><!-- end 2.1 LENGTH -->
                                    <div class="col-md-4"><!--2.1 LENGTH -->
                                        <div class="mb-3">
                                            <label class="form-label" for="booking-phone">Số điện thoại người đặt</label>
                                            <input class="form-control" id="booking-phone" type="number" placeholder="">
                                        </div>
                                    </div><!-- end 2.1 LENGTH -->
                                    
                                </div><!-- end 1. SERVICE | BOOKING NAME | BOOKING PHONE -->


                                <div class="row mb-4"><!-- 2. PATIENT NAME | PATIENT GENDER | PATIENT BIRTHDAY -->

                                        <div class="col-md-4"><!--2.1 LENGTH -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-name">Tên bệnh nhân</label>
                                                <input class="form-control" id="patient-name" type="text" placeholder="">
                                            </div>
                                        </div><!-- end 2.1 LENGTH -->

                                        <div class="col-md-4"><!-- 1.1 SPECIALITY -->
                                            <label class="form-label" for="patient-gender">Giới tính</label>
                                            <select class="form-select" id="patient-gender" required="">
                                                <option selected="" value="1">Nam</option>
                                                <option value="0">Nữ</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div><!-- end 1.1 SPECIALITY -->
                                        
                                        <div class="col-md-4"><!--2.3 PATIENT BIRTHDAY -->
                                            <label class="form-label" for="datepicker">Ngày sinh bệnh nhân</label>
                                            <p><input class="form-control rounded" type="text" id="patient-birthday"></p>
                                        </div><!-- end 2.3 PATIENT BIRTHDAY -->

                                </div><!-- end 2. PATIENT NAME | PATIENT GENDER | PATIENT BIRTHDAY -->




                                <div class="row mb-4"><!-- 3. PATIENT-ADDRESS -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="patient-address">Địa chỉ</label>
                                        <textarea class="form-control" id="patient-address" rows="1"></textarea>
                                    </div>
                                </div><!-- end 3. PATIENT-ADDRESS -->


                                <div class="row mb-4"><!-- 4. PATIENT-REASON -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="patient-reason">Mô tả bệnh lý</label>
                                        <textarea class="form-control" id="patient-reason" rows="3"></textarea>
                                    </div>
                                </div><!-- end 4. PATIENT-REASON -->


                                <div class="row mb-4"><!-- 5. APPOINTMENT-TIME | STATUS -->

                                    <div class="col-md-4"><!-- 4.1 APPOINTMENT-TIME -->
                                        <label class="form-label" for="datepicker">Ngày hẹn khám</label>
                                        <p><input class="form-control rounded" type="text" id="appointment-date"></p>
                                    </div><!-- end 4.1 APPOINTMENT-TIME -->

                                    <div class="col-md-4"><!-- 4.1 APPOINTMENT-TIME -->
                                        <label class="form-label" for="datepicker">Thời gian hẹn khám</label>
                                        <p><input class="form-control rounded" type="text" id="appointment-time"></p>
                                    </div><!-- end 4.1 APPOINTMENT-TIME -->


                                </div><!-- end 5. APPOINTMENT-TIME | STATUS -->

              
                                <div class="text-center"><!-- 5. BUTTON CONFIRM, RESET & CANCEL -->
                                    <button class="btn btn-outline-primary btn-lg btn-block col-sm-2" type="button" id="button-confirm">
                                        <i class="icon cil-check"></i>
                                        Xác nhận
                                    </button>
                                    <button class="btn btn-outline-success btn-lg btn-block col-sm-2" type="button" id="button-reset">
                                        <i class="icon cil-paint"></i>
                                        Làm mới
                                    </button>
                                    <button class="btn btn-outline-danger btn-lg btn-block col-sm-2" type="button" id="button-cancel">
                                        <i class="icon cil-compress"></i>
                                        Hủy bỏ
                                    </button>
                                </div><!-- end 5. BUTTON CONFIRM & CANCEL -->

                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>