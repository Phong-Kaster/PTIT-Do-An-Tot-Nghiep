/**
 * @author Phong-Kaster
 * @since 12-111-2022
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
 * @since 12-11-2022
 * setup record table
 */
function setupRecordTable(url, params)
{
    /**Step 1 - set title for table */
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
                  createRecordTable(resp);
                  setupButton(resp);
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
 * @since 12-11-2022
 */
function createRecordTable(resp)
{
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
        let element = resp.data[i];
        let recordId = element.id;
        
        let patientId = element.appointment.patient_id;
        let patientName = element.appointment.patient_name;
        let patientBirthday = element.appointment.patient_birthday;
        let patientReason = element.appointment.patient_reason;

        let appointmentId = element.appointment.id;
        let appointmentDate = element.appointment.date;
        let appointmentStatus = element.appointment.status;

        let specialityId = element.speciality.id;
        let specialityName = element.speciality.name;
        let doctorId = element.doctor.id;
        let doctorName = element.doctor.name;
        let body1 = `
            <tr data-id="${appointmentId}" class="align-middle">
            <td class="text-center" id="appointment-id">
                ${appointmentId}
            </td>

            <td class="fw-semibold">
            <div class="fw-semibold" id="appointment-date">${appointmentDate}</div>
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
                <div class="fw-semibold" id="doctor-name">${doctorName}</div>
            </td>

            <td>
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <button id="button-watch" class="btn btn-outline-primary" type="button" data-coreui-toggle="modal" data-id=${recordId} data-coreui-target="#record">Xem</button>`;
                    

                
        let body2 = `</div>
            </td>
        </tr>`;

        let today = getCurrentDate();
        if( appointmentDate == today && appointmentStatus == "processing")
        {
            body1 += `<a href="${APP_URL}/appointment-record/?id=${recordId}" class="btn btn-outline-success" type="button">Sửa</a>` 
        }

        body1 += body2
        $("tbody").append(body1);
     }
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
                         createRecordTable(resp);
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
                        
                         createRecordTable(resp);
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
 * @since 12-11-2022
 * @param {@} resp 
 * print appointment record info
 */
function printAppointmentRecord(resp)
{
    let element = resp.data;
    let recordId = element.id;
    let patientId = element.appointment.patient_id;
    let patientName = element.appointment.patient_name ? element.appointment.patient_name : "Không có";
    let patientBirthday = element.appointment.patient_birthday ? element.appointment.patient_birthday : "Không có";
    let patientReason = element.appointment.patient_reason ? element.appointment.patient_reason : "Không có";
    let patientPhone = element.appointment.patient_phone ? element.appointment.patient_phone : "Không có";

    let appointmentDate = element.appointment.date;
    let appointmentId = element.appointment.id;

    let specialityId = element.speciality.id;
    let specialityName = element.speciality.name;

    let doctorId = element.doctor.id;
    let doctorName = element.doctor.name;
    
    let statusBefore = element.status_before;
    let statusAfter = element.status_after;
    let description = element.description;
    let reason = element.reason;

    let body = `
    <div class="modal-body" id="body">
            <div class="example">
            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1252">
                <h3>Thông tin bệnh án</h3>
                <div class="row mb-4"><!-- 1. APPOINTMENT DATE - APPOINTMENT ID - RECORD ID  -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="appointment-date">Ngày khám</label>
                                <p id="id"><strong>${appointmentDate}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="appointment-id">Mã lịch khám</label>
                                <p id="email"><strong>${appointmentId}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="record-id">Mã bệnh án</label>
                                <p id="email"><strong>${recordId}</strong></p>
                            </div>
                        </div>
                </div><!-- end 1. APPOINTMENT DATE - APPOINTMENT ID - RECORD ID  -->

                <div class="row mb-4"><!-- 2. DOCTOR NAME | SPECIALITY  -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="doctor-name">Bác sĩ</label>
                                <p id="name"><strong>${doctorName}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-4"><!-- SPECIALITY -->
                            <div class="mb-3">
                                <label class="form-label" for="name">Chuyên khoa</label>
                                <p id="name"><strong>${specialityName}</strong></p>
                            </div>
                        </div><!-- SPECIALITY -->
                </div><!-- end 2. NAME | SPECIALITY-->


                <h3>Thông tin bệnh nhân</h3>
                <div class="row mb-4"><!-- 3. PATIENT NAME | BIRTHDAY | PHONE  -->
                    <div class="col-md-4"><!-- NAME -->
                        <div class="mb-3">
                            <label class="form-label" for="patient-nam">Tên bệnh nhân</label>
                            <p id="name"><strong>${patientName}</strong></p>
                        </div>
                    </div><!-- end NAME -->

                    <div class="col-md-4"><!-- BIRTHDAY -->
                        <div class="mb-3">
                            <label class="form-label" for="patient-birthday">Ngày sinh</label>
                            <p id="name"><strong>${patientBirthday}</strong></p>
                        </div>
                    </div><!-- end BIRTHDAY -->

                    <div class="col-md-4"><!-- PHONE -->
                        <div class="mb-3">
                            <label class="form-label" for="patient-phone">Số điện thoại</label>
                            <p id="name"><strong>${patientPhone}</strong></p>
                        </div>
                    </div><!-- end PHONE -->
                    
                </div><!-- end 3. PATIENT NAME | BIRTHDAY | PHONE  -->



                <div class="row mb-4"><!-- 4. DESCRIPTION -->
                    <div class="col-md-10">
                        <label class="form-label" for="patient-reason">Mô tả bệnh lý</label>
                        <p><strong>${patientReason}</strong></p>
                    </div>
                </div><!-- end 4. DESCRIPTION -->


                <h3>Chuẩn đoán của bác sĩ</h3>
                <div class="row mb-4"><!-- 5. STATUS BEFORE  -->
                    <div class="col-md-10">
                        <label class="form-label" for="status-before">Trình trạng trước khám</label>
                        <p><strong>${statusBefore}</strong></p>
                    </div>
                </div><!-- end 5.STATUS BEFORE -->

                <div class="row mb-4"><!-- 6. STATUS AFTER  -->
                    <div class="col-md-10">
                        <label class="form-label" for="status-after">Trình trạng sau khám</label>
                        <p><strong>${statusAfter}</strong></p>
                    </div>
                </div><!-- end 6.STATUS AFTER -->

                <div class="row mb-4"><!-- 7.  REASON  -->
                    <div class="col-md-10">
                        <label class="form-label" for="status-after">Nguyên nhân</label>
                        <p><strong>${reason}</strong></p>
                    </div>
                </div><!-- end 7. REASON -->

                <div class="row mb-4"><!-- 8.  DESCRIPTION  -->
                    <div class="col-md-10">
                        <label class="form-label" for="description">Mô tả</label>
                        <p><strong>${description}</strong></p>
                    </div>
                </div><!-- end 8. DESCRIPTION -->

        </div><!-- end tab-content -->
    </div>`;
    
    $("#body").replaceWith(body);
}



/**
 * @author Phong-Kaster
 * @since 03-11-2022
 * @returns Object params contains filtering conditions
 */
 function getFilteringCondition()
 {
     let search = $("#search").val();
     let orderDir = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
     let orderColumn = $("#order-column :selected").val() ? $("#order-column :selected").val() : "position";
     let specialityId = $("#speciality :selected").val() ? $("#speciality :selected").val() : "" ;
     let doctorId = $("#doctor :selected").val() ? $("#doctor :selected").val() : "";
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
         speciality_id: specialityId,
         doctor_id: doctorId,
         date: date
     };
 
     return params;
 }
 


/**
 * @author Phong-Kaster
 * @since 12-11-2022
 * set up button
 */
function setupButton()
{
    /**BUTTON WATCH */
    $(document).on('click','#button-watch',function(){
        let recordId = $(this).attr("data-id");


        $.ajax({
            type: "GET",
            url: `${API_URL}/appointment-records/${recordId}`,
            dataType: "JSON",
            success: function(resp) {
                if(resp.result == 1)
                {
                    printAppointmentRecord(resp);
                }
                else
                {
                    showMessageWithButton('error','Thất bại', resp.msg);
                }
            },
            error: function(err) {
                console.log(err.responseText);
                showMessageWithButton('error','Thất bại', err);
            }
        });
    });


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

        let url = API_URL + "/appointment-records";
        date = getCurrentDate();
        let order = { column:"id", dir:"asc"};
        let params = { order:order, date: date }
        setupRecordTable(url, params);

        params = {order:order};
        setupDropdownDoctor(params);
    });



    /** BUTTON SEARCH*/
    $("#button-search").click(function(){
        /**Step 1 - get filter values */
        let params = getFilteringCondition();
        
        
        /**Step 2 - query */
        let url = API_URL + "/appointment-records";
        setupRecordTable(url, params);
    });
}