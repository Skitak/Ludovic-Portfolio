import {ajaxForm, ajaxDelete} from './ajaxUtils';

export default function projectForm() { 
    // ProjectDynamicEditing()
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
    
}   

function ProjectDynamicEditing(){
    let formTitle = $("#project_form_name")
    let formDescription = $("#project_form_description")

    formTitle.keyup(function(){
        $(".project h1").text($(this).val())  
    })

    formDescription.keyup(function(){
        $(".project p").text($(this).val())  
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
