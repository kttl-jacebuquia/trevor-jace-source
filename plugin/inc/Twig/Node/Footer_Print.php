<?php namespace TrevorWP\Twig\Node;

use Twig;

/**
 * footerprint tag node
 */
class Footer_Print extends Twig\Node\Node {
	/**
	 * @param Twig\Node\Node $body
	 * @param int $line_no
	 * @param string $tag
	 */
	public function __construct( Twig\Node\Node $body, $line_no, $tag = 'footerprint' ) {
		parent::__construct( [ 'body' => $body ], [], $line_no, $tag );
	}

	/**
	 * Compiles the node to PHP.
	 *
	 * @param Twig\Compiler $compiler A Twig_Compiler instance
	 */
	public function compile( Twig\Compiler $compiler ) {
		$compiler
			->addDebugInfo( $this )
			->write( "ob_start();\n" )
			->subcompile( $this->getNode( 'body' ) )
			->write( '\TrevorWP\Util\Tools::footer_print(ob_get_clean());' . "\n" );
	}
}
