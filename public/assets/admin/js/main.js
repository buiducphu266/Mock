function removeRow(id, url) {
    if (confirm('Xóa mà không thể khôi phục. Bạn có chắc ?')) {
        $.ajax({
            method: 'POST',
            data: { id_cate:id },
            url: url,
            success: function () {
                alert("Xoá thành công");
                location.reload();

            }
        })
    }
}

/*Upload File */
$('#upload').change(function () {
    const form = new FormData();
    form.append('file', $(this)[0].files[0]);
    $.ajax({
        processData: false,
        contentType: false,
        method: 'POST',
        data: form,
        url: '/MVC/upload/store',
        success: function (results) {
            $('#image_show').html('<a href="' + results + '" target="_blank">' +
                '<img src="' + results + '" width="100px"></a>');
            $('#image').val(results);

        }
    });
});
