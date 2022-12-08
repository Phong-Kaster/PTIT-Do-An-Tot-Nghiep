/**
 * @since 08-12-2022
 * get all photos that patient supplied before appointment is created by SUPPORTER
 * id is the id of booking
 */
function getBookingPhotos(id)
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/booking/photos/${id}`,
        dataType: "JSON",
        success: function(resp) {
        Swal.close();// close loading screen
        msg = resp.msg;
        if(resp.result == 1)
        {
            let data = resp.data;
            createPhotoTable(data);
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
 * @since 08-12-2022
 * createPhotoTable
 */
function createPhotoTable(data)
{
    let quantity = data.length;
    /**If quantity == 0 => show a message */
    if(quantity == 0)
    {
        Swal
        .fire({
            title: 'Thông báo',
            text: "Lịch khám này không có bất kì tư liệu/ hồ sơ khám bệnh nào",
            icon: 'question',
            confirmButtonText: 'Xác nhận',
            confirmButtonColor: '#FF0000',
            cancelButtonColor: '#0000FF',
            cancelButtonText: 'Hủy',
            reverseButtons: false,
            showCancelButton: false
        })
        .then((result) => 
            {
                if (result.isConfirmed) 
                {

                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
        return;
    }
    /**If quantity > 0 => print photo */
    for(let i = 0; i < data.length; i++)
    {
        let url = data[i].url;
        let body = `
            <a class="example-image-link" href="${API_URL}/assets/uploads/${url}" 
                data-lightbox="example-set" data-title="Nhấn bất kì vị trí nào bên ngoài tấm ảnh để thoát">
                    <img class="example-image" src="${API_URL}/assets/uploads/${url}" alt="Photo">
            </a>`;

        $(".container").append(body);
    }
}