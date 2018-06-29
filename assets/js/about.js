import {ajaxForm} from './ajaxUtils'

export default function aboutForm() {
    let formAboutTitle = $("#about_form_aboutTitle")
    let formAbout = $("#about_form_about")

    formAboutTitle.keyup(function(){
        $(".about h1").text($(this).val())  
    })

    formAbout.keyup(function(){
        $(".about p").text($(this).val())  
    })

    ajaxForm("about_form")
}