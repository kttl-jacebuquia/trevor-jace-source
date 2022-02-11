<?php namespace TrevorWP\Theme\Helper;

/**
 * Breathing Exercise
 */
class Breathing_Exercise {

	const TRIGGER_ELEMENT_CLASS        = 'js-breathing-exercise-toggle';
	public static $is_overlay_rendered = false;


	public function __construct() {}

	/**
	 * @return string|null
	 */

	static public function render_overlay(): void {
		// Render overlay only in footer
		// Render only if not yet existing
		if ( ! static::$is_overlay_rendered ) {
			static::$is_overlay_rendered = true;
			add_action( 'wp_footer', array( self::class, 'render_overlay_contents' ), 10, 0 );
		}
	}

	static public function render_overlay_contents(): void {
		ob_start(); ?>
		<div class="breathing-exercise-overlay" tabindex="0" role="dialog" title="Breathing Exercise">
			<div class="breathing-exercise-overlay__container">
				<div class="breathing-exercise-overlay__content content">
					<div class="countdown">
						<strong class="breathing-exercise-overlay__heading countdown__heading">Begin by counting down from 5</strong>
						<p class="countdown__number">5</p>
						<button class="countdown__skip">Skip</button>
					</div>
					<div class="step-one">
						<div class="step-one__content">
							<h2 class="step-one__heading breathing-exercise-overlay__heading">1-Minute<br>Breathing Exercise</h2>
							<p class="step-one__description">Start by following your breath as it moves in through your nose and out through your mouth.</p>
							<div class="step-one__arrow start">
								<button class="step-one__cta step-one__cta--icon start" aria-label="Start">
									<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<circle cx="23" cy="23" r="23" fill="#101066"/>
										<path d="M13.6753 22.871C13.2611 22.871 12.9253 23.2068 12.9253 23.621C12.9253 24.0352 13.2611 24.371 13.6753 24.371V22.871ZM31.611 24.1513C31.9039 23.8584 31.9039 23.3835 31.611 23.0906L26.8381 18.3177C26.5452 18.0248 26.0703 18.0248 25.7774 18.3177C25.4845 18.6106 25.4845 19.0854 25.7774 19.3783L30.02 23.621L25.7774 27.8636C25.4845 28.1565 25.4845 28.6314 25.7774 28.9243C26.0703 29.2172 26.5452 29.2172 26.8381 28.9243L31.611 24.1513ZM13.6753 24.371H31.0807V22.871H13.6753V24.371Z" fill="white"/>
									</svg>
								</button>
							</div>
							<button class="step-one__cta start">Start</button>
						</div>
					</div>
					<div class="breathing">
						<div class="breathing__content">
							<div class="breathing__blob-container">
								<canvas id="blob" class="canvas-blob small"></canvas>
							</div>
							<div class="copy-wrapper">
								<span class="breathing-exercise-overlay__heading rotate-copy">Let's begin</span>
							</div>
						</div>
					</div>
					<div class="breathing-end">
						<div class="breathing-end__content">
							<p class="breathing-end__heading breathing-exercise-overlay__heading">If you're still feeling distracted or tense, go ahead and try the exercise again</p>
							<div class="breathing-end__icon">
								<button type="button" class="breathing-end__cta breathing-end__cta--icon repeat" aria-label="Try again?">
									<svg aria-hidden="true" width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="23" cy="23" r="23" fill="#101066"/>
										<path d="M22.8824 11.2712C24.9451 11.2712 27.011 11.8567 28.8569 12.9646C30.0277 13.669 31.0798 14.5543 31.9735 15.5881L28.8898 15.4864C28.5276 15.4743 28.2243 15.7583 28.2124 16.1207C28.2005 16.4829 28.4843 16.7864 28.8467 16.7981L33.1133 16.939C33.3584 17.0399 33.6402 16.9835 33.8276 16.7958C34.0149 16.6082 34.0708 16.3264 33.9695 16.0813L33.8291 11.8157C33.8209 11.4533 33.5204 11.1662 33.158 11.1742C32.7959 11.1824 32.5084 11.4829 32.5166 11.845C32.5166 11.85 32.5166 11.8545 32.517 11.8592L32.5982 14.3201C31.6954 13.3554 30.6637 12.5202 29.5323 11.8387C27.4825 10.6088 25.183 9.95874 22.8824 9.95874C19.2074 9.95874 15.8191 11.4741 13.343 14.2254C11.1495 16.6627 9.83984 19.9434 9.83984 23.0013C9.83984 23.3637 10.1335 23.6575 10.4961 23.6575C10.8587 23.6575 11.1523 23.3637 11.1523 23.0013C11.1523 20.2588 12.3358 17.3063 14.3185 15.1035C16.5428 12.6325 19.5839 11.2712 22.8824 11.2712Z" fill="white"/>
										<path d="M35.4334 22.3447C35.0709 22.3447 34.7772 22.6384 34.7772 23.001C34.7772 25.7433 33.5937 28.696 31.611 30.8989C29.3867 33.3699 26.3456 34.7308 23.0471 34.7308C20.9845 34.7308 18.9185 34.1457 17.0726 33.0379C15.9018 32.3333 14.8498 31.4479 13.956 30.4143L17.0398 30.5161C17.402 30.5278 17.7053 30.2437 17.7172 29.8816C17.7291 29.5194 17.4452 29.2161 17.0829 29.204L12.8162 29.0633C12.5711 28.9624 12.2894 29.019 12.1019 29.2064C11.9147 29.3941 11.8587 29.6757 11.96 29.9207L12.1005 34.1863C12.1087 34.5489 12.4091 34.8362 12.7715 34.828C13.1337 34.82 13.4212 34.5196 13.413 34.1572C13.413 34.1525 13.413 34.148 13.4126 34.1431L13.3314 31.6821C14.2341 32.647 15.2659 33.4819 16.3973 34.1638C18.447 35.3938 20.7466 36.0437 23.0471 36.0437C26.7221 36.0437 30.1104 34.5286 32.5866 31.7773C34.7801 29.3397 36.0897 26.0591 36.0897 23.001C36.0897 22.6384 35.796 22.3447 35.4334 22.3447Z" fill="white"/>
									</svg>
								</button>
							</div>
							<button type="button" class="breathing-end__cta repeat">Try again?</button>
						</div>
					</div>
				</div>
			</div>

			<button class="breathing-exercise-overlay__close trevor-ti-close" aria-label="click here to close the breathing excercise popup"></button>
		</div>
		<?php
		echo ob_get_clean();
	}
}
