<?php

namespace App\Models;

use DateTime;
use Exception;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;


class CalendarModel extends Model
{
    public function select()
    {
        $builder = $this->db->table('event');
        $builder->select();
        $builder->orderBy('id');
        $query = $builder->get();
        
        $calendar = $query->getResult();
        return $calendar;
    }

    public function postCalendar($param)
    {   
        $builder = $this->db->table('event');
        $builder->insert([
            'title' => $param['title'],
            'start'   => $param['start'],
            'end' => $param['end'],
        ]);
    }

    public function putCalendar($param)
    {
        $builder = $this->db->table('event');
        $builder->set('start', $param['start']);
        $builder->set('end', $param['end']);
        $builder->where('event.id', $param['id']);
        $builder->update();
    }

    public function deleteCalendar($param)
    {
        $builder = $this->db->table('event');
        $queryDelete = $builder->where('id', $param["id"]);
        $queryDelete->delete();
    }
}