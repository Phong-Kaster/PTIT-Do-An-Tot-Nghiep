<div class="body flex-grow-1 px-3">
        <div class="container-lg">

          <!-- 4 TILES -->
          <div class="row">
            <!-- USER TILE -->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-primary">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div id="doctor-quantity" class="fs-4 fw-semibold">0</div>
                    <div>Bác sĩ</div>
                  </div>
                  
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart1" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- end USER TILE -->

            <!-- INCOME TILE -->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-info">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div id="current-appointment-quantity" class="fs-4 fw-semibold">0</div>
                    <div>Số lượt bệnh nhân hôm nay</div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart2" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- end INCOME TILE-->

            <!-- CONVERSION RATE -->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-warning">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div id="current-booking-quantity" class="fs-4 fw-semibold">0</div>
                    <div>Tổng lịch hẹn hôm nay</div>
                  </div>
                  
                </div>
                <div class="c-chart-wrapper mt-3" style="height:70px;">
                  <canvas class="chart" id="card-chart3" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- end CONVERSION RATE -->
            
            <!-- SESSION -->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-danger">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div id="current-cancelled-appointment" class="fs-4 fw-semibold">0</div>
                    <div>Số lượt khám bị hủy</div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart4" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- end SESSION -->
          </div><!-- end 4 TILES -->

          <!-- APPOINTMENTS IN LAST 7 DAYS -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4 class="card-title mb-0">Số lượng bệnh nhân trong 7 ngày gần nhất</h4>
                  <div class="small text-medium-emphasis">Bao gồm cả khám trực tiếp và đặt lịch hẹn</div>
                </div>

                <!-- <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                  <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                    <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">
                    <label class="btn btn-outline-secondary"> Day</label>
                    <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked="">
                    <label class="btn btn-outline-secondary active"> Month</label>
                    <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">
                    <label class="btn btn-outline-secondary"> Year</label>
                  </div>
                  <button class="btn btn-primary" type="button">
                    <i class="icon cil-cloud-download"></i>
                  </button>
                </div> -->

              </div>
              <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
                <canvas class="chart" id="main-chart" height="300"></canvas>
              </div>
            </div>
            <!-- BOOKING & APPOINTMENT footer-->
            <!-- <div class="card-footer">
              <div class="row row-cols-1 row-cols-md-5 text-center">

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Visits</div>
                  <div class="fw-semibold">29.703 Users (40%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Unique</div>
                  <div class="fw-semibold">24.093 Users (20%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Pageviews</div>
                  <div class="fw-semibold">78.706 Views (60%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">New Users</div>
                  <div class="fw-semibold">22.123 Users (80%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Bounce Rate</div>
                  <div class="fw-semibold">40.15%</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div> -->
            <!-- end BOOKING & APPOINTMENT footer-->
            </div>
          </div>
          <!-- end APPOINTMENTS IN LAST 7 DAYS-->


          <!-- BOOKING AND APPOINTMENTS IN LAST 7 DAYS -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4 class="card-title mb-0">Số lượng bệnh nhân đặt lịch hẹn với số lượt khám</h4>
                  <div class="small text-medium-emphasis">Biểu đồ so sánh số lịch hẹn với tổng số lượt khám</div>
                </div>

                <!-- <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                  <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                    <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">
                    <label class="btn btn-outline-secondary"> Day</label>
                    <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked="">
                    <label class="btn btn-outline-secondary active"> Month</label>
                    <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">
                    <label class="btn btn-outline-secondary"> Year</label>
                  </div>
                  <button class="btn btn-primary" type="button">
                    <i class="icon cil-cloud-download"></i>
                  </button>
                </div> -->

              </div>
              <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
                <canvas class="chart" id="booking-and-appointment-chart" height="300"></canvas>
              </div>
            </div>
            <!-- BOOKING & APPOINTMENT footer-->
            <!-- <div class="card-footer">
              <div class="row row-cols-1 row-cols-md-5 text-center">

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Visits</div>
                  <div class="fw-semibold">29.703 Users (40%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Unique</div>
                  <div class="fw-semibold">24.093 Users (20%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Pageviews</div>
                  <div class="fw-semibold">78.706 Views (60%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">New Users</div>
                  <div class="fw-semibold">22.123 Users (80%)</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col mb-sm-2 mb-0">
                  <div class="text-medium-emphasis">Bounce Rate</div>
                  <div class="fw-semibold">40.15%</div>
                  <div class="progress progress-thin mt-2">
                    <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div> -->
            <!-- end BOOKING & APPOINTMENT footer-->
            </div>
          </div>
          <!-- end BOOKING AND APPOINTMENTS IN LAST 7 DAYS-->


          <!-- DOCTOR INFO TABLE -->
          <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">Danh sách bác sĩ</div>
                <div class="card-body">
              
                  <div class="table-responsive">
                    <table class="table border mb-0">
                      <thead class="table-light fw-semibold">
                        <tr class="align-middle">
                          <th class="text-center">
                            <i class="icon cil-people"></i>
                          </th>
                          <th>Họ tên</th>
                          <th class="text-center">Chuyên khoa</th>
                          <th>Số điện thoại</th>
                          <th class="text-center">Vai trò</th>
                          <th>Cập nhật lần cuối</th>
                          <th></th>
                        </tr>
                      </thead>


                      <tbody>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-->
          </div>
          <!-- end DOCTOR INFO TABLE-->
        </div>
      </div>