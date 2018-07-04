import { EHOSTUNREACH } from "constants";

export default function filter(){
    $(".filter").on("click",function(e){
        if ($(this).hasClass("active"))
            closeFilterMenu()
        else
            openMenuFilter()
    })

    assignButtonEvents()
}

function openMenuFilter(){
    $(".filter").addClass("active")
    $(".filters-menu").addClass("active")
    $(".projects").addClass("filtered")
    removeContainers()
}

function closeFilterMenu(){
    $(".filter").removeClass("active")
    $(".filters-menu").removeClass("active")
    $(".projects").removeClass("filtered")
    $(".filter-button").removeClass("selected")
    addContainers()
}

function removeContainers(){
    $(".project").each(function(obj){
        $(this).appendTo(".projects")
    })
    $(".primary-project-wrapper").remove()
    $(".projects hr").remove()
    $(".secondary-projects-wrapper").remove()
    $(".all-projects-wrapper").remove()
    
}

function addContainers(){
    $(".projects").append(
    "<div class='primary-project-wrapper'>" +
    "</div>" +
    "<hr/>" +
    "<div class='secondary-projects-wrapper'>" +
    "</div>" +
    "<div class='all-projects-wrapper'>" +
    "</div>" )
    $(".projects > .project").first().appendTo(".primary-project-wrapper")
    $(".projects > .project").first().appendTo(".secondary-projects-wrapper")
    $(".projects > .project").first().appendTo(".secondary-projects-wrapper")
    $(".projects > .project").each(function(){
        $(this).appendTo(".all-projects-wrapper")
    })
}


function assignButtonEvents(){
    $(".context .filter-button").on("click", function(){
        if ($(this).hasClass("selected")){
            $(this).removeClass("selected")
            removeContext($(this).text())
        } else { 
            $(".context .filter-button").removeClass("selected")
            $(this).addClass("selected")
            addContext($(this).text())
        }
    })

    $(".roles .filter-button").on("click", function(){
        if ($(this).hasClass("selected")){
            $(this).removeClass("selected")
            removeRole($(this).text())
        } else { 
            $(this).addClass("selected")
            changeRole($(this).text())
        }
    })
}

function removeContext(value){

}

function addContext(value){

}

function removeRole(value) {

}

function changeRole(value){

}