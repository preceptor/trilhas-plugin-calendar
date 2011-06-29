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
 * @package    Calendar_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Calendar_Form_Form extends Zend_Form
{
    public function init()
    {
        $session   = new Zend_Session_Namespace('data');
        $calendar  = new Tri_Db_Table('calendar');
        $classroom = new Tri_Db_Table('classroom');

        $validators = $calendar->getValidators();
        $filters    = $calendar->getFilters();
        $options    = $classroom->fetchPairs('id', 'name', array('id IN(?)' => $session->classrooms));

        $this->setAction('calendar/index/save')
             ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
           ->addFilters($filters['id'])
           ->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $classroom_id = new Zend_Form_Element_Select('classroom_id');
        $classroom_id->setLabel('Classroom')
                     ->addValidators($validators['classroom_id'])
                     ->addFilters($filters['classroom_id'])
                     ->addMultiOptions(array('' => '[select]') + $options);

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->addValidators($validators['description'])
                    ->addFilters($filters['description'])
                    ->setAllowEmpty(false)
                    ->setAttrib('rows', 7);

        $begin = new Zend_Form_Element_Text('begin');
        $begin->setLabel('Begin')
              ->setAttrib('class', 'date')
              ->addFilters($filters['begin'])
              ->addValidators($validators['begin'])
              ->setAllowEmpty(false)
              ->getPluginLoader('filter')->addPrefixPath('Tri_Filter', 'Tri/Filter');

        $end = new Zend_Form_Element_Text('end');
        $end->setLabel('End')
            ->setAttrib('class', 'date')
            ->addFilters($filters['end'])
            ->getPluginLoader('filter')->addPrefixPath('Tri_Filter', 'Tri/Filter');

        $this->addElement($id);
        $this->addElement($classroom_id);
        $this->addElement($description);
        $this->addElement($begin);
        $this->addElement($end);
        $this->addElement('submit', 'Save');
    }
}
