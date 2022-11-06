/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * setup date picker for the "DATE" fiend in APPOINTMENTS table
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
 * @param {String} url 
 * @param {int} totalRecord 
 * @param {int} currentRecord is the number of AJAX returns for us.
 * For instance, total record is 15 records but DEFAULT_LENGTH is 5 
 * so that AJAX returns first 5 records.
 * The "currentRecord" is used to calculate next step for AJAX.
 */
function pagination(url, totalRecord, currentRecord)
{
    let buttonPrevious = $("ul#pagination li#button-previous");
    let buttonNext = $("ul#pagination li#button-next");
    let page = $("ul#pagination li#current-page");
    buttonNext.removeClass("disabled");
    buttonPrevious.removeClass("disabled");

    let currentPage = 1;
    let quantityOnePage = DEFAULT_LENGTH;
    let totalPage = Math.ceil(totalRecord / quantityOnePage);
    let start = 0;

    // console.log("=====================================");
    // console.log("totalRecord: " + totalRecord);
    // console.log("currentRecord: " + currentRecord);
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
            let params = getFilteringCondition();
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
                        createBookingTable(resp);
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
            if(currentRecord == quantityOnePage && currentPage == 1)
            {
                start = quantityOnePage;
            }
            if(currentRecord == quantityOnePage && currentPage != 1)
            {
                start = quantityOnePage*(currentPage-1);
            }
            
            let params = getFilteringCondition();
            params["start"] = start;
            // console.log("===next button");
            // console.log("currentPage: " + currentPage);
            // console.log("start: " + start);
            // console.log(params);
            /**Step 2 - call AJAX */
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                    if(resp.result == 1)// result = 1
                    {
                       
                        createBookingTable(resp);
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
 * @since 05-11-2022
 * @returns Object params contains filtering conditions
 */
 function getFilteringCondition()
 {
     let search         = $("#search").val() ? $("#service :selected").val() : "";
     let orderDir       = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
     let orderColumn    = $("#order-column :selected").val() ? $("#order-column :selected").val() : "id";
     let status         = $("#status :selected").val() ? $("#status :selected").val() : "";
     let serviceId      = $("#service :selected").val() ? $("#service :selected").val() : "";
     let date           = $("#datepicker").val() ? $("#datepicker").val() : getCurrentDate();
 
 
     /**Step 2 - set up parameters */
     let order = {
         "dir": orderDir,
         "column": orderColumn
     };
     let params = {
         search: search,
         order: order,
         length: DEFAULT_LENGTH,
         service_id: serviceId,
         date: date,
         status: status
     };
 
     return params;
 }


 /**
 * @author Phong-Kaster
 * @since 05-11-2022
 * @param {} url is the API_URL to make AJAX
 * this function always get bookings created today
 */
function setupBookingTable(url, params)
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
                createBookingTable(resp);
                pagination(url, resp.quantity, resp.data.length);
          }
          else// result = 0
          {
                showMessageWithButton('error','Thất bại', resp.msg);
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
 function createBookingTable(resp)
 {
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
         let bookingID = resp.data[i].id;
         let serviceName = resp.data[i].service.name;
         let bookingName = resp.data[i].booking_name;
         let bookingPhone = resp.data[i].booking_phone;
         let patientName = resp.data[i].name;
         let patientReason = resp.data[i].reason;
         let appointmentTime = resp.data[i].appointment_time;

         let gender = resp.data[i].gender == 1 ? "Nam" : "Nữ";
         let patientBirthday = resp.data[i].birthday;
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
            case "verified":
                statusValue = `<div class="badge me-1 rounded-pill bg-success">
                                    Đã xác thực
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
                 <tr data-id=${bookingID} class="align-middle">
                     <td class="text-center" id="numerical-order">
                         ${bookingID}
                     </td>
 
                     <td class="fw-semibold">
                     <div class="fw-semibold">${serviceName}</div>
                     </td>

                     <td class="text-center">
                        <div class="fw-semibold">${appointmentTime}</div>
                     </td>

 
                     <td class="text-center">
                        <div class="fw-semibold">${bookingName}</div>
                     </td>

 

                     <td>
                     <div class="fw-semibold" id="patient-name">${patientName}</div>
                     <div class="small text-medium-emphasis fw-semibold" id="patient-gender-birthday">Ngày sinh: ${patientBirthday}</div>
                     </td>

 
                     <td>
                         <div class="fw-semibold" id="room-name">${statusValue}</div>
                     </td>
 
 
                     <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">`;

         let element2 = `<div class="btn-group" role="group">
                            <button class="btn btn-outline-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-coreui-toggle="dropdown" aria-expanded="false">Khác</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <li id="button-create" class="dropdown-item" data-id=${bookingID}  type="button">Tạo thứ tự khám</li>
                                    <li><a class="dropdown-item" href="${APP_URL}/appointment/${bookingID}">Sửa</a></li>
                                    <li><a id="button-delete" 
                                        class="dropdown-item text-danger" 
                                        data-id="${bookingID}" type="button" >Xóa</a></li>
                                </ul>
                            </div>
                        </div>`;

            let element3 = `</td>
                 </tr>
                 <tr data-id=${bookingID} class="collapse" id="appointment-${bookingID}">
                     <td colspan="9">
                         <table class="table">
                         <thead>
                             <tr>
                                <th class="text-center" scope="col">Số điện thoại</th>
                                 <th class="text-center" scope="col">Giới tính bệnh nhân</th>
                                 <th class="text-center" scope="col">Mô tả</th>
                                 <th class="text-center" scope="col">Thời gian tạo</th>
                                 <th class="text-center" scope="col">Cập nhật cuối</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr class="align-middle">
                                 <td class="text-center" id="appointment-id">${bookingPhone}</td>
                                 <td class="text-center" id="appointment-id">${gender}</td>
                                 <td class="text-center" id="appointment-id">${patientReason}</td>
                                 <td class="text-center" id="appointment-id">${createAt}</td>
                                 <td class="text-center" id="appointment-id">${updateAt}</td>

                             </tr>
                         </tbody>
                         </table>
                     </td>
                 </tr>
                 <!-- end EXAMPLE 2 -->
         `;
         
         let element = element1;


         /** ADMIN & SUPPORTER has DELETE button */
        if( status == "processing")
        {
            element += 
                    `<button class="btn btn-outline-info"  data-coreui-toggle="collapse" href="#appointment-${bookingID}"
                        aria-expanded="false" aria-controls="#appointment-${bookingID}">Chi tiết</button>`
                    + element2
                    + element3;
        }
        else
        {
            element += element3;
        }
         
         $("tbody").append(element);
     }
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
        $("#service").val("");
        

        let url = API_URL + "/bookings";
        date = getCurrentDate();
        $("#datepicker").val(date);
        let params = { date: date,  status:"" , length:DEFAULT_LENGTH }
        setupBookingTable(url, params);
    });


    /** BUTTON SEARCH*/
    $("#button-search").click(function(){
        /**Step 1 - get filter values */
        let params = getFilteringCondition();
       
        
        /**Step 2 - query */
        let url = API_URL + "/bookings";
        setupBookingTable(url, params);
    });
}


/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * this function handles when users click on buttons
 */
 function setupBookingActions()
 {
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
                     let url = API_URL+"/bookings/"+id;
                     let method = "patch";
                     let params = { newStatus: "cancelled"};
                     makeBookingAction(method, url, id, params );
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


 function makeBookingAction(method, url, id, params = [])
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
                showMessageWithButton('success','Thành công', 'Thao tác thực hiện thành công');

                /**update current PROCESSING booking in left navigation */
                let quantity = $("#booking-quantity").text();
                $("#booking-quantity").text(quantity-1);
            }
            else// result = 0
            {
                showMessageWithButton('error','Thất bại', resp.msg);
            }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    })//end AJAX
}
