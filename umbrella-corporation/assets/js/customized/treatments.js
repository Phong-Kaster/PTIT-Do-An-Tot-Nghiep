/**
 * @author Phong-Kaster
 * @since 13-11-2022
 */

let appointmentId;
let method;
let url;

function setupTreatmentTable(id)
{
    appointmentId = id;
    let order = {dir:"asc", column:"id"};
    let params = {
        appointment_id: appointmentId,
        order: order
    };


    $.ajax({
        type: "GET",
        url: `${API_URL}/treatments`,
        dataType: "JSON",
        data: params,
        success: function(resp) 
        {
            if(resp.result == 1)
            {
                createTreatmentTable(resp);
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
}


/**
 * @author Phong-Kaster
 * @since 13-11-2022
 */
function createTreatmentTable(resp)
{
    $("tbody").empty();// empty the table
    /** loop resp to append into table */
    for(let i=0; i< resp.data.length; i++)
    {
        let element = resp.data[i];
        let id = element.id;
        let name = element.name;
        let type = element.type;
        let times = element.times;
        let purpose = element.purpose;
        let instruction = element.instruction;
        let body = `
        <tr data-id="${id}" class="align-middle">
            <td class="text-center" id="id">
                ${id}
            </td>

            <td class="fw-semibold">
                <div class="fw-semibold" id="name">${name}</div>
            </td>

            <td>
                <div class="fw-semibold" id="type">${type}</div>
            </td>

            <td>
                <div class="fw-semibold" id="times">${times}</div>
            </td>

            <td>
                <div class="clearfix">
                    <div class="fw-semibold" id="purpose">${purpose}</div>
                </div>
            </td>

            <td>
                <div class="clearfix">
                    <div class="fw-semibold" id="instruction">${instruction}</div>
                </div>
            </td>

            

            <td>
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <button id="button-update" data-id="${id}" class="btn btn-outline-warning" type="button">Sửa</button>
                    <button id="button-delete" data-id="${id}" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                </div>
            </td>
        </tr>`;

        $("tbody").append(body);
    }
}


/**
 * @author Phong-Kaster
 * @since 13-11-2022
 * @returns 
 */
function getNecessaryInfo()
{
    let name = $("#form #name").val();
    let type = $("#form #type :selected").val();
    let times = $("#form #times").val();
    let purpose = $("#form #purpose").val();
    let instruction = $("#form #instruction").val();
    let repeatDays = $("#form #repeatDays").val();
    let repeatTime = $("#form #repeatTime").val();

    let params = {
        appointment_id: appointmentId,
        name: name,
        type: type,
        times: times,
        purpose: purpose,
        instruction: instruction,
        repeat_days: repeatDays,
        repeat_time: repeatTime
    }

    return params;
}


function reset()
{
    $("#name").val("");
    $("#type").val("");
    $("#times").val("");
    $("#purpose").val("");
    $("#instruction").val("");
    $("#form #id").val("");
}   


/**
 * @author Phong-Kaster
 * @since 13-11-2022
 */
function setupButton()
{

    /**BUTTON FORM */
    $("#button-form").click(function(){
        
        $("#form .modal-footer").find("#button-update-confirm").hide();
        $("#form .modal-footer").find("#button-create").show();
    })


    
    /**BUTTON CREATE */
    $(document).on('click',"#button-create",function(){
        let params = getNecessaryInfo();

        method = "POST";
        url = `${API_URL}/treatments`;

        $.ajax({
            type: method,
            url: url,
            data: params,
            dataType: "JSON",
            success: function(resp) 
            {
                if(resp.result == 1)
                {
                    showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                    reset();
                    setupTreatmentTable(appointmentId);
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



    /**BUTTON DELETE */
    $(document).on('click', "#button-delete", function(){
        let treatmentId = $(this).attr("data-id");
        
        method = "DELETE";
        url = `${API_URL}/treatments/${treatmentId}`;

        $.ajax({
            type: method,
            url: url,
            dataType: "JSON",
            success: function(resp) 
            {
                if(resp.result == 1)
                {
                    showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                    $("tbody").find(`tr[data-id="${treatmentId}"]`).remove();
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



    /**BUTTON UPDATE - this button just print data to FORM */
    $(document).on('click', "#button-update", function(){
        /**HIDE and SHOW corresponding buttons */
        $("#button-form").click();
        $("#form .modal-footer").find("#button-update-confirm").show();
        $("#form .modal-footer").find("#button-create").hide();


        /**PRINT DATA TO FORM */
        let treatmentId = $(this).attr("data-id");
        let selector = $("tbody").find(`tr[data-id="${treatmentId}"]`);


        let name = selector.find("#name").text();
        let type = selector.find("#type").text();
        let times = selector.find("#times").text();
        let purpose = selector.find("#purpose").text();
        let instruction = selector.find("#instruction").text();


        $("#form #name").val(name);
        $("#form #type").val(type);
        $("#form #times").val(times);
        $("#form #purpose").val(purpose);
        $("#form #instruction").val(instruction);
        $("#form #id").val(treatmentId);
    });



    /**BUTTON UPDATE CONFIRM - this button sends PUT request to server */
    $(document).on('click', "#button-update-confirm", function(){
        /**PREPARE DATA */
        let params = getNecessaryInfo();
        let id = $("#form #id").val();

        console.log(params);

        /**SET METHOD AND URL */
        method = "PUT";
        url = `${API_URL}/treatments/${id}`;


        /**AJAX */
        $.ajax({
            type: method,
            url: url,
            data: params,
            dataType: "JSON",
            success: function(resp) 
            {
                if(resp.result == 1)
                {
                    showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                    setupTreatmentTable(appointmentId);
                    reset();
                    $("#button-close").click();
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

