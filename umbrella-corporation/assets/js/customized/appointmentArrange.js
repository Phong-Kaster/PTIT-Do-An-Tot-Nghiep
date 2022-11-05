/**
 * @author Phong-Kaster
 * @since 05-11-2022
 */
function setupButton()
{
    /**BUTTON SEARCH */
    $("#button-search").click(function(){
        /**Step 1 - setup params */
        let specialityId = $("#speciality").val();
        let doctorId = $("#doctor").val();
        let date = getCurrentDate();
        let response;

        let order = { column:"position", dir:"asc"};
        let params = {
            date:date,
            doctor_id: doctorId,
            length: 20,
            order: order,
            start: 0,
            status: "processing"
        };


        let titleTable = {
            doctor: $("#doctor").text(),
            date: date,
            speciality: $("#speciality").text()
        }
        setupTitle(titleTable);


        /**Step 2 - AJAX */
        $.ajax({
            type: "GET",
            url: `${API_URL}/appointments`,
            data: params,
            dataType: "JSON",
            success: function(resp) {
            if(resp.result == 1)
            {
                createSortableTable(resp);
            }
            else
            {
                showMessageWithButton('error','Thất bại', resp.msg);
            }
            },
            error: function(err) {
                Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
            }
        });
    });



    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#doctor").val("");
        $("#speciality").val("");
        $("#appointmentList").find(".container").remove();
        setupTitle(params={});
    });


    
    /**BUTTON SAVE */
    $("#button-save").click(function(){
        /**Step 1 */
        let queue = [];
        queue.push(firstElement);// find function createSortableTable();
        queue.push(secondElement);// find function createSortableTable();

        $("#appointmentList .container").each(function(index){
            let appointmentId = $(this).attr("data-id");
            queue.push(appointmentId);
        });
        

        /**Step 2 */
        let data = {
            doctor_id: doctor_id,
            queue: queue
        }

        $.ajax({
            type: "POST",
            url: `${API_URL}/appointment-queue`,
            data: data,
            dataType: "JSON",
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
            }
            else
            {
                showMessageWithButton('error','Thất bại', resp.msg);
            }
            },
            error: function(err) {
                Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
            }
        });
    });
}


/**
 * @author Phong-Kaster
 * @since 05-11-2022
 * first element & second element stores the id of 
 * the first appointment and the 2nd appointment
 * 
 */
let firstElement; 
let secondElement;
let doctor_id;
function createSortableTable(resp)
{
    /**Step 1 - set value for global variable */
    firstElement = resp.data[0].id;
    secondElement = resp.data[0].id;
    doctor_id = resp.data[0].doctor.id;


    /**Step 2 - draw table */
    $("#appointmentList").find(".container").remove();
    let size = resp.data.length;
    for(let i = 2; i< size; i++)
    {
        let element = resp.data[i];
        let appointmentID = element.id;
        let numericalOrder = element.numerical_order;
        let patientName = element.patient_name;
        let patientReason = element.patient_reason;
        let patientBirthday = element.patient_birthday;
        let appointmentTime = element.appointment_time;
        let container = `
        <div data-id=${appointmentID} class="container list-group-item"><!-- item -->
        <div class="row">
                <div class="col-sm-1 text-center">
                <div class="text-center">${numericalOrder}</div>
                </div>

                <div class="col-sm-3">
                    <div class="fw-semibold" id="patient-name">${patientName}</div>
                </div>

                <div class="col-sm-3">
                    <div class="clearfix">
                        <div class="fw-semibold" id="patient-reason">${patientReason}</div>
                    </div>
                </div>

                <div class="col">
                    <div class="fw-semibold" id="patient-name">${patientBirthday}</div>
                </div>

                <div class="col">
                    <div class="fw-semibold" id="patient-name">${appointmentTime}</div>
                </div>
            </div>
        </div><!-- end item -->`;

        $("#appointmentList").append(container);
    }
}

$(document).ready(function(){
    setupButton();
});