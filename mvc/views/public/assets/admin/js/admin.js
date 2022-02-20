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
let userApi = 'http://localhost/PHP-MVC-API/user';
let option = '';
let listCates = '';
let listNewss = '';
let listPages = '';
let total_pages ;
function start(){
    getCategorys(renderCategorys)
    getCategorys(renderListCate)
    getNewss(renderListNewss)
    $('#info').html(`<a class="d-block">${getCookie("user_name")}</a>`)
}

start();

function getCategorys(callback){
    let options = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        }
    }
    fetch(categoryApi+ '/show-admin',options)
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function getNewss(callback){
    let options = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        }
    }
    fetch(newsApi+ '/page-admin/1',options)
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function renderListNewsOfPage(page){
    let options = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        }
    }
    fetch(newsApi+ '/page-admin/'+page,options)
        .then(function (response){
            return response.json();
        })
        .then(function (newss){
            let stt = 1;
            let htmls = '';
            if (newss.data.newss){
                htmls = newss.data.newss.map(function (news){
                    return `
                        <tr>
                            <td>${stt++}</td>
                            <td>
                                <a target="_blank" href="#"><img width="100px" src="/public/uploads/${news.image}"></a>
                            </td>
                            <td>${news.news_title}</td>
                            <td>${news.description}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" onclick="renderUpdateNews(${news.id})" href="#">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="deleteNews(${news.id})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
        `
                })
            }

            listNewss = htmls;
        })
        .then(function (response){
            getListNewss()
        })
}

function renderListNewss(newss){
    let stt = 1;
    let htmls = '';
    if (newss.data.newss){
        total_pages = newss.data.total_page;
        htmls = newss.data.newss.map(function (news){
            return `
            <tr>
                <td>${stt++}</td>
                <td>
                    <a target="_blank" href="#"><img width="100px" src="/public/uploads/${news.image}"></a>
                </td>
                <td>${news.news_title}</td>
                <td>${news.description}</td>
                <td>
                    <a class="btn btn-primary btn-sm" onclick="renderUpdateNews(${news.id})" href="#">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" onclick="deleteNews(${news.id})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `
        })
    }

    listNewss = htmls;
}

function renderListCate(categorys){
    let stt = 1;
    let htmls = categorys.data.map(function (category){
        return `
            <tr>
                <td>${stt++}</td>
                <td>
                    <a target="_blank" href="#"><img width="100px" src="/public/uploads/${category.image}"></a>
                </td>
                <td>${category.title}</td>
                <td>${category.description}</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="#" onclick="renderUpdateCate(${category.id})">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" onclick="deleteCate(${category.id})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `
    })

    listCates = htmls;
}

function renderUpdateCate(id){
    fetch(categoryApi + '/detail/' + id)
        .then(function (response){
            return response.json();
        })
        .then(function (category){
            let mainContent = document.querySelector('#main-content');
            let htmls = `
            <div class="card-body">

                <div class="form-group">
                    <label for="menu">Tiêu đề</label>
                    <input type="text" id="cate_title" value="${category.data.title}" id="cate_title" class="form-control"  placeholder="Tiêu đề">
                </div>

                <div class="form-group">
                    <label>Danh Mục</label>
                    <select class="form-control" id="cate_parent_id">
                        <option value="0"> Danh Mục Cha </option>
                        ${option}
                    </select>
                </div>

                <div class="form-group">
                    <label>Mô Tả </label>
                    <textarea id="cate-description" name="description" class="form-control">${category.data.description}</textarea>

                </div>
                <div class="form-group">
                    <label for="menu">Ảnh</label>
                    <input type="file" class="form-control"  id="upload_file">
                    <img id="image_show" src="/public/uploads/${category.data.image}" width="100px" >
                    <input type="hidden" value="${category.data.image}" name="image" id="image">
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" onclick="updateCate(${category.data.id})" class="btn btn-primary">Tạo Danh Mục</button>
            </div>
    `;

            mainContent.innerHTML = htmls;
            CKEDITOR.replace('cate-description')
        });
}

