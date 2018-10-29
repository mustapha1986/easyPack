// this variable is the list in the dom, it's initiliazed when the document is ready
var $collectionHolder;
// the link which we click on to add new items
$('#exp_list').html("");
// when the page is loaded and ready
$(document).ready(function () {


    $('.carriers , #clientHasCarrier_client').on('change', function () {
        var carrier = $(".carriers").val();
        var client = $("#clientHasCarrier_client").val();
        console.log(carrier);
        $.ajax({
            url: '/form',
            type: "POST",
            dataType: "html",
            data: {
                "client": client,
                "type": carrier

            },
            async: true,
            success: function (data) {

                $('#exp_list').html(data);

            }
        });
        return false;

    });


// click voir details transporteur
    $('.details').on('click', function () {
        var id = $(this).attr('data-id');
        console.log(id);
        $.ajax({
            url: '/detail/' + id,
            type: "POST",
            dataType: "html",
            data: {
                "id": id
            },
            async: true,


            success: function (data, status, jqXHR) {
                $('#modal').html(data);
            },
            error: function (jqXHR, status, err) {
                alert("Local error callback.");
            },
            complete: function (jqXHR, status) {
                console.log("ok")
                $('#configDetails' + id).modal('show');
            }

        });
        return false;

    });


    // edit transporteur
    $('.transporteurEdit').on('click', function () {
        var id = $(this).attr('data-id');

        $.ajax({
            url: '/edit/' + id,
            type: "POST",
            dataType: "html",
            data: {
                "id": id
            },
            async: true,


            success: function (data, status, jqXHR) {
                $('#modal').html(data);
            },
            error: function (jqXHR, status, err) {

            },
            complete: function (jqXHR, status) {
                $('#configDetails' + id).modal('show');
            }

        });
        return false;

    });


    // modal suppression
    $('.transporteurDelete').on('click', function () {
        var id = $(this).attr('data-id');

        $.ajax({
            url: '/deleteModal/' + id,
            type: "POST",
            dataType: "html",
            data: {
                "id": id
            },
            async: true,


            success: function (data, status, jqXHR) {
                $('#modal').html(data);
            },
            error: function (jqXHR, status, err) {

            },
            complete: function (jqXHR, status) {
                $('#modalDelete').modal('show');
            }

        });
        return false;

    });


    // suppression

    $('body').on('click', '.supprimer', function () {
        var id = $(this).attr('data-id');

        $.ajax({
            url: '/delete/' + id,
            type: "POST",
            dataType: "json",
            data: {
                "id": id
            },
            async: true,


            success: function (data, status, jqXHR) {

                $('#modalDelete').modal('hide');


            },
            error: function (jqXHR, status, err) {

            },
            complete: function (jqXHR, status) {
                var tr = $(".elem" + id).closest('tr');
                tr.css("background-color", "#FF3700");

                tr.fadeOut(400, function () {
                    tr.remove();
                });
                return false;
            }

        });
        return false;

    });



    // hide message flashBag

    (function($) {

        $.fn.fadeDelay = function(delay) {

            var that = $(this);
            delay = delay || 3000;

            return that.each(function() {

                $(that).queue(function() {

                    setTimeout(function() {

                        $(that).dequeue();

                    }, delay);
                });

                $(that).fadeOut('slow');
            });
        };

    })(jQuery);

    $('.flash-notice').fadeDelay(3000);
});

