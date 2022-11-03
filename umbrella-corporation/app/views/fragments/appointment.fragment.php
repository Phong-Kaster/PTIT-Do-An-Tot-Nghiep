<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Tạo hoặc chỉnh sửa lịch khám</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            
                                <div class="row mb-4"><!-- SPECIALITY | DOCTOR -->
                                    <div class="col-md-4"><!-- 1.1 SPECIALITY -->
                                        <label class="form-label" for="speciality">Lọc theo chuyên khoa</label>
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
                                            <!-- <option value="id">ID</option> -->
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.2 DOCTOR -->
                                </div>


                                <div class="row mb-4"><!-- 2. PATIENT NAME | PHONE | DATE -->
                                        <div class="col-md-4"><!--2.1 LENGTH -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-name">Tên bệnh nhân</label>
                                                <input class="form-control" id="patient-name" type="email" placeholder="Nguyễn Văn A">
                                            </div>
                                        </div><!-- end 2.1 LENGTH -->
                                        <div class="col-md-4"><!--2.2 PHONE -->
                                            <div class="mb-3">
                                                <label class="form-label" for="patient-phone">Số điện thoại</label>
                                                <input class="form-control" id="patient-phone" type="email" placeholder="">
                                            </div>
                                        </div><!-- end 2.2 PHONE -->
                                        <div class="col-md-4"><!--2.3 DATE -->
                                            <label class="form-label" for="datepicker">Ngày</label>
                                            <p><input class="form-control rounded" type="text" id="datepicker"></p>
                                        </div><!-- end 2.3 DATE -->
                                </div><!-- end 2. PATIENT NAME | PHONE | DATE -->


                                <div class="row mb-4"><!-- 3. PATIENT-REASON -->
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label" for="patient-reason">Mô tả bệnh lý</label>
                                            <textarea class="form-control" id="patient-reason" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div><!-- end 3. PATIENT-REASON -->

                                <div class="row mb-4"><!-- 4. APPOINTMENT-TIME | STATUS -->

                                    <div class="col-md-4"><!-- 4.1 APPOINTMENT-TIME -->
                                        <label class="form-label" for="datepicker">Thời gian hẹn khám</label>
                                        <p><input class="form-control rounded" type="text" id="appointment-time"></p>
                                    </div><!-- end 4.1 APPOINTMENT-TIME -->

                                    <div class="col-md-4"><!-- 4.2 STATUS -->
                                        <label class="form-label" for="order-dir">Trạng thái lịch khám </label>
                                        <select class="form-select" id="order-dir" required="">
                                            <option selected="" disabled="" value="">Chọn...</option>
                                            <option value="processing" class="text-dark text-uppercase font-weight-bold">Đang xử lý</option>
                                            <option value="cancelled" class="text-warning text-uppercase font-weight-bold">Hủy</option>
                                            <option value="done" class="text-success text-uppercase font-weight-bold">Xong</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- 4.2 STATUS -->
                                </div><!-- end 4. APPOINTMENT-TIME | STATUS -->

                                <div class="row mb-8 center">
                                        <button class="btn btn-primary btn-lg" type="button">Xác nhận</button>    
                                        <button class="btn btn-danger btn-lg" type="button">Hủy</button>
                                </div>
                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>