/**
 * @author Phong-Kaster 
 * @since 07-11-2022
 */
function setupPatientTable(params)
{
    /**Step 1 - declare params */
    let url = `${API_URL}/patients`;

    /**Step 2 - ajax */
    $.ajax({
        type: "GET",
        url: url,
        data: params,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            createPatientTable(resp);
            pagination(url, resp.quantity, resp.data.length);
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
function createPatientTable(resp)
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
        let email = element.email;
        let phone = element.phone;
        let name = element.name;
        let gender = "Nam";
        if( element.gender != 1)
        {
            gender = "Nữ";
        }
        let birthday = element.birthday;
        let address = element.address;
        let avatar = element.avatar ? element.avatar : "default_avatar.jpg";
        let createAt = element.create_at;
        let updateAt = element.update_at;
        let id = element.id;
        let body = `
        <tr data-id="${id}" class="align-middle"><!-- TR -->
            <td class="text-center">
                <div class="avatar avatar-md">
                    <img class="avatar-img" src="${API_URL}/assets/uploads/${avatar}" alt="${name}">
                </div>
            </td>

            <td>
            <div class="fw-semibold" id="patient-name">${name}</div>
            <div class="small text-medium-emphasis fw-semibold" id="patient-birthday">Ngày sinh: ${birthday}</div>
            </td>

            <td class="fw-semibold">
                <div class="fw-semibold" id="patient-email">${email}</div>
            </td>

            <td>
            <div class="fw-semibold" id="patient-phone">${phone}</div>
            </td>

            <td>
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <a href="${APP_URL}/patient/${id}" data-id="${id}" class="btn btn-outline-success" type="button">Sửa</a>
                <a data-id="${id}" class="btn btn-outline-info" type="button" data-coreui-toggle="collapse" href="#patient-${id}" aria-expanded="false" aria-controls="#appointment-147">Chi tiết</a>
            </td>

        </tr><!-- end TR -->

        <tr data-id="${id}" class="collapse" id="patient-${id}">
            <td colspan="5">
                <table class="table">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Mã thẻ bảo hiểm y tế</th>
                        <th class="text-center" scope="col">Giới tính</th>
                        <th class="text-center" scope="col">Địa chỉ</th>
                        <th class="text-center" scope="col">Khởi tạo</th>
                        <th class="text-center" scope="col">Cập nhật lần cuối</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="align-middle">
                        <td class="text-center">${id}</td>
                        <td class="text-center">${gender}</td>
                        <td class="text-center">${address}</td>
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
 * @since 07-11-2022
 * @returns params to filter data
 */
 function getFilteringCondition()
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
         length: DEFAULT_LENGTH
     };
 
     return params;
 }


/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function setupButton()
{
    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#search").val("");
        $("#order-dir").val("");
        $("#order-column").val("");

        let order = { column:"id", dir:"asc"}
        let params = {
            order: order,
            length: DEFAULT_LENGTH
        }
        setupPatientTable(params);
    });

    /**BUTTON SEARCH */
    $("#button-search").click(function(){
        let params = getFilteringCondition();
        setupPatientTable(params);
    })
}


 /**
 * @author Phong-Kaster
 * @since 07-11-2022
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
                          createPatientTable(resp);
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
                         
                          createPatientTable(resp);
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