export default function indexFocus(){
    callAjax()
    upArrow()
}
var isArrowVisible = false

function callAjax(){
    let obj = $(".id") 
    obj.on("click", function(e){
        e.preventDefault()
        let urlIndex = $(this).prop("href")
        $.ajax({
            url: urlIndex,
            success: function (data){
                document.body.innerHTML += data
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
    callAjax()
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