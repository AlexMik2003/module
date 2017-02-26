$(document).ready(function() {
    function rss() {
        $('#overlay').fadeIn(400,function(){
                $('#modal_form')
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
            });
    }

    //setInterval(rss,15000);

    $('#modal_close, #overlay').click( function(){
        $('#modal_form')
            .animate({opacity: 0, top: '45%'}, 200,
                function(){ // пoсле aнимaции
                    $(this).css('display', 'none');
                    $('#overlay').fadeOut(400);
                }
            );
    });

    if(this.close())
    {
        confirm("are you sure?");
    }

    $(".r1").on("mouseenter", function () {
        var price = $("#price").text();
        price = price-(price*0.1);
        $("#price").text(price).css({"color":"red", "font-size": "22px"});
        function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 10; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        var id = makeid();
        $(".skidka").show().text("Use this " + id + " and take skidka").css({"color":"blue", "font-size": "18px"});

    });

    $(".r2").on("mouseenter", function () {
        var price = $("#price").text();
        price = price-(price*0.1);
        $("#price2").text(price).css({"color":"red", "font-size": "22px"});
        function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 10; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        var id = makeid();
        $(".skidka2").show().text("Use this " + id + " and take skidka").css({"color":"blue", "font-size": "18px"});

    });

    $(".r3").on("mouseenter", function () {
        var price = $("#price").text();
        price = price-(price*0.1);
        $("#price3").text(price).css({"color":"red", "font-size": "22px"});
        function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 10; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        var id = makeid();
        $(".skidka3").show().text("Use this " + id + " and take skidka").css({"color":"blue", "font-size": "18px"});

    });

    $(".r4").on("mouseenter", function () {
        var price = $("#price").text();
        price = price-(price*0.1);
        $("#price4").text(price).css({"color":"red", "font-size": "22px"});
        function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 10; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        var id = makeid();
        $(".skidka4").show().text("Use this " + id + " and take skidka").css({"color":"blue", "font-size": "18px"});

    });

    $(".r1").on("mouseleave", function () {
       location.reload();
    });
    $(".r2").on("mouseleave", function () {
        location.reload();
    });
    $(".r3").on("mouseleave", function () {
        location.reload();
    });
    $(".r4").on("mouseleave", function () {
        location.reload();
    });

});