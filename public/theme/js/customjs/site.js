$(document).ready(function() {

    applyOnlyNumbers();

    if ($('#btn_generar_password')) {
        var generatePassword = (
                length = 8,
                wishlist = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz~!@-#$"
            ) => Array(length)
            .fill('')
            .map(() => wishlist[Math.floor(crypto.getRandomValues(new Uint32Array(1))[0] / (0xffffffff + 1) * wishlist.length)])
            .join('');

        $('#btn_generar_password').off('click').on('click', function() {
            var pass = generatePassword();
            $('#password').val(pass);
            $('#password_confirmation').val(pass);
            $('#div_random_text').text(pass);
        });
    }

    $.each($('.padre'), function(i, el) {
        if ($('.opcion[parent="' + $(el).attr('parent') + '"]').length > 0) {
            var ul = $('<ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;"></ul>');
            ul.append($('.opcion[parent="' + $(el).attr('parent') + '"]'));
            $(el).append(ul);
        } else {
            $(el).find('span.arrow').remove();
        }
    });

    setTimeout(function() {
        $('.padre.active').children('a').addClass('active');
        $('ul.nav.collapse.in').attr('style', '');
        $('ul.nav.collapse.in').attr('aria-expanded', 'true');
    }, 100);

    if ($('#search_control').length > 0) {
        $('#search_control').off('keyup').on('keyup', function() {
            var action = $('#link_search').attr('url');
            $('#form_search').attr('action', action + $(this).val());
        });
    }

});

function applyOnlyNumbers() {
    $('.onlyNumbers').bind("cut copy paste", function(evt) {
        evt.preventDefault();
    });

    $('.onlyNumbers').off('keypress').on('keypress', function(evt) {
        if (evt.which !== 8 && evt.which !== 0 && (evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
            return false;
        }
    });

    $('.onlyNumbers').off('keyup').on('keyup', function(evt) {
        if ($.trim($(this).val()).length > 0) {
            $(this).trigger('change');
        }
    });

    $('.onlyNumbers').off('change').on('change', function(evt) {
        if ($(this).val().length > 0) {
            $(this).val($(this).val().replace(/\D/g, ''));
        }
    });
}
