/**
 * @author Phong-Kaster
 * @since 12-11-2022
 * type is the keyword to request server find appointment record by its ID or its appointment id
 */
function setupAppointmentRecordInfo(type, id)
{
    let params = {
        type: type
    };

    console.log(type);

    $.ajax({
        type: "GET",
        url: `${API_URL}/appointment-records/${id}`,
        data: params,
        dataType: "JSON",
        success: function(resp) 
        {
            appointmentID = type == "appointment_id" ? id : resp.data.appointment.id;
            console.log("type: " + type);
            console.log("appointment id: " + appointmentID);
            if(resp.result == 1)
            {
                printAppointmentRecord(resp);
                setupButton(appointmentID);
            }
            else
            {
                showMessageWithButton('info','Oops !', resp.msg);
            }
        },
        error: function(err) {
            console.log(err.responseText);
            showMessageWithButton('error','Oops !', err);
        }
    });
}

/**
 * @author Phong-Kaster
 * @since 12-11-2022
 * @param {Object} resp 
 */
function printAppointmentRecord(resp)
{
    let statusBefore = resp.data.status_before;
    let statusAfter = resp.data.status_after;
    let reason = resp.data.reason;
    let description = resp.data.description;
    let createAt = resp.data.create_at;
    let updateAt = resp.data.update_at;

    $("#status-before").val(statusBefore);
    $("#status-after").val(statusAfter);
    $("#reason").val(reason);
    $("#description").val(description);
    $("create-at").replaceWith(`<p><strong>${createAt}</strong></p>`);
    $("update-at").replaceWith(`<p><strong>${updateAt}</strong></p>`);



}



/**
 * @author Phong-Kaster
 * @since 12-11-2022
 * @returns 
 */
function getNecessaryInfo()
{
    let statusBefore = $("#status-before").val();
    let statusAfter = $("#status-after").val();
    let reason = $("#reason").val();
    let description = CKEDITOR.instances.description.getData();
    description = encodeURIComponent(description);

    let params = {
        status_before: statusBefore,
        status_after: statusAfter,
        reason: reason,
        description: description
    }

    return params;
}



/**
 * @author Phong-Kaster
 * @since 12-11-2022
 * @param {int} id is the of appointment record 
 */
let appointmentID;
function setupButton()
{
    /**BUTTON SAVE */
    $("#button-save").click(function(){
        let params = getNecessaryInfo();
        params["appointment_id"] = appointmentID;


        $.ajax({
            type: "POST",
            url: `${API_URL}/appointment-records`,
            data: params,
            dataType: "JSON",
            success: function(resp) 
            {
                if(resp.result == 1)
                {
                    showMessageWithButton('success','Thành công', resp.msg);
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
}