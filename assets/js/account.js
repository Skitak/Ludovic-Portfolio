import {ajaxForm} from './ajaxUtils'

export default function account() {
    ajaxForm("email_form")
    ajaxForm("password_form")

    setButtons ()
}

function setButtons(){
    $(".form-wrapper").on("click", function(){
        if (!$(this).hasClass("activeForm"))
            $(this).addClass("activeForm")
    })
    $(".form-reducer").on("click", function (event) {
        $(this).parent().removeClass("activeForm")
        event.stopImmediatePropagation()
    })
}
