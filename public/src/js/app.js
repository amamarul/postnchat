/////////////////////////////////////POST////////////////////////////////////
var postId = 0;
//this saves the comment
$('article').on('keydown', '.comment_body', function (event) {
    if(event.keyCode == 13 && !event.shiftKey) {
        event.preventDefault();
        var postBody = event.target.value;
        postId = event.target.parentNode.parentNode.parentNode.parentNode.dataset['postid'];
        $.ajax({
            method: 'POST',
            url: urlCreateComment,
            data: {comment: postBody, postId: postId, _token: token}
        }).done(function (html) {
            var target = $(event.target.parentNode.parentNode.parentNode.childNodes[1]);
            event.target.value = '';
            $(event.target).css('height', '40px');
            $(target).html(html)
                .css('opacity', 0)
                .animate(
                { opacity: 1},
                1000
            );
            $( event.target )
                .closest( "article" )
                .find('.comment_count').fadeOut('slow', function() {
                    var comment_count = $(this).closest('article').find('.hcp_comment_count').text();
                    $(this).text(comment_count);
                    $(this).fadeIn('slow');
                });
        });
        return false;
    }
});

$('.hide_post').on('click', function (event) {
    event.preventDefault();
    var post;
    post = event.target.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
    $(post).fadeTo("medium", 0.00, function () { //fade
        $(this).slideUp("slow", function () { //slide up
            $(this).remove(); //then remove from the DOM
        });
    });
});

$('.delete_post').on('click', function (event) {
    if (confirm('Are You Sure, You want to delete this item?')) {
        event.preventDefault();
        var post;
        var url;
        post = event.target.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        url = $(this).attr("href");
        $.get(url, function (msg) {
            $(post).fadeTo("medium", 0.00, function () { //fade
                $(this).slideUp("slow", function () { //slide up
                    $(this).remove(); //then remove from the DOM
                });
            });
        });
    }
    $(this).closest('.dropdown.open').removeClass('open');
    return false;
});


////////////////////////////////////COMMENT/////////////////////////////////////

//  the following simple make the textbox "Auto-Expand" as it is typed in
$('article').on('keyup', '.comment_body', function(event) {
    while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height()+1);
    };
});


$('.comments').on('mouseover', '.comment', function (event) {
    event.preventDefault();
    $(this).find('span').find('a').show();
});

$('.comments').on('mouseout', '.comment', function (event) {
    event.preventDefault();
    $(this).find('span').find('a').hide();
    //$(event.target.childNodes[1].childNodes[2]).hide();
});

$('.comments').on('click', '.delete_comment', function (event) {
    if (confirm('Are You Sure, You want to delete this item?')) {
        var comment;
        var url;
        comment = event.target.parentNode.parentNode.parentNode;
        url = $(this).attr("href");
        $.get(url, function (msg) {
            $(comment).fadeTo("medium", 0.00, function () { //fade
                $(this).slideUp("slow", function () { //slide up
                    $(this).remove(); //then remove from the DOM
                });
            });
            $( event.target )
                .closest( "article" )
                .find('.comment_count').fadeOut('slow', function() {
                    $(this).text( msg['comment_count']);
                    $(this).fadeIn('slow');
                });
        });
    }
    return false;
});

////////////////////////////////////LIKE////////////////////////////////////////
$('.hit_like').on('click', function (event) {
    event.preventDefault();
    postId = event.target.parentNode.parentNode.parentNode.dataset['postid'];
    $.ajax({
        method: 'POST',
        url: urlLikePost,
        data: {like: true, postId: postId, _token: token}
    }).done(function (msg) {
        if (msg['like']) {
            event.target.innerText = 'Unlike';
        } else {
            event.target.innerText = 'Like';
        }
        //console.log(event.target.parentNode.firstChild.firstChild );
        event.target.parentNode.childNodes[1].childNodes[1].innerText = msg['like_count'];
    });
});


//////////////////////////////////////USER LIST///////////////////////////////////////////
//this search the user
$('.user-search').on('keyup', function (event) {
    var searchterm = $('.user-search').val();
    if (searchterm.length > 1) {
        $('.user-search').addClass('search_active');
        var url = $(this.parentNode).attr("action");
        $.ajax({
            method: 'POST',
            url: url,
            data: {searchterm: searchterm, _token: token}
        }).done(function (msg) {
            $('.userlist').html(msg);
        });
    } else {
        $('.user-search').removeClass('search_active');
    }
});

$('.user-search').keydown(function(event){
    if(event.keyCode == 13) {
        event.preventDefault();
        $('.user-search').val('');
        return false;
    }
});

$('.user-search').focusout(function () {
    $(this).val('');
    $(this).removeClass('search_active');
});

$('.user-search').keydown(function(event){
    if(event.keyCode == 13) {
        event.preventDefault();
        $('.user-search').val('');
        return false;
    }
});

function updateuserlist() {
    if (!$('.userslist .user-search').hasClass('search_active')) {
        $.get(urlGetUsersList, function (msg) {
            $('.userlist').html(msg);
            //console.log('userlist updated');
        });
    }
    setTimeout(updateuserlist, 1500);
}

//////////////////////////////////////////CHAT///////////////////////////////////////

$(window).on("blur focus", function(e) {
    var prevType = $(this).data("prevType");

    if (prevType != e.type) {   //  reduce double fire issues
        switch (e.type) {
            case "blur":
                $('.chatbox .messages').hide();
                $('.chatbox .messages').removeClass('chatisopen');
                break;
            case "focus":
                break;
        }
    }

    $(this).data("prevType", e.type);
})

