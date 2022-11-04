<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4"><!-- SECTION FILTER -->
            <div class="card-header"><strong>Bộ lọc tìm kiếm</strong></div>
                <div class="card-body">
                    <div class="example">


                        <div class="row mb-3"><!-- 1. SPECIALITY | ROOM | DOCTOR -->
                            <!-- order[dir] -->
                            <div class="col-md-4">
                                <label class="form-label" for="speciality">Sắp xếp chuyên khoa</label>
                                <select class="form-select" id="speciality" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->

                            <!-- order[column] -->
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="room">Sắp xếp phòng khám</label>
                                <select class="form-select" id="room" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                            <!-- end order[column] -->

                            <!-- order[dir] -->
                            <div class="col-md-4">
                                <label class="form-label" for="doctor">Sắp xếp theo bác sĩ</label>
                                <select class="form-select" id="doctor" required="">
                                    <option selected="" disabled="" value="">Chọn...</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- end order[dir] -->

                            <div class="col-md-4">
                                    <div class="row">
                                    <label class="form-label" for="doctor">Chức năng</label>
                                    </div>
                                    <div>
                                    <button class="btn btn-info" type="button" id="button-search">
                                        <i class="icon cil-search"></i>
                                        Lọc
                                    </button>
                                    <button class="btn btn-success" type="button" id="button-reset">
                                        <i class="icon cil-paint"></i>
                                        Làm mới
                                    </button>
                                    <button class="btn btn-dark" type="button" id="button-save">
                                        <i class="icon cil-save"></i>
                                        Lưu thứ tự
                                    </button>
                                    </div>
                                    
                                    
                                
                            </div>
                        </div><!-- end 1. SPECIALITY | ROOM | DOCTOR -->
                       
                    </div>
                </div>
        </div><!-- end SECTION FILTER -->

       
        <div class="row"><!-- APPOINTMENTS LIST -->
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">
                    Danh sách khám bệnh
                </div>

                    

                    <div class="card-body"><!-- SORTABLE APPOINTMENT LIST -->
                            <div class="table-responsive">
                                <div class="container mb-4"><!-- TITLE -->
                                    <div class="row">
                                        <div class="col-sm-1 text-center">
                                            <div class="fw-semibold text-center">STT</div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="fw-semibold" id="patient-name">Họ tên</div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="clearfix">
                                                <div class="fw-semibold" id="patient-reason">Mô tả</div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="fw-semibold" id="patient-name">Ngày sinh</div>
                                        </div>

                                        <div class="col">
                                            <div class="fw-semibold" id="patient-name">Thời gian hẹn khám</div>
                                        </div>
                                    </div>
                                </div><!-- end TITLE -->      

                                <div id="appointmentList" class="list-group col">
                                    <div class="containe list-group-item"><!-- item -->
                                        <div class="row">
                                            <div class="col-sm-1 text-center">
                                               <div class="text-center"> 10</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="fw-semibold" id="patient-name">Jeffrey Dahmer</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="clearfix">
                                                    <div class="fw-semibold" id="patient-reason">Tâm thần phân liệt</div>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022</div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022 09:00</div>
                                            </div>
                                        </div>
                                    </div><!-- end item -->
                                    <div class="containe list-group-item"><!-- item -->
                                        <div class="row">
                                            <div class="col-sm-1 text-center">
                                               <div class="text-center"> 10</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="fw-semibold" id="patient-name">Jeffrey Dahmer</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="clearfix">
                                                    <div class="fw-semibold" id="patient-reason">Tâm thần phân liệt</div>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022</div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022 09:00</div>
                                            </div>
                                        </div>
                                    </div><!-- end item -->
                                    <div class="containe list-group-item"><!-- item -->
                                        <div class="row">
                                            <div class="col-sm-1 text-center">
                                               <div class="text-center"> 10</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="fw-semibold" id="patient-name">Jeffrey Dahmer</div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="clearfix">
                                                    <div class="fw-semibold" id="patient-reason">Tâm thần phân liệt</div>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022</div>
                                            </div>

                                            <div class="col">
                                                <div class="fw-semibold" id="patient-name">01-05-2022 09:00</div>
                                            </div>
                                        </div>
                                    </div><!-- end item -->
                                </div>
                            </div>
                    </div><!-- end SORTABLE APPOINTMENT LIST -->
                </div>
              </div>
            </div><!-- end APPOINTMENT LIST-->
            
        </div>

            
        </div>
    </div>
</div>