function renderUpdateNews(id){
    fetch(newsApi + '/detail/' + id)
        .then(function (response){
            return response.json();
        })
        .then(function (news){
            let mainContent = document.querySelector('#main-content');
            let htmls = `
                        <div class="card-body">
            
                            <div class="form-group">
                                <label for="menu">Tiêu đề</label>
                                <input type="text" id="title" class="form-control" value="${news.data.title}" placeholder="Tiêu đề">
                            </div>
            
                            <div class="form-group">
                                <label>Danh Mục</label>
                                <select class="form-control" id="category_id">
                                   
                                    ${option}
                                </select>
                            </div>
                            <input type="hidden" id="user_id" value="1">
                            <div class="form-group">
                                <label>Mô Tả </label>
                                <textarea id="description" class="form-control">${news.data.description}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Nội dung </label>
                                <textarea id="content" class="form-control">${news.data.content}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="menu">Công bố (d/m/Y H:i:s)</label>
                                <input type="text" id="public_at" class="form-control" value="${news.data.public_at}"  placeholder="Công bố">
                            </div>
                            
                            <div class="form-group">
                                <label for="menu">Key word</label>
                                <input type="text" id="key_word" class="form-control" value="${news.data.keyword}"  placeholder="Key word">
                            </div>
                            
                            <div class="form-group">
                                <label for="menu">Ảnh</label>
                                <input type="file" class="form-control" id="upload_file">
                                <img id="image_show" src="/public/uploads/${news.data.image}" width="100px">
                                <input type="hidden" name="image" id="image" value="${news.data.image}">
                            </div>
            
                        </div>
                        <div class="card-footer">
                            <button type="submit" onclick="updateNews(${news.data.id})" class="btn btn-primary">Tạo Danh Mục</button>
                        </div>
            `;

            mainContent.innerHTML= htmls;
            CKEDITOR.replace('description')
            CKEDITOR.replace('content')
        })
}

function updateCate(id){
    let data = {
        "title" : $('#cate_title').val(),
        "parent_id" : $('#cate_parent_id').val(),
        "description" : CKEDITOR.instances['cate-description'].getData(),
        "image" : $('#image').val(),
    }
    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        },
        body: JSON.stringify(data)
    }
    fetch(categoryApi+ '/update/' + id,options)
        .then(function (response){
            return response.json();
        })
        .then(function (response){
            if (response.success){
                getListCategorys()
            }
            else {
                alert(response.message)
            }
        })

}

function updateNews(id){
    start()
    let data = {
        "title" : $("#title").val(),
        "description" : CKEDITOR.instances['description'].getData(),
        "category_id" : $('#category_id').val(),
        "user_id" : $('#user_id').val(),
        "content" : CKEDITOR.instances['content'].getData(),
        "image" : $('#image').val(),
        "public_at" : $('#public_at').val(),
        "keyword" : $('#key_word').val()
    }
    let optionss = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        },
        body: JSON.stringify(data)
    }
    fetch(newsApi+ '/update/' + id,optionss)
        .then(function (responses){
            return  responses.json();
        })
        .then(function (response){
            if (response.success){
                getListNewss()
            }
            else {
                alert(response.message)
            }
        })

}

function deleteNews(id){
    if (confirm('Xóa mà không thể khôi phục. Bạn có chắc ?')){
        let options = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization' : getCookie("access_token")
            }
        }
        fetch(newsApi+ '/delete/'+id,options)
            .then(function (response){
                return response.json();
            })
            .then(function (response){
                if (response.success){
                    getListNewss()
                }
                else {
                    alert(response.message)
                }
            })
    }
}

function deleteCate(id){
    if (confirm('Xóa mà không thể khôi phục. Bạn có chắc ?')){
        let options = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization' : getCookie("access_token")
            }
        }
        fetch(categoryApi+ '/delete/'+id,options)
            .then(function (response){
                return response.json();
            })
            .then(function (response){
                if (response.success){
                    getListCategorys()
                }
                else {
                    alert(response.message)
                }
            })

    }
}

function renderCategorys(categorys){
    let htmls = categorys.data.map(function (category){
        return `
            <option value="${category.id}">${category.title}</option>
        `
    })
    option = htmls;
}

function renderCreateCategory(){
    $('#page-news-admin').html('');
    let mainContent = document.querySelector('#main-content');
    let ckediter = document.querySelector('#ckediter');
    let htmls = `
            <div class="card-body">

                <div class="form-group">
                    <label for="menu">Tiêu đề</label>
                    <input type="text" id="cate_title" id="cate_title" class="form-control"  placeholder="Tiêu đề">
                </div>

                <div class="form-group">
                    <label>Danh Mục</label>
                    <select class="form-control" id="cate_parent_id">
                        <option value="0"> Danh Mục Cha </option>
                        ${option}
                    </select>
                </div>

                <div class="form-group">
                    <label>Mô Tả </label>
                    <textarea id="cate-description" name="description" class="form-control"></textarea>

                </div>
                <div class="form-group">
                    <label for="menu">Ảnh</label>
                    <input type="file" class="form-control"  id="upload_file">
                    <img id="image_show" src="" width="100px">
                    <input type="hidden" name="image" id="image">
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" onclick="createCategory()" class="btn btn-primary">Tạo Danh Mục</button>
            </div>
    `;

    mainContent.innerHTML = htmls;
    CKEDITOR.replace('cate-description')
}

