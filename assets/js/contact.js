import {ajaxFetchInformations} from "./ajaxUtils"

export default function contact(){
    $(".contact-button").on("click", function(){
        $(this).addClass("active")
    })
    ajaxFetchInformations($(".contact-button"),".front-project-wrapper" )
}
