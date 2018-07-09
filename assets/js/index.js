import {ajaxFetchInformations} from "./ajaxUtils"

var isArrowVisible = false

function setupWrapper(){
    if ($(".caroussel-wrapper").length)
        addCaroussel()
    else {
        $(".front-project-wrapper").on("click", function(){
            $(this).removeClass("visible")
            $(".contact-button").removeClass("active")
        }).on("scroll", function(e){
            e.preventDefault()
        })
    }
    return
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

function addCaroussel(data = null){
    $(".caroussel-wrapper").slick({
        dots: true,
        slidesToShow: 1,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        // adaptiveHeight: true
    })
}


export default function indexFocus(){
    setupWrapper()
    ajaxFetchInformations($(".id"), ".front-project-wrapper", addCaroussel)
    upArrow()
}