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
}

function closeFilterMenu(){
    $(".filter").removeClass("active")
    $(".filters-menu").removeClass("active")
    $(".projects").removeClass("filtered")
    $(".filter-button").removeClass("selected")
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