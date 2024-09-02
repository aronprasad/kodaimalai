jQuery(window).on('elementor/frontend/init', function () {

	elementorFrontend.hooks.addAction( 'frontend/element_ready/scp_sensei_course_carousel.default', function ( $scope ) {
		$this = $scope.find( '.wh-sensei-courses-carousel' );
		itemsDesktop = $this.data('itemsDesktop') || 4;
		itemsSmallDesktop = $this.data('itemsSmallDesktop') || 3;
		itemsTablet = $this.data('itemsTablet') || 2;
		itemsMobile = $this.data('itemsMobile') || 1;
		slideSpeed = $this.data('slideSpeed') || 500;
		autoPlay = $this.data('autoPlay') || false;
		showBullets = $this.data('showBullets') || false;
		autoHeight = $this.data('autoHeight') || false;

		var options = {
			items: itemsDesktop,
			itemsCustom: false,
			itemsDesktop: [1200, itemsDesktop],
			itemsDesktopSmall: [1000, itemsSmallDesktop],
			itemsTablet: [768, itemsTablet],
			itemsTabletSmall: false,
			itemsMobile: [525, itemsMobile],
			singleItem: false,
			itemsScaleUp: false,

			//Basic Speeds
			slideSpeed: slideSpeed,
			paginationSpeed: 800,
			rewindSpeed: 1000,

			//Autoplay
			autoPlay: autoPlay,
			stopOnHover: false,

			// Navigation
			//navigationText: ['<', '>'],
			rewindNav: true,
			scrollPerPage: false,

			//Pagination
			pagination: showBullets,
			paginationNumbers: !showBullets, // this has to be reversed to use bullets

			// Responsive
			responsive: true,
			responsiveRefreshRate: 200,
			responsiveBaseWidth: window,

			// CSS Styles
			baseClass: 'owl-carousel',
			theme: 'owl-theme',

			//Lazy load
			lazyLoad: true,
			lazyFollow: true,
			lazyEffect: 'fade',

			//Auto height
			autoHeight: autoHeight
		};

		$this.owlCarousel(options);
	} );
});
