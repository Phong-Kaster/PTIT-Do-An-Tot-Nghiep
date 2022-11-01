/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * @param {} url is the API_URL to make AJAX
 * this function always get appointments created today
 */
function setup(url, params)
{
    /**Step 1 - set title for the table */
    setupTitle(params);

    /**Step 2 - call AJAX */
    $.ajax({
      type: "GET",
      url: url,
      data: params,
      dataType: "JSON",
      success: function(resp) {
          if(resp.result == 1)// result = 1
          {
                setupAppointmentTable(resp);
          }
          else// result = 0
          {
              title = 'error';
              Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Warning',
                text: msg,
                showConfirmButton: false,
                timer: 1500
              });
          }
      },
      error: function(err) {
          Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
      }
    })//end AJAX
}



/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup date picker
 */
 function setupAppointmentTable(resp)
 {
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
         let numericalOrder = resp.data[i].numerical_order;
         let patientName = resp.data[i].patient_name;
         //let gender = resp.data[i].gender == 1 ? "Nam" : "Nữ";
         let patientBirthday = resp.data[i].patient_birthday;
         let appointmentID = resp.data[i].id;
         let specialityName = resp.data[i].speciality.name;
         let patientReason = resp.data[i].patient_reason;
         let roomName = resp.data[i].room.name;
         let doctorName = resp.data[i].doctor.name;
         let appointmentDate = resp.data[i].date;
         let appointmentTime = resp.data[i].appointment_time;
         let patientPhone = resp.data[i].patient_phone;
         let position = resp.data[i].position;
         let createAt = resp.data[i].create_at;
         let updateAt = resp.data[i].update_at;
         let element = `
                 <!-- EXAMPLE 2 -->
                 <tr data-id=${appointmentID} class="align-middle">
                     <td class="text-center" id="numerical-order">
                         ${numericalOrder}
                     </td>
 
                     <td class="fw-semibold">
                     <div class="fw-semibold" id="speciality-name">${appointmentDate}</div>
                     </td>

                     <td>
                     <div class="fw-semibold" id="patient-name">${patientName}</div>
                     <div class="small text-medium-emphasis fw-semibold" id="patient-gender-birthday">Ngày sinh: ${patientBirthday}</div>
                     </td>
 
                     <td class="text-center">
                     <div class="fw-semibold" id="speciality-name">${specialityName}</div>
                     </td>
 
                     <td>
                     <div class="clearfix">
                         <div class="fw-semibold" id="patient-reason">${patientReason}</div>
                     </div>
                     </td>
 
                     <td class="text-center">
                         <div class="fw-semibold" id="room-name">${roomName}</div>
                     </td>
 
                     <!-- UPDATE AT -->
                     <td>
                         <div class="fw-semibold" id="doctor-name">
                             ${doctorName}
                         </div>
                     </td>
 
                     <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <button id="button-done" data-id=${appointmentID} class="btn btn-outline-success" type="button">Xong</button>
                                <button id="button-cancel" data-id=${appointmentID} class="btn btn-outline-warning" type="button">Hủy</button>
                                <button id="button-delete" data-id=${appointmentID} class="btn btn-outline-danger" type="button">Xóa</button>
                            <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-coreui-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <li class="dropdown-item"  data-coreui-toggle="collapse" href="#appointment-${appointmentID}"
                                        aria-expanded="false" aria-controls="#appointment-${appointmentID}">Chi tiết</li>
                                    <li class="dropdown-item" id="button-update" data-id=${appointmentID}>Sửa</li>
                                </ul>
                            </div>
                        </div>
                     </td>
                 </tr>
 
 
                 <tr data-id=${appointmentID} class="collapse" id="appointment-${appointmentID}">
                     <td colspan="9">
                         <table class="table">
                         <thead>
                             <tr>
                                 <th class="text-center" scope="col">ID</th>
                                 <th class="text-center" scope="col">Ngày khám</th>
                                 <th class="text-center" scope="col">Mã số</th>
                                 <th class="text-center" scope="col">Số thự tự</th>
                                 <th class="text-center" scope="col">Thời gian hẹn khám</th>
                                 <th class="text-center" scope="col">Số điện thoại bệnh nhân</th>
                                 <th class="text-center" scope="col">Bác sĩ - chuyên viên</th>
                                 <th class="text-center" scope="col">Khởi tạo</th>
                                 <th class="text-center" scope="col">Cập nhật lần cuối</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr class="align-middle">
                                 <td class="text-center" id="appointment-id">${appointmentID}</td>
                                 <td class="text-center" id="appointment-date">${appointmentDate}</td>
                                 <td class="text-center" id="numerical-order">${numericalOrder}</td>
                                 <td class="text-center" id="position">${position}</td>
                                 <td class="text-center" id="appointment-time">${appointmentTime}</td>
                                 <td class="text-center" id="patient-phone">${patientPhone}</td>
                                 <td class="text-center" id="doctor-name">${doctorName}</td>
                                 <td class="text-center" id="create-at">${createAt}</td>
                                 <td class="text-center" id="update-at">${updateAt}</td>
                             </tr>
                         </tbody>
                         </table>
                     </td>
                 </tr>
                 <!-- end EXAMPLE 2 -->
         `;
 
         
         $("tbody").append(element);
     }
 }

