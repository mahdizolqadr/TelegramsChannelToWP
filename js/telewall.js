jQuery(document).ready(function($) {
    container = $("#TWContents");
    apikey = $("#TWapikey").val();
    channel = $('#TWusername').val();
    if (!apikey || !channel )
    {
        container.html("<h3>API-Key is not set.</h3>");
    }
    else{
        url ='http://www.tele-wall.ir/api/' + apikey + '/' + channel + '/?callback=localJsonpCallback';
        $.ajax({
            url: url,
            crossOrigin: true,
            type: 'GET',
            dataType: "jsonp",
            jsonpCallback: 'localJsonpCallback',
            success: function(data) {
                if (data.length == 0)
                    container.html("<h3>NO Message</h3>");
                else
                    res = "";
                    for (var i =0; i<data.length; i++)
                    {
                        post = data[i];
                        res += '<div class="twRow">' +
                                    '<div class="twHeader">' +
                                        '<div class="tw-post-head-img" >' +
                                            '<img alt="کانال ' + post.title + '" ' +
                                                'class="tw-img" ' +
                                                 'src="http://static.tele-wall.ir/static/channel/s128_' + post.pic + '">' +
                                        '</div>' +
                                        '<div class="tw-post-title">' +
                                            ' <h5 >' +
                                            '     <a title="مشاهده کانال ' + post.title + '" target="_blank" ' +
                                                                'href="http://www.tele-wall.ir/telegram/channels/view/' + post.username + '">' +
                                            '         <span  > ' + post.title + '</span>' +
                                            '     </a>' +
                                            ' </h5>' +
                                            '<span class="twDate">' +
                                                '     <a  target="_blank" ' +
                                                'href="http://www.tele-wall.ir/telegram/message/view/' + post.date_insert + '/' + post.id + '">' +
                                                '         <span  > ' + post.date_insert + '</span>' +
                                                '     </a>' +
                                            '</span>' +
                                            '<span class="twView">' + post.post_view_count + '</span>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="twMsg">' +
                                        post.msg_text
                                    '</div><div class="twFile">' ;

                        switch (post.mimetype_id){
                            case 4:
                                if (post.atach_file)
                                    res += ' <a class="btn btn-success" href="http://static.tele-wall.ir/static/files/' +
                                             post.atach_file +
                                        '" > دانلود فایل </a>';
                                else{
                                    res += '<img alt="NOT FOUND " class="twimg-responsive  "' +
                                        'src="http://static.tele-wall.ir/static/img/file_not_found.jpg" />';
                                }
                                break;
                            case 2:
                                if (post.atach_file)
                                    res += ' <audio style="max-width:90%"  controls src="http://static.tele-wall.ir/static/files/' +
                                          post.atach_file +
                                        '" </audio>';
                                else{
                                    res += '<img alt="NOT FOUND " class="twimg-responsive  "' +
                                        'src="http://static.tele-wall.ir/static/img/audio_not_found.jpg" />';
                                }
                                break;
                            case 1:
                                if (post.atach_file)
                                    res += '<video   preload="none" style="max-width:90%"  controls src="http://static.tele-wall.ir/static/files/' +
                                          post.atach_file +
                                        '" </video>';
                                else{
                                    res += '<img alt="NOT FOUND " class="twimg-responsive  "' +
                                        'src="http://static.tele-wall.ir/static/img/video_not_found.jpg" />';
                                }
                                break;
                            case 3:
                                if (post.atach_file)
                                    res += '<img style="max-width:90%" src="http://static.tele-wall.ir/static/messages/s512_' +
                                          post.atach_file +
                                        '" />';
                                else{
                                    res += '<img alt="NOT FOUND " class="twimg-responsive  "' +
                                        'src="http://static.tele-wall.ir/static/img/file_not_found.jpg" />';
                                }
                                break;
                        }

                        res += '</div></div><hr/>';
                    }
                    container.html(res);
            },
            error: function (jQXHR, textStatus, errorThrown) {
                console.log("An error occurred whilst trying to contact the server: " + jQXHR.status + " " + textStatus + " " + errorThrown);
            },
            accept: '*/*'
        });
    }
});







