$(document).ready(function () {
    $('nav').find('.nav-item').each(function (index, element) {
        let curUrl = window.location.pathname;
        let itemHref = $(element).find('.nav-link').attr('href');

        if (arraysEqual(curUrl.split('/'), itemHref.split('/'))) {
            $('nav').find('.nav-item').removeClass('active');
            $(element).addClass('active');
        }
    });

    function arraysEqual(arr1, arr2) {
        let result = true;

        arr1 = arr1.filter(function (element) {
            return (element !== null && element !== '');
        });

        arr2 = arr2.filter(function (element) {
            return (element !== null && element !== '');
        });

        arr1.forEach(function (item, key) {
            if (item !== arr2[key]) {
                result = false;
            }
        });

        return result;
    }
});
