<?php

// function buildWhereConditions($wheres): string
//     {
//         $conditions = [];
//         foreach ($wheres as $where) {
//             if (is_null($where['value'])) {
//                 $conditions[] = $where['field'] . " " . $where['operator']; // это под null
//             }
//             elseif (in_array($where['operator'], ['IN', 'NOT IN'])) {
//                 $conditions[] = $where['field'] . " " . $where['operator'] . "(" .
//                     implode(', ', array_map(
//                         fn($value) => "'" . $this->connection->real_escape_string($value) . "'",
//                          $where['value']))
//                     . ")" ;
//             } else {
//                 $conditions[] = $where['field'] . " " . $where['operator'] . " '" . $where['value'] . "'";
//             }
//         }
//         return implode(' AND ', $conditions);
//     }
// echo buildWhereConditions([
//     [
//         'field' => 'ID',
//         'operator' => '!=',
//         'value' => '1'
//     ],
//     [
//         'field' => 'name',
//         'operator' => '=',
//         'value' => 'any_string'
//     ]
// ]);