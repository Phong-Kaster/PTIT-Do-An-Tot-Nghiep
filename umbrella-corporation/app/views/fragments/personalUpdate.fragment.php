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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" disabled type="email" autocomplete="off" placeholder="example@gmail.com">
                                            </div>
                                        </div>
                                </div><!-- end 2. PATIENT ID  -->

                                <div class="row mb-4"><!-- 3. NAME  -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Tên</label>
                                                <input class="form-control" id="name" type="text" autocomplete="off" placeholder="">
                                            </div>
                                        </div>
                                </div><!-- end 3. NAME -->


                                <div class="row mb-4"><!-- 4. PHONE  -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Số điện thoại</label>
                                            <input class="form-control" id="phone" type="number" placeholder="">
                                        </div>
                                    </div>
                                </div><!-- end 4. PHONE | PRICE  -->



                                <div class="row mb-4"><!-- 5. DESCRIPTION -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="description">Mô tả</label>
                                        <textarea class="form-control" id="description" rows="3"></textarea>
                                    </div>
                                </div><!-- end 5. DESCRIPTION -->

                                <div class="d-grid gap-2 col-3 mx-auto"><!-- 6. BUTTON -->
                                    <button id="button-save" class="btn btn-primary" type="button">Lưu lại</button>
                                </div><!-- end 6. BUTTON -->

                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>