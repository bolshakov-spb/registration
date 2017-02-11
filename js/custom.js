// регулярка для проверки email
var regexp_email = /^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/;
// массив названий месяцев года
var month_aliases = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

// функция вывода сообщения о неправильно заполненом поле ввода
function showAlertBlock(elem) {
    var alert_elem = elem.parents('.form-group').find('.alert');
    alert_elem.removeClass('hidden');
}

// функция скрытия сообщения о неправильно заполненом поле ввода
function hideAlertBlock(elem) {
    var alert_elem = elem.parents('.form-group').find('.alert');
    if (!alert_elem.hasClass('hidden')) {
        alert_elem.addClass('hidden');
    }
}

$(document).ready(function () {
    // заполнение селекта с выбором месяца
    for (var i = 1; i <= 12; i++) {
        $('select[name="month"]').append('<option value="' + i + '">' + month_aliases[i - 1] + '</option>');
    }

    // получение текущего года
    var now = Number(new Date().getFullYear());
    // заполнение селекта с выбором года
    // (от (текущий год - 10) до (текущий год - 100))
    for (i = now - 10; i >= now - 100; i--) {
        $('select[name="year"]').append('<option value="' + i + '">' + i + '</option>');
    }

    // валидация фамилии
    $('#lastname').focusout(function () {
        var that = $(this);
        if (that.val().length < 2) {
            showAlertBlock(that);
        } else {
            hideAlertBlock(that);
        }
    });

    // валидация имени
    $('#firstname').focusout(function () {
        var that = $(this);
        if (that.val().length < 2) {
            showAlertBlock(that);
        } else {
            hideAlertBlock(that);
        }
    });

    // валидация email
    $('#inputemail').focusout(function () {
        var that = $(this);
        if (!regexp_email.test(that.val())) {
            showAlertBlock(that);
        } else {
            hideAlertBlock(that);
        }
    });

    // валидация пароля
    $('#inputpassword').focusout(function () {
        var that = $(this);
        if (that.val().length < 6) {
            showAlertBlock(that);
        } else {
            hideAlertBlock(that);
        }
    });

    // валидация подтверждения пароля
    $('#confirmpassword').focusout(function () {
        var that = $(this);
        if ($('#inputpassword').val() !== $('#confirmpassword').val()) {
            showAlertBlock(that);
        } else {
            hideAlertBlock(that);
        }
    });

    // обработчик изменения выбранного года
    $('select[name="year"]').change(function () {
        // сброс выбранного месяца и дня
        $('select[name="month"]').prop('selectedIndex', 0).change();
        // если год не выбран
        if (Number($(this).val()) === 0) {
            // блокирование возможности выбора месяца
            $('select[name="month"]').attr('disabled', 'true');
        } else {
            // иначе включение возможности выбора месяца
            $('select[name="month"]').removeAttr('disabled');
        }
    });

    // обработчик изменения выбранного месяца
    $('select[name="month"]').change(function () {
        // очистка селекта с выбором дня
        $('select[name="day"]').html('<option value="0">День</option>').prop('selectedIndex', 0);
        // если месяц не выбран, то селект с выбором дня блокируется и функция завершается
        if (Number($(this).val()) === 0) {
            $('select[name="day"]').attr('disabled', 'true');
            return false;
        }
        // разблокирование селекта с выбором дня
        $('select[name="day"]').removeAttr('disabled');
        // подсчет количества дней в выбраном месяце
        var days_in_month = 32 - new Date(Number($('select[name="year"]').val()),
                Number($(this).val()) - 1, 32).getDate();
        // заполнение селекта с выбором дня
        for (i = 1; i <= days_in_month; i++) {
            $('select[name="day"]').append('<option value="' + i + '">' + i + '</option>');
        }
    });

    // проверка правильности введенных данных перед отправкой формы
    $('#registerform').submit(function () {
        if ($('#firstname').val().length < 2 || $('#lastname').val().length < 2
            || !regexp_email.test($('#inputemail').val())
            || $('#inputPassword').val().length < 6
            || $('#inputPassword').val() !== $('#confirmpassword').val()
            || Number($('select[name="day"]').val()) === 0
            || $('select[name="day"]').is(':disabled')) {
            alert('Одно или несколько полей заполнены неверно!');
            return false;
        }
    });

    // обработчик кнопки входа
    $('#login-btn').click(function () {
        $.ajax({
            type: 'POST',
            url: 'ajax/login.php',
            data: {
                email: $('#login-form #email').val(),
                password: $('#login-form #password').val()
            },
            success: function (message) {
                alert(message);
            }
        });
    });
});