(function ($) {
  // Cores do título e bio
  wp.customize('title_color', function (value) {
    value.bind(function (newval) {
      $('.site-title').css('color', newval);
    });
  });

  wp.customize('bio_color', function (value) {
    value.bind(function (newval) {
      $('.bio-text').css('color', newval);
    });
  });

  // Texto da biografia
  wp.customize('bio_text', function (value) {
    value.bind(function (newval) {
      $('.bio-text').text(newval);
    });
  });

  // Tamanho da imagem
  wp.customize('profile_image_size', function (value) {
    value.bind(function (newval) {
      $('.profile-image').css({ width: `${newval}px`, height: `${newval}px` });
      $('.profile-image-wrapper').css({ width: `${newval}px`, height: `${newval}px` });
    });
  });

  // Cor e largura do anel
  wp.customize('profile_image_ring', function (value) {
    value.bind(function (newval) {
      const width = wp.customize('profile_image_ring_width')();
      $('.profile-image').css('box-shadow', `0 0 0 ${width}px ${newval}`);
    });
  });

  wp.customize('profile_image_ring_width', function (value) {
    value.bind(function (newval) {
      const color = wp.customize('profile_image_ring')();
      $('.profile-image').css('box-shadow', `0 0 0 ${newval}px ${color}`);
    });
  });

  // Cores do menu
  wp.customize('menu_bg_color', function (value) {
    value.bind(function (newval) {
      $('.linktree-menu a').css('background-color', newval);
    });
  });

  wp.customize('menu_text_color', function (value) {
    value.bind(function (newval) {
      $('.linktree-menu a').css('color', newval);
    });
  });

  wp.customize('menu_hover_bg_color', function (value) {
    value.bind(function (newval) {
      $('.linktree-menu a').hover(
        function () {
          $(this).css('background-color', newval);
        },
        function () {
          $(this).css('background-color', wp.customize('menu_bg_color')());
        }
      );
    });
  });

  wp.customize('menu_hover_text_color', function (value) {
    value.bind(function (newval) {
      $('.linktree-menu a').hover(
        function () {
          $(this).css('color', newval);
        },
        function () {
          $(this).css('color', wp.customize('menu_text_color')());
        }
      );
    });
  });

})(jQuery);
