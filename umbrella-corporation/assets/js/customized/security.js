/**
 * @author Phong-Kaster
 * @since 10-11-2022
 */
function getNecessaryInfo()
{
    let currentPassword = $("#current-password").val();
    let newPassword = $("#new-password").val();
    let confirmPassword = $("#confirm-password").val();

    let params = {
        action: "password",
        currentPassword: currentPassword,
        newPassword: newPassword,
        confirmPassword: confirmPassword
    }

    return params;

}

/**
 * @author Phong-Kaster
 * @since 10-11-2022
 */
function setupButton(email)
{
    /**BUTTON CONFIRM */
    $("#button-confirm").click(function(){
        let params = getNecessaryInfo();
        


        $.ajax({
            type: "POST",
            url: `${API_URL}/doctor/profile`,
            data: params,
            dataType: "JSON",
            success: function(resp) {
            if(resp.result == 1)
            {
                Swal
                .fire({
                    title: 'Mật khẩu đã được thay đổi thành công',
                    text: "Nhấn tiếp tục để đăng nhập lại với mật khẩu mới",
                    icon: 'success',
                    confirmButtonText: 'Xác nhận',
                    confirmButtonColor: '#FF0000',
                    cancelButtonColor: '#0000FF',
                    cancelButtonText: 'Hủy',
                    reverseButtons: false
                })
                .then((result) => 
                    {
                        if (result.isConfirmed) 
                        {
                            $("#current-password").val("");
                            $("#new-password").val("");
                            $("#confirm-password").val("");


                            let password = params.newPassword; 
                            login(email, password);
                        } 
                        else
                        {
                            Swal.close();
                        }
                    });
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

/**
 * @author Phong-Kaster
 * @since 10-11-2022
 * @param {} email 
 * @param {*} password
 * after changing password successfully, making a login 
 * request to have a new access token 
 */
function login(email, password)
{
    let params = {
        action: "login",
        email: email,
        password: password
    }


    $.ajax({
        type: "POST",
        url: `${APP_URL}/login`,
        data: params,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            console.log(resp);
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            console.log(err.responseText);
            //showMessageWithButton('error','Thất bại', err);
        }
    });
}