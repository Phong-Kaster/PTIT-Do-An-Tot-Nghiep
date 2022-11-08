<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Sửa thông tin bệnh nhân</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            <div class="row mb-4"><!--1. AVATAR & BUTTON UPLOAD | https://mdbcdn.b-cdn.net/img/new/avatars/5.webp -->
                                <div class="row mb-3 mx-1">
                                    <img id="avatar" src="http://localhost:8080/PTIT-Do-An-Tot-Nghiep/api/assets/uploads/avatar_1_1667399080.png?v=040300" 
                                        class="rounded-circle mb-3" style="width: 200px;" alt="Avatar" />
                                </div>
                        </div><!-- end 1. AVATAR & BUTTON UPLOAD -->

                                <div class="row mb-4"><!-- 2. PATIENT ID  -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="id">Mã thẻ bảo hiểm y tế</label>
                                                <input class="form-control" id="id" disabled type="text" placeholder="">
                                            </div>
                                        </div>
                                </div><!-- end 2. PATIENT ID  -->

                                <div class="row mb-4"><!-- 3. NAME | EMAIL -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Tên</label>
                                                <input class="form-control" id="name" type="text" autocomplete="off" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" type="email" autocomplete="off" disabled placeholder="example@gmail.com">
                                            </div>
                                        </div>
                                </div><!-- end 3. NAME | EMAIL-->

                                <div class="row mb-4"><!-- 4. PHONE | GENDER  -->
                                    <div class="col-md-4"><!-- PHONE -->
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Số điện thoại</label>
                                            <input class="form-control" id="phone" type="number" placeholder="">
                                        </div>
                                    </div><!-- end PHONE -->
                                    <div class="col-md-4"><!-- GENDER -->
                                        <label class="form-label" for="gender">Giới tính</label>
                                        <select class="form-select" id="gender" required="">
                                            <option value="1" >Nam</option>
                                            <option value="0" >Nữ</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- GENDER -->
                                </div><!-- end 4. PHONE | GENDER  -->

                                <div class="row mb-4"><!-- 5. BIRTHDAY  -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="birthday">Ngày sinh</label>
                                            <input class="form-control" id="birthday" type="text" placeholder="">
                                        </div>
                                    </div>
                                </div><!-- end 5. BIRTHDAY  -->


                                <div class="row mb-4"><!-- 5. ADDRESS -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="address">Địa chỉ</label>
                                        <textarea class="form-control" id="address" rows="1"></textarea>
                                    </div>
                                </div><!-- end 5. ADDRESS -->

                                <div class="row mb-4"><!-- 6. CREATE AT | UPDATE AT -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="create-at">Khởi tạo</label>
                                                <input class="form-control" id="create-at" disabled type="text" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="update-at">Cập nhật lần cuối</label>
                                                <input class="form-control" id="update-at" disabled type="text" placeholder="">
                                            </div>
                                        </div>
                                </div><!-- end 6. CREATE AT | UPDATE AT -->

                                <div class="d-grid gap-2 col-3 mx-auto"><!-- 7. BUTTON -->
                                    <button id="button-save" class="btn btn-primary" type="button">Lưu lại</button>
                                </div><!-- end 7. BUTTON -->

                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>