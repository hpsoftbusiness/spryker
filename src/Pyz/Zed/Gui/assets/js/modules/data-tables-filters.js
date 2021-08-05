'use strict';

require('../../sass/data-tables-filters.scss');

$(document).ready(function () {

    $(document).on('init.dt', function (e, settings) {
        let table = new $.fn.dataTable.Api(settings);

        $(e.target.outerHTML).find('.table-filter-cell').each(function (i) {
            let $th = $('#' + $(this).attr('id')),
                currentVal = table.column(i).search();
            if (currentVal) {
                let currentValArray = currentVal.split(',');
                $th.find('input[type="checkbox"]').each(function () {
                    if ($.inArray($(this).attr('name'), currentValArray) !== -1) {
                        $(this).prop('checked', true);
                    }
                });

                $th.find('input[type!="checkbox"], select').val(currentVal);
            }

            $('input, select', $th).on('keyup change', function () {
                let value = null;
                if ($th.find('input[type="checkbox"]').length) {
                    value = [];
                    $th.find('input[type="checkbox"]:checked').each(function () {
                        value.push($(this).attr('name'));
                    });
                } else {
                    value = this.value;
                }
                if (table.column(i).search() !== this.value) {
                    table.column(i)
                        .search(value)
                        .draw();
                }
            });
        });
    });
});
