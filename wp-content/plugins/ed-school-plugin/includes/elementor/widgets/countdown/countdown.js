jQuery(window).on('elementor/frontend/init', function () {

    var symbols = {
        years: '%Y',
        months: '%m',
        weeks: '%w',
        days: '%d',
        hours: '%H',
        minutes: '%M',
        seconds: '%S'
    };

    var template = '';
    template += '<div class="time <%= label %>">';
    template += '<span class="count"><%= curr %></span>';
    template += '<span class="label"><%= translation.length < 6 ? translation : translation.substr(0, 3)  %></span>';
    template += '</div>';
    template = _.template(template);

    elementorFrontend.hooks.addAction( 'frontend/element_ready/scp_countdown.default', function ( $scope ) {

        var $this = $scope.find('.scp-countdown');
        var labels = $this.data('labels').split(/[\s,]+/);
        var labelTranslations = $this.data('translations').split(/[\s,]+/);
        var date = $this.data('date').split(/[\s,]+/);

        var markup = '';

        jQuery.each(labels, function (i, label) {

            var symbol = symbols[label];
            if (symbol) {

                var translation ='';
                if (labelTranslations.length && labelTranslations[i]) {
                    translation = labelTranslations[i];
                } else {
                    translation = label;
                }

                markup += template({
                    curr: symbol,
                    label: label,
                    translation: translation
                });
            }

        });

        $this.countdown(date, function (event) {
            jQuery(this).html(event.strftime(markup));
        });

    });

});
