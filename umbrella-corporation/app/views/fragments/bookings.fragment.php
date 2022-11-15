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
                               
                                <input class="form-control" id="search" size="16" type="text" placeholder="Bạn đang cần tìm gì?">
                                <button id="button-search" class="btn btn-info" type="button">Tìm kiếm</button>
                                <button id="button-reset" class="btn btn-success" type="button">Làm mới</button>
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
                                    <option value="service_id">Dịch vụ</option>
                                    <option value="booking_name">Tên người đặt lịch hẹn</option>
                                    <option value="name">Tên bệnh nhân</option>
                                    <option value="appointment_time">Ngày hẹn khám</option>
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
                                    <option value="verified">Đã xác thực</option>
                                    <option value="cancelled">Hủy</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->
                        </div><!-- end 2. ORDER dir | column | STATUS -->
                        

                        <div class="row mb-3"><!-- 3. SERVICE | DATE -->
                            
                            <div class="col-md-4"><!-- 3.1 SPECIALITY -->
                                <label class="form-label" for="service">Sắp xếp theo dịch vụ</label>
                                <select class="form-select" id="service" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.1 SPECIALITY -->

                            <div class="col-md-4"><!--3.2 DATE -->
                                <label class="form-label" for="order-column">Ngày khám</label>
                                <p><input class="form-control rounded" type="text" id="datepicker"></p>
                            </div><!-- end 3.2 DATE -->

                        </div><!-- end  3. SERVICE | DATE -->
                        
                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">
                    Danh sách phác đồ điều trị - đơn thuốc
                </div>
                    <div class="card-body">
              
                    <div class="table-responsive">
                        <table class="table border mb-0">
                            <thead class="table-light fw-semibold">
                                <tr class="align-middle">
                                    <th class="text-center">
                                        <i class="icon cil-people"></i>
                                    </th>
                                    <th>Dịch vụ</th>
                                    <th>Giờ hẹn</th>
                                    <th>Tên người đặt</th>
                                    <th>Tên bệnh nhân</th>
                                    <th>Trạng thái</th>
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