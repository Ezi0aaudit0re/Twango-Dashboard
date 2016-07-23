$(function(){


    // code for sliding function
    $('.glyphicon-zoom-out').click(function(){
        $(this).toggleClass('glyphicon-zoom-out glyphicon-zoom-in');
        $('.remove').toggle();
        $('.static').toggleClass('col-md-2 col-md-1');
        $('.change').toggleClass('col-md-10 col-md-11');
    });
    var category = ['famous', 'movie'];
    $.ajax({type: 'post', url: 'https://andruxnet-random-famous-quotes.p.mashape.com/' , 'data': {'cat': category[0]},  success: function(data){
        data = JSON.parse(data);
            $('.quote').append('<h4><i>' + data.quote + '<i></h4><p><u>' + data.author + '</u></p>')
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader("X-Mashape-Authorization", "S8nIPyz9QNmshYoUngzR4jr2ZQ7Zp1ZQVWBjsnCO3gJPPnC7hb")
            }
    })

    $('.fade, .chats-fade').hide();

    $('.options').click(function(){
        $('.fade').fadeToggle()
    })

    $('.chats').click(function(){
        $('.chats-fade').fadeToggle()
    })






})
