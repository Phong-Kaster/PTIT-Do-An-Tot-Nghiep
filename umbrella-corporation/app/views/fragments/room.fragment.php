<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        
            <div class="card mb-4">
                <div class="card-header"><strong>Tạo - sửa thông tin phòng khám</strong>
                </div>

                <div class="card-body">
                    <div class="example">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                                
                            
                                <div class="row mb-4"><!--1. AREA | FLOOR | NAME -->
                                    <div class="col-md-4"><!-- 1.1 AREA -->
                                        <label class="form-label" for="area">Khu vực</label>
                                        <select class="form-select" id="area" required="">
                                            <option value="">Khu A</option>
                                            <option value="">Khu B</option>
                                            <option value="">Khu C</option>
                                            <!-- <option value="id">ID</option> -->
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.1 AREA -->

                                    <div class="col-md-4"><!-- 1.2 FLOOR -->
                                        <label class="form-label" for="floor">Tầng</label>
                                        <select class="form-select" id="floor" required="">
                                            <option>Tầng 1</option>
                                            <option>Tầng 2</option>
                                            <option>Tầng 3</option>
                                            <option>Tầng 4</option>
                                            <option>Tầng 5</option>
                                           
                                            <!-- <option value="id">ID</option> -->
                                        </select>
                                        <div class="invalid-feedback">Please select a valid state.</div>
                                    </div><!-- end 1.2 FLOOR -->

                                    <div class="col-md-4"><!--1.3 NAME -->
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Tên phòng</label>
                                            <input class="form-control" id="name" type="text" placeholder="">
                                        </div>
                                    </div><!-- end 1.3 NAME -->
                                    
                                </div><!-- end 1. AREA | FLOOR | NAME -->

                                
                                <div class="row mb-4"><!-- 2. COMPLETE NAME -->
                                    <div class="col-md-5">
                                        <label class="form-label" for="complete-location">Địa chỉ</label>
                                        <input class="form-control" id="complete-location" disabled rows="1"></input>
                                    </div>
                                </div><!-- end 2. COMPLETE NAME -->

                                <div class="d-grid gap-2 col-6 mx-auto"><!-- 5. BUTTON CONFIRM, RESET & CANCEL -->
                                <button class="btn btn-outline-primary btn-lg btn-block" type="button" id="button-confirm">
                                        <i class="icon cil-check"></i>
                                        Xác nhận
                                    </button>
                                    <button class="btn btn-outline-danger btn-lg btn-block" type="button" id="button-cancel">
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