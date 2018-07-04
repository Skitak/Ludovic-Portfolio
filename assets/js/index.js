import {ajaxFetchInformations} from "./ajaxUtils"

export default function indexFocus(){
    setupWrapper()
    ajaxFetchInformations($(".id"), ".front-project-wrapper")
    upArrow()
}
var isArrowVisible = false

function setupWrapper(){
    $(".front-project-wrapper").on("click", function(){
        $(this).addClass("not-visible")
    }).on("scroll", function(e){
        e.preventDefault()
    })
}

function upArrow(){
    $(document).on("scroll", function(e){
        let newPosition = $(window).scrollTop() 
        let visibilityThreshold = 450
        if ( newPosition > visibilityThreshold && !isArrowVisible){
            isArrowVisible = true
            $(".go-top").removeClass("not-visible");
        } else if (newPosition < visibilityThreshold && isArrowVisible) {
            $(".go-top").addClass("not-visible");
            isArrowVisible = false
        }

    })
}
