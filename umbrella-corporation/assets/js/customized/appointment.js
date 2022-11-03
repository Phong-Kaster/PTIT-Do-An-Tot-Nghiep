/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup date picker
 */
 function setupDatePicker()
 {
     let today = new Date();
     let year = today.getFullYear();
     let month = today.getMonth()+1;
     let day = today.getDate();
     if( month < 10)
     {
         month = "0" + month;
     }
     if( day < 10)
     {
         day = "0" + day;
     }
 
     date = year + "-" + month + "-" + day;
     $("#datepicker").val(date);
     $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd'});
 }


$(document).ready(function(){
    setupDatePicker();
})