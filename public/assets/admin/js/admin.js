function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

let categoryApi = 'http://localhost/PHP-MVC-API/category';
let newsApi = 'http://localhost/PHP-MVC-API/news';
let option = '';

function start(){
    getCategorys(renderCategorys)
}

start();

function getCategorys(callback){
    fetch(categoryApi + '/show')
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function renderCategorys(categorys){
    let htmls = categorys.data.map(function (category){
        return `
            <option value="${category.id}">${category.title}</option>
        `
    })

    option = htmls.toString();

}

function createCategory(){
    let data = {

    }
    fetch(categoryApi+ '/create/')
        .then(function (response){
            return response.json();
        })
        .then(renderNewsByCateID)
        .then(function (){
            getRandomNewsByCateID(id);
            getHotNewsByCateID(id);
        })
}

function renderCreateCategory(){
    let mainContent = document.querySelector('#main-content');
    let htmls = `
            <div class="card-body">

                <div class="form-group">
                    <label for="menu">Tiêu đề</label>
                    <input type="text" name="name" class="form-control"  placeholder="Tiêu đề">
                </div>

                <div class="form-group">
                    <label>Danh Mục</label>
                    <select class="form-control" name="parent_id">
                        <option value="0"> Danh Mục Cha </option>
                        ${option}
                    </select>
                </div>

                <div class="form-group">
                    <label>Mô Tả </label>
                    <textarea name="description" class="form-control"></textarea>
                    <script>
                        CKEDITOR.replace( 'description' );
                    </script>
                </div>
                <div class="form-group">
                    <label for="menu">Ảnh</label>
                    <input type="file" class="form-control"  id="upload_file">
                    <div id="image_show">
                    
                    </div>
                    <input type="hidden" name="image" id="image">
                </div>

            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Tạo Danh Mục</button>
            </div>
    `;

    mainContent.innerHTML = htmls.toString();
}

$(document).on('change','#upload_file', function (){
    const form = new FormData();
    form.append('file', $(this)[0].files[0]);
    $.ajax({
        processData: false,
        contentType: false,
        method: 'POST',
        data: form,
        url: 'http://localhost/PHP-MVC-API/upload/store',
        success: function (results) {
            $('#image_show').html('<a href="' + results + '" target="_blank">' +
                '<img src="' + results + '" width="100px"></a>');
            $('#image').val(results);

        }
    });
});

function renderCreateNews(){
    let mainContent = document.querySelector('#main-content');
    let htmls = `
            <div class="card-body">

                <div class="form-group">
                    <label for="menu">Tiêu đề</label>
                    <input type="text" name="name" class="form-control"  placeholder="Tiêu đề">
                </div>

                <div class="form-group">
                    <label>Danh Mục</label>
                    <select class="form-control" name="parent_id">
                        <option value="0"> Danh Mục Cha </option>
                        ${option}
                    </select>
                </div>

                <div class="form-group">
                    <label>Mô Tả </label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Nội dung </label>
                    <textarea name="content" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="menu">Công bố (Y-m-d H:i:s)</label>
                    <input type="text" name="public_at" class="form-control"  placeholder="Tiêu đề">
                </div>
                
                <div class="form-group">
                    <label for="menu">Key word</label>
                    <input type="text" name="key_word" class="form-control"  placeholder="Tiêu đề">
                </div>
                
                <div class="form-group">
                    <label for="menu">Ảnh</label>
                    <input type="file" class="form-control" id="upload_file">
                    <div id="image_show">
                    
                    </div>
                    <input type="hidden" name="image" id="image">
                </div>

            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Tạo Danh Mục</button>
            </div>
    `;

    mainContent.innerHTML = htmls.toString();
}


