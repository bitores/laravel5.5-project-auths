(function( $ ){
    // 当domReady的时候开始初始化
    $(function() {
        var $wrap = $('#uploader'),

            // 图片容器
            $queue = $( '<ul class="filelist"></ul>' )
                .appendTo( $wrap.find( '.queueList' ) ),

            // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find( '.statusBar' ),

            // 文件总体选择信息。
            $info = $statusBar.find( '.info' ),

            // 上传按钮
            $upload = $wrap.find( '.uploadBtn' ),

            // 没选择文件之前的内容
            $placeHolder = $wrap.find( '.placeholder' ),

            $progress = $statusBar.find( '.progress' ).hide(),

            // 添加的文件数量
            fileCount = 0,

            // 添加的文件总大小
            fileSize = 0,

            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 110 * ratio,
            thumbnailHeight = 110 * ratio,

            // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

            // 所有文件的进度信息，key为file id
            percentages = {},
            // 判断浏览器是否支持图片的base64
            isSupportBase64 = ( function() {
                var data = new Image();
                var support = true;
                data.onload = data.onerror = function() {
                    if( this.width != 1 || this.height != 1 ) {
                        support = false;
                    }
                }
                data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                return support;
            } )(),

            supportTransition = (function(){
                var s = document.createElement('p').style,
                    r = 'transition' in s ||
                            'WebkitTransition' in s ||
                            'MozTransition' in s ||
                            'msTransition' in s ||
                            'OTransition' in s;
                s = null;
                return r;
            })(),

            // WebUploader实例
            uploader;

        
        $queue.on('click', 'li', function(evt){
            $queue.find('.success').remove();
            $(this).append( '<span class="success"></span>' );
            evt.stopPropagation();
        })

        // $queue.on('click', '.cancel', function(evt){
        //     console.log('cancel');
        //     imagesUploader.removeFile( file );
        //     evt.stopPropagation();
        // })

        // 实例化
        imagesUploader = WebUploader.create({
            // 允许重复文件
            auto:true,
            duplicate:true,
            pick: {
                id: '#filePicker',
                label: '点击选择图片'
            },
            dnd: '#dndArea',
            paste: '#uploader',
            swf: '/js/Uploader.swf',
            chunked: true,
            chunkSize: 5 * 1024 * 1024,
            server: '/demandside/product/upload',
            // runtimeOrder: 'flash',
            // 限制可接受的文件格式
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,image/bmp'
            },

            formData:{
                datatype:'IMAGE'
            },

            headers:{
                'X-CSRF-TOKEN': CSRF_TOKEN
            },

            // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
            disableGlobalDnd: true,
            // 设置单次允许上传 10 张
            fileNumLimit: 20,
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
        });

        // 拖拽时不接受 js, txt 文件。
        // uploader.on( 'dndAccept', function( items ) {
        //     var denied = false,
        //         len = items.length,
        //         i = 0,
        //         // 修改js类型
        //         unAllowed = 'text/plain;application/javascript ';

        //     for ( ; i < len; i++ ) {
        //         // 如果在列表里面
        //         if ( ~unAllowed.indexOf( items[i].type ) ) {
        //             denied = true;
        //             break;
        //         }
        //     }

        //     return !denied;
        // });

        // uploader.on('filesQueued', function() {
        //     uploader.sort(function( a, b ) {
        //         if ( a.name < b.name )
        //           return -1;
        //         if ( a.name > b.name )
        //           return 1;
        //         return 0;
        //     });
        // });

        // 添加“添加文件”的按钮，
        imagesUploader.addButton({
            id: '#filePicker2',
            label: '继续添加'
        });

        // imagesUploader.on('ready', function() {
        //     window.imagesUploader = imagesUploader;
        // });

        // 当有文件添加进来时执行，负责view的创建
        function addFile( file , params) {
            var $li = $( '<li id="' + file.id + '">' +
                    '<p class="imgWrap"></p>'+
                    '<p class="progress"><span></span></p>' +
                    '<div class="file-panel"><span class="cancel">删除</span></div>'+
                    '</li>' );
                $li.appendTo( $queue );
            if(params) {
                $li.attr('file_id', params);
                $li.attr('class', "state-complete");
                // 设置为封面
            }
            var $btns =  $li.find('file-panel'),
                $prgress_parent = $li.find('p.progress'),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find( 'p.imgWrap' ),
                $info = $('<p class="error"></p>'),

                $cancel = $li.find('.cancel'),

                showError = function( code ) {
                    switch( code ) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;

                        case 'interrupt':
                            text = '上传暂停';
                            break;

                        default:
                            text = '上传失败，请重试';
                            break;
                    }

                    $info.text( text ).appendTo( $li );
                };

            $prgress_parent.hide();

            if ( file.getStatus() === 'invalid' ) {
                showError( file.statusText );
            } else {
                // @todo lazyload
                $wrap.text( '预览中' );
                imagesUploader.makeThumb( file, function( error, src ) {
                    var img;

                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }

                    if( isSupportBase64 ) {
                        img = $('<img src="'+src+'">');
                        $wrap.empty().append( img );
                    } else {
                        $.ajax('../../server/preview.php', {
                            method: 'POST',
                            data: src,
                            dataType:'json'
                        }).done(function( response ) {
                            if (response.result) {
                                img = $('<img src="'+response.result+'">');
                                $wrap.empty().append( img );
                            } else {
                                $wrap.text("预览出错");
                            }
                        });
                    }
                }, thumbnailWidth, thumbnailHeight );

                percentages[ file.id ] = [ file.size, 0 ];
                file.rotation = 0;
            }

            file.on('statuschange', function( cur, prev) {
                if ( prev === 'progress' ) {
                    $prgress_parent.show();
                    $prgress.hide().width(0);
                } else if ( prev === 'queued' ) {
                    $li.off( 'mouseenter mouseleave' );
                    // $btns.hide();
                }

                // 成功
                if ( cur === 'error' || cur === 'invalid' ) {
                    showError( file.statusText );
                    percentages[ file.id ][ 1 ] = 1;
                } else if ( cur === 'interrupt' ) {
                    showError( 'interrupt' );
                } else if ( cur === 'queued' ) {
                    percentages[ file.id ][ 1 ] = 0;
                } else if ( cur === 'progress' ) {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if ( cur === 'complete' ) {
                    // $li.append( '<span class="success"></span>' );
                    $prgress_parent.hide();
                }

                $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
            });

            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });

            $cancel.on( 'click', function(evt) {
                evt.stopPropagation();
                var index = $(this).index(), deg;

                switch ( index ) {
                    case 0:
                        imagesUploader.removeFile( file );
                        return;
                    // case 1:
                    //     file.rotation += 90;
                    //     break;

                    // case 2:
                    //     file.rotation -= 90;
                    //     break;
                    // 设置为封面
                    // class .titleImage 提供基础样式
                    // setTitleImage 提供选中后样式
                    // beforeTitleImage 提供未选中样式
                    // case 1:
                    //     var $siblings = $(this).parent().parent().siblings().find('.titleImage');
                    //     $(this).addClass('setTitleImage')
                    //     .removeClass('beforeTitleImage');
                    //     $siblings.removeClass('setTitleImage')
                    //     .addClass('beforeTitleImage');
                    //     break;
                }

                if ( supportTransition ) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                }


            });

            // $li.appendTo( $queue );
        }
        
        // 负责view的销毁
        function removeFile( file ) {
            var $li = $('#'+file.id);

            delete percentages[ file.id ];
            updateTotalProgress();
            $li.off().find('.file-panel').off().end().remove();
        }

        function updateTotalProgress() {
            var loaded = 0,
                total = 0,
                spans = $progress.children(),
                percent;

            $.each( percentages, function( k, v ) {
                total += v[ 0 ];
                loaded += v[ 0 ] * v[ 1 ];
            } );

            percent = total ? loaded / total : 0;


            spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
            spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
            updateStatus();
        }

        function updateStatus(state) {
            var text = '', stats;

            if ( state === 'ready' ) {
                text = '选中' + fileCount + '张图片，共' +
                        WebUploader.formatSize( fileSize ) + '。';
            } else if ( state === 'confirm' ) {
                stats = imagesUploader.getStats();
                if ( stats.uploadFailNum ) {
                    text = '已成功上传' + stats.successNum+ '张照片，'+
                        stats.uploadFailNum + '张照片上传失败，<a class="retry">重新上传</a>失败图片或<a class="ignore">忽略</a>'
                }

            } else {
                stats = imagesUploader.getStats();
                text = '共' + fileCount + '张（' +
                        WebUploader.formatSize( fileSize )  +
                        '），已上传' + stats.successNum + '张';

                if ( stats.uploadFailNum ) {
                    text += '，失败' + stats.uploadFailNum + '张';
                }
            }

            $info.html( text );
        }

        function setState( val ) {
            var file, stats;

            if ( val === state ) {
                return;
            }

            $upload.removeClass( 'state-' + state );
            $upload.addClass( 'state-' + val );
            state = val;

            switch ( state ) {
                case 'pedding':
                    $placeHolder.removeClass( 'element-invisible' );
                    $queue.hide();
                    $statusBar.addClass( 'element-invisible' );
                    imagesUploader.refresh();
                    break;

                case 'ready':
                    $placeHolder.addClass( 'element-invisible' );
                    $( '#filePicker2' ).removeClass( 'element-invisible');
                    $queue.show();
                    $statusBar.removeClass('element-invisible');
                    imagesUploader.refresh();
                    break;

                case 'uploading':
                    $( '#filePicker2' ).addClass( 'element-invisible' );
                    $progress.show();
                    $upload.text( '暂停上传' );
                    break;

                case 'paused':
                    $progress.show();
                    $upload.text( '继续上传' );
                    break;

                case 'confirm':
                    $progress.hide();
                    $( '#filePicker2' ).removeClass( 'element-invisible' );
                    $upload.text( '开始上传' );

                    stats = imagesUploader.getStats();
                    if ( stats.successNum && !stats.uploadFailNum ) {
                        setState( 'finish' );
                        return;
                    }
                    break;
                case 'finish':
                    stats = imagesUploader.getStats();
                    if ( stats.successNum ) {
                        
                        console.log( '上传成功' );
                    } else {
                        // 没有成功的图片，重设
                        state = 'done';
                        location.reload();
                    }
                    break;
            }

            updateStatus(state);
        }

        imagesUploader.onUploadProgress = function( file, percentage ) {
            var $li = $('#'+file.id),
                $percent = $li.find('.progress span');

            $percent.css( 'width', percentage * 100 + '%' );
            percentages[ file.id ][ 1 ] = percentage;
            updateTotalProgress();
        };

        imagesUploader.onUploadSuccess = function( file, res ) {
           var $li = $('#'+file.id);
           $li.attr({
            file_id:res.file_id
           });

        };

        imagesUploader.onFileQueued = function( file ) {
            fileCount++;
            fileSize += file.size;

            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }

            addFile( file );
            setState( 'ready' );
            updateTotalProgress();
        };

        imagesUploader.onFileDequeued = function( file ) {
            fileCount--;
            fileSize -= file.size;

            if ( !fileCount ) {
                setState( 'pedding' );
            }

            removeFile( file );
            updateTotalProgress();

        };

        imagesUploader.on( 'all', function( type ,s) {
            var stats;
            switch( type ) {
                case 'uploadFinished':
                    setState( 'confirm' );
                    break;

                case 'startUpload':
                    setState( 'uploading' );
                    break;

                case 'stopUpload':
                    setState( 'paused' );
                    break;

            }
        });

        imagesUploader.onError = function( code ) {
            // swal('', '格式不支持！请重新选择图片文件。出错代码->' + code ,'');
            swal('','格式不支持或数量限制','')
        };

        $upload.on('click', function() {
            if ( $(this).hasClass( 'disabled' ) ) {
                return false;
            }

            if ( state === 'ready' ) {
                imagesUploader.upload();
            } else if ( state === 'paused' ) {
                uploader.upload();
            } else if ( state === 'uploading' ) {
                imagesUploader.stop();
            }
        });

        $info.on( 'click', '.retry', function() {
            imagesUploader.retry();
        } );

        $info.on( 'click', '.ignore', function() {
            
        } );

        $upload.addClass( 'state-' + state );
        updateTotalProgress();






        var getFileBlob = function (url, params, cb) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url);
            xhr.responseType = "blob";
            xhr.addEventListener('load', function() {
                cb(xhr.response, params);
            });
            xhr.send();
        };

        var blobToFile = function (blob, name) {
            blob.lastModifiedDate = new Date();
            blob.name = name;
            return blob;
        };

        var getFileObject = function(filePathOrUrl,params, cb) {
            getFileBlob(filePathOrUrl, params, function (blob,params) {
                cb(blobToFile(blob, 'test.jpg'),params);
            });
        };

        //需要编辑的图片列表
        var picList = ['https://dim3d.xyz/uploads/materials/20171026/150899935276c708cf0155e8de.jpg','https://dim3d.xyz/uploads/materials/20171026/150899935276c708cf0155e8de.jpg' ]
            picList = [];
        $.each(webupload_pickList, function(index,item){
            getFileObject(item.path, item.id, function (fileObject, params) {
                var wuFile = new WebUploader.Lib.File(WebUploader.guid('rt_'),fileObject);
                var file = new WebUploader.File(wuFile);

                fileCount++;
                fileSize += file.size;

                if ( fileCount === 1 ) {
                    $placeHolder.addClass( 'element-invisible' );
                    $statusBar.show();
                }

                addFile(file,params)
                setState( 'ready' );
                updateTotalProgress();

            })
        });

    });

})( jQuery );