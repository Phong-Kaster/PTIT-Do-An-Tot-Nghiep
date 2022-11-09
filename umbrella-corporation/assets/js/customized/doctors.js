/**
 * @author Phong-Kaster
 * @since 09-11-2022
 * @param {} url is the API_URL to make AJAX
 * this function always get appointments created today
 */
 function setupDoctorTable(url, params)
 {
     //console.log(params);
     /**Step 1 - set title for the table */
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
                //console.log(resp);
                 createDoctorTable(resp);
                 //pagination(url, resp.quantity, resp.data.length);
           }
           else// result = 0
           {
                 showMessageWithButton('error','Thất bại', resp.msg);
           }
       },
       error: function(err) {
            console.log(err.responseText);
            showMessageWithButton('error','Thất bại', err);
       }
     })//end AJAX
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
                         createDoctorTable(resp);
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
                        
                         createDoctorTable(resp);
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
 * @since 09-11-2022
 * setup date picker
 */
 function createDoctorTable(resp)
 {
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
         let avatar = resp.data[i].avatar ? resp.data[i].avatar : "default_avatar.jpg";
         let speciality = resp.data[i].speciality.name;
         let name = resp.data[i].name;
         let birthday = resp.data[i].birthday;
         let phone = resp.data[i].phone;
         let role = resp.data[i].role;
         switch(role){
            case "admin":
                role = "Trưởng khoa";
                break;
            case "supporter":
                role = "Hỗ trợ viên";
                break;
            case "member":
                role = "Bác sĩ";
                break;
            default:
                role = "Bác sĩ";
                break;
         }
         let status = `<div class="clearfix">
                        <i class="text-center bi bi-shield-slash-fill text-danger" alt="Vô hiệu hóa"></i>
                    </div>`;
        if(resp.data[i].active == 1 )
        {
            status = `<div class="clearfix">
                        <i class="text-center bi bi-shield-fill-check text-success" alt="Đang hoạt động"></i>
                    </div>`;
        }
        let id = resp.data[i].id;
        let email = resp.data[i].email;
        let price = resp.data[i].price;
        let room = resp.data[i].room.location + ", " + resp.data[i].name;
        let createAt = resp.data[i].create_at;
        let updateAt = resp.data[i].update_at;
        let body = 
        `<tr data-id="${id}" class="align-middle">
                <td class="text-center" id="id">
                    <div class="avatar avatar-md">
                        <img class="avatar-img" src="${API_URL}/assets/uploads/${avatar}" alt="avatar">
                    </div>
                </td>

                <td class="fw-semibold">
                <div class="fw-semibold" id="speciality">${speciality}</div>
                </td>

                <td>
                <div class="fw-semibold" id="name">${name}</div>
                </td>

                <td>
                <div class="fw-semibold" id="phone">${phone}</div>
                </td>

                <td>
                    <div class="clearfix">
                        <div class="fw-semibold" id="role">${role}</div>
                    </div>
                </td>

                <td id="status">
                    ${status}
                </td>



                <td>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="${APP_URL}/doctor/${id}" class="btn btn-outline-primary" type="button">Sửa</a>
                    <button id="button-delete" data-id="${id}" class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                    <button class="btn btn-outline-info" data-coreui-toggle="collapse" href="#doctor-${id}" aria-expanded="false" aria-controls="#doctor-${id}">Chi tiết</button>
                </td>
            </tr>
            <tr data-id="${id}" class="collapse" id="doctor-${id}">
                <td colspan="10">
                    <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">Email</th>
                            <th class="text-center" scope="col">Giá</th>
                            <th class="text-center" scope="col">Phòng khám</th>
                            <th class="text-center" scope="col">Khởi tạo</th>
                            <th class="text-center" scope="col">Cập nhật lần cuối</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td class="text-center">${id}</td>
                            <td class="text-center">${email}</td>
                            <td class="text-center">${price}</td>
                            <td class="text-center">${room}</td>
                            <td class="text-center">${createAt}</td>
                            <td class="text-center">${updateAt}</td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            </tr>`;
        
         
         $("tbody").append(body);
    }
 }


 /**
 * @author Phong-Kaster
 * @since 09-11-2022
 * @returns Object params contains filtering conditions
 */
  function getFilteringCondition()
  {
      let search         = $("#search").val() ? $("#service :selected").val() : "";
      let orderDir       = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
      let orderColumn    = $("#order-column :selected").val() ? $("#order-column :selected").val() : "id";
      let status         = $("#status :selected").val() ? $("#status :selected").val() : "0";
      let specialityId      = $("#speciality :selected").val() ? $("#speciality :selected").val() : "";
      let roomId      = $("#room :selected").val() ? $("#room :selected").val() : "";
      let length        = $("#length :selected").val() ? $("#length :selected").val() : "";
  
      /**Step 2 - set up parameters */
      let order = {
          "dir": orderDir,
          "column": orderColumn
      };
      let params = {
          search: search,
          order: order,
          length: length,
          speciality_id: specialityId,
          room_id: roomId,
          active: parseInt(status)
      };
  
      return params;
  }


 /**
 * @author Phong-Kaster
 * @since 01-11-2022
 * listen SEARCH and RESET button
 * if we click it on, we will make an AJAX request
 */
  function setupButton()
  {
      /**BUTTON RESET */
      $("#button-reset").click(function(){
          $("#search").val("");
          $("#order-dir").val("");
          $("#order-column").val("");
          $("#status").val("");
          $("#room").val("");
          $("#speciality").val("");
          $("#length").val("");
          
  
          let url = API_URL + "/doctors";

          let params = { length:DEFAULT_LENGTH }
          setupDoctorTable(url, params);
      });
  
  
      /** BUTTON SEARCH*/
      $("#button-search").click(function(){
          /**Step 1 - get filter values */
          let params = getFilteringCondition();
         
          
          /**Step 2 - query */
          let url = API_URL + "/doctors";
          setupDoctorTable(url, params);
      });

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
                    let url = `${API_URL}/doctors/${id}`;
                    let method = "DELETE";
                    let params = {};
                    makeDoctorAction(method, url, id, params );
                } 
                else
                {
                    Swal.close();
                }
            });
        
    });

  }


function makeDoctorAction(method, url, id, params = [])
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: method,
        url: url,
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                if( resp.type == "delete")
                {
                    $("tbody").find("tr[data-id="+id+"]").remove();
                }
                else
                {
                    $(`tbody tr[data-id="${id}"]`).find("td#status").replaceWith(
                        `<td id="status">
                            <div class="clearfix">
                                <i class="text-center bi bi-shield-slash-fill text-danger" alt="Vô hiệu hóa"></i>
                            </div>
                        </td>`);
                }
                
                showMessageWithButton('success','Thành công', resp.msg);
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