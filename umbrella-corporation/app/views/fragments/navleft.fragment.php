<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
      <div class="sidebar-brand d-none d-md-flex">
        <img  src="<?= APPURL."/assets/img/umbrella-banner.jpg?v=".VERSION ?>" 
                        alt="Banner" width="255" height="auto">
      </div>

      <!-- -->
      <ul class="sidebar-nav" data-simplebar="">

        <li class="nav-item">
          <a class="nav-link <?= $Nav->activeMenu == "dashboard" ? "active" : "" ?>" href="<?= APPURL."/dashboard" ?>"> 
            <i class="nav-icon cil-home"></i>
              Dashboard
          </a>
        </li>


        <!-- BOOKING & APPOINTMENTS -->
        <li class="nav-title">Lịch khám bệnh</li>

        <li class="nav-item">
          <a class="nav-link <?= $Nav->activeMenu == "appointment" ? "active" : "" ?>" href="<?= APPURL."/appointments/?" ?>">
            <i class="nav-icon cil-calendar"></i> 
            Thứ tự lịch khám
          </a>

        <?php if($AuthUser->get("role") == "admin" || 
            $AuthUser->get("role") == "supporter" ): ?>

          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "appointmentArrange" ? "active" : "" ?>" href="<?= APPURL."/appointment/arrange/?" ?>">
              <i class="nav-icon cil-align-left"></i> 
                Sắp xếp thứ tự
            </a>
          </li>
        
          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "booking" ? "active" : "" ?>" href="<?= APPURL."/bookings" ?>">
              <i class="nav-icon cil-description"></i> 
              Lịch hẹn
              <span id="booking-quantity" class="badge badge-sm bg-info ms-auto">0</span>
            </a>
          </li><!-- end BOOKING & APPOINTMENTS -->
        <?php endif; ?>
          
        <?php if($AuthUser->get("role") == "admin"): ?><!-- 1. ADMIN ONLY -->
        <li class="nav-title">Quản trị viên</li>

        <li class="nav-item">
          <a class="nav-link <?= $Nav->activeMenu == "speciality" ? "active" : "" ?>" href="<?= APPURL."/specialities" ?>">
            <i class="nav-icon cil-medical-cross"></i> 
            Chuyên khoa
          </a>
        </li>



          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "doctor" ? "active" : "" ?>" href="<?= APPURL."/doctors" ?>">
              <i class="nav-icon cil-people"></i> 
              Bác sĩ
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "patient" ? "active" : "" ?>" href="<?= APPURL."/patients" ?>">
              <i class="nav-icon cil-disabled"></i> 
                Bệnh nhân
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "room" ? "active" : "" ?>" href="<?= APPURL."/rooms" ?>">
              <i class="nav-icon cil-room"></i>
                Phòng khám
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $Nav->activeMenu == "service" ? "active" : "" ?>" href="<?= APPURL."/services" ?>">
              <i class="nav-icon cil-task"></i>
                Dịch vụ
            </a>
          </li>
        <?php endif; ?><!-- end 1. ADMIN ONLY -->

        <?php if($AuthUser->get("role") == "admin" 
              || $AuthUser->get("role") == "member"): ?><!-- 2. ADMIN | MEMBER -->
        <li class="nav-title">Khám chữa bệnh</li>
        
        <li class="nav-item">
          <a class="nav-link <?= $Nav->activeMenu == "treatment" ? "active" : "" ?>" href="<?= APPURL."/treatment" ?>"">
          <i class="nav-icon cil-balance-scale"></i>
            Phác đồ điều trị
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $Nav->activeMenu == "record" ? "active" : "" ?>" href="<?= APPURL."/appointment-records" ?>">
          <i class="nav-icon cil-justify-left"></i>
            Bệnh án
          </a>
        </li>
        <?php endif; ?><!-- end 2. ADMIN | MEMBER  -->

        <!--PERSONAL-->
        <li class="nav-title">Cá nhân</li>


            <li class="nav-item">
              <a class="nav-link <?= $Nav->activeMenu == "personal" ? "active" : "" ?>" href="<?= APPURL."/personal" ?>" target="_top">
                <i class="nav-icon cil-dialpad"></i> 
                Thông tin cá nhân
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= $Nav->activeMenu == "password" ? "active" : "" ?>" href="<?= APPURL."/security" ?>" target="_top">
                <i class="nav-icon cil-shield-alt"></i> 
                Bảo mật
              </a>
            </li>

            <!-- <li class="nav-item">
              <a class="nav-link <?= $Nav->activeMenu == "avatar" ? "active" : "" ?>" href="<?= APPURL."/avatar" ?>" target="_top">
                <i class="nav-icon cil-soccer"></i> 
                Thay đổi ảnh đại diện
              </a>
            </li> -->

        </li>

      </ul>
      <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>