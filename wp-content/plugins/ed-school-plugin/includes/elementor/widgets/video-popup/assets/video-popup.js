jQuery(window).on('elementor/frontend/init', function () {

    elementorFrontend.hooks.addAction( 'frontend/element_ready/scp_video_popup.default', function ( $scope ) {

        var $this = $scope.find('.wh-video-popup .video-link');

        $this.magnificPopup({
          type:'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
        });

    });

});
