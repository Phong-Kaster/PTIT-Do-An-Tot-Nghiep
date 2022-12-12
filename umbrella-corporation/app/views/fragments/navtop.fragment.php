<header class="header header-sticky mb-4">
        <div class="container-fluid">
          <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <i class="icon icon-lg cil-applications"></i>
          </button><a class="header-brand d-md-none" href="#">
          <i width="118" height="46" alt="CoreUI Logo" class="cil-bell"></i></a>
            
          <ul class="header-nav d-none d-md-flex">
            <li class="nav-item"><a class="nav-link" href="<?= APPURL."/dashboard" ?>">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= APPURL."/doctors" ?>"">Quản lý bác sĩ</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= APPURL."/personal" ?>"">Cá nhân</a></li>
          </ul>
          <ul class="header-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="JavaScript: location.reload(true);">
                <i class="icon icon-lg cil-bell"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="icon icon-lg cil-list-rich"></i>
              </a>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="icon icon-lg cil-envelope-open"></i>
                </a>
              </li>
          </ul>
          <ul class="header-nav ms-3">
            <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md"><img class="avatar-img" src="<?= API_URL."/assets/uploads/".$AuthUser->get("avatar")."?v=".VERSION ?>" alt="user@email.com"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-light py-2">
                  <div class="fw-semibold">Tài khoản: <?= $AuthUser->get("email"); ?></div>
                  <div class="fw-semibold">Vai trò: <?= $AuthUser->get("role"); ?></div>
                </div>
                  <a class="dropdown-item" href="#">

                    <!-- <svg class="icon me-2">
                      <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
                    </svg> Updates<span class="badge badge-sm bg-info ms-2">42</span></a><a class="dropdown-item" href="#"> -->

                    <i class="icon me-2 cil-clipboard"></i>
                    Thông tin cá nhân</a><a class="dropdown-item" href="#">

                    <i class="icon me-2 cil-dialpad"></i>
                    Thay đổi thông tin cá nhân</a><a class="dropdown-item" href="#">

                    <i class="icon me-2 cil-keyboard"></i>
                    Thay đổi mật khẩu</a><a class="dropdown-item" href="#">
                  </a>



                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= APPURL."/logout" ?>">
                  <i class="nav-icon cil-account-logout"></i> 
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </div>
        <div class="header-divider"></div>
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
              <li class="breadcrumb-item">
                
                  <?php if($Nav->activeMenu  == "dashboard"): ?>
                    <span> Dashboard </span>
                  <?php elseif( $Nav->activeMenu  == "appointment"): ?>
                    <span> Thứ tự lịch khám </span>
                  <?php elseif( $Nav->activeMenu  == "booking"): ?>
                    <span> Lịch hẹn </span>
                  <?php elseif( $Nav->activeMenu  == "speciality"): ?>
                    <span> Quản lý chuyên khoa </span>
                  <?php elseif( $Nav->activeMenu  == "doctor"): ?>
                    <span> Quản lý bác sĩ </span>
                  <?php elseif( $Nav->activeMenu  == "patient"): ?>
                    <span> Quản lý bệnh nhân </span>
                  <?php elseif( $Nav->activeMenu  == "room"): ?>
                    <span> Quản lý phòng khám </span>
                  <?php elseif( $Nav->activeMenu  == "service"): ?>
                    <span> Quản lý dịch vụ </span>
                  <?php elseif( $Nav->activeMenu  == "serviceAndDoctor"): ?>
                    <span> Quản lý bác sĩ làm việc theo dịch vụ </span>
                  <?php elseif( $Nav->activeMenu  == "treatment"): ?>
                    <span> Quản lý phác đồ điều trị - đơn thuốc </span>
                  <?php elseif( $Nav->activeMenu  == "record"): ?>
                    <span> Quản lý bệnh án </span>
                  <?php elseif( $Nav->activeMenu  == "personal"): ?>
                    <span> Thông tin cá nhân </span>
                  <?php elseif( $Nav->activeMenu  == "security"): ?>
                    <span> Bảo mật </span>
                  <?php endif; ?>
                  
                
              </li>
              <!-- <li class="breadcrumb-item active">
                <span>

                </span>
            </li> -->
            </ol>
          </nav>
        </div>
      </header>