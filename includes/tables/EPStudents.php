<?php

/**
 * Class representing the ep_students table.
 *
 * @since 0.1
 *
 * @file EPStudents.php
 * @ingroup EducationProgram
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EPStudents extends ORMTable {

	/**
	 * (non-PHPdoc)
	 * @see ORMTable::getName()
	 * @since 0.1
	 * @return string
	 */
	public function getName() {
		return 'ep_students';
	}

	/**
	 * (non-PHPdoc)
	 * @see ORMTable::getFieldPrefix()
	 * @since 0.1
	 * @return string
	 */
	public function getFieldPrefix() {
		return 'student_';
	}

	/**
	 * (non-PHPdoc)
	 * @see ORMTable::getRowClass()
	 * @since 0.1
	 * @return string
	 */
	public function getRowClass() {
		return 'EPStudent';
	}

	/**
	 * (non-PHPdoc)
	 * @see ORMTable::getFields()
	 * @since 0.1
	 * @return array
	 */
	public function getFields() {
		return array(
			'id' => 'id',

			'user_id' => 'int',
			'first_enroll' => 'str', // TS_MW
			'first_course' => 'int',
			'last_enroll' => 'str', // TS_MW
			'last_course' => 'int',
			'last_active' => 'str', // TS_MW
			'active_enroll' => 'bool',
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see ORMTable::getSummaryFields()
	 * @since 0.1
	 * @return array
	 */
	public function getSummaryFields() {
		return array(
			'last_active',
			'active_enroll',
		);
	}

}