/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup date picker
 */
function setupDatePicker()
{
    let today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth()+1;
    let day = today.getDate();
    if( month < 10)
    {
        month = "0" + month;
    }
    if( day < 10)
    {
        day = "0" + day;
    }

    date = year + "-" + month + "-" + day;
    $("#datepicker").val(date);
    $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd'});
}



/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * listen SEARCH and RESET button
 * if we click it on, we will make an AJAX request
 */
function setupButton()
{
    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#search").val("");
        $("#order-dir").val("");
        $("#order-column").val("");
        $("#status").val("");
        $("#speciality").val("");
        $("#doctor").val("");
        $("#length").val("");
        $("#datepicker").val("");

        let url = API_URL + "/appointments";
        let today = new Date();
        date = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();
        let params = { date: date }
        setup(url, params);

        let paramsDoctor = {};
        setupDropdownDoctor(paramsDoctor);
    });


    /** BUTTON SEARCH*/
    $("#button-search").click(function(){
        /**Step 1 - get filter values */
        let search = $("#search").val();
        let orderDir = $("#order-dir :selected").val();
        let orderColumn = $("#order-column :selected").val();
        let status = $("#status :selected").val();
        let speciality = $("#speciality :selected").val();
        //let room = $("#room :selected").val();
        let doctor = $("#doctor :selected").val();
        let length = $("#length :selected").val();
        let date = $("#datepicker").val();


        /**Step 2 - set up parameters */
        let order = {
            "dir": orderDir,
            "column": orderColumn
        };
        let params = {
            search: search,
            order: order,
            length: length,
            speciality: speciality,
            doctor: doctor,
            date: date,
            status: status
        };


        /**Step 3 - query */
        let url = API_URL + "/appointments";
        setup(url, params);
    });
}


/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * @param {JSON} params 
 */
function setupTitle(params)
{
    let tile = params.title;
    let date = params.date;
    let doctor = params.doctor;
    let search = params.search;
    let status = params.status;
    let speciality = params.speciality;

    let title = "Danh sách khám bệnh";
    if(date)
    {
        title += " - Ngày: " + date;
    }
    if(doctor)
    {
        title += " - Bác sĩ " + $("#doctor :selected").text()
    }
    if(search)
    {
        title += " - Từ khóa: " + search;
    }
    if(status)
    {
        title += " - Trạng thái: " + status
    }
    if(speciality)
    {
        title += " - Chuyên khoa " + $("#speciality :selected").text()
    }
    $(".row .card-header").first().text(title);
}



/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup dropdown DOCTOR's speciality
 */
 function setupDropdownSpeciality(params)
 {
     /**Step 1 - make AJAX call */
     $.ajax({
         type: "GET",
         url: API_URL + "/specialities",
         data: params,
         dataType: "JSON",
         success: function(resp) {
             if(resp.result == 1)// result = 1
             {
                 createDropdownSpeciality(resp);
             }
             else// result = 0
             {
                 console.log(resp.msg);
             }
         },
         error: function(err) {
             Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
         }
       })//end AJAX
 }



