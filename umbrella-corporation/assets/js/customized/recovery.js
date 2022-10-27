/**
         * @author Phong-Kaster
         * @since 27-10-2022
         * @params email is the email address of user
         * @params id is the id of user in DOCTOR table
         * redirect to password reset page
         */
 function redirectToPasswordReset(email, id)
 {
     console.log(email);
     console.log(id);
 }

 $(document).ready(function() {
     /**BUTTON CONFIRM SEND EMAIL RECOVERY */
     $("#submitButton").click(function(){
         /**Step 1 - declare */
         let email = $("#email").val();
         
         let data = {
             email: email
         };


         /**Step 2 - show loading */
         Swal.fire({
         title: 'Hệ thống đang xử lý yêu cầu của bạn',
         html: 'Xin vui lòng đợi trong giây lát...',
         allowEscapeKey: false,
         allowOutsideClick: false,
         didOpen: () => {
             Swal.showLoading()
         }
         });


         /**Step 3 - make AJAX request */
         let title = 'success';
         let msg = "";
         $.ajax({
             type: "POST",
             url: API_URL + "/recovery",
             data: data,
             dataType: "JSON",
             success: function(resp) {
                 Swal.close();// close loading screen
                 msg = resp.msg;
                 if(resp.result == 1)// result = 1
                 {
                     Swal
                     .fire({
                         title: 'Success',
                         text: "Mã xác thực đã được gửi tới email của bạn. Hãy kiểm tra Gmail để lấy mã xác thực và nhập ở trang tiếp theo",
                         icon: 'success',
                         confirmButtonText: 'Xác nhận',
                         confirmButtonColor: '#FF0000',
                         reverseButtons: false
                     })
                     .then((result) => 
                         {
                             if (result.isConfirmed) 
                             {
                                 let id = resp.result.id;
                                 redirectToPasswordReset(email, id);
                             } 
                             else
                             {
                                 Swal.close();
                             }
                         });
                 }
                 else// result = 0
                 {
                     title = 'error';
                     Swal.fire({
                     position: 'center',
                     icon: 'warning',
                     title: 'Warning',
                     text: msg,
                     showConfirmButton: false,
                     timer: 1500
                     });
                 }
             },
             error: function(err) {
                 Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
             }
         });
     });
     /**end BUTTON CONFIRM SEND EMAIL RECOVERY */
 });