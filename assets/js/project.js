import {ajaxForm, ajaxDelete} from './ajaxUtils';
import Quill from "quill"
import 'slick-carousel'

export default function projectForm() { 
    if( !$('.project-edit-wrapper').length)
        return
    projectEditing()
    ajaxForm("project_form")
    ajaxForm("artwork_form", function (data) {
        // let array = JSON.parse(data[0])
        $.each(data, function(){
            let artwork = JSON.parse(this)[0]
            addArtwork(artwork)
        })
        
    })

    ajaxDelete($(".artwork .cross"), 1, false)
    ajaxFront($(".artwork .set-front"))
    setDragDropOrder($(".gallery .artwork"))
    setSlider()
    
}   

function projectEditing(){
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
    let editor = new Quill($("#description-editor").get(0), options)
    $("#description-editor .ql-editor").html($(".description-input").val())
    editor.on('text-change', function(delta, onDelta, source){
        $(".description-input").val($("#description-editor .ql-editor").html())
    })

}

function addArtwork(artwork){
    let divArt = $('<div></div>').addClass("artwork")
    let divFront = $('<a></a>').addClass("set-front").attr('href', "/admin/project/frontArtwork/" + artwork.id)
    divFront.append($('<img />').attr("src", "/images/projets/" + artwork.thumbnail))
    divArt.append($('<a>X</a>').addClass("cross").attr('href', "/admin/delete/artwork/"+ artwork.id))
    divArt.append(divFront)
    $('.gallery').prepend(divArt)
    ajaxDelete(divArt.find(".cross"), 1, false)
    ajaxFront(divArt.find(".set-front"))
}

function ajaxFront(div){
    div.on("click", function (){
        event.preventDefault()
        let urlFront = $(this).prop('href')
        $.ajax({
            url: urlFront,
            success: function (data){
                $(".project img").attr("src", "/images/projets/" + data[0])
                $(window).scrollTop(0);
            },
            error: function (data,fsd,error){
                alert (error)
            }
        })
    })
}

var draggedDiv
var isDragging = false

function setDragDropOrder(obj){
    isDragging = false
    obj.on("dragover", function(e){
        e.preventDefault()
        e.stopPropagation()
    })
    obj.on("dragenter", function(e){
        if (!isDragging){
            draggedDiv = $(this)
            isDragging = true;
        }
        e.preventDefault()
        e.stopPropagation()
    })
    obj.on("drop", swapImageOrder) 
}

function swapImageOrder(event){
    event.preventDefault()
    let copySelf = $(this).clone()
    let copyOther = draggedDiv.clone()

    $(this).replaceWith(copyOther)
    draggedDiv.replaceWith(copySelf)

    let idA = $(this).find(".hidden-id").text()
    let idB = draggedDiv.find(".hidden-id").text()

    $.ajax({
        url : window.location.origin + "/admin/image/swapOrder/" + idA + "/" + idB,
        success: function (data) {console.log(data)},
        error: function (data, sdj, error) {alert(error)}
    })

    setDragDropOrder(copySelf)
    setDragDropOrder(copyOther)

}

function setSlider(){
    $('.caroussel-wrapper').slick({
        dots: true,
        slidesToShow: 1,
        infinite: true,
        autoplay: true,
        arrows: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    arrows: false
                }
            },
        ]
    })
}
