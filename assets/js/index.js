export default function indexFocus(){
    fetchProjectInformations()
    upArrow()
}
var isArrowVisible = false

function fetchProjectInformations(){
    let obj = $(".id") 
    obj.on("click", function(e){
        e.preventDefault()
        putLoadingIcon()
        let urlIndex = $(this).prop("href")
        $.ajax({
            url: urlIndex,
            success: function (data){
                $(".front-project-wrapper").html(data)
                addEvents()
            },
            error: function (data,fsd,error){
                alert (error)
            }
        })
    })
    
}

function addEvents(){
    $(".front-project-wrapper").on("click", function(){
        $(this).remove()
    })
    $(".front-project").on("click", function(e){
        e.stopPropagation()
    }).on("scroll", function(e){
        e.stopPropagation()
    })
    fetchProjectInformations()
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
        console.log(newPosition)

    })
}

function putLoadingIcon(){
    document.body.innerHTML +=
    "<div class='front-project-wrapper'>" +
    "<div class='loading-icon'></div>" + 
    "</div>"
}