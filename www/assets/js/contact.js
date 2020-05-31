$(document).ready(function () {
    $(document).on('click tap', '.js-delete-contact', function () {
        let $self = $(this);

        $.post('/contact/delete/' + $self.data('id'), function (response) {
            if (response === 'success') {
                $self.closest('tr').remove();
            } else {
                alert('Не удалось удалить контакт.');
            }
        });
    })

    $(document).on('click tap', '.js-sort-list',function () {
        let $self = $(this);

        $('.triangle').not($self.find('.triangle')).removeClass('rotated');
        $self.find('.triangle').toggleClass('rotated');

        let sort = $self.find('.triangle').hasClass('rotated') ? 'DESC' : 'ASC';

        $.post(
            '/contact/getSortedTbody/' + $self.data('sort') + '/' + sort,
            function (response) {
                if (response) {
                    let table = $self.closest('table');
                    table.find('tbody').remove();
                    table.find('thead').after(response);
                }
            }
        );
    });
});
