<body>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            
            <div class="card mb-4 mx-4">
              <div align="center">
                  <img src="<?= APPURL."/assets/img/umbrella-banner.jpg?v=".VERSION ?>" 
                        alt="Banner" width="500">
              </div>


              <div align="center" class="card-body p-4">
                
                <h3 style="margin-bottom: 40px;"> Khôi phục mật khẩu</h3>
                <!-- <p class="text-medium-emphasis">Nhập địa chỉ email đã dùng để đăng kí tài khoản!</p> -->


                <div class="input-group mb-3">
                  <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="<?= APPURL."/assets/vendors/@coreui/icons/svg/free.svg#cil-user?v=".VERSION ?>"></use>
                    </svg>
                  </span>
                  <input class="form-control" 
                          type="email" placeholder="Nhập địa chỉ email đã dùng để đăng kí tài khoản"
                          name="email" id="email" required autofocus
                          value="">
                </div>

                <div class="input-group mb-3"><span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="<?= APPURL."/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked?v=".VERSION ?>"></use>
                    </svg></span>
                  <input class="form-control" 
                        type="password" 
                        placeholder="Mật khẩu mới" 
                        name="password" 
                        id="password" required
                        value="">
                </div>

                <div class="input-group mb-4">
                  <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="<?= APPURL."/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked?v=".VERSION ?>"></use>
                    </svg>
                  </span>
                  <input class="form-control" 
                        type="password" 
                        placeholder="Xác nhận mật khẩu" 
                        name="passwordConfirm" 
                        id="passwordConfirm" required
                        value="">
                </div>

                <div class="input-group mb-4">
                  <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="<?= APPURL."/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked?v=".VERSION ?>"></use>
                    </svg>
                  </span>
                  <input class="form-control" 
                          type="number" 
                          placeholder="Mã xác thực" 
                          name="recoveryToken" id="recoveryToken" required
                          value="">
                </div>


                <div align="center">
                  <button class="btn btn-block btn-primary m-2" type="button" id="submitButton">Nhận mã xác thực</button>
                  <button class="btn btn-block btn-danger m-2" type="button" id="resetButton">Thay đổi mật khẩu</button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    </body>