function renderCreateNews(){
    $('#page-news-admin').html('');
    let mainContent = document.querySelector('#main-content');
    let htmls = `
                <div class="card-body">
    
                    <div class="form-group">
                        <label for="menu">Tiêu đề</label>
                        <input type="text" id="title" class="form-control"  placeholder="Tiêu đề">
                    </div>
    
                    <div class="form-group">
                        <label>Danh Mục</label>
                        <select class="form-control" id="category_id">
                            ${option}
                        </select>
                    </div>
                    <input type="hidden" id="user_id" value="${getCookie("user_id")}">
                    <div class="form-group">
                        <label>Mô Tả </label>
                        <textarea id="description" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Nội dung </label>
                        <textarea id="content" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="menu">Công bố (YYYY-mm-dd H:i:s)</label>
                        <input type="text" id="public_at" value="2022-02-16 20:00:00" class="form-control"  placeholder="Công bố">
                    </div>
                    
                    <div class="form-group">
                        <label for="menu">Key word</label>
                        <input type="text" id="key_word" class="form-control"  placeholder="Key word">
                    </div>
                    
                    <div class="form-group">
                        <label for="menu">Ảnh</label>
                        <input type="file" class="form-control" id="upload_file">
                        <img id="image_show" src="" width="100px">
                        <input type="hidden" name="image" id="image">
                    </div>
    
                </div>
                <div class="card-footer">
                    <button type="submit" onclick="createNews()" class="btn btn-primary">Tạo Danh Mục</button>
                </div>
    `;

    mainContent.innerHTML= htmls;
    CKEDITOR.replace('description')
    CKEDITOR.replace('content')
}

function createNews(){
    let data = {
        "title" : $("#title").val(),
        "description" : CKEDITOR.instances['description'].getData(),
        "category_id" : $('#category_id').val(),
        "user_id" : $('#user_id').val(),
        "content" : CKEDITOR.instances['content'].getData(),
        "image" : $('#image').val(),
        "public_at" : $('#public_at').val(),
        "keyword" : $('#key_word').val(),
    }
    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        },
        body: JSON.stringify(data)
    }
    fetch(newsApi+ '/create',options)
        .then(function (response){
            return response.json();
        })
        .then(function (response){
            if (response.success){
                renderCreateNews()
            }
            else {
                alert(response.message)
            }
        })

}

function createCategory(){
    let data = {
        "title" : $('#cate_title').val(),
        "parent_id" : $('#cate_parent_id').val(),
        "description" : CKEDITOR.instances['cate-description'].getData(),
        "image" : $('#image').val(),
    }
    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        },
        body: JSON.stringify(data)
    }
    fetch(categoryApi+ '/create',options)
        .then(function (response){
            return response.json();
        })
        .then(function (response){
            if (response.success){
                renderCreateCategory()
            }
            else {
                alert(response.message)
            }
        })

}

$(document).on('change','#upload_file', function (e){
    if (e.target.files && e.target.files[0]){
        let reader = new FileReader();
        reader.onload = function (e){
            $('#image_show').attr('src', e.target.result)
            $('#image').val(e.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
    }
})

function getListCategorys(){
    start()
    $('#page-news-admin').html('');
    let mainContent = document.querySelector('#main-content');
    let htmls = `
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav ml-auto">
    
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" id="keyword" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" >
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    
    </nav>
    <table class="table">
        <thead>
        <tr>
            <th style="width: 100px">STT</th>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th style="width: 100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody id="list">
            ${listCates}
        </tbody>
    </table>
    `;
    mainContent.innerHTML = htmls
}

function getListNewss(){
    start()
    let mainContent = document.querySelector('#main-content');
    let pageContent = document.querySelector('#page-news-admin');
    let htmls = `
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav ml-auto">
    
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" id="keyword" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" >
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    
    </nav>
    <table class="table">
        <thead>
        <tr>
            <th style="width: 100px">STT</th>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th style="width: 100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody id="list">
            ${listNewss}
        </tbody>
    </table>
    
    `;
    renderPage();
    mainContent.innerHTML = htmls;
    pageContent.innerHTML = listPages;
}

function renderPage(){
    let htmls_page = ''
    for (let i = 1; i <= total_pages; i++){
        htmls_page += `<li class="page-item"><a href="#" onclick="renderListNewsOfPage(${i})" class="page-link">${i}</a></li>`
    }
    listPages = htmls_page;

}

function logout(){
    let id = getCookie("session_id");
    let options = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : getCookie("access_token")
        }
    }
    fetch(userApi+ '/logout/'+id,options)
        .then(function (response){
            return response.json();
        })
        .then(function (response){
            if (response.success){
                window.location = "/"
            }
            else {
                alert(response.message)
            }
        })

}
