import $ from 'jquery';

// Social Media Sharing
$(() => {
	$('.post-social-share-btn').on('click', (e) => {
		e.preventDefault();

		window.open(e.currentTarget.href, 'Social Media Sharing', "width=480,height=640");
	});
});
