const API_URL = "http://localhost:8080/PTIT-Do-An-Tot-Nghiep/API";
const APP_URL = "http://localhost:8080/PTIT-Do-An-Tot-Nghiep/umbrella-corporation";
const DEFAULT_LENGTH = 5;
/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * this function get current day with format yyyy-dd-mm. 
 * For instance, 2022-01-01
 */
function getCurrentDate()
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

    let date =  year + "-" + month + "-" + day;
    return date;
}