<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4"><!-- SECTION FILTER -->
            <div class="card-header"><strong>Bộ lọc tìm kiếm</strong></div>
                <div class="card-body">
                    <div class="example">

                        <div class="col-md-10 mb-3"><!-- 1. SEARCH - search -->
                            <div class="input-group ">
                                <span class="input-group-text ">
                                <i class="icon cil-magnifying-glass"></i></span>
                                <input class="form-control" id="search" size="16" type="text" placeholder="Bạn đang cần tìm gì?">
                                <button class="btn btn-primary" type="button" id="button-search">Tìm kiếm</button>
                                <button class="btn btn-danger" type="button" id="button-reset">Làm mới</button>
                            </div>
                        </div><!-- end 1. SEARCH - search -->


                        <div class="row mb-3"><!-- 2. ORDER dir | column | STATUS -->
                            <!-- order[dir] -->
                            <div class="col-md-4">
                                <label class="form-label" for="order-dir">Sắp xếp theo chiều</label>
                                <select class="form-select" id="order-dir" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="">Mặc định</option>
                                    <option value="asc">Từ trên xuống dưới</option>
                                    <option value="desc">Từ dưới lên trên</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->

                            <!-- order[column] -->
                            <div class="col-md-4">
                                <label class="form-label" for="order-column">Sắp xếp theo giá trị</label>
                                <select class="form-select" id="order-column" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="id">ID</option>
                                    <option value="speciality_id">Mã chuyên khoa</option>
                                    <option value="doctor_id">Tên bác sĩ</option>
                                    <option value="create_at">Thời gian tạo</option>
                                    <option value="update_at">Thời gian cập nhật lần cuối</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[column] -->

                            <div class="col-md-4"><!--4.2 DATE -->
                                <label class="form-label" for="datepicker">Ngày</label>
                                <p><input class="form-control rounded" type="text" autocomplete="off" id="datepicker"></p>
                            </div><!-- end 4.2 DATE -->

                        </div><!-- end 2. ORDER dir | column | date -->
                        
                        <?php if($AuthUser->get("role") == "admin" || 
                                         $AuthUser->get("role") == "supporter" ): ?>
                        <div class="row mb-3"><!-- 3. SPECIALITY | ROOM | DOCTOR -->
                            <div class="col-md-4"><!-- 3.1 SPECIALITY -->
                                <label class="form-label" for="speciality">Sắp xếp theo chuyên khoa</label>
                                <select class="form-select" id="speciality" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.1 SPECIALITY -->


                            
                            <div class="col-md-4"><!-- 3.3 SPECIALITY -->
                                <label class="form-label" for="doctor">Sắp xếp theo bác sĩ</label>
                                <select class="form-select" id="doctor" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.3 SPECIALITY -->
                        </div><!-- end 3. SPECIALITY | ROOM | DOCTOR -->
                        <?php endif; ?>


                        <div class="row"><!-- 4. DATE | LENGTH -->
                        <div class="col-md-4"><!--4.1 LENGTH -->
                                <label class="form-label" for="length">Số lượng kết quả trả về</label>
                                <select class="form-select" id="length" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="30">30</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 4.1 LENGTH -->
                            
                        </div><!-- end 4. DATE -->
                    </div>
                    </div>
                </div>
        </div><!-- end SECTION FILTER -->


        <!-- APPOINTMENT RECORD -->
        <div class="modal fade" id="record" tabindex="-1" aria-labelledby="record" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h4" id="title">
                            BỆNH ÁN</h5>
                        <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="body">
                        <p>What follows is just some placeholder text for this modal dialog. I feel like I'm already there. I’m gon’ put her in a coma. Boom, boom, boom. You're reading me like erotica, boy, you make me feel exotic, yeah. Happy birthday. From Tokyo to Mexico, to Rio. I knew you were.</p>
                        <p>Last Friday night. Calling out my name.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end APPOINTMENT RECORD-->
       
        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">
                    Danh sách bệnh án
                </div>
                    <div class="card-body">
              
                    <div class="table-responsive">
                        <table class="table border mb-0">
                            <thead class="table-light fw-semibold">
                                <tr class="align-middle">
                                <th class="text-center">
                                    <i class="icon cil-people"></i>
                                </th>
                                <th>Ngày khám</th>
                                <th>Họ tên</th>
                                <th class="text-center">Chuyên khoa</th>
                                <th>Nguyên nhân</th>
                                <th class="text-center">Bác sĩ</th>
                                <th>Hành động</th>
                                <th></th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <!-- <ul id="pagination" class="pagination justify-content-center"></ul>   -->
                    <ul id="pagination" class="pagination pagination justify-content-center">
                        <li id="button-previous" class="page-item page-link disabled">Previous</li>
                        <li id="current-page" class="page-item page-link">1</li>
                        <li id="button-next" class="page-item page-link">Next</li>
                    </ul>
              </div>
            </div><!-- end APPOINTMENT LIST-->
            
        </div>
    </div>
</div>