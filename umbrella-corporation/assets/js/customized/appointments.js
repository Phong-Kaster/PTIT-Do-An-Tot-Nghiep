
function getConditionFilter()
{
    let search = $("#search").val();
    let orderDir = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
    let orderColumn = $("#order-column :selected").val() ? $("#order-column :selected").val() : "position";
    let status = $("#status :selected").val();
    let speciality = $("#speciality :selected").val();
    //let room = $("#room :selected").val();
    let doctor = $("#doctor :selected").val();
    //let length = $("#length :selected").val() ? $("#length :selected").val() : DEFAULT_LENGTH;
    let date = $("#datepicker").val() ? $("#datepicker").val() : getCurrentDate();


    /**Step 2 - set up parameters */
    let order = {
        "dir": orderDir,
        "column": orderColumn
    };
    let params = {
        search: search,
        order: order,
        length: DEFAULT_LENGTH,
        speciality: speciality,
        doctor: doctor,
        date: date,
        status: status
    };

    return params;
}



/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * @param {} url is the API_URL to make AJAX
 * this function always get appointments created today
 */
function setupAppointmentTable(url, params)
{
    //console.log(params);
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
                createAppointmentTable(resp);
                pagination(url, resp.quantity, resp.data.length);
          }
          else// result = 0
          {
              title = 'error';
              Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Warning',
                text: msg,
                showConfirmButton: true
              });
          }
      },
      error: function(err) {
          Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
      }
    })//end AJAX
}



