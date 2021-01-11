export default function sharingPopUp($btn) {
	$btn.on('click', (e) => {
		e.preventDefault();

		window.open(e.currentTarget.href, 'Social Media Sharing', "width=480,height=640");
	});
}
