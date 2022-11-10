<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Thông tin cá nhân</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            <div class="row mb-4"><!--1. AVATAR & BUTTON UPLOAD | https://mdbcdn.b-cdn.net/img/new/avatars/5.webp -->
                                    <div class="row mb-3 mx-1">
                                        <img id="avatar" src="https://mdbcdn.b-cdn.net/img/new/avatars/5.webp" 
                                            class="rounded-circle mb-3" style="width: 200px;" alt="Avatar" />
                                    </div>
                            </div><!-- end 1. AVATAR & BUTTON UPLOAD -->

                                <div class="row mb-4"><!-- 2. PATIENT ID  -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="id">Mã số bác sĩ</label>
                                                <p id="id"><strong>1</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email</label>
                                                <p id="email"><strong>example@gmail.com</strong></p>
                                            </div>
                                        </div>
                                </div><!-- end 2. PATIENT ID  -->

                                <div class="row mb-4"><!-- 3. NAME | SPECIALITY | ROOM -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Tên</label>
                                                <p id="name"><strong>Nguyễn Thành Phong</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4"><!-- SPECIALITY -->
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Chuyên khoa</label>
                                                <p id="name"><strong>Ngoại khoa</strong></p>
                                            </div>
                                        </div><!-- SPECIALITY -->
                                        <div class="col-md-4"><!-- ROOM -->
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Phòng</label>
                                                <p id="name"><strong>Khu D, tầng 3, phòng 303</strong></p>
                                            </div>
                                        </div><!-- ROOM -->
                                </div><!-- end 3. NAME | SPECIALITY | ROOM-->


                                <div class="row mb-4"><!-- 4. PHONE | PRICE  -->
                                    <div class="col-md-4"><!-- PHONE -->
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Số điện thoại</label>
                                            <p id="name"><strong>0366253623</strong></p>
                                        </div>
                                    </div><!-- end PHONE -->
                                    <div class="col-md-4"><!-- PRICE -->
                                        <div class="mb-3">
                                            <label class="form-label" for="price">Giá</label>
                                            <p id="name"><strong>0</strong></p>
                                        </div>
                                    </div><!-- end PRICE -->
                                    
                                </div><!-- end 4. PHONE | PRICE  -->



                                <div class="row mb-4"><!-- 6. DESCRIPTION -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="description">Mô tả</label>
                                        <p id="description" rows="3"><strong>Hello</strong></p>
                                    </div>
                                </div><!-- end 6. DESCRIPTION -->

                                <div class="row mb-4"><!-- 7. ACTIVE -->
                                        <div class="col-md-4"><!-- ACTIVE -->
                                            <label class="form-label" for="active">Trạng thái</label>
                                            <p id="active"><strong>Đang hoạt động</strong></p>
                                        </div><!-- end ACTIVE -->
                                        <div class="col-md-4"><!-- ACTIVE -->
                                            <label class="form-label" for="role">Vai trò</label>
                                            <p id="role"><strong>Bác sĩ</strong></p>
                                        </div><!-- end ACTIVE -->

                                </div><!-- end 7. ACTIVE -->

                                <div class="row mb-4"><!-- 8. CREATE AT | UPDATE AT -->
                                        <div class="col-md-4">
                                            
                                                <label class="form-label" for="create-at">Khởi tạo</label>
                                                <p id="create-at"><strong>2022-11-10 11:13</strong></p>
                                    
                                        </div>
                                        <div class="col-md-4">

                                                <label class="form-label" for="update-at">Cập nhật lần cuối</label>
                                                <p id="update-at"><strong>2022-11-10 11:13</strong></p>
                                        </div>
                                </div><!-- end 8. CREATE AT | UPDATE AT -->

                                <div class="d-grid gap-2 col-3 mx-auto"><!-- 9. BUTTON -->
                                    <a href="<?= APPURL."/personal/update" ?>" class="btn btn-primary" type="button">Cập nhật thông tin</a>
                                </div><!-- end 9. BUTTON -->

                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>