function pagination(url, totalRecords, records)
{
    let buttonPrevious = $("ul#pagination li#button-previous");
    let buttonNext = $("ul#pagination li#button-next");
    let page = $("ul#pagination li#current-page");
    buttonNext.removeClass("disabled");
    buttonPrevious.removeClass("disabled");

    let currentPage = 1;
    let quantityOnePage = DEFAULT_LENGTH;
    let totalPage = Math.ceil(totalRecords / quantityOnePage);
    let start = 0;

    // console.log("=====================================");
    // console.log("totalRecords: " + totalRecords);
    // console.log("records: " + records);
    // console.log("totalPage: " + totalPage);
    if( totalPage == 1 )
    {
        buttonNext.addClass("disabled");
        buttonPrevious.addClass("disabled");
        page.text(1);
    }
    // if( currentPage == totalPage && totalPage > 1 )
    // {
    //     buttonNext.addClass("disabled");
    //     buttonPrevious.removeClass("disabled");
    // }

    /***********BUTTON PREVIOUS***********/
    buttonPrevious.click(function(){
        if( currentPage == 1)
        {
            buttonPrevious.addClass("disabled");
        }
        else
        {
            currentPage--;
            page.text(currentPage);
            if( currentPage < totalPage && currentPage > 1)/**Case 1 - total page == 3 & current page == 2 => enable */
            {
                buttonNext.removeClass("disabled");
            }
            
            if( currentPage == 1 && totalPage != 1 )/**Case 2 - total page == currentPage == 1 => disable*/
            {
                buttonPrevious.addClass("disabled");
                buttonNext.removeClass("disabled");
            }
            
            if( currentPage > 1)/**Case 3 - current page > 1 */
            {
                buttonPrevious.removeClass("disabled");
            }
            
            /**query */
            start = quantityOnePage*(currentPage-1);
            let params = getConditionFilter();
            params["start"] = start;
            params["length"] = quantityOnePage;

            /**Step 1 - get filter values */
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                    if(resp.result == 1)// result = 1
                    {
                        createAppointmentTable(resp);
                    }
                    else// result = 0
                    {
                        console.log(resp.msg);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    });



    /*************BUTTON NEXT************/
    buttonNext.click(function(){
        if( totalPage == currentPage )
        {
            buttonNext.addClass("disabled");
        }
        else
        {
            currentPage++;
            page.text(currentPage);
            /**Case 1 - total page == 2 && next current page == 2 => disable NEXT , enable PREVIOUS */
            if( totalPage > 1 && currentPage == totalPage)
            {
                buttonNext.addClass("disabled");
                buttonPrevious.removeClass("disabled");
            }
            /**Case 2 - total page == 3 && next current page == 2 => enable both buttons */
            if( totalPage > 1 && currentPage < totalPage)
            {
                buttonNext.removeClass("disabled");
                buttonPrevious.removeClass("disabled");
            }
            /**Step 3 - set up start where query begin returns the result for us */
            if(records == quantityOnePage && currentPage == 1)
            {
                start = quantityOnePage;
            }
            if(records == quantityOnePage && currentPage != 1)
            {
                start = quantityOnePage*(currentPage-1);
            }
            
            let params = getConditionFilter();
            params["start"] = start;
            console.log("===next button");
            console.log("currentPage: " + currentPage);
            console.log("start: " + start);
            console.log(params);
            /**Step 2 - call AJAX */
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                    if(resp.result == 1)// result = 1
                    {
                       
                        createAppointmentTable(resp);
                    }
                    else// result = 0
                    {
                        console.log(resp.msg);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })//end AJAX
        }
    })
}



/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup date picker
 */
 function createAppointmentTable(resp)
 {
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
         let numericalOrder = resp.data[i].numerical_order;
         let patientName = resp.data[i].patient_name;
         //gender = resp.data[i].gender == 1 ? "Nam" : "Nữ";
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
         let status = resp.data[i].status;
         let statusValue;
         switch(status)
         {
            case "processing":
                statusValue = `<div class="badge me-1 rounded-pill bg-dark">
                                    Đang xử lý
                               </div>`;
                break;
            case "done":
                statusValue = `<div class="badge me-1 rounded-pill bg-success">
                                    Xong
                                </div>`;
                break;
            case "cancelled":
                statusValue = `<div class="badge me-1 rounded-pill bg-warning">
                                    Hủy bỏ
                              </div>`;
                break;
            default:
                statusValue = "Đang xử lý";
                break;
         }
         element1 = `
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
                             ${statusValue}
                     </td>
 
                     <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">`;
         let element2 = `<div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-coreui-toggle="dropdown" aria-expanded="false">Khác</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <li class="dropdown-item"  data-coreui-toggle="collapse" href="#appointment-${appointmentID}"
                                        aria-expanded="false" aria-controls="#appointment-${appointmentID}">Chi tiết</li>
                                    <li ><a class="dropdown-item" href="${APP_URL}/appointment/${appointmentID}">Sửa</a></li>
                                    `;
         let element3 = `
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
         
         let element = element1;

         /**ROLE is from umbrella-corporation/assets/views/appointments.php
          * MEMBER has 2 buttons: Viết bệnh án & Viết phác đồ điều trị
          */
         if( ROLE == "member")
         {
            if( status == "processing")
            {
                element += 
                        `<button id="button-done" data-id=${appointmentID} class="btn btn-outline-success" type="button">Xong</button>
                        <button id="button-cancel" data-id=${appointmentID} class="btn btn-outline-warning" type="button">Hủy</button>
                        <button id="button-delete" data-id=${appointmentID} class="btn btn-outline-danger" type="button">Xóa</button>`
            }
            element += element2+
                    `<li class="dropdown-item" id="button-create-record" data-id=${appointmentID}>Bệnh án</li>
                     <li class="dropdown-item" id="button-create-treatments" data-id=${appointmentID}>Phác đồ điều trị</li>` 
                    +element3;
         }
         /** ADMIN & SUPPORTER has DELETE button */
         if( ROLE != "member")
         {
            if( status == "processing")
            {
                element += 
                        `<button id="button-done" data-id=${appointmentID} class="btn btn-outline-success" type="button">Xong</button>
                        <button id="button-cancel" data-id=${appointmentID} class="btn btn-outline-warning" type="button">Hủy</button>
                        <button id="button-delete" data-id=${appointmentID} class="btn btn-outline-danger" type="button">Xóa</button>`
                        + element2
                        + element3;
            }
            else
            {
                element += element2
                    +element3;
            }
         }
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
        date = getCurrentDate();
        let params = { date: date,  status:"" }
        setupAppointmentTable(url, params);

        let paramsDoctor = {};
        setupDropdownDoctor(paramsDoctor);
    });


    /** BUTTON SEARCH*/
    $("#button-search").click(function(){
        /**Step 1 - get filter values */
        let params = getConditionFilter();

        /**Step 2 - query */
        let url = API_URL + "/appointments";
        setupAppointmentTable(url, params);
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
                    let id = $(this).attr("data-id");
                    let url = API_URL+"/appointments/"+id;
                    let method = "patch";
                    let params = { status:"done" };
                    makeAppointmentAction(method, url, id, params );
                } 
                else
                {
                    Swal.close();
                }
            });
    });

    /**BUTTON CANCEL */
    $(document).on('click','#button-cancel',function(){
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
                    let id = $(this).attr("data-id");
                    let url = API_URL+"/appointments/"+id;
                    let method = "patch";
                    let params = { status:"cancelled" };
                    makeAppointmentAction(method, url, id, params );
                } 
                else
                {
                    Swal.close();
                }
            });
    });

    /**BUTTON DELETE */
    $(document).on('click','#button-delete',function(){
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
                    let id = $(this).attr("data-id");
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


function makeAppointmentAction(method, url, id, params = [])
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: method,
        url: url,
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                
                $("tbody").find("tr[data-id="+id+"]").remove();
                

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
    //this block of code is written in views/appointments.php
});