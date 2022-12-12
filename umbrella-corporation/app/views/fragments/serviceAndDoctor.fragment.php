<div class="body flex-grow-1 px-3">
    <div class="container-lg">


        <!-- FORM -->
        <div class="modal fade" id="form" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm bác sĩ vào dịch vụ khám</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-6"><!-- TYPE -->
                                <label class="col-form-label" for="doctor">Bác sĩ</label>
                                <select class="form-select" id="doctor" required="">
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div><!-- end TYPE -->                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button-confirm" class="btn btn-primary" type="button">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div><!-- FORM -->


        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">

                    Phía dưới là danh sách bác sĩ được biên chế cho dịch vụ này. Bạn có thể xóa hoặc ấn nút
                    <div class="btn-group" role="group">
                    <button id="button-form" class="btn btn-primary" ư
                            type="button" data-coreui-toggle="modal" data-coreui-target="#form" data-coreui-whatever="@fat">Thêm</button>
                    </div>
                    để thêm mới bác sĩ vào danh sách này.
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
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr data-id="1" class="align-middle">
                                    <td class="text-center" id="id">
                                        <div class="avatar avatar-md">
                                            <img class="avatar-img" src="http://localhost:8080/PTIT-Do-An-Tot-Nghiep/API/assets/uploads/avatar_doctor_1_1669026326.jpg" alt="avatar">
                                        </div>
                                    </td>

                                    <td class="fw-semibold">
                                    <div class="fw-semibold" id="speciality">Thần kinh</div>
                                    </td>

                                    <td>
                                    <div class="fw-semibold" id="name">Nguyễn Thị Thu Trang</div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold" id="email">phongkaster@gmail.com</div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold" id="phone">0123456789</div>
                                    </td>


                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <button id="button-delete" data-id="1" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                                        </div>
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