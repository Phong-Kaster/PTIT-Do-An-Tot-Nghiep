<body>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <div class="card col-md-7 p-4 mb-0">

                <!-- LOGIN -->
                <form class="form-horizontal" method="POST" action="<?= APPURL."/login" ?>">
                <img src="<?= APPURL."/assets/img/umbrella-banner.jpg?v=".VERSION ?>" 
                        alt="Banner" width="430">
                  <div class="card-body">
                    <!-- <h3> <?= WEBSITE_NAME ?></h3> -->
                    <!-- <p class="text-medium-emphasis">Đăng nhập</p> -->
                    <input type="hidden" name="action" value="login">
                        <?php if (Input::post("action") == "login"): ?>
                            <p class="bg-danger text-center" style="padding: 10px"><?= __("Email hoặc mật khẩu chưa chính xác!") ?></p>
                        <?php endif; ?>

                    <div class="input-group mb-3">
                      <span class="input-group-text">
                        <i class="icon cil-envelope-letter"></i>
                      </span>
                      <input class="form-control" type="email" name="email" placeholder="Email" value="phongkaster@gmail.com" required autofocus>
                    </div>

                    <div class="input-group mb-4">
                      <span class="input-group-text">
                          <i class="icon cil-keyboard"></i>
                      </span>
                      <input class="form-control" type="password" name="password" value="123456" placeholder="Password">
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <button class="btn btn-primary px-4" type="submit">Đăng nhập</button>
                      </div>
                      <div class="col-6 text-end">
                        <!-- <button class="btn btn-link px-0" type="button" >Quên mật khẩu?</button> -->
                        <a href="<?= APPURL ?>/recovery" class="btn btn-link px-0">Quên mật khẩu?</a>
                      </div>
                    </div>
                  </div>
                </div>
            </form>
            <!-- END LOGIN -->

              <div class="card col-md-5 text-white bg-primary py-5">
                <div class="card-body text-center">
                  <div>
                    <h2>Đăng ký mới</h2>
                    <p>Bạn muốn trở thành bác sĩ của Umbrella Corporation với mạng lưới khắp thế giới. Còn chần chừ gì nữa đăng kí tài khoản ngay nào !</p>
                    <button class="btn btn-lg btn-outline-light mt-3" onclick="location.href='<?= APPURL ?>/register'" type="button" >Let's go !</button>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>