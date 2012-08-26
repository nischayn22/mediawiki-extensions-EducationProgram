<?php

/**
 * Abstract action for viewing ORMRow items.
 *
 * @since 0.1
 *
 * @file EPViewAction.php
 * @ingroup EducationProgram
 * @ingroup Action
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class EPViewAction extends EPAction {

	/**
	 * @since 0.1
	 * @var EPPageTable
	 */
	protected $table;

	/**
	 * @since 0.2
	 * @var EPPageObject
	 */
	protected $object;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param Page $page
	 * @param IContextSource $context
	 * @param IORMTable $table
	 */
	protected function __construct( Page $page, IContextSource $context = null, EPPageTable $table ) {
		$this->table = $table;
		parent::__construct( $page, $context );
	}

	/**
	 * Returns the identifier for the object being viewed.
	 *
	 * @since 0.2
	 *
	 * @return string
	 */
	protected function getIdentifier() {
		return $this->getTitle()->getText();
	}

	/**
	 * @see Action::requiresUnblock
	 *
	 * @since 0.2
	 *
	 * @return boolean
	 */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see FormlessAction::onView()
	 */
	public function onView() {
		$out = $this->getOutput();

		$name = $this->getIdentifier();

		$object = false;

		if ( $this->getRequest()->getCheck( 'revid' ) ) {
			$currentObject = $this->table->get( $name, 'id' );

			if ( $currentObject !== false ) {
				$rev = EPRevisions::singleton()->selectRow( null, array(
					'id' => $this->getRequest()->getInt( 'revid' ),
					'object_id' => $currentObject->getField( 'id' )
				) );

				if ( $rev === false ) {
					// TODO high
				}
				else {
					$object = $rev->getObject();
					$this->displayRevisionNotice( $rev );
				}
			}
		}

		if ( $object === false ) {
			$object = $this->table->get( $name );
		}

		if ( $object === false ) {
			$this->displayNavigation();

			if ( $this->getUser()->isAllowed( $this->page->getEditRight() ) ) {
				$out->redirect( $this->getTitle()->getLocalURL( array( 'action' => 'edit' ) ) );
			}
			else {
				EPUtils::displayResult( $this->getContext() );

				$out->addWikiMsg( strtolower( get_called_class() ) . '-none', $name );

				$this->displayDeletionLog();
			}
		}
		else {
			EPUtils::displayResult( $this->getContext() );

			$this->displayNavigation();

			$this->object = $object;

			$this->startCache( 3600 );

			$this->addCachedHTML( array( $this, 'getPageHTML' ), $object );

			$this->saveCache();
		}

		return '';
	}

	/**
	 * (non-PHPdoc)
	 * @see Action::getDescription()
	 */
	protected function getDescription() {
		return '';
	}

	/**
	 * Display a revision notice as subtitle.
	 *
	 * @since 0.1
	 *
	 * @param EPRevision $rev
	 */
	protected function displayRevisionNotice( EPRevision $rev ) {
		$lang = $this->getLanguage();

		$td = $lang->timeanddate( $rev->getField( 'time' ), true );
		$tddate = $lang->date( $rev->getField( 'time' ), true );
		$tdtime = $lang->time( $rev->getField( 'time' ), true );

		$userToolLinks = Linker::userLink(  $rev->getUser()->getId(), $rev->getUser()->getName() )
			. Linker::userToolLinks( $rev->getUser()->getId(), $rev->getUser()->getName() );

		$infomsg = $rev->isLatest() && !wfMessage( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$this->getOutput()->setSubtitle(
			"<div id=\"mw-{$infomsg}\">" .
				wfMessage( $infomsg, $td )->rawParams( $userToolLinks )->params(
					$rev->getId(),
					$tdtime,
					$tddate,
					$rev->getUser()
				)->parse() .
				"</div>"
		);
	}

	/**
	 * Display the actual page.
	 *
	 * @since 0.1
	 *
	 * @param IORMRow $object
	 *
	 * @return string
	 */
	public function getPageHTML( IORMRow $object ) {
		return $this->getSummary( $object );
	}

	/**
	 * Displays the navigation menu.
	 *
	 * @since 0.1
	 */
	protected function displayNavigation() {
		$menu = new EPMenu( $this->getContext() );
		$menu->display();
	}

	/**
	 * Display the summary data.
	 *
	 * @since 0.1
	 *
	 * @param IORMRow $item
	 * @param boolean $collapsed
	 * @param array $summaryData
	 *
	 * @return string
	 */
	protected function getSummary( IORMRow $item, $collapsed = false, array $summaryData = null ) {
		$html = '';

		$class = 'wikitable ep-summary mw-collapsible';

		if ( $collapsed ) {
			$class .= ' mw-collapsed';
		}

		$html .= Html::openElement( 'table', array( 'class' => $class ) );

		$html .= '<tr>' . Html::element( 'th', array( 'colspan' => 2 ), wfMsg( 'ep-item-summary' ) ) . '</tr>';

		$summaryData = is_null( $summaryData ) ? $this->getSummaryData( $item ) : $summaryData;

		foreach ( $summaryData as $stat => $value ) {
			$html .= '<tr>';

			$html .= Html::element(
				'th',
				array( 'class' => 'ep-summary-name' ),
				wfMsg( strtolower( get_called_class() ) . '-summary-' . $stat )
			);

			$html .= Html::rawElement(
				'td',
				array( 'class' => 'ep-summary-value' ),
				$value
			);

			$html .= '</tr>';
		}

		$html .= Html::closeElement( 'table' );

		return $html;
	}

	/**
	 * Gets the summary data.
	 * Returned values must be escaped.
	 *
	 * @since 0.1
	 *
	 * @param IORMRow $item
	 *
	 * @return array
	 */
	protected function getSummaryData( IORMRow $item ) {
		return array();
	}

	/**
	 * @see CachedAction::getCacheKey
	 * @return array
	 */
	protected function getCacheKey() {
		return array_merge(
			array( $this->object->getTouched() ),
			$this->getRequest()->getValues(),
			parent::getCacheKey()
		);
	}

}