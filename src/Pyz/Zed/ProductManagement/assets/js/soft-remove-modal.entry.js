'use strict';

$('#removeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var url = button.data('url')
    var modal = $(this)
    modal.find('a').attr('href',url)
})