/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * @param {JSON} resp 
 */
function createDropdownSpeciality(resp)
{
    for(let i = 0; i < resp.data.length; i++)
    {
        let id = resp.data[i].id;
        let name = resp.data[i].name;
        let element = `<option value="${id}">${name}</option>`;
        $("#speciality").append(element);
    }
}

/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup dropdown DOCTOR
 */
 function setupDropdownDoctor(params)
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: "GET",
        url: API_URL + "/doctors",
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownDoctor(resp);
            }
            else// result = 0
            {
                console.log(resp.msg);
            }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    })//end AJAX
}



/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * @param {JSON} resp 
 */
 function createDropdownDoctor(resp)
 {
    $("#doctor").empty();
    $("#doctor").append(`<option selected="" disabled="" value="">Chọn...</option>`);
     for(let i = 0; i < resp.data.length; i++)
     {
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let element = `<option value="${id}">${name}</option>`;
         $("#doctor").append(element);
     }
 }


/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * whenever ADMIN or SUPPORTER choose a speciality when this function will query to get
 * all DOCTORS match with the chosen speciality.
 */
function setupChooseSpeciality()
{
    $("#speciality").click(function(){
        let specialityId = $(this).val();
        console.log("speciality id " + specialityId);
        if(specialityId != null)
        {
            let params = {
                speciality: specialityId
            }
            setupDropdownDoctor(params);
        }
    })
}



/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * this function handles when users click on buttons
 */
function setupAppointmentActions()
{
    /**BUTTON DONE */
    $(document).on('click','#button-done',function(){
        let id = $(this).attr("data-id");
    });

    /**BUTTON CANCEL */
    $(document).on('click','#button-cancel',function(){
        console.log("cancel"); 
    });

    /**BUTTON DELETE */
    $(document).on('click','#button-delete',function(){
        let id = $(this).attr("data-id");
        Swal
        .fire({
            title: 'Bạn chắc chắn muốn thực hiện hành động ngày',
            text: "Hành động này không thể khôi phục sau khi thực hiện",
            icon: 'warning',
            confirmButtonText: 'Xác nhận',
            confirmButtonColor: '#FF0000',
            cancelButtonColor: '#0000FF',
            cancelButtonText: 'Hủy',
            reverseButtons: false,
            showCancelButton: true
        })
        .then((result) => 
            {
                if (result.isConfirmed) 
                {
                    let url = API_URL+"/appointments/"+id;
                    let method = "delete";
                    makeAppointmentAction(method, url, id );
                } 
                else
                {
                    Swal.close();
                }
            });
        
    });
    $(document).on('click','#button-update',function(){
        let id = $(this).attr("data-id");
        
    });
}


function makeAppointmentAction(method, url, id)
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: method,
        url: url,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                if(method = "delete")
                {
                    $("tbody").find("tr[data-id="+id+"]").remove();
                }

                Swal
                .fire({
                    title: 'Thành công',
                    text: "Thao tác thực hiện thành công",
                    icon: 'success',
                    confirmButtonText: 'Xác nhận',
                    confirmButtonColor: '#FF0000',
                    reverseButtons: false
                });
            }
            else// result = 0
            {
                Swal.fire({
                    position: 'center',
                    icon: title,
                    title: 'Warning',
                    text: resp.msg,
                    showConfirmButton: true
                });
            }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    })//end AJAX
}


$(document).ready(function(){

    /**Step 1 - prepare parameters */
    let paramsSpeciality = {};
    let paramsDoctor = {};


    /**Step 2 - setup necessary filter dropdown */
    setupDropdownSpeciality(paramsSpeciality);
    setupDropdownDoctor(paramsDoctor);
    setupDatePicker();
    setupButton();
    setupChooseSpeciality();
    setupAppointmentActions();
    

    /**Step 3 - run setup for the first time open this page */
    let date = getCurrentDate();
    let order = { column: "position", dir: "asc" }
    let params = { date: date, order: order }
    let url = API_URL + "/appointments";
    setup(url, params);
});