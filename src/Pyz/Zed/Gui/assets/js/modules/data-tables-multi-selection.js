'use strict';

$(document).ready(function () {
    $('table #select_all input').click(function() {
        $('table #select_all input').parents('table')
            .find('td.column-select_all input')
            .prop('checked', $(this).prop('checked'));
    });

    $(document).on('draw.dt', function (e, settings) {
        $('#select_all input').prop('checked', false);
    });
});
