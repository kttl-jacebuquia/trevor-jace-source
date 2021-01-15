// Vendors
import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';

window.trevorWP = {features, vendors, matchMedia};

const $body = $('body');
const isSingle = $body.hasClass('single');
const isGetHelp = !isSingle && $body.hasClass('is-get-help');
const isTrevorspace = !isSingle && $body.hasClass('is-trevorspace');

// Tag Box Ellipsis
features.tagBoxEllipsis($('.card-post'));

// Single (Detail) Page
if (isSingle) {
	// Floating Blocks
	features.floatingBlock(
		$('.post-content .trevor-block-floating'),
		$('.post-content-sidebar .floating-blocks-home')
	);

	// Highlights
	features.articleHighlights($('.post-highlights-list'));

	// Sharing More: Dropdown/Native
	features.sharingMore(
		document.querySelector('.post-share-more-btn'),
		document.querySelector('.post-share-more-content')
	);

	// Open social media sharing pages in a popup
	features.sharingPopUp($('.post-social-share-btn'));
}

// Get Help
isGetHelp && console.log('Get-Help page');

// Trevorspace
isTrevorspace && console.log('Trevorspace page');

features.modal($('.modal'), {}, $('.modal-open'))
