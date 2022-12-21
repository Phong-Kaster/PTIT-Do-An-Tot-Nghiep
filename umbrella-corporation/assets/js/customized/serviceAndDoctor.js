/**
 * @author Phong-Kaster
 * @since 12-12-2022
 * Service and Doctor Table
 */
function setupServiceAndDoctorTable(id)
{
    $.ajax({
    type: "GET",
    url: `${API_URL}/doctors-and-services/${id}`,
    dataType: "JSON",
    success: function(resp) {
        if(resp.result == 1)// result = 1
        {
            createServiceAndDoctorTable(resp);
            //pagination(url, resp.quantity, resp.data.length);
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
function createServiceAndDoctorTable(resp)
{
    $("tbody").empty();// empty the table
    /** loop resp to append into table */
    for(let i=0; i< resp.data.length; i++)
    {
        let avatar = resp.data[i].avatar != "" > 0 ? resp.data[i].avatar : "default_avatar.jpg";
        let doctorAndServiceId = resp.data[i].doctor_and_service_id;
        let doctorId = resp.data[i].id;
        let name = resp.data[i].name;
        let email = resp.data[i].email;
        let phone = resp.data[i].phone;
        let specialityName = resp.data[i].speciality.name;
        let body = `
        <tr data-id="${doctorAndServiceId}" class="align-middle">
            <td class="text-center" id="id">
                <div class="avatar avatar-md">
                    <img class="avatar-img" src="${API_URL}/assets/uploads/${avatar}" alt="avatar">
                </div>
            </td>

            <td class="fw-semibold">
            <div class="fw-semibold" id="speciality">${specialityName}</div>
            </td>

            <td>
            <div class="fw-semibold" id="name">${name}</div>
            </td>

            <td>
                <div class="fw-semibold" id="email">${email}</div>
            </td>

            <td>
                <div class="fw-semibold" id="phone">${phone}</div>
            </td>


            <td>
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <button id="button-delete" data-id="${doctorAndServiceId}" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                </div>
            </td>
        </tr>`;

        
        $("tbody").append(body);
    }
}

/**
 * @author Phong-Kaster
 * @since 12-12-2022
 */
function setupButton()
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
                    let url = API_URL+"/doctors-and-services/"+id;
                    let method = "delete";
                    $.ajax({
                        type: method,
                        url: url,
                        dataType: "JSON",
                        success: function(resp) {
                        if(resp.result == 1)
                        {
                            showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                            $(`tbody tr[data-id="${id}"]`).remove();
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
                else
                {
                    Swal.close();
                }
            });/**end Swal */
    });/** end BUTTON DELETE */
}


/**
 * @since 12-12-2022
 * tạo một dropdown các bác sĩ có thể thêm vào dịch vụ
 */
function setupDropdownDoctorReady(id)
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: "GET",
        url: `${API_URL}/doctors-and-services-ready/${id}`,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownDoctorReady(resp);
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
function createDropdownDoctorReady(resp)
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
 * @since 12-12-2022
 */
function setupButton(id)
{
    /**BUTTON CONFIRM */
    $(document).on('click', '#button-confirm', function(){
        let doctorId = $("#doctor").val();

        $.ajax({
            type: "POST",
            url: `${API_URL}/doctors-and-services/${id}`,
            data: { doctor_id: doctorId},
            dataType: "JSON",
            success: function(resp) {
            Swal.close();// close loading screen
            msg = resp.msg;
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                location.reload();
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
    });/**end BUTTON CONFIRM */

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
                    let url = API_URL+"/doctors-and-services/"+id;
                    let method = "delete";
                    $.ajax({
                        type: method,
                        url: url,
                        dataType: "JSON",
                        success: function(resp) {
                        if(resp.result == 1)
                        {
                            showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                            $(`tbody tr[data-id="${id}"]`).remove();
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
                else
                {
                    Swal.close();
                }
            });/**end Swal */
    });/** end BUTTON DELETE */
}