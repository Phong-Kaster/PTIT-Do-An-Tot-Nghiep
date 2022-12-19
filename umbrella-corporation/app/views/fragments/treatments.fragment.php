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
                                <button id="button-form" class="btn btn-primary" type="button" data-coreui-toggle="modal" data-coreui-target="#form" data-coreui-whatever="@fat">Tạo mới</button>
                            </div>
                        </div><!-- end 1. SEARCH - search -->

                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

        <!-- FORM -->
        <div class="modal fade" id="form" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Viết phác đồ điều trị - phác đồ</h5>
                        <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="mb-6"><!-- NAME -->
                                <label class="col-form-label" for="id">ID</label>
                                <input class="form-control" disabled id="id" type="text">
                            </div><!-- end NAME -->

                            

                            <div class="mb-6"><!-- TYPE -->
                                <label class="col-form-label" for="name">Tên thuốc</label>
                            </div><!-- end TYPE -->
                            <div class="mb-6"><!-- TYPE -->
                                <select class="js-example-basic-single" id="name" name="state">
                                    
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            

                            <div class="mb-6"><!-- TYPE -->
                                <label class="col-form-label" for="type">Hình thức thực hiện</label>
                                <select class="form-select" id="type" required="">
                                    <option selected="" value="Uống">Uống</option>
                                    <option value="Tiêm">Tiêm</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end TYPE -->


                            <div class="mb-6"><!-- TIMES -->
                                <label class="col-form-label" for="times">Số lần thực hiện</label>
                                <input class="form-control" id="times" type="number">
                            </div><!-- end TIMES -->


                            <div class="mb-6"><!-- PURPOSE -->
                                <label class="col-form-label" for="purpose">Tác dụng</label>
                                <textarea class="form-control" id="purpose" type="text"></textarea>
                            </div><!-- end PURPOSE -->

                            <div class="mb-6"><!-- INSTRUCTION -->
                                <label class="col-form-label" for="instruction">Hướng dẫn</label>
                                <textarea class="form-control" id="instruction" type="text"></textarea>
                            </div><!-- end INSTRUCTION -->

                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button-close" class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Đóng</button>
                        <button id="button-create" class="btn btn-primary" type="button">Tạo</button>
                        <button id="button-update-confirm" class="btn btn-primary" type="button">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div><!-- FORM -->

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
                                    <th>Tên</th>
                                    <th>Hình thức thực hiện</th>
                                    <th>Số lần thực hiện</th>
                                    <th>Mục đích</th>
                                    <th>Hướng dẫn</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                            <tr data-id="159" class="align-middle">
                                <td class="text-center" id="id">
                                    159
                                </td>
            
                                <td class="fw-semibold">
                                    <div class="fw-semibold" id="name">Pharacetamon</div>
                                </td>

                                <td>
                                    <div class="fw-semibold" id="type">Tiêm</div>
                                </td>
            
                                <td>
                                    <div class="fw-semibold" id="speciality-name">10</div>
                                </td>
            
                                <td>
                                    <div class="clearfix">
                                        <div class="fw-semibold" id="purpose">Giảm đau, an thần</div>
                                    </div>
                                </td>
            
                                <td>
                                    <div class="clearfix">
                                        <div class="fw-semibold" id="purpose">Uống sau ăn 30 phút</div>
                                    </div>
                                </td>
            
                                
            
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button id="button-done" data-id="159" class="btn btn-outline-info" type="button">Xem</button>
                                        <button id="button-update" data-id="159" class="btn btn-outline-warning" type="button">Sửa</button>
                                        <button id="button-delete" data-id="159" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div><!-- end APPOINTMENT LIST-->
        
    </div>
</div>
</div>