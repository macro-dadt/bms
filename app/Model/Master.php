<?php
/**
 * Master
 *
 * @package    app.Model
 * @property Master $Master
 */
class Master extends AppModel {

	/**
	 * マスターデータ全取得
	 *
	 * @return [type] [description]
	 */
	public function get($type = null)
	{
		$fields = array(
			'fields' => array(
				$this->alias . '.key',
				$this->alias . '.value'
			)
		);

		if($type) {
			$conditions = array(
				'conditions' => array(
					$this->alias . '.type' => $type
				)
			);
			$data = Set::merge($fields, $conditions);
		} else {
			$data = array(
				$fields
			);
		}

		return $this->find('list', $data);

	}

}
