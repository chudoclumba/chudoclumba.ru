    <div class="header-area">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-12">
                         <div class="single-menu">
                            <nav>
                                <ul>
                                    <li><a href="terms">Условия работы</a></li>
                                    <li><a href="shipping-payment">Доставка</a></li>
                                    <li><a href="payment">Оплата</a></li>
                                    <li><a class="lastbdr" href="contacts">Контакты</a></li>
                                </ul>
                            </nav>
                        </div>
                   </div>
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="single-drop single-menu">
                            <nav>
                                <ul>
                                    <li id="u_menu">
                            
                                    <?=User::gI()->form();?>
                                    </li>
                                     <li><a class="lastbdr link-wishlist" href="wishlist/show">Wishlist</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-2 col-xs-12">
                        <div class="logo-area">
                            <a href=""><img src="<?=TEMP_FOLDER?>images/logo15.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 nopadding">
                        <div class="block-header">
                         <?  if (isset(Site::gi()->sets['Phone1']) && !empty(Site::gi()->sets['Phone1'])) echo '<div class="phone"><i class="fa fa-phone"></i>'.Site::gi()->sets['Phone1'].'</div>' ?><div class="phone callme_viewform"><i class="fa fa-phone"></i>Обратный звонок</div>
                             <div class="phone"><a href="mailto:<?=$ebox['email']?>"><i class="fa fa-envelope-o"></i><?=$ebox['email']?></a></div>
                             <div class="email hidden-xs">
                             <a href="https://www.facebook.com/%D0%A7%D1%83%D0%B4%D0%BE-%D0%9A%D0%BB%D1%83%D0%BC%D0%B1%D0%B0-385763158477116/" target="_blank"><i class="fa fa-facebook-official"></i></a>
                             <a href="https://vk.com/chudoclumba" target="_blank"><i class="fa fa-vk"></i></a>
                             <a href="https://www.instagram.com/chudo_clumba/" target="_blank"><i class="fa fa-instagram"></i></a>
                             <a href="https://ok.ru/group/53375359189176" target="_blank"><i class="fa fa-odnoklassniki"></i></a>
                             <a href="https://www.youtube.com/channel/UCvEFGWSYzpzFFtKPZzdxEKA" target="_blank"><i class="fa fa-youtube-play"></i></a></div>
                        </div>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <form action="search/1" method="post">
                        <div class="search-categori">
                            <div class="categori">
                                <!--form id="select-categoris" method="post" class="form-horizontal"-->
                                    <select name="category" class="orderby">
                                        <option value="catalogue">Каталог</option>
                                        <option value="site">Весь сайт</option>
                                    </select>
                                <!--/form-->
                            </div>
                            <div class="search-box">
                                <!--form action="search/1" method="post"-->
                                    <input type="text" class="form-control input-sm" maxlength="64" placeholder="Введите поисковый запрос... " name="bsearch_str">
                                    <button type="submit">Поиск</button>
                                <!--/form-->
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs" id="cart_btn"><?=$this->view('ishop/cart')?></div>
                    <div class="clarfix"></div>

                  </div></div>
            </div>
        </div>
     </div>
