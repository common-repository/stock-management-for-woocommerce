//This function displays a background coverlay over the plugin content
function mp_stoman_shared_displayoverlay() {

    jQuery("#mp-stoman-ajaxloader").fadeIn(200);
}


//This function removes an overlay previously displayed
function mp_stoman_shared_removeoverlay() {

    jQuery("#mp-stoman-ajaxloader").fadeOut(200);

}


function mp_stoman_maxLengthCheck(object) {

    if (object.value.length > object.max.length)
        object.value = object.value.slice(0, object.max.length)

    if (object.value.length > object.min.length)
        object.value = object.value.slice(0, object.min.length)

}

function mp_stoman_isNumeric(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /^-?\d*\.?\d*$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
    }
}


//displays a notify popup
function mp_stoman_shared_displaynotify(message) {

    notifyClassName = "minimalistgreen";
    valueDelay = 2000;

    return jQuery.notify(
    {
        message: message,
    },
    {
        type: notifyClassName,
        template: '<div data-notify="container" class="row alert alert-{0}" role="alert">' +
                        '<span data-notify="message">{2}</span>' +
                  '</div>',
        animate:
        {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        offset: 10,
        z_index: 99931,
        delay: valueDelay,
        placement:
        {
            from: "bottom",
            align: "right"
        }
    });

}
