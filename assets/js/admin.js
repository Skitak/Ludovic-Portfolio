import {ajaxDelete} from './ajaxUtils';

export default function admin(){
    ajaxDelete($(".project-wrapper .project .cross"), 1, true)
    setDragDropOrder($(".project-wrapper .project"))
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
    obj.on("drop", swapProjectOrder) 
}

function swapProjectOrder(event){
    event.preventDefault()
    let copySelf = $(this).clone()
    let copyOther = draggedDiv.clone()

    $(this).replaceWith(copyOther)
    draggedDiv.replaceWith(copySelf)

    let idA = $(this).find(".hidden-id").text()
    let idB = draggedDiv.find(".hidden-id").text()

    $.ajax({
        url : window.location.origin + "/admin/project/swapOrder/" + idA + "/" + idB,
        // success: function (data) {console.log(data)},
        error: function (data, sdj, error) {alert(error)}
    })

    setDragDropOrder(copySelf)
    setDragDropOrder(copyOther)

}



