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
                                <a class="btn btn-success" type="button" href="<?= APPURL."/doctor/create" ?>">Tạo mới</a>

                            </div>
                        </div><!-- end 1. SEARCH - search -->


                        <div class="row mb-3"><!-- 2. ORDER dir | column | STATUS -->
                            <!-- order[dir] -->
                            <div class="col-md-4">
                                <label class="form-label" for="order-dir">Sắp xếp theo chiều</label>
                                <select class="form-select" id="order-dir" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <option value="asc">Mặc định</option>
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
                                    <option value="email">Email</option>
                                    <option value="name">Họ tên</option>
                                    <option value="price">Mức giá</option>
                                    <option value="role">Vai trò</option>
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
                                    <option value="1">Đang hoạt động</option>
                                    <option value="0">Vô hiệu hóa</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->
                        </div><!-- end 2. ORDER dir | column | STATUS -->
                        

                        <div class="row mb-3"><!-- 3. SPECIALITY | ROOM  -->
                            <div class="col-md-4"><!-- 3.1 SPECIALITY -->
                                <label class="form-label" for="speciality">Sắp xếp theo chuyên khoa</label>
                                <select class="form-select" id="speciality" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.1 SPECIALITY -->

                            
                            <div class="col-md-4"><!-- 3.2 ROOM -->
                                <label class="form-label" for="room">Sắp xếp theo phòng khám</label>
                                <select class="form-select" id="room" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                    <!-- <option value="id">ID</option> -->
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end 3.2 ROOM -->
                        </div><!-- end 3. SPECIALITY | ROOM -->

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
                                <th>Chuyên khoa</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th></th>
                                </tr>
                            </thead>

                            <tbody>
                            <tr data-id="144" class="align-middle">
                                <td class="text-center" id="id">
                                    <div class="avatar avatar-md">
                                        <img class="avatar-img" src="http://localhost:8080/PTIT-Do-An-Tot-Nghiep/API/assets/uploads/avatar_1_1667918148.jpg" alt="Miu Lê">
                                    </div>
                                </td>
            
                                <td class="fw-semibold">
                                <div class="fw-semibold" id="speciality">Ngoại khoa</div>
                                </td>

                                <td>
                                <div class="fw-semibold" id="name">Nguyễn Thành Phong</div>
                                <div class="small text-medium-emphasis fw-semibold" id="patient-gender-birthday">Ngày sinh: 2000-01-01</div>
                                </td>
            
                                <td class="text-center">
                                <div class="fw-semibold" id="phone">0366253623</div>
                                </td>
            
                                <td>
                                    <div class="clearfix">
                                        <div class="fw-semibold" id="role">Trưởng khoa</div>
                                    </div>
                                </td>
            
                                <td >
                                    <div class="clearfix">
                                        <i class="text-center bi bi-shield-fill-check text-success" alt="Đang hoạt động"></i>
                                    </div>
                                </td>
            

            
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <a id="button-update" data-id="144" class="btn btn-outline-primary" type="button">Sửa</a>
                                    <button id="button-delete" data-id="144" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                                    <button class="btn btn-outline-info" data-coreui-toggle="collapse" href="#appointment-144" aria-expanded="false" aria-controls="#appointment-143">Chi tiết</button>
                                </td>
                            </tr>
                            <tr data-id="144" class="collapse" id="appointment-144">
                                <td colspan="9">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">ID</th>
                                            <th class="text-center" scope="col">Email</th>
                                            <th class="text-center" scope="col">Giá</th>
                                            <th class="text-center" scope="col">Phòng khám</th>
                                            <th class="text-center" scope="col">Khởi tạo</th>
                                            <th class="text-center" scope="col">Cập nhật lần cuối</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="align-middle">
                                            <td class="text-center" id="appointment-id">1</td>
                                            <td class="text-center" id="appointment-date">phongkaster@gmail.com</td>
                                            <td class="text-center" id="appointment-date">149000</td>
                                            <td class="text-center" id="appointment-date">Khu A, tầng 1, phòng 240</td>
                                            <td class="text-center" id="appointment-date">2022-11-04 21:37:27</td>
                                            <td class="text-center" id="appointment-date">2022-11-04 21:37:27</td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                            </tr>
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