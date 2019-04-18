
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportRange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Next 7 Days': [moment(), moment().add(6, 'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Next 2 Months': [moment(), moment().add(2, 'month')]
            }
        }, cb);
        $('#reportRange').data('daterangepicker').autoUpdateInput(true);
        cb(start, end);
    });
