$(function () {
    $('.btn-edit').on('click', function () {
        const id = $(this).data('id');

        $('.form-title').text('Edit News');
        $('#news-form').attr('action', '/admin/news/' + id);

        const title = $('#title-' + id).text() || '';
        const desc  = $('#desc-' + id).text()  || '';
        $('#news-form #title').val(title);
        $('#news-form #description').val(desc);

        $('#form-submit').text('Save');
        $('#form-cancel').show();
    });

    $('#form-cancel').on('click', function () {
        $('.form-title').text('Create News');
        $('#news-form').attr('action', '/admin/news');
        $('#news-form #title').val('');
        $('#news-form #description').val('');
        $('#form-submit').text('Create');
        $('#form-cancel').hide();
    });
});
