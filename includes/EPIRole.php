<?php

/**
 * Interface for classes representing a user in a certain role.
 *
 * @since 0.1
 *
 * @file EPIRole.php
 * @ingroup EducationProgram
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface EPIRole {

	/**
	 * Create a new role object from a user id.
	 *
	 * @since 0.1
	 *
	 * @param integer $userId
	 * @param boolean $load If the object should be loaded from the db if it already exists
	 * @param null|array|string $fields Fields to load
	 *
	 * @return EPIRole
	 */
	public static function newFromUserId( $userId, $load = false, $fields = null );

	/**
	 * Create a new role object from a user object.
	 *
	 * @since 0.1
	 *
	 * @param User $user
	 * @param boolean $load If the object should be loaded from the db if it already exists
	 * @param null|array|string $fields Fields to load
	 *
	 * @return EPIRole
	 */
	public static function newFromUser( User $user, $load = false, $fields = null );


	/**
	 * Returns the user.
	 *
	 * @since 0.1
	 *
	 * @return User
	 */
	public function getUser();

	/**
	 * Returns name of the user.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Retruns the user link.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getUserLink();

	/**
	 * Returns the tool links for this user.
	 *
	 * @since 0.1
	 *
	 * @param IContextSource $context
	 * @param EPCourse|null $course
	 *
	 * @return string
	 */
	public function getToolLinks( IContextSource $context, EPCourse $course = null );

	/**
	 * Returns a short name for the role.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getRoleName();

}
