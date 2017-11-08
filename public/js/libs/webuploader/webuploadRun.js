// 文件上传
//  后期时间多了应该封装一下，不能这样子丑陋的留2个实例在这里
var $list1 = $('.filelist1'),
    $list2 = $('.filelist2'),
    $btn = $('#ctlBtn'),
    state = 'pending',
    uploader1,
    uploader2;


// 文件上传实例1
uploader1 = WebUploader.create({
        auto:true,
        // 不压缩image
        resize: false,
        // swf文件路径
        swf:'/js/Uploader.swf',

        // 文件接收服务端。
        server: '/demandside/product/upload',
        chunked: true,
        chunkSize: 2*1024*1024,
        chunkRetry:2,
        // duplicate: true,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.

        // pick: '#picker'
        // multiple:false
        headers:{
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    });

    // 当有文件添加进来的时候
    uploader1.on( 'fileQueued', function( file ) {
        $list1.empty();
        $list1.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
        '</div>' );
    });


    // 文件上传过程中创建进度条实时显示。
    uploader1.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
            $percent = $li.find('.progress .progress-bar');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress progress-striped active">' +
              '<div class="progress-bar" role="progressbar" style="width: 0%">' +
              '</div>' +
            '</div>').appendTo( $li ).find('.progress-bar');
        }
        $li.find('p.state').text('上传中');

        $percent.css( 'width', percentage * 100 + '%' );
        
    });

    uploader1.on( 'uploadSuccess', function( file, res ) {
        $( '#'+file.id ).find('p.state').text('已上传');
        $('.fileupload1').remove();
        $list1.attr({
            file_id:res.file_id
       });
    });

    uploader1.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错');
    });

    uploader1.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').fadeOut();
        // console.log('上传完毕 开始重置队列');
        // uploader1.reset();
        // console.log('队列重置完毕');
    });

    uploader1.on( 'all', function( type ) {
        if ( type === 'startUpload' ) {
            state = 'uploading';
        } else if ( type === 'stopUpload' ) {
            state = 'paused';
        } else if ( type === 'uploadFinished' ) {
            state = 'done';
        }

        if ( state === 'uploading' ) {
            $btn.text('暂停上传');
        } else {
            $btn.text('开始上传');
        }
    });

    $btn.on( 'click', function() {
        if ( state === 'uploading' ) {
            uploader1.stop();
        } else {
            uploader1.upload();
        }
    });

/**
 * add by fyg
 * 开启 auto，选择完文件自动上传，上传进度立刻引发状态字符的变化
 */
uploader1.on( 'uploadProgress', function() {
    if ( state === 'uploading' ) {
        uploader1.stop();
    } else {
        uploader1.upload();
    }
});


//  上传文件第二个实例
//  上传文件第二个实例
//  上传文件第二个实例


uploader2 = WebUploader.create({
        auto:true,
        // 不压缩image
        resize: false,
        // swf文件路径
        swf:'/js/Uploader.swf',

        chunked: true,
        chunkSize: 2*1024*1024,
        chunkRetry:2,

        // 文件接收服务端。
        server: '/demandside/product/upload',
        // duplicate: true,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.

        // pick: '#picker'
        headers:{
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    });

    // 当有文件添加进来的时候
    uploader2.on( 'fileQueued', function( file ) {
        $list2.empty();
        $list2.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
        '</div>' );
    });


    // 文件上传过程中创建进度条实时显示。
    uploader2.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
            $percent = $li.find('.progress .progress-bar');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress progress-striped active">' +
              '<div class="progress-bar" role="progressbar" style="width: 0%">' +
              '</div>' +
            '</div>').appendTo( $li ).find('.progress-bar');
        }
        $li.find('p.state').text('上传中');

        $percent.css( 'width', percentage * 100 + '%' );
        
    });

    uploader2.on( 'uploadSuccess', function( file, res ) {
        $( '#'+file.id ).find('p.state').text('已上传');
        $('.fileupload2').remove();
        $list2.attr({
            file_id:res.file_id
       });
    });

    uploader2.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错');
    });

    uploader2.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').fadeOut();
        // console.log('上传完毕 开始重置队列');
        // uploader2.reset();
        // console.log('队列重置完毕');
    });

    uploader2.on( 'all', function( type ) {
        if ( type === 'startUpload' ) {
            state = 'uploading';
        } else if ( type === 'stopUpload' ) {
            state = 'paused';
        } else if ( type === 'uploadFinished' ) {
            state = 'done';
        }

        if ( state === 'uploading' ) {
            $btn.text('暂停上传');
        } else {
            $btn.text('开始上传');
        }
    });

    $btn.on( 'click', function() {
        if ( state === 'uploading' ) {
            uploader2.stop();
        } else {
            uploader2.upload();
        }
    });

uploader2.on( 'uploadProgress', function() {
    if ( state === 'uploading' ) {
        uploader2.stop();
    } else {
        uploader2.upload();
    }
});