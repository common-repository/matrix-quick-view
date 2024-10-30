jQuery(document).ready(function ($) {

    var prev_post_title, prev_thumbnail, next_post_title, next_thumbnail;

    var MatrixQuickView = {

        init: function () {
            this.bindEvents();
        },
        bindEvents: function () {
            var _that = this;
            $(window).resize(function () {
                _that.resizeWindow();
            });

            //remodel js open
            $(document).on('opened', '.matrix-quick-view-remodal', function () {
                $('body').css('overflow', 'hidden');
                $('.spinner').remove();
            });

            //remodel js closed
            $(document).on('closed', '.matrix-quick-view-remodal', function () {
                $('body').css('overflow', 'auto');
            });

            //shop page button click
            $(document).on('click', ".matrix-quick-view-button", function (e) {
                e.preventDefault();
                var product_id = $(this).data('product-id');
                _that.getProductDetails(product_id);
                $(this).append('<div class="spinner"></div>')

            });

            $(document).on('click', '#matrix_quick_view_modal_container .product .images a', function (e) {
                e.preventDefault();
                _that.initPrettyPhoto($(this));
            });
            $(document).on('mouseenter', "#matrix_quick_view_modal_container .summary", function () {

                _that.scrollBar();
            });

            $(document).on('mouseleave', "#matrix_quick_view_modal_container .summary", function (scrollable) {
                var scrollable = document.getElementsByClassName('scrollable')[0];
                if ((scrollable.scrollHeight > scrollable.clientHeight) === true) {

                    $('.scrollbar').hide();
                }
            });
            $(document).on('mouseenter', ".matrix_quick_view_prev", function () {
                if ($('.matrix_quick_view_prev_title').length === 0) {
                    $(this).append('<div class="matrix_quick_view_prev_title"><h4>' + prev_post_title + '</h4></div>');
                    $(this).append('<div class="matrix_quick_view_prev_thumbnail"></div>');
                    $('.matrix_quick_view_prev_thumbnail').html(prev_thumbnail);

                }
            });
            $(document).on('mouseleave', ".matrix_quick_view_prev", function () {
                if ($('.matrix_quick_view_prev_title').length !== 0) {
                    $(this).removeClass('matrix_quick_view_prev_title');
                    $('.matrix_quick_view_prev_title').remove();
                    $('.matrix_quick_view_prev_thumbnail').remove();
                }
            });
            $(document).on('click', ".matrix_quick_view_prev", function () {

                var product_id = $(this).data('data-prev-post');
                _that.getProductDetails(product_id);
            });

            //hover next button
            $(document).on('mouseenter', ".matrix_quick_view_next", function () {
                if ($('.matrix_quick_view_next_title').length === 0) {
                    $(this).append('<div class="matrix_quick_view_next_thumbnail"></div>');
                    $(this).append('<div class="matrix_quick_view_next_title"><h4>' + next_post_title + '</h4></div>');
                    $('.matrix_quick_view_next_thumbnail').html(next_thumbnail);

                }
            });
            $(document).on('mouseleave', ".matrix_quick_view_next", function () {
                if ($('.matrix_quick_view_next_title').length !== 0) {
                    $(this).removeClass('matrix_quick_view_next_title');
                    $('.matrix_quick_view_next_title').remove();
                    $('.matrix_quick_view_next_thumbnail').remove();

                }
            });

            $(document).on('click', ".matrix_quick_view_next", function () {

                var product_id = $(this).data('data-next-post');
                _that.getProductDetails(product_id);
            });


        },
        resizeWindow: function () {
            var height = $('.matrix-quick-view-remodal').height();
            $('#matrix_quick_view_modal_container .summary').css('height', height);
            var scrollable = document.getElementsByClassName('scrollable')[0];
            if (scrollable) {
                if ((scrollable.scrollHeight > scrollable.clientHeight) === true) {
                    $('.scrollbar_bg').css('height', height);
                    $('.scrollbar_bg').show();
                } else {
                    $('.scrollbar_bg').hide();
                }
            }
        },
        initPrettyPhoto: function ($this) {

            var img_url = $this.attr('href');
            var img_src = $this.find('img').attr('srcset');
            $('.woocommerce-main-image').find('img').attr('src', img_url);
            $('.woocommerce-main-image').find('img').attr('srcset', img_src);
            $('.woocommerce-main-image').closest('a').attr('href', img_url);

            $("a.zoom").prettyPhoto({
                hook: 'data-rel',
                social_tools: false,
                theme: 'pp_woocommerce',
                horizontal_padding: 20,
                opacity: 0.8,
                deeplinking: false
            });
            $("a[data-rel^='prettyPhoto']").prettyPhoto({
                hook: 'data-rel',
                social_tools: false,
                theme: 'pp_woocommerce',
                horizontal_padding: 20,
                opacity: 0.8,
                deeplinking: false
            });
        },
        scrollBar: function () {
            var scrollable = document.getElementsByClassName('scrollable')[0];
            if ((scrollable.scrollHeight > scrollable.clientHeight) === true) {
                var $scrollable = $('.scrollable'),
                    $scrollbar = $('.scrollbar'),
                    H = $scrollable.outerHeight(true),
                    sH = $scrollable[0].scrollHeight,
                    sbH = H * H / sH;

                $scrollbar.height(sbH).hide();

                $scrollable.on("scroll", function () {

                    $scrollbar.css({top: $scrollable.scrollTop() / H * sbH});
                });
                $('.scrollbar').show();
            }
        },
        getProductDetails: function (product_id) {

            if (product_id !== undefined) {

                jQuery.ajax({
                    type: 'POST',
                    url: matrix_quick_view_frontend_obj.ajaxurl,
                    data: {
                        'action': 'matrix_quick_view_get_product',
                        'product_id': product_id,

                    },
                    success: function (response) {

                        var container = $('#matrix_quick_view_modal_container');
                        container.html(response);
                        container.find('.summary').addClass('scrollable');
                        container.closest('.matrix-quick-view-remodal').show();

                        var prev_post_id = $('.matrix_quick_view_prev_data').data('matrix-quick-view-prev-id');
                        var next_post_id = $('.matrix_quick_view_next_data').data('matrix-quick-view-next-id');
                        prev_post_title = $('.matrix_quick_view_prev_data').text();
                        next_post_title = $('.matrix_quick_view_next_data').text();
                        var prev_src = ($('.matrix_quick_view_prev_data>img').length !== 0) ? $('.matrix_quick_view_prev_data>img').attr('src') : '';
                        var nex_src = ($('.matrix_quick_view_next_data>img').length !== 0) ? $('.matrix_quick_view_next_data>img').attr('src') : '';
                        prev_thumbnail = '<img src = "' + prev_src + '">';
                        next_thumbnail = '<img src = "' + nex_src + '">';

                        if (($('.matrix_quick_view_prev').length === 0) && (prev_post_id !== '')) {

                            container.closest('.remodal-wrapper').prepend('<div class="matrix_quick_view_prev wrapper" data-prev-post=' + prev_post_id + ' style="display:block;left:0;"><div class="icon"></div></div>');

                        }

                        if (($('.matrix_quick_view_next').length === 0) && (next_post_id !== '')) {

                            container.closest('.remodal-wrapper').prepend('<div class="matrix_quick_view_next wrapper" data-next-post=' + next_post_id + ' style="display:block;right:0;"><div class="icon"></div></div>');

                        }

                        $('.matrix_quick_view_prev').data('data-prev-post', prev_post_id);
                        $('.matrix_quick_view_prev_title').html('<h4>' + prev_post_title + '</h4>');
                        $('.matrix_quick_view_prev_thumbnail').html(prev_thumbnail);

                        $('.matrix_quick_view_next').data('data-next-post', next_post_id);
                        $('.matrix_quick_view_next_title').html('<h4>' + next_post_title + '</h4>');
                        $('.matrix_quick_view_next_thumbnail').html(next_thumbnail);


                        if (prev_post_id === '') {
                            $('.matrix_quick_view_prev').remove();
                        }
                        if (next_post_id === '') {
                            $('.matrix_quick_view_next').remove();
                        }

                        //open modal
                        var inst = $('[data-remodal-id=matrix-quick-view-modal]').remodal();
                        var state = inst.getState();
                        if (state == 'closed') {
                            inst.open();
                        }


                        var height = container.closest('.matrix-quick-view-remodal').height();
                        $('#matrix_quick_view_modal_container .summary').css('height', height);

                        //sroll
                        var color = container.closest('.matrix-quick-view-remodal').css('background-color');

                        $('#matrix_quick_view_modal_container .scrollbar_bg').css('background', color);
                        $('#matrix_quick_view_modal_container .scrollbar_bg').html('<div class="scrollbar"></div>');
                        var height = container.closest('.matrix-quick-view-remodal').height();
                        container.find('.scrollbar_bg').css('height', height);
                        var scrollable = document.getElementsByClassName('scrollable')[0];
                        if ((scrollable.scrollHeight > scrollable.clientHeight) === false) {
                            container.find('.scrollbar_bg').hide();
                        }
                        //end scroll

                    }
                });


            }

        }

    };
    MatrixQuickView.init();


});