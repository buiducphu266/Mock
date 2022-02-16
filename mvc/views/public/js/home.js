
let categoryApi = 'http://localhost/PHP-MVC-API/category';
let newsApi = 'http://localhost/PHP-MVC-API/news';
function start(){
    getCategorys(renderCategorys);
    getCategorys(renderMenu);
    getCategorys(renderCategoryFooters);
    getNewss(renderNews);
    getRandomNewss(renderRandomNews);
    getHotNewss(renderHotNews);
}

start();

function getCategorys(callback){
    fetch(categoryApi + '/show')
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function getNewss(callback){
    fetch(newsApi + '/page/1')
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function getRandomNewss(callback){
    fetch(newsApi + '/random')
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function getHotNewss(callback){
    fetch(newsApi + '/hot')
        .then(function (response){
            return response.json();
        })
        .then(callback);
}

function renderCategorys(categorys){
    let listCategorys = document.querySelector('#list-categorys');
    let htmls = categorys.data.map(function (category){
        return `
            <li class="p-b-10">
                <a href="#" onclick="getNewsByCateID(${category.id})" class="stext-107 cl7 hov-cl1 trans-04">
                        ${category.title}
                 </a>
            </li>
        `;

    });
    listCategorys.innerHTML = htmls.join('');
}
function renderCategoryFooters(categorys){
    let listCategorys = document.querySelector('#footer-category');
    let htmls = categorys.data.map(function (category){
        return `
            <li class="p-b-10">
                <a href="#" onclick="getNewsByCateID(${category.id})" class="stext-107 cl7 hov-cl1 trans-04">
                        ${category.title}
                 </a>
            </li>
        `;

    });
    listCategorys.innerHTML = htmls.join('');
}

function renderMenu(categorys){
    let listMenus = document.querySelector('.main-menu');
    let listMenusM = document.querySelector('.main-menu-m');
    let htmls = categorys.data.map(function (category){
        return `
            <li>
              <a href="#" onclick="getNewsByCateID(${category.id})">${category.title}</a>
            </li>
        `;

    });
    listMenus.innerHTML = htmls.join('');
    listMenusM.innerHTML = htmls.join('');
}

function renderNews(newss){
    let listNews = document.querySelector('#main-home-news');
    let listPage = document.querySelector('#page');
    let htmls = '';
    // console.log(newss)
    if (newss.data.newss){
        htmls = newss.data.newss.map(function (news){
            return `
            <div class="p-b-63">
            <a href="#" onclick="handleNewsDetail(${news.id})" class="hov-img0 how-pos5-parent">
              <img src="/public/uploads/${news.image}" alt="IMG-BLOG">
            </a>

            <div class="p-t-32">
              <h4 class="p-b-15">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="ltext-108 cl2 hov-cl1 trans-04">
                  ${news.news_title}
                </a>
              </h4>

              <p class="stext-117 cl6">
                ${news.description}
              </p>

              <div class="flex-w flex-sb-m p-t-18">
                <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                    <span>
                        <span class="cl4">By</span> ${news.user_name}
                        <span class="cl12 m-l-4 m-r-6">|</span>
                    </span>

                    <span>
                        ${news.category_title}
                        <span class="cl12 m-l-4 m-r-6">|</span>
                    </span>

                 
                </span>

                <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                  Continue Reading
                  <i class="fa fa-long-arrow-right m-l-9"></i>
                </a>
              </div>
            </div>
          </div>
        `;
        });

    }
    let htmls_page = '';
    for (let i=1 ; i <= newss.data.total_page; i++){
        if (i == newss.data.number_of_page){
            htmls_page += `
            <a href="#" onclick="getPage(${i})" class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
              ${i}
            </a>
        `;
        }
        else {
            htmls_page += `
            <a href="#" onclick="getPage(${i})" class="flex-c-m how-pagination1 trans-04 m-all-7">
              ${i}
            </a>
        `;
        }

    }
    listPage.innerHTML = htmls_page;
    listNews.innerHTML = htmls.join('');
}

function renderRandomNews(newss){
    let listMenusRandom = document.querySelector('#random-news');
    if (newss.data){
        let htmls = newss.data.map(function (news){
            return `
              <li class="p-b-7">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                    <span>
                        ${news.title}
                    </span>
                </a>
              </li>
            `;
        });
        listMenusRandom.innerHTML = htmls.join('');
    }

}

function renderHotNews(newss){
    let listHotNews = document.querySelector('#hot-news');
    if (newss.data){
        let htmls = newss.data.map(function (news){
            return `
              <li class="flex-w flex-t p-b-30">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                  <img src="/public/uploads/${news.image}" width="100%" alt="PRODUCT">
                </a>

                <div class="size-215 flex-col-t p-t-8">
                  <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-116 cl8 hov-cl1 trans-04">
                    ${news.title}
                  </a>

                  <span class="stext-116 cl6 p-t-20">
					Lượt xem: ${news.numofreads}
                  </span>
                </div>
              </li>
        `;
        });
        listHotNews.innerHTML = htmls.join('');
    }


}

function handleNewsDetail(id){
    fetch(newsApi+ '/get/' + id)
        .then(function (response){
            return response.json();
        })
        .then(renderNewsDetail)
        .then(function (){
            getRandomNewss(renderRandomNews);
            getHotNewss(renderHotNews);
            getCategorys(renderCategorys);
            getCategorys(renderMenu);
        });
}

function renderNewsDetail(news){
    let news_detail = document.querySelector('#detail-news');
    let htmls = `
        <div class="p-r-45 p-r-0-lg">
                <!--  -->
                <div class="wrap-pic-w how-pos5-parent">
                  <img src="../../public/uploads/${news.data.image}" alt="IMG-BLOG">
                  
                </div>
    
                <div class="p-t-32">
                  <span class="flex-w flex-m stext-111 cl2 p-b-19">
                      <span>
                          <span class="cl4">By</span> ${news.data.user_name}
                          <span class="cl12 m-l-4 m-r-6">|</span>
                      </span>
    
                      <span>
                          ${news.data.public_at}
                          <span class="cl12 m-l-4 m-r-6">|</span>
                      </span>
    
                      <span>
                          ${news.data.category_title}
                          <span class="cl12 m-l-4 m-r-6">|</span>
                      </span>
    
                   
                  </span>
    
                  <h4 class="ltext-109 cl2 p-b-28">
                    ${news.data.news_title}
                  </h4>
                  
                  <div>${news.data.content}</div>
                    
                  
                </div>

        </div>
    `;

    news_detail.innerHTML = htmls;
}

function getNewsByCateID(id){

    fetch(categoryApi+ '/get/' + id)
        .then(function (response){
            return response.json();
        })
        .then(renderNewsByCateID)
        .then(function (){
                getRandomNewsByCateID(id);
                getHotNewsByCateID(id);
        })
}

function renderNewsByCateID(newss){
    let listNews = document.querySelector('#detail-news');
    if (!newss.data){
        listNews.innerHTML = '';
    }
    else {
        let htmls = newss.data.map(function (news) {
            return `
                        <div class="p-b-63">
                        <a href="#" onclick="handleNewsDetail(${news.id})" class="hov-img0 how-pos5-parent">
                          <img src="/public/uploads/${news.image}" alt="IMG-BLOG">
                        </a>
            
                        <div class="p-t-32">
                          <h4 class="p-b-15">
                            <a href="#" onclick="handleNewsDetail(${news.id})" class="ltext-108 cl2 hov-cl1 trans-04">
                              ${news.news_title}
                            </a>
                          </h4>
            
                          <p class="stext-117 cl6">
                            ${news.description}
                          </p>
            
                          <div class="flex-w flex-sb-m p-t-18">
                            <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                                <span>
                                    <span class="cl4">By</span> ${news.user_name}
                                    <span class="cl12 m-l-4 m-r-6">|</span>
                                </span>
            
                                <span>
                                    ${news.category_title}
                                    <span class="cl12 m-l-4 m-r-6">|</span>
                                </span>
            
                             
                            </span>
            
                            <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                              Continue Reading
                              <i class="fa fa-long-arrow-right m-l-9"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    `;
        });
        listNews.innerHTML = htmls.join('');
    }
}

function getRandomNewsByCateID(id){
    fetch(categoryApi + '/random/' + id)
        .then(function (response){
            return response.json();
        })
        .then(function (newss){
            let listMenusRandom = document.querySelector('#random-news');
            let htmls = newss.data.map(function (news){
                return `
              <li class="p-b-7">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                    <span>
                        ${news.title}
                    </span>
                </a>
              </li>
            `;
            });
            listMenusRandom.innerHTML = htmls.join('');
        });
}

function getHotNewsByCateID(id){
    fetch(categoryApi + '/hot/' + id)
        .then(function (response){
            return response.json();
        })
        .then(function (newss){
            let listHotNews = document.querySelector('#hot-news');
            let htmls = newss.data.map(function (news){
                return `
              <li class="flex-w flex-t p-b-30">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                  <img src="/public/uploads/${news.image}" width="100%" alt="PRODUCT">
                </a>

                <div class="size-215 flex-col-t p-t-8">
                  <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-116 cl8 hov-cl1 trans-04">
                    ${news.title}
                  </a>

                  <span class="stext-116 cl6 p-t-20">
					Lượt xem: ${news.numofreads}
                  </span>
                </div>
              </li>
        `;
            });
            listHotNews.innerHTML = htmls.join('');
        });
}

$(document).ready(function (){
    $(document).on('keyup','#keyword', function (){
        var keyword = $(this).val();

        $.ajax({
            type: "get",
            url: `http://localhost/PHP-MVC-API/news/search/${keyword}`,
            datatype: "json",
            success: function (results){
                let listSearch = document.querySelector('#list-search');
                let htmls = results.data.map(function (news) {
                    return `
                        <li class="flex-w flex-t p-b-30">
                            <a href="#" onclick="handleNewsDetail(${news.id})" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                              <img src="/public/uploads/${news.image}" width="100%" alt="PRODUCT">
                            </a>
            
                            <div class="size-215 flex-col-t p-t-8">
                              <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-116 cl8 hov-cl1 trans-04">
                                ${news.title}
                              </a>
                            </div>
                        </li>
                    `;
                });
                listSearch.innerHTML = htmls.join('');
            }
        })
    })
})

$(document).dblclick(function(){
    $('#list-search').html('');
})

function getPage(id){
    fetch(newsApi + '/page/' + id)
        .then(function (response){
            return response.json();
        })
        .then(function (newss) {
            let listNews = document.querySelector('#main-home-news');
            let listPage = document.querySelector('#page');
            let htmls = newss.data.newss.map(function (news){
                return `
            <div class="p-b-63">
            <a href="#" onclick="handleNewsDetail(${news.id})" class="hov-img0 how-pos5-parent">
              <img src="../../public/images/${news.image}" alt="IMG-BLOG">
            </a>

            <div class="p-t-32">
              <h4 class="p-b-15">
                <a href="#" onclick="handleNewsDetail(${news.id})" class="ltext-108 cl2 hov-cl1 trans-04">
                  ${news.news_title}
                </a>
              </h4>

              <p class="stext-117 cl6">
                ${news.description}
              </p>

              <div class="flex-w flex-sb-m p-t-18">
                <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                    <span>
                        <span class="cl4">By</span> ${news.user_name}
                        <span class="cl12 m-l-4 m-r-6">|</span>
                    </span>

                    <span>
                        ${news.category_title}
                        <span class="cl12 m-l-4 m-r-6">|</span>
                    </span>

                 
                </span>

                <a href="#" onclick="handleNewsDetail(${news.id})" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                  Continue Reading
                  <i class="fa fa-long-arrow-right m-l-9"></i>
                </a>
              </div>
            </div>
          </div>
        `;
            });
            let htmls_page = '';
            for (let i=1 ; i <= newss.data.total_page; i++){
                if (i == newss.data.number_of_page){
                    htmls_page += `
            <a href="#" onclick="getPage(${i})" class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
              ${i}
            </a>
        `;
                }
                else {
                    htmls_page += `
            <a href="#" onclick="getPage(${i})" class="flex-c-m how-pagination1 trans-04 m-all-7">
              ${i}
            </a>
        `;
                }

            }
            listPage.innerHTML = htmls_page;
            listNews.innerHTML = htmls.join('');
        })
}





