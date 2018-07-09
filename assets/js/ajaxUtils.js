/**
 *  Ajax utils is a page that possess functions that might be usefull throughout the project
 */

import {notice, error} from './notifications';

let isLoading = false

export function ajaxForm(formName, successDataFunction = null) {
    $('form[name="'+ formName + '"]').on('submit', function (event) {
        event.preventDefault()
        let urlForm = $(this).prop('action')?
            $(this).prop('action') : window.location.href
        loading(formName)
        $.post({
            url: urlForm ,
            data: new FormData($(this)[0]),
            processData : false,
            contentType:false,
            cache: false,
            success: function (data) {
                ajaxSuccess (data, formName, successDataFunction)
            },
            error : function (data, thing, error) {
                ajaxError (error, formName)
            }
        })
    })
}
function loading(formName){
    $('form[name="'+ formName + '"]').find('button[type="submit"]').addClass("loading").attr("disabled", true)
}

function loadingMiscelaniou(){

}

function ajaxSuccess(data, formName, successDataFunction){
    let state = "finished"
    if (successDataFunction != null)
        successDataFunction(data)
    else{
        if (data["state"] == "success"){
            notice(data["message"])
        }
        else{
            error(data["message"])
                
            state="errorLoading"
        }
    }

    $('form[name="'+ formName + '"]').find('button[type="submit"]').removeClass("loading").addClass(state)
    window.setTimeout(function(){
        $('form[name="'+ formName + '"]').find('button[type="submit"]').removeClass(state).attr("disabled", false)
    }, 2000);
}

function ajaxError( errorForm, formName) {
    error(errorForm)
    $('form[name="'+ formName + '"]').find('button[type="submit"]').removeClass("loading").addClass("errorLoading")
    window.setTimeout(function(){
        $('form[name="'+ formName + '"]').find('button[type="submit"]').removeClass("errorLoading").attr("disabled", false)
    }, 2000);
}

export function ajaxDelete(selector, parentHeight = 0, demandConfirmation = true) {
    selector.on("click", function (){
        event.preventDefault()
        if (isLoading)
            return
        isLoading = true
        if (demandConfirmation && !confirm("Voulez vous vraiment faire ça?"))
            return
        let urlDelete = $(this).prop('href')
        let div = $(this)
        $.ajax({
            url: urlDelete,
            success: function (data){
                for(let height = parentHeight; height > 0; height--)
                    div = div.parent() 
                div.remove()
                isLoading = false;
            },
            error: function (data,fsd,error){
                alert (error)
                isLoading = false;
            }
        })
    })
}

export function ajaxFetchInformations(link, wrapper, onSuccess = null){
    link.on("click", function(e){
        e.preventDefault()
        putLoadingIcon(wrapper)
        let urlIndex = $(this).prop("href")
        $.ajax({
            url: urlIndex,
            success: function (data){
                $(wrapper).html(data).
                children().on("click", function(e){
                    e.stopPropagation()
                })
                if (onSuccess != null)
                onSuccess(data)
            },
            error: function (errorMessage){
                error(errorMessage)
                $(wrapper).addClass("not-visible")
            }
        })
    })
}

function putLoadingIcon(wrapper){
    $(wrapper).html("<div class='loading-icon'></div>").removeClass("not-visible")
    
}