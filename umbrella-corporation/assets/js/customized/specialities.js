/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function setupSpecialityTable(params)
{
    /**Step 1 - declare params */
    let url = `${API_URL}/specialities`;

    /**Step 2 - ajax */
    $.ajax({
        type: "GET",
        url: url,
        data: params,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            createSpecialityTable(resp);
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            showMessageWithButton('error','Thất bại', err);
        }
    });
}

/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function createSpecialityTable(resp)
{
    let size = resp.data.length;
    if( size == 0)
    {
        return;
    }
    $("tbody").empty();
    for(let i = 0; i< size; i++)
    {
        let element = resp.data[i];
        let specialityId = element.id;
        let specialityName = element.name;
        let specialityDescription = element.description;
        let specialityDoctorQuantity = element.doctor_quantity;
        let body = 
            `<tr data-id=${specialityId} class="align-middle">
                <td class="text-center" id="speciality-id">
                    
                </td>

                <td class="fw-semibold">
                <div class="fw-semibold" id="speciality-id">${specialityId}</div>
                </td>


                <td>
                    <div class="fw-semibold" id="speciality-name">${specialityName}</div>
                </td>

                <td>
                    <div class="fw-semibold" id="speciality-doctor-quantity">${specialityDoctorQuantity}</div>
                </td>

                <td>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <button id="button-cancel" data-id=${specialityId} class="btn btn-outline-warning" type="button">Sửa</button>
                        <button id="button-delete" data-id=${specialityId} class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                    </div>
                </td>
            </tr>`;
        $("tbody").append(body);
    }
}


function getNecessaryParams()
{
    let search         = $("#search").val();
    let orderDir       = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
    let orderColumn    = $("#order-column :selected").val() ? $("#order-column :selected").val() : "id";


    /**Step 2 - set up parameters */
    let order = {
        "dir": orderDir,
        "column": orderColumn
    };
    let params = {
        search: search,
        order: order,
    };

    return params;
}

/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function setupButton()
{
    /**BUTTON DELETE on ELEMENT */
    $(document).on('click','#button-delete',function(){
        let id = $(this).attr("data-id");

        Swal
        .fire({
            title: 'Bạn chắc chắn muốn thực hiện hành động ngày',
            text: "Tạo thứ tự khám đồng nghĩa sẽ hoàn thành lịch hẹn này",
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
                    /*update booking status from PROCESSING to VERIFIED */
                    $.ajax({
                        type: "DELETE",
                        url: `${API_URL}/specialities/${id}`,
                        dataType: "JSON",
                        success: function(resp) {
                            if( resp.result == 1)
                            {
                                showMessageWithButton('success','Thành công', resp.msg);
                                $(`tbody tr[data-id="${id}"]`).remove();
                            }
                            else
                            {
                                showMessageWithButton('info','Không thành công', resp.msg);
                            }
                            
                        },
                        error: function(err) {
                            showMessageWithButton('error','Thất bại', err);
                        }
                    })
                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
    });

    /**BUTTON SEARCH */
    $("#button-search").click(function(){
        let params = getNecessaryParams();
        setupSpecialityTable(params);
    })

    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#search").val("");
        $("#order-dir").val("");
        $("#order-column").val("");

        let order = { column:"id", dir:"asc"}
        let params = {
            order: order
        }
        setupSpecialityTable(params);
    })
}