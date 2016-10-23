$(document).ready(function() {
    if($("#slider li").length > 1) {
        setInterval("content('slider')", 5000);
    }

    $("form").ajaxForm({dataType:  'json', success:   processJson});
    function processJson(data) {
        if (data == 1){
            window.location.reload()
        }else{
            $("#authorError").html(data.author)
        }
    }
});

function content(id) {
    var c = $("#" + id + " li.current").index();
    var n = $("#" + id + " li").length;
    if(c < n-1) {
        $("#" + id + " li").removeClass('current').fadeOut(100);
        $("#" + id + " li:eq(" + ((c + 1) / 1) + ")").addClass('current').hide().delay(100).fadeIn(200);
    }
    else {
        $("#" + id + " li").removeClass('current').fadeOut(100);
        $("#" + id + " li:eq(0)").addClass('current').hide().delay(100).fadeIn(300);
    }
}