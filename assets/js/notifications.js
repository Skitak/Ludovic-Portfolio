/**
 * 
 * All these functions add a notification to the page
 */


 /**
  * Grey notification, for basic user feedback
  */
export function notice(message) {
    let notif = $('<div class="notification"></div>').addClass('notice')
    notif.append($('<p></p>').text(message))
    setTimeout(function() {
        notif.remove()
    }, 5000);
    $('body').prepend(notif)
}

/**
 *  Blue notification
 *  For important user feedback informations
 */
export function primary(message) {
    let notif = $('<div class="notification"></div>').addClass('primary')
    notif.append($('<p></p>').text(message))
    setTimeout(function() {
        notif.remove()
    }, 5000);
    $('body').prepend(notif)
}

/**
 *  Red notification
 *  For errors :|
 */
export function error(message) {
    let notif = $('<div class="notification"></div>').addClass('error')
    notif.append($('<p></p>').text(message))
    setTimeout(function() {
        notif.remove()
    }, 5000);
    $('body').prepend(notif)
}

/**
 *  Green notification
 *  Because I felt like having a green notification
 */
export function success(message) {
    let notif = $('<div class="notification"></div>').addClass('success')
    notif.append($('<p></p>').text(message))
    setTimeout(function() {
        notif.remove()
    }, 5000);
    $('body').prepend(notif)
}

/**
 *  Used to remove notifications embedded in view
 */
export function notifications (){
    setTimeout(function() {
        $(".notification").remove()
    }, 5000);
}

