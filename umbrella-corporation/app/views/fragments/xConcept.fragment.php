<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4"><!-- SECTION FILTER -->
            <div class="card-header"><strong>Bộ lọc tìm kiếm</strong></div>
                <div class="card-body">
                    <div class="example">

                        <!-- <div class="tab-content rounded-bottom">
                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-927">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-primary id="button-search">Tìm kiếm</button>
                            <input class="form-control" type="text" placeholder="Hãy nhập nội dung tìm kiếm" aria-label="Example text with button addon" aria-describedby="button-addon1">
                        </div> -->

                        <div class="col-md-6 mb-3"><!-- 1. SEARCH - search -->
                            <div class="input-group ">
                                <span class="input-group-text ">
                                <i class="icon cil-magnifying-glass"></i></span>
                               
                                <input class="form-control" id="prependedInput" size="16" type="text" placeholder="Bạn đang cần tìm gì?">
                                <button class="btn btn-info" type="button">Search</button>
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
                                    <option value="asc">Tên bệnh nhân</option>
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
                                <label class="form-label" for="order-dir">Trạng thái</label>
                                <select class="form-select" id="order-dir" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="done">Hoàn thành</option>
                                    <option value="cancelled">Hủy bỏ</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->
                        </div><!-- end 2. ORDER dir | column | STATUS -->
                        

                        <div class="row mb-3"><!-- 3. SPECIALITY | ROOM | DOCTOR -->
                            
                            <div class="col-md-4"><!-- 3.1 SPECIALITY -->
                                <label class="form-label" for="order-column">Sắp xếp theo chuyên khoa</label>
                                <select class="form-select" id="order-column" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.1 SPECIALITY -->

                            <div class="col-md-4"><!-- 3.2 ROOM -->
                                <label class="form-label" for="order-column">Sắp xếp theo phòng</label>
                                <select class="form-select" id="order-column" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.2 ROOM -->

                            <div class="col-md-4"><!-- 3.3 SPECIALITY -->
                                <label class="form-label" for="order-column">Sắp xếp theo bác sĩ</label>
                                <select class="form-select" id="order-column" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.3 SPECIALITY -->
                        </div><!-- end 3. SPECIALITY | ROOM | DOCTOR -->
                        


                        <div class="row"><!-- 4. DATE | LENGTH -->
                        <div class="col-md-4"><!--4.1 LENGTH -->
                                <label class="form-label" for="order-column">Sắp xếp theo bác sĩ</label>
                                <select class="form-select" id="order-column" required="">
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
                                <label class="form-label" for="order-column">Ngày</label>
                                <p><input class="form-control rounded" type="text" id="datepicker"></p>
                            </div><!-- end 4.2 DATE -->
                        </div><!-- end 4. DATE -->
                    </div>
                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

        <div class="card mb-4">
        </div>
        
    </div>
</div>
</div>