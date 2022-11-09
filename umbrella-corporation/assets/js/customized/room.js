/**
 * @author Phong-Kaster
 * @since 06-11-2022
 * setup booking info
 */
 function setupBookingInfo(id)
 {
     $.ajax({
         type: "GET",
         url: `${API_URL}/rooms/${id}`,
         dataType: "JSON",
         success: function(resp) {
         if(resp.result == 1)
         {
             let location = resp.data.location;
             let name = resp.data.name;
             let completeLocation = location + ", " + name;
 
             $("#complete-location").val(completeLocation);
             $("#name").val(name);
         }
         else
         {
             window.location = `${APP_URL}/error`;
         }
         },
         error: function(err) {
             Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
         }
     });
 }

/**
 * @author Phong-Kaster
 * @since 09-11-2022
 */
function setupButton(id)
{
    /**BUTTON SAVE */
    $("#button-confirm").click(function(){
        let area = $("#area :selected").text();
        let floor = $("#floor :selected").text();
        let name = $("#name").val();
        let location = area + ", " + floor;


        let params = { name: name, location: location }
        let url = id > 0 ? `${API_URL}/rooms/${id}` : `${API_URL}/rooms`;
        let method = id > 0 ? "PUT" : "POST";
        let msg = method == "PUT" ? "Cập nhật thành công !" : "Tạo mới thành công !";
        

        $.ajax({
            type: method,
            url: url,
            data: params,
            dataType: "JSON",
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công', msg);
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


    /**BUTTON CANCEL */
    $("#button-cancel").click(function(){
        Swal
        .fire({
            title: 'Bạn chắc chắn muốn huỷ bỏ',
            text: "Hành động này không thể khôi phục sau khi thực hiện",
            icon: 'question',
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
                    window.location.href = `${APP_URL}/rooms`;
                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
    });

    /**DROPDOWN AREA */
    $("#area").on('change', function(element){
        let area = $("#area :selected").text();
        let floor = $("#floor :selected").text();
        let name = $("#name").val();

        let completeLocation = area + ", " + floor + ", " + name;
        $("#complete-location").val(completeLocation);
    })

    /**DROPDOWN AREA */
    $("#floor").on('change', function(element){
        let area = $("#area :selected").text();
        let floor = $("#floor :selected").text();
        let name = $("#name").val();

        let completeLocation = area + ", " + floor + ", " + name;
        $("#complete-location").val(completeLocation);
    })

    /**DROPDOWN AREA */
    $(document).on('keypress',function(e) {
        if(e.which == 13) 
        {
            let name = $("#name").val();
            let area = $("#area :selected").text();
            let floor = $("#floor :selected").text();


            let completeLocation = area + ", " + floor + ", " + name;
            $("#complete-location").val(completeLocation);
        }
    });
}