//this gets the chat
$('.userlist').on('click', '.userchathandler', function (event) {
    event.preventDefault();
    var url = $(event.target).attr('href');
    //this shows the chat box
    $('.chatbox').show();
    //this shows the chat box messages
    $('.chatbox .messages').addClass('chatisopen');
    $('.chatbox .messages').show();
    //this shows the minimize option in the chat box
    $('.glyphicon-minus').show();
    //this hide the maximize option in the chat box
    $('.glyphicon-unchecked').hide();
    //this insert the name of the receiver in the chat box
    $('.chatbox').find('h3').text($(event.target).text());
    $.get(url, function (html) {
        //this sets the new updated messages in the chatbox
        $('.chatbox').find('.messages ul').html(html);
        //this insert receiver id as dataset in the save chat button
        var receiver_id = event.target.dataset['receiverid'];
        $('.chatmsg').attr('data-receiverid', receiver_id);
        //this focus the chat input box
        $('.chatbox').find('textarea').focus();
        //this scrolls the chat box to the latest message
        var mydiv = $('.chatbox').find('.messages ul');
        mydiv.scrollTop(mydiv.prop("scrollHeight"));
    });
});


//this saves the chat message
$('.chatbox').on('keydown', '.chatmsg', function (event) {
    if(event.keyCode == 13 && !event.shiftKey) {
        event.preventDefault();
        chatmsgelement = $(this).val();
        var receiver_id = event.target.dataset['receiverid'];
        $.ajax({
            method: 'POST',
            url: urlSaveChatMsg,
            data: {receiver_id: receiver_id, message: chatmsgelement, _token: token}
        }).done(function (html) {
            //this sets the new updated chat message in the chat box
            $('.chatbox').find('.messages ul').html(html);
            //this clears the chat input box
            $('.chatbox').find('textarea').val('');
            //this focus  the chat input box for inserting new message
            $('.chatbox').find('textarea').focus();
            $('.chatbox').find('textarea').css('height', '35px');
            //this scrolls the chat box to the latest message
            var mydiv = $('.chatbox').find('.messages ul');
            mydiv.scrollTop(mydiv.prop("scrollHeight"));
            console.log(html['exec_time']);
        });
    }
});

//  the following simple make the textbox "Auto-Expand" as it is typed in
$('.chatbox').on('keyup', '.chatmsg', function(event) {
    while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height()+1);
    };
});



$('.chatbox').on('click', '.glyphicon-remove', function (event) {
    //event.preventDefault();
    $('.chatbox .messages').removeClass('chatisopen');
    $('.chatbox').hide();
});


$('.chatbox').on('click', '.head h3', function (event) {
    //event.preventDefault();
    $('.chatbox .messages').toggle();
    $('.chatbox .messages').toggleClass('chatisopen');
    $('.chatbox').find('textarea').focus();
});


function updatechatmessage() {
    if ($('.chatbox .messages').hasClass('chatisopen')) {
        var url = $('.chatbox .messages ul a').attr('href');
        $.get(url, function (html) {
            $('.chatbox').find('.messages ul').html(html);
            $('.glyphicon-minus').show();
            $('.glyphicon-unchecked').hide();
            console.log($.now());
        });
    }
    setTimeout(updatechatmessage, 1500); // you could choose not to continue on failure...
}

/////////////////////////////////////NOTIFICATION//////////////////////////////////////
$('.post_notification').on('mouseover', function (event) {
    $('.post_notif_box').show();
});

$('.post_notification').on('mouseout', function (event) {
    $('.post_notif_box').hide();
});

///////////////////////////////////////OTHER//////////////////////////////////////////

function arrangelayout() {
    //this enables the masonry layout
    var $grid = $('.grid').masonry({
        // options
        itemSelector: '.grid-item',
    });

    $(this).toggleClass('gigante');
    // trigger layout after item size changes
    $grid.masonry('layout');
    //console.log('grid updated');
    setTimeout(arrangelayout, 300);
}

//this enables the date picker
$(function() {
    $( ".datepicker" ).datepicker();
});

//this makes the footer stick at the bottom of the window if the document size is less than window size
var h = $(window).height();
$('.maincontainer').css('min-height', h - 120);


/////////////////////////////////timeout functions/////////////////////////////////////
setTimeout(updatechatmessage, 1500);
setTimeout(updateuserlist, 1500);
setTimeout(arrangelayout, 300);





//setInterval(function(){ alert("Hello"); }, 1500);

//$(window).on('resize', function() {
//    $('.maincontainer').css('min-height', h -130);
//});

//$(document).ready(function() {
//    var win = $(window);
//
//    // Each time the user scrolls
//    win.scroll(function() {
//        // End of the document reached?
//        if ($(document).height() - win.height() == win.scrollTop()) {
//            $('#loading').show();
//
//            $.ajax({
//                url: 'get-post.php',
//                dataType: 'html',
//                success: function(html) {
//                    $('#posts').append(html);
//                    $('#loading').hide();
//                }
//            });
//        }
//    });
//});
//
//$('document').ready(function(){
//    updatestatus();
//    scrollalert();
//});
//function updatestatus(){
//    //Show number of loaded items
//    var totalItems=$('#content p').length;
//    $('#status').text('Loaded '+totalItems+' Items');
//}
//function scrollalert(){
//    var scrolltop=$('#scrollbox').attr('scrollTop');
//    var scrollheight=$('#scrollbox').attr('scrollHeight');
//    var windowheight=$('#scrollbox').attr('clientHeight');
//    var scrolloffset=20;
//    if(scrolltop>=(scrollheight-(windowheight+scrolloffset)))
//    {
//        //fetch new items
//        $('#loading').text('Loading more items...');
//        $.get('new-items.html', '', function(newitems){
//            $('#content').append(newitems);
//            updatestatus();
//        });
//    }
//    setTimeout('scrollalert();', 1500);
//}