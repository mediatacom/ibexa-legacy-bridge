<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Legacy\Templating\Adapter;

use MediataCom\MediataEzpageFieldtypeBundle\Core\FieldType\Page\Parts\Block;
use eZFlowPool;

/**
 * Adapter for page Block objects.
 * /!\ Warning /!\ This object can only used in a legacy context (e.g. in a LegacyKernel::runCallback()) !
 */
class BlockAdapter extends DefinitionBasedAdapter
{
    protected function definition()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'action' => 'action',
            'items' => 'items',
            'rotation' => 'rotation',
            'custom_attributes' => 'customAttributes',
            'type' => 'type',
            'view' => 'view',
            'overflow_id' => 'overflowId',
            'zone_id' => 'zoneId',
            'valid_items' => static function (Block $block) {
                return eZFlowPool::validItems($block->id);
            },
            'valid_nodes' => static function (Block $block) {
                return eZFlowPool::validNodes($block->id);
            },
            'archived_items' => static function (Block $block) {
                return eZFlowPool::archivedItems($block->id);
            },
            'waiting_items' => static function (Block $block) {
                return eZFlowPool::waitingItems($block->id);
            },
            'last_valid_items' => static function (Block $block) {
                $validItems = eZFlowPool::validItems($block->id);
                if (empty($validItems)) {
                    return;
                }

                $result = null;
                $lastTime = 0;
                foreach ($validItems as $item) {
                    if ($item->attribute('ts_visible') >= $lastTime) {
                        $lastTime = $item->attribute('ts_visible');
                        $result = $item;
                    }
                }

                return $result;
            },
            // The following is only for block_view_gui template function in legacy.
            'view_template' => static function (Block $block) {
                return 'view';
            },
        ];
    }
}
