var roles = []
var context = []
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
    $(".project").removeClass("not-visible")
    $(".filter-button").removeClass("selected")
    addContainers()
}

function removeContainers(){
    $(".project").each(function(obj){
        $(this).appendTo(".projects")
    })
    $(".primary-project-wrapper").remove()
    $(".projects > hr").remove()
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
            addRole($(this).text())
        }
    })
}

function removeContext(value){
    context.splice(context.indexOf(value), 1)
    modifyProjectVisibility()
}

function addContext(value){
    context = []
    context.push(value)
    modifyProjectVisibility()
}

function removeRole(value) {
    roles.splice(roles.indexOf(value), 1)
    modifyProjectVisibility()
}

function addRole(value){
    roles.push(value)
    modifyProjectVisibility()
}

function modifyProjectVisibility(){
    $(".project").each(function(){
        if (hasAllRoles($(this)) && hasContext($(this)))
            $(this).removeClass("not-visible")
        else if (!$(this).hasClass("not-visible"))
            $(this).addClass("not-visible")
    })
}

function hasAllRoles(obj){
    if (roles.length == 0)
        return true
    let foundAll = true
    let objectRoles = []
    obj.find(".role").each(function(){
        objectRoles.push($(this).text())
        // if (roles.indexOf($(this).text()) == -1)
        //     foundAll = false
    })
    roles.forEach(function(element){
        if (!objectRoles.includes(element))
            foundAll = false
    })
    return foundAll
}

function hasContext(obj){
    if (context.length == 0)
    return true
    let found = false
    obj.find(".context").each(function(){
        if (context.indexOf($(this).text()) != -1)
            found = true
    })
    return found
}