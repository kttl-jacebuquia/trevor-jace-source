<?php namespace TrevorWP\Twig\Token_Parser;

use Twig;

/**
 * footerprint tag token parser
 */
class Footer_Print extends Twig\TokenParser\AbstractTokenParser {
	/**
	 * Parses a token and returns a node.
	 *
	 * @param Twig\Token $token A Twig_Token instance
	 *
	 * @return Twig\Node\Node A Twig_NodeInterface instance
	 */
	public function parse( Twig\Token $token ) {
		$line_no = $token->getLine();

		$this->parser->getStream()->expect( Twig\Token::BLOCK_END_TYPE );
		$body = $this->parser->subparse( array( $this, 'decideEnd' ), true );
		$this->parser->getStream()->expect( Twig\Token::BLOCK_END_TYPE );

		return new \TrevorWP\Twig\Node\Footer_Print( $body, $line_no, $this->getTag() );
	}

	/**
	 * Gets the tag name associated with this token parser.
	 *
	 * @return string The tag name
	 */
	public function getTag() {
		return 'footerprint';
	}

	public function decideEnd( Twig\Token $token ) {
		return $token->test( 'endfooterprint' );
	}
}
