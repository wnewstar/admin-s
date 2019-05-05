<?php

namespace App\Back\Widget;

class Tree
{
	public $filter = null;

	public function getTree($list, $k = 'id', $pk = 'pid')
	{
		$list = array_column($list, null, $k);
		foreach ($list as $k => $item) {
			if (!empty($list[$item[$pk]])) {
				$list[$k]['leaf'] = true;
				$list[$item[$pk]]['list'][$k] = &$list[$k];
			}
		}

		return array_filter($list, $this->filter ?: 'boolval');
	}
}