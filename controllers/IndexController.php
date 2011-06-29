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
 * @category   Calendat
 * @package    Calendar_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Calendar_IndexController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Calendar";
    }

    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $calendar = new Tri_Db_Table('calendar');
        $courses  = Application_Model_Classroom::getAllByUser($identity->id);

        $this->view->data = Calendar_Model_Calendar::getByClassroom($courses);
    }

    public function formAction()
    {
        $id   = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Calendar_Form_Form();

        if ($id) {
            $calendar = new Tri_Db_Table('calendar');
            $row      = $calendar->find($id)->current();

            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function saveAction()
    {
        $form  = new Calendar_Form_Form();
        $table = new Tri_Db_Table('calendar');
        $data  = $this->_getAllParams();

        if ($form->isValid($data)) {
            $data = $form->getValues();
            $data['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;

            if (!$data['classroom_id']) {
                unset($data['classroom_id']);
            }

            if (isset($data['id']) && $data['id']) {
                $row = $table->find($data['id'])->current();
                $row->setFromArray($data);
            } else {
                unset($data['id']);
                $row = $table->createRow($data);
            }

            $id = $row->save();

            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('calendar/index/form/id/'.$id);
        }

        $this->_helper->_flashMessenger->addMessage('Error');
        $this->view->form = $form;
        $this->render('form');
    }

    public function deleteAction()
    {
        $calendar = new Zend_Db_Table('calendar');
        $id       = Zend_Filter::filterStatic($this->_getParam('id'), 'int');

        if (!$id) {
            $this->_redirect('calendar/index/');
        }

        $calendar->delete(array('id = ?' => $id));
        $this->_helper->_flashMessenger->addMessage('Success');
        $this->_redirect('calendar/index/');
    }

    public function widgetAction()
    {
        $calendar = new Tri_Db_Table('calendar');

        $where = array('classroom_id IS NULL', 'end IS NULL OR end > ?' => date('Y-m-d'));
        $this->view->data = $calendar->fetchAll($where, 'begin ASC', 3);
    }

    public function dashboardAction()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $calendar  = new Tri_Db_Table('calendar');
        $courses   = Application_Model_Classroom::getAllByUser($identity->id);

        $this->view->data = Calendar_Model_Calendar::getByClassroom($courses, 3);

        $this->render('widget');
    }
}
