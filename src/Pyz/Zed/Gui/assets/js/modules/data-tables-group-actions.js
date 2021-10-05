'use strict';

require('../../sass/data-tables-group-actions.scss');

$(document).ready(function () {
    let $tableGroupAction = $('.table__group-action'),
        $tableGroupActionForm = $tableGroupAction.find('form'),
        $checkboxes = $tableGroupActionForm.find('input[type="checkbox"]');
    $checkboxes.change(function() {
        let isChecked = $(this).prop('checked');
        if (isChecked) {
            $checkboxes.prop('checked', false);
            $(this).prop('checked', true);
        }
    });

    $tableGroupActionForm.submit(function (e) {
        e.preventDefault();

        let data = $(this).serializeArray(),
            $table = $tableGroupAction.prev().find('table'),
            $selectedInputs = $table.find('td.column-select_all input:checked'),
            selectedIds = [];
        if (!$selectedInputs.length) {
            alert('You have to select table rows');
            return;
        }

        $selectedInputs.each(function () {
            selectedIds.push($(this).prop('id'));
        });
        data.push({name: 'ids', value: selectedIds});

        $.ajax({
            url: $(this).prop('action'),
            method: $(this).prop('method'),
            data: data
        }).done(function(response) {
            if (response.success) {
                $checkboxes.prop('checked', false);
                $table.DataTable().ajax.reload();
            }
            alert(response.message);
        });
    });
});
