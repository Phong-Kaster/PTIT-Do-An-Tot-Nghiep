<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Sửa thông tin chuyên khoa</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                                <div class="row mb-4"><!--0. IMAGE & BUTTON UPLOAD | https://mdbcdn.b-cdn.net/img/new/avatars/5.webp -->
                                        <div class="row mb-3 mx-1">
                                            <img id="avatar" src="https://mdbcdn.b-cdn.net/img/new/avatars/5.webp" 
                                                style="width: 200px;" alt="Avatar" />
                                        </div>
                                        <div class="row col-md-4 mb-3 mx-1">
                                            <input class="mb-4" type="file" id="file" name="filename"
                                            accept="image/png, image/jpeg, image/jpg"/>
                                            <button class="file btn btn-secondary" id="button-avatar" type="button" >Cập nhật ảnh đại diện</button>
                                        </div>
                                </div><!-- end 0. IMAGE & BUTTON UPLOAD -->
                            
                                <div class="row mb-4"><!--1. SPECIALITY ID-->
                                    <div class="col-md-4"><!--2.1 LENGTH -->
                                        <div class="mb-3">
                                            <label class="form-label" for="id">Mã chuyên khoa</label>
                                            <input class="form-control" id="id" disabled type="text" placeholder="">
                                        </div>
                                    </div><!-- end 2.1 LENGTH -->
                                    
                                </div><!-- end 1. SPECIALITY ID -->

                                <div class="row mb-4"><!-- 2. SPECIALITY NAME -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="name">Tên chuyên khoa</label>
                                        <textarea class="form-control" id="name" rows="1"></textarea>
                                    </div>
                                </div><!-- end 2. SPECIALITY NAME -->


                                <div class="row mb-4"><!-- 3. SPECIALITY DESCRIPTION -->
                                    <div class="col-md-10">
                                        <label class="form-label" for="description">Mô tả chuyên khoa</label>
                                        <textarea class="form-control" id="description" rows="5" column="10"></textarea>
                                    </div>
                                </div><!-- end 3. SPECIALITY DESCRIPTION -->



              
                                <div class="text-center"><!-- 5. BUTTON CONFIRM, RESET & CANCEL -->
                                    <button class="btn btn-outline-primary btn-lg btn-block col-sm-2" type="button" id="button-confirm">
                                        <i class="icon cil-check"></i>
                                        Xác nhận
                                    </button>
                                    <button class="btn btn-outline-success btn-lg btn-block col-sm-2" type="button" id="button-reset">
                                        <i class="icon cil-paint"></i>
                                        Làm mới
                                    </button>
                                    <button class="btn btn-outline-danger btn-lg btn-block col-sm-2" type="button" id="button-cancel">
                                        <i class="icon cil-compress"></i>
                                        Hủy bỏ
                                    </button>
                                </div><!-- end 5. BUTTON CONFIRM & CANCEL -->

                        </div><!-- end tab-content -->
                    </div><!-- end EXAMPLE -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>