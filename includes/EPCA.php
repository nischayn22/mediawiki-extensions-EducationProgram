<?php

/**
 * Class representing a single campus ambassador.
 *
 * @since 0.1
 *
 * @file EPCA.php
 * @ingroup EducationProgram
 *
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EPCA extends EPRoleObject implements EPIRole {

	/**
	 * Display a pager with campus ambassadors.
	 *
	 * @since 0.1
	 *
	 * @param IContextSource $context
	 * @param array $conditions
	 *
	 * @return string
	 */
	public static function getPager( IContextSource $context, array $conditions = array() ) {
		$pager = new EPCAPager( $context, $conditions );

		if ( $pager->getNumRows() ) {
			return
				$pager->getFilterControl() .
				$pager->getNavigationBar() .
				$pager->getBody() .
				$pager->getNavigationBar() .
				$pager->getMultipleItemControl();
		}
		else {
			return $pager->getFilterControl( true ) .
				$context->msg( 'ep-ca-noresults' )->escaped();
		}
	}
	
	/**
	 * @since 0.1
	 * @see EPIRole::getRoleName
	 */
	public function getRoleName() {
		return 'campus';
	}
	
}
