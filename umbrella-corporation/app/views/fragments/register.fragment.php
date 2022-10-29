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
                
                <h3> Đăng kí tài khoản</h3>
                <p class="text-medium-emphasis">Vui lòng nhập đầy đủ những thông tin dưới đây !</p>


                <div class="input-group mb-3">
                  <span class="input-group-text">
                      <i class="icon cil-envelope-letter"></i>
                  </span>
                  <input class="form-control" 
                          type="email" placeholder="Email"
                          name="email" id="email" required autofocus
                          value="">
                </div>

                <div class="input-group mb-4">
                  <span class="input-group-text">
                    <i class="cil-phone"></i>
                  </span>
                  <input class="form-control" 
                          type="number" 
                          placeholder="Số điện thoại" 
                          name="phone" id="phone" required
                          value="">
                </div>

                <div class="input-group mb-4">
                  <span class="input-group-text">
                      <i class="cil-speak"></i>
                  </span>
                  <input class="form-control" 
                        type="text" 
                        placeholder="Họ tên"
                        name="name" 
                        id="name" required
                        value="">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text">
                    <i class="icon cil-keyboard"></i>
                  </span>
                  <input class="form-control" 
                        type="password" 
                        placeholder="Mật khẩu" 
                        name="password" 
                        id="password" required
                        value="">
                </div>

                <div class="input-group mb-4">
                  <span class="input-group-text">
                      <i class="icon cil-keyboard"></i>
                  </span>
                  <input class="form-control" 
                        type="password" 
                        placeholder="Xác nhận mật khẩu" 
                        name="passwordConfirm" 
                        id="passwordConfirm" required
                        value="">
                </div>

                <div align="center">
                  <button class="btn btn-block btn btn-primary" type="button" id="submitButton">Tạo tài khoản</button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    </body>