<?php namespace TrevorWP\Theme\ACF;

class ACF {
	const ALL_GROUPS = array(
		// Common Fields
		Field_Group\DOM_Attr::class,
		Field_Group\Advanced_Link::class,
		Field_Group\Button::class,
		Field_Group\Button_Group::class,
		Field_Group\Carousel_Data::class,
		Field_Group\Block_Styles::class,
		// Options
		Options_Page\Header::class,
		Options_Page\Footer::class,
		Options_Page\Site_Banners::class,
		Options_Page\Quick_Exit::class,
		Options_Page\Fundraiser_Quiz::class,
		Options_Page\Donation_Modal::class,
		Options_Page\Promo::class,
		Options_Page\Four_O_Four::class,
		Options_Page\Search::class,
		Options_Page\External_Scripts::class,
		Options_Page\API_Keys::class,
		Options_Page\Trevorspace::class,
		Options_Page\Fundraise::class,
		// - Post Type Options
		Options_Page\Post_Type\Post::class,
		Options_Page\Post_Type\Financial_Report::class,
		Options_Page\Post_Type\Research::class,
		Options_Page\Post_Type\Letter::class,
		Options_Page\Post_Type\Bill::class,
		Options_Page\Resource_Center::class,
		// Blocks
		Field_Group\HTML_Elem::class,
		Field_Group\Grid_Row::class,
		Field_Group\Grid_Rows_Container::class,
		Field_Group\Page_Section::class,
		Field_Group\Testimonials_Carousel::class,
		Field_Group\Page_Circulation::class,
		Field_Group\Info_Boxes::class,
		Field_Group\Post_Grid::class,
		Field_Group\Post_Carousel::class,
		Field_Group\Info_Card::class,
		Field_Group\Info_Card_Grid::class,
		Field_Group\Address::class,
		Field_Group\Embed::class,
		Field_Group\Donate_Form::class,
		Field_Group\Charity_Navigator::class,
		Field_Group\FAQ::class,
		Field_Group\URL_File_List::class,
		Field_Group\Alternating_Image_Text::class,
		Field_Group\Pillars::class,
		Field_Group\Checkmark_Text::class,
		Field_Group\Guiding_Principles::class,
		Field_Group\Topic_Cards::class,
		Field_Group\Center_Text_Full_Width_Image::class,
		Field_Group\Text_Icon::class,
		Field_Group\Image_Carousel::class,
		Field_Group\Current_Partners_Table::class,
		Field_Group\Breathing_Exercise::class,
		Field_Group\Messaging_Block::class,
		Field_Group\Overlapping_Text_Image_Tiles::class,
		Field_Group\Resource_Center_Block::class,
		Field_Group\Current_Funders_Table_CTA::class,
		Field_Group\ECT_Map::class,
		Field_Group\Campaign_Form::class,
		Field_Group\Header_Image_Grid::class,
		Field_Group\Text_Only_Two_Up::class,
		Field_Group\Training::class,
		Field_Group\Statistics_Block::class,
		Field_Group\Information_Cards::class,
		Field_Group\Featured_Resource_Two_Up::class,
		Field_Group\Featured_Card_Three_Up::class,
		Field_Group\Staff_Module::class,
		Field_Group\Side_Text_Image::class,
		Field_Group\Organization_Mission::class,
		Field_Group\Volunteer_Information_Cards::class,
		Field_Group\Recent_Highlights::class,
		Field_Group\Text_Overlapping_Image::class,
		Field_Group\Current_Openings::class,
		Field_Group\Article_River::class,
		Field_Group\Video_Player::class,
		Field_Group\Custom_Heading::class,
		// Page Specific
		Field_Group\Page_Header::class,
		Field_Group\Page_Circulation_Card::class,
		Field_Group\Page_Sidebar::class,
		Field_Group\Team_Member::class,
		Field_Group\Partners::class,
		Field_Group\Post_Images::class,
		Field_Group\Financial_Report::class,
		Field_Group\Event::class,
		Field_Group\Product::class,
		Field_Group\Text_Only_Popup::class,
		Field_Group\Chat_Link_Option::class,
		Field_Group\What_To_Expect_Popup::class,
		Field_Group\Post_Details::class,
		Field_Group\Promo_Popup::class,
		Field_Group\Form::class,
	);

	public static function construct() {
		add_action( 'acf/init', array( self::class, 'acf_init' ), 10, 0 );
	}

	/**
	 * @link https://www.advancedcustomfields.com/resources/acf-init/
	 */
	public static function acf_init(): void {
		# Groups
		/** @var Field_Group\A_Field_Group $group */
		foreach ( static::ALL_GROUPS as $group ) {
			$group::register();

			if ( $group::is_block() ) {
				# Block
				$group::register_block();

				# Block Patterns
				if ( $group::has_patterns() ) {
					$group::register_patterns();
				}
			} elseif ( $group::is_options_page() ) {
				# Options Page
				/* acf_init is at init:5,
				   we should wait until init:10 to get post types to be registered */
				add_action( 'init', array( $group, 'register_page' ), 10, 0 );
			}
		}
	}
}
