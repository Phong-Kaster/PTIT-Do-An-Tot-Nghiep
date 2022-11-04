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
                                <?php if($AuthUser->get("role") == "admin" || 
                                         $AuthUser->get("role") == "supporter" ): ?>
                                    <a class="btn btn-success" type="button" href="<?= APPURL."/appointment/create" ?>">Tạo mới</a>
                                    <a class="btn btn-warning" type="button" href="<?= APPURL."/appointment/arrange" ?>">Sắp xếp thứ tự</a>
                                <?php endif; ?>
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
                                    <option value="numerical_order">Số thứ tự</option>
                                    <option value="patient_name">Tên bệnh nhân</option>
                                    <option value="position">Thứ tự lượt khám</option>
                                    <option value="date">Ngày khám</option>
                                    <option value="create_at">Thời gian tạo</option>
                                    <option value="update_at">Thời gian cập nhật lần cuối</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[column] -->

                            <!-- order[dir] -->
                            <div class="col-md-4">
                                <label class="form-label" for="status">Trạng thái</label>
                                <select class="form-select" id="status" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="done">Hoàn thành</option>
                                    <option value="cancelled">Hủy bỏ</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->
                        </div><!-- end 2. ORDER dir | column | STATUS -->
                        
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

                            <!-- 3.2 ROOM -->
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="order-column">Sắp xếp theo phòng</label>
                                <select class="form-select" id="order-column" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                            <!-- end 3.2 ROOM -->
                            
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
                            <div class="col-md-4"><!--4.2 DATE -->
                                <label class="form-label" for="datepicker">Ngày</label>
                                <p><input class="form-control rounded" type="text" id="datepicker"></p>
                            </div><!-- end 4.2 DATE -->
                        </div><!-- end 4. DATE -->
                    </div>
                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

       
        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">
                    Danh sách khám bệnh
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
                                <th class="text-center">Phòng khám</th>
                                <th>Tình trạng</th>
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