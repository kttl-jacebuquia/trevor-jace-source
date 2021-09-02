<?php
/*
Template Name: Technology Microsite
*/
?>
<head>
	<?php if ( $_ENV['PANTHEON_ENVIRONMENT'] === 'live' ) : ?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-WV5P347');</script>
		<!-- End Google Tag Manager -->
	<?php endif ?>
	<?php wp_head(); ?>
	<link rel="icon" type="image/png" href="/favicon.png">
	<link rel="mask-icon" href="/favicon.svg" color="orange">
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,900,900i" rel="stylesheet">
	<link rel="stylesheet" media="screen" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js" type="text/javascript" charset="utf-8"></script>
	<script>
        jQuery(function ($){
        	function setFocus(i){
				i.focus();
	      		if (i.is(":focus")) {
		        	return false;
		        } else {
		            i.attr('tabindex','-1');
		            i.focus();
		        };
			};
			$('a[href*="#"]')
			  .not('[href="#"]')
			  .not('[href="#0"]')
			  .click(function(event) {
			    if (
			      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
			      && 
			      location.hostname == this.hostname
			    ) {
			      var target = $(this.hash);
			      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			      if (target.length) {
			        event.preventDefault();
			        $('html, body').animate({
			          scrollTop: target.offset().top
			        }, 500, function() {
			          var $target = $(target);
			          setFocus($target);
			        });
			      }
			    }
			  });

		  	$(document).ready(function(){
		  		AOS.init();
		  		var controller = new ScrollMagic.Controller({
					globalSceneOptions: {
						triggerHook: 'onLeave',
						duration: "200%" // this works just fine with duration 0 as well
						// However with large numbers (>20) of pinned sections display errors can occur so every section should be unpinned once it's covered by the next section.
						// Normally 100% would work for this, but here 200% is used, as Panel 3 is shown for more than 100% of scrollheight due to the pause.
					}
				});

				// get all slides
				var slides = document.querySelectorAll(".scroller");
				var scenes = [];
				// create scene for every slide
				for (var i=0; i<slides.length; i++) {
					scenes[i] = new ScrollMagic.Scene({
							triggerElement: slides[i]
						})
						.setPin(slides[i], {pushFollowers: true})
						.addTo(controller);
				}
				var viewDuration = $('#scrollAni').outerHeight();
				var padding;
				if (($(window).width() <= 634)) {
					padding = 600;
				} else {
					padding = 1200;
				}

				// var skip = new ScrollMagic.Scene({triggerElement: "#scrollAni", duration: viewDuration, triggerHook: 'onEnter'})
				// 		.setPin("#skip")
				// 		.addTo(controller);
				var startFixed = $('#first').outerHeight() + $('#prescroll').outerHeight() + padding;
				var scrollSection = $('#scrollAni').outerHeight() + $('#first').outerHeight() + $('#view4').outerHeight() + padding;
				$(window).scroll(function() {
				  if( $(this).scrollTop() > startFixed && $(this).scrollTop() < scrollSection ) {
				  	$('.skip').addClass('fixed');
				  } else {
				    $('.skip').removeClass('fixed');
				  }
				});

		  	});
		 //  	var mn = $("#chatBlock");
			// mns = "fixed";
			// hdr = $('#prescroll')
			// .outerHeight();

			// $(window).scroll(function() {
			//   if( $(this).scrollTop() > hdr ) {
			//     mn.addClass(mns);
			//   } else {
			//     mn.removeClass(mns);
			//   }
			// });

        });
      </script>
</head>
<body id="tech">
<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
	

<?php endwhile;?>
<?php edit_post_link( __( '(Edit)', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>


		<?php do_action( 'foundationpress_layout_end' ); ?>

<?php wp_footer(); ?>
<?php do_action( 'foundationpress_before_closing_body' ); ?>
</body>