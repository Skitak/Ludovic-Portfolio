import {ajaxForm} from './ajaxUtils'
import Quill from "quill"

export default function aboutForm() {
    if( !$('.about-wrapper').length)
        return
    let formAboutTitle = $("#about_form_aboutTitle")
    let formAbout = $("#about_form_about")

    formAboutTitle.keyup(function(){
        $(".about h1").text($(this).val())  
    })

    formAbout.keyup(function(){
        $(".about p").text($(this).val())  
    })

    ajaxForm("about_form")
    aboutEditing()
}

function aboutEditing(){
    let options = {
        modules: {
            toolbar:[
                ['bold', 'italic', 'underline'],
                ['link', 'image'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'color': []}],
                [{ 'align': [] }],
            ]
        },
        placeholder : "Description ici",
        theme: "snow"
    }
    let editor = new Quill($("#about-editor").get(0), options)
    $("#about-editor .ql-editor").html($(".about-input").val())
    editor.on('text-change', function(delta, onDelta, source){
        $(".about-input").val($("#about-editor .ql-editor").html())
    })

}