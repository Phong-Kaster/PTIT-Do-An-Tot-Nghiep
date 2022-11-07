<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4"><!-- SECTION FILTER -->
            <div class="card-header"><strong>Bộ lọc tìm kiếm</strong></div>
                <div class="card-body">
                    <div class="example">

                        <div class="col-md-6 mb-3"><!-- 1. SEARCH - search -->
                            <div class="input-group ">
                                <span class="input-group-text ">
                                <i class="icon cil-magnifying-glass"></i></span>
                               
                                <input class="form-control" id="search" size="16" type="text" placeholder="Bạn đang cần tìm gì?">
                                <button id="button-search" class="btn btn-info" type="button">Tìm kiếm</button>
                                <button id="button-reset" class="btn btn-success" type="button">Làm mới</button>
                                <a href="<?= APPURL."/patient/create"?>" class="btn btn-dark" type="button">Tạo mới</a>
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
                                    <option value="name">Tên</option>
                                    <option value="gender">Giới tính</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[column] -->
                        

                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">
                    Danh sách bệnh nhân
                </div>
                    <div class="card-body">
              
                    <div class="table-responsive">
                        <table class="table border mb-0">
                            <thead class="table-light fw-semibold">
                                <tr class="align-middle">
                                    <th class="text-center">
                                        <i class="icon cil-people"></i>
                                    </th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
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