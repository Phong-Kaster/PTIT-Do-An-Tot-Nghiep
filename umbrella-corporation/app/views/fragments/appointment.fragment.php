<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Tạo hoặc chỉnh sửa lịch khám</strong>
                </div>

                <div class="card-body">
                    <p class="text-medium-emphasis small">
                        Để tạo lượt khám cho bệnh nhân, có 2 phương thức như sau:
                        <p class="text-medium-emphasis small">
                            1. Nếu bệnh nhân đến khám trực tiếp, hãy chọn mục 
                            <code> Nhu cầu khám bệnh </code>, hệ thống sẽ lựa chọn bác sĩ phù hợp để bệnh nhân được khám sớm nhất có thể
                        </p>
                        <p class="text-medium-emphasis small">
                            2. Nếu bệnh nhân đặt khám online và chỉ định bác sĩ thì sẽ được xếp khám với bác sĩ đó.
                        </p>
                        <p class="text-medium-emphasis small">
                            Trường hợp vừa chọn yêu cầu khám bệnh vừa chỉ định bác sĩ thì hệ thống sẽ ưu tiên xếp khám với bác sĩ đó.
                        </p>
                        <p class="text-medium-emphasis small">
                            Trường hợp vừa bệnh nhân đặt khám online muốn khám sớm nhất có thể, hãy lựa chọn <code> Để hệ thống lựa chọn </code> và chọn Yêu cầu khám bệnh tương ứng.
                        </p>
                    </p>
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                                <div class="row mb-4"><!-- SERVICE-->
                                    <div class="col-md-6">
                                        <label class="form-label" for="service"><code>Phương thức 1: </code> Tạo theo nhu cầu khám bệnh</label>
                                        <select class="form-select" id="service" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <option value="0">Để hệ thống lựa chọn</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div>
                                </div><!-- end SERVICE-->

                                <p>
                                    <code>Phương thức 2: </code> Chỉ định bác sĩ
                                </p>

                                <div class="row mb-4"><!--1. SPECIALITY | DOCTOR | DATE-->
                                    <div class="col-md-4"><!-- 1.1 SPECIALITY -->
                                    <label class="form-label" for="speciality">Chuyên khoa</label>
                                        <select class="form-select" id="speciality" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <!-- <option value="id">ID</option> -->
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.1 SPECIALITY -->

                                    <div class="col-md-4"><!-- 1.2 DOCTOR -->
                                        <label class="form-label" for="doctor">Bác sĩ</label>
                                        <select class="form-select" id="doctor" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <option value="0">Để hệ thống lựa chọn</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.2 DOCTOR -->

                                    <div class="col-md-4"><!--1.3 DATE -->
                                            <label class="form-label" for="datepicker">Ngày</label>
                                            <p><input autocomplete="off" class="form-control rounded" type="text" id="datepicker"></p>
                                        </div><!-- end 1.3 DATE -->
                                </div><!-- end 1. SPECIALITY | DOCTOR | DATE -->


                                <div class="row mb-4"><!-- 2. PATIENT ID | PATIENT NAME -->
                                        <div class="col-md-4"><!--2.1 LENGTH -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-id">Mã thẻ bảo hiểm y tế</label>
                                                <input class="form-control" id="patient-id" type="text" placeholder="Nhập 1 nếu là lượt khám thông thường">
                                            </div>
                                        </div><!-- end 2.1 LENGTH -->
                                        <div class="col-md-4"><!--2.1 LENGTH -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-name">Tên bệnh nhân</label>
                                                <input class="form-control" id="patient-name" type="text" placeholder="Nguyễn Văn A">
                                            </div>
                                        </div><!-- end 2.1 LENGTH -->
                                </div><!-- end 2. PATIENT ID | PATIENT NAME -->


                                <div class="row mb-4"><!-- 2. PATIENT PHONE | PATIENT BIRTHDAY -->
                                        <div class="col-md-4"><!--2.2 PATIENT PHONE -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-phone">Số điện thoại</label>
                                                <input class="form-control" id="patient-phone" type="number" placeholder="0979.999.999">
                                            </div>
                                        </div><!-- end 2.2 PATIENT PHONE -->
                                        <div class="col-md-4"><!--2.3 PATIENT BIRTHDAY -->
                                            <label class="form-label" for="datepicker">Ngày sinh bệnh nhân</label>
                                            <p><input autocomplete="off" class="form-control rounded" type="text" id="patient-birthday"></p>
                                        </div><!-- end 2.3 PATIENT BIRTHDAY -->
                                </div><!-- end 2. PATIENT PHONE | PATIENT BIRTHDAY -->



                                <div class="row mb-4"><!-- 3. PATIENT-REASON -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="patient-reason">Mô tả bệnh lý</label>
                                        <textarea class="form-control" id="patient-reason" rows="3"></textarea>
                                    </div>
                                </div><!-- end 3. PATIENT-REASON -->


                                <div class="row mb-4"><!-- 4. APPOINTMENT-TIME | STATUS -->

                                    <div class="col-md-4"><!-- 4.1 APPOINTMENT-TIME -->
                                        <label class="form-label" for="datepicker">Thời gian hẹn khám</label>
                                        <p><input autocomplete="off" class="form-control rounded" type="text" id="appointment-time"></p>
                                    </div><!-- end 4.1 APPOINTMENT-TIME -->

                                    <div class="col-md-4"><!-- 4.2 STATUS -->
                                        <label class="form-label" for="status">Trạng thái lịch khám </label>
                                        <select class="form-select" id="status" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <option value="processing" class="text-dark text-uppercase font-weight-bold">Đang xử lý</option>
                                            <option value="cancelled" class="text-warning text-uppercase font-weight-bold">Hủy</option>
                                            <option value="done" class="text-success text-uppercase font-weight-bold">Xong</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- 4.2 STATUS -->
                                </div><!-- end 4. APPOINTMENT-TIME | STATUS -->

              
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