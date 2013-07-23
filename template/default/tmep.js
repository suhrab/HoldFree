var uploader = new plupload.Uploader({
    runtimes : 'silverlight',
    browse_button : 'upload',
    container: 'uploading-files',
    max_file_size : '2048mb',
    chunk_size: '25mb',
    url: 'index.php?module=manager&action=upload&is_ajax=1',
    silverlight_xap_url: '{$_template}/js/plupload.silverlight.xap',
    flash_swf_url : '{$_template}/js/plupload/plupload.flash.swf',
    filters : [
        { title : "Video files", extensions : "mp4,avi,mkv" }
    ]
});

uploader.init();

uploader.bind('FilesAdded', function(up, files)
{
    for (i = 0; i < files.length; i++)
    {
        if (files[i].status === 1) {
            $('#uploading-files').append('<tr id="file_'+ files[i].id +'"><td>'+ files[i].name +'</td><td>'+ getBytesWithUnit(files[i].size) +'</td><td class="dark-blue" id="status_'+ files[i].id +'">В очереди'+ files[i].status +'</td><td id="speed_'+ files[i].id +'"></td></tr>');
        }
    }

    up.start();
});

uploader.bind('UploadProgress', function(up, file)
{
    var speed = uploader.total.bytesPerSec;

    $('#speed_' + file.id).html(getBytesWithUnit(speed) + '/s');
    $('#status_' + file.id).removeClass('dark-blue').html(file.percent + '%');
});

uploader.bind('FileUploaded', function(up, file, response)
{
    $('#file_' + file.id).fadeOut();
});

uploader.bind('Error', function(up, error)
{
    noty({
        text: 'Файл "' + error.file.name + '" не может быть загружен. Размер файла превышает 2 GB',
        type: 'error',
        dismissQueue: true,
        timeout: 4500,
        layout: 'topRight',
        theme: 'defaultTheme'
    });
});