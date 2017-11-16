// 文件上传
//  后期时间多了应该封装一下，不能这样子丑陋的留2个实例在这里
//  
var bindWebupload = function(uploadBtn, filelist, innerHTML, filetTpe){

    this.filelist = filelist;
    this.state = 'pending';
    this.uploader = null;
    this.uploaderBtn = $(uploadBtn);

    var that = this;

    // 文件上传实例1
    this.uploader = WebUploader.create({
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
        duplicate: true,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.

        pick: uploadBtn,
        multiple:false,
        accept: {
            title: 'Images',
            extensions: 'rar,zip,7z,cad,max',
            mimeTypes: 'application/x-rar-compressed,application/zip,'
        },

        formData:{
            datatype:filetTpe
        },

        headers:{
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    });

    // this.uploader.addButton({
    //     id: uploadBtn,
    //     innerHTML: innerHTML,
    //     multiple:false
    // });

    // 当有文件添加进来的时候
    this.uploader.on( 'fileQueued', function( file ) {
        that.filelist.empty();
        that.filelist.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
        '</div>' );
    });


    // 文件上传过程中创建进度条实时显示。
    this.uploader.on( 'uploadProgress', function( file, percentage ) {
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

    this.uploader.on( 'uploadSuccess', function( file, res ) {
        $( '#'+file.id ).find('p.state').text('已上传');
        // 删除上传按钮
        // that.uploaderBtn.remove();
        that.filelist.attr({
            file_id: res.file_id
       });
    });

    this.uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错');
    });

    this.uploader.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').fadeOut();
    });

    this.uploader.on( 'all', function( type ) {
        if ( type === 'startUpload' ) {
            that.state = 'uploading';
        } else if ( type === 'stopUpload' ) {
            that.state = 'paused';
        } else if ( type === 'uploadFinished' ) {
            that.state = 'done';
        }
    });

    /**
     * add by fyg
     * 开启 auto，选择完文件自动上传，上传进度立刻引发状态字符的变化
     */
    this.uploader.on( 'uploadProgress', function() {
        if ( that.state === 'uploading' ) {
            that.uploader.stop();
        } else {
            that.uploader.upload();
        }
    });   


};

// new bindWebupload('#fileupload1',$('#filelist1'),'上传CAD压缩包','CAD');
// new bindWebupload('#fileupload2',$('#filelist2'),'上传其它资料压缩包','OTHER');