<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Sửa thông tin bác sĩ</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            <div class="row mb-4"><!--1. AVATAR & BUTTON UPLOAD | https://mdbcdn.b-cdn.net/img/new/avatars/5.webp -->
                                    <div class="row mb-3 mx-1">
                                        <img id="avatar" src="https://mdbcdn.b-cdn.net/img/new/avatars/5.webp" 
                                            class="rounded-circle mb-3" style="width: 200px;" alt="Avatar" />
                                    </div>
                                    <div class="row col-md-4 mb-3 mx-1">
                                        <input class="mb-4" type="file" id="file" name="filename"
                                        accept="image/png, image/jpeg, image/jpg"/>
                                        <button class="file btn btn-secondary" id="button-avatar" type="button" >Cập nhật ảnh đại diện</button>
                                    </div>
                            </div><!-- end 1. AVATAR & BUTTON UPLOAD -->

                                <div class="row mb-4"><!-- 2. PATIENT ID  -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="id">Mã số bác sĩ</label>
                                                <input class="form-control" id="id" disabled type="text" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" type="email" autocomplete="off" placeholder="example@gmail.com">
                                            </div>
                                        </div>
                                </div><!-- end 2. PATIENT ID  -->

                                <div class="row mb-4"><!-- 3. NAME | SPECIALITY | ROOM -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Tên</label>
                                                <input class="form-control" id="name" type="text" autocomplete="off" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-4"><!-- SPECIALITY -->
                                            <label class="form-label" for="speciality">Chuyên khoa</label>
                                            <select class="form-select" id="speciality" required="">
                                                <option selected="" disabled="" value="">Chọn...</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div><!-- SPECIALITY -->
                                        <div class="col-md-4"><!-- ROOM -->
                                            <label class="form-label" for="room">Phòng khám</label>
                                            <select class="form-select" id="room" required="">
                                                <option selected="" disabled="" value="">Chọn...</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div><!-- ROOM -->
                                </div><!-- end 3. NAME | SPECIALITY | ROOM-->


                                <div class="row mb-4"><!-- 4. PHONE | PRICE  -->
                                    <div class="col-md-4"><!-- PHONE -->
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Số điện thoại</label>
                                            <input class="form-control" id="phone" type="number" placeholder="">
                                        </div>
                                    </div><!-- end PHONE -->
                                    <div class="col-md-4"><!-- PRICE -->
                                        <div class="mb-3">
                                            <label class="form-label" for="price">Giá</label>
                                            <input class="form-control" id="price" type="number" value="159000">
                                        </div>
                                    </div><!-- end PRICE -->
                                    
                                </div><!-- end 4. PHONE | PRICE  -->



                                <div class="row mb-4"><!-- 6. DESCRIPTION -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="description">Mô tả</label>
                                        <textarea class="form-control" id="description" rows="3"></textarea>
                                    </div>
                                </div><!-- end 6. DESCRIPTION -->

                                <div class="row mb-4"><!-- 7. ACTIVE -->
                                        <div class="col-md-4"><!-- ACTIVE -->
                                            <label class="form-label" for="active">Trạng thái</label>
                                            <select class="form-select" id="active" required="">
                                                <option selected="" disabled="" value="">Chọn...</option>
                                                <option value="1">Hoạt động</option>
                                                <option value="0">Vô hiệu hóa</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div><!-- end ACTIVE -->
                                        <div class="col-md-4"><!-- ACTIVE -->
                                            <label class="form-label" for="role">Vai trò</label>
                                            <select class="form-select" id="role" required="">
                                                <option selected="" disabled="" value="">Chọn...</option>
                                                <option value="admin">Trưởng khoa</option>
                                                <option value="supporter">Hỗ trợ viên</option>
                                                <option value="member">Bác sĩ</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div><!-- end ACTIVE -->

                                </div><!-- end 7. ACTIVE -->

                                <div class="row mb-4"><!-- 7. CREATE AT | UPDATE AT -->
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
                                </div><!-- end 7. CREATE AT | UPDATE AT -->

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