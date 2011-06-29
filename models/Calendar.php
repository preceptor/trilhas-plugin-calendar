<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @category   Calendar
 * @package    Calendar_Model
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Calendar_Model_Calendar
{
    public static function getByClassroom($courses, $limit = null)
    {
        if ($courses) {
            foreach ($courses as $course) {
                $ids[] = $course['classroom_id'];
            }
        } else {
            $ids = array(0);
        }

        $calendar = new Tri_Db_Table('calendar');
        $where = array();
        $where['end IS NULL OR end > ?'] = date('Y-m-d');
        $where['classroom_id IS NULL OR classroom_id IN(?)'] = $ids;
        return $calendar->fetchAll($where, 'begin', $limit);
    }
}