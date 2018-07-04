import {ajaxFetchInformations} from "./ajaxUtils"

export default function contact(){
    ajaxFetchInformations($(".contact-button"),".front-project-wrapper" )
}
