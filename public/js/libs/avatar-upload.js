(function () {

    $('#avatar_file').change(function () {
        $('.avatar-error').slideUp(300);

        $('#avatar_file_name').val($(this).prop('files')[0]['name']);

        var formData = new FormData(document.forms.upload_file),
            fileData = $(this).prop('files')[0];

        formData.append('upload_file[name]', $(this).prop('files')[0]['name']);
        formData.append('upload_file[data]', fileData);

        $.ajax({
            url: "/upload-avatar",
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    $('div.edit-avatar-current-img').css({'background-image': 'url(' + response.data.fileUrl + ')'});
                    $('#upload_file_avatar').val(response.data.fileName);
                } else if (response.status == 'error') {
                    var errors = '';
                    $.each(response.dataset.avatarErrors, function(i, error) {
                        errors += '<p>'+error+'</p>';
                    });
                    $('.avatar-error > div').html('errors');
                    $('.avatar-error').slideDown(300);
                }
            }
        });
    });

    $('.edit-avatar-current-delete').on('click', function () {
        $('.avatar-error').slideUp(300);

        console.log('hi');
        console.log($('#avatar_file_name').val());

        if($('#avatar_file_name').val() != '') {

            var formData = new FormData(document.forms.upload_file),
                fileData = '';

            formData.append('upload_file[name]', $('#avatar_file_name').val());
            formData.append('upload_file[data]', fileData);

            $.ajax({
                url: "/remove-avatar",
                type: 'POST',
                data: formData,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 'success') {
                        $('div.edit-avatar-current-img').css({'background-image': 'url(' + response.data.fileUrl + ')'});
                        $('#upload_file_avatar').val(response.data.fileName);
                    } else if (response.status == 'error') {
                        var errors = '';
                        $.each(response.dataset.avatarErrors, function (i, error) {
                            errors += '<p>' + error + '</p>';
                        });
                        $('.avatar-error > div').html('errors');
                        $('.avatar-error').slideDown(300);
                    }
                }
            });
        }
    });
})();