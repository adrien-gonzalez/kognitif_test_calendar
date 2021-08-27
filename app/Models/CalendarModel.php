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
            'eventId' =>$param['eventId']
        ]);
    }

    public function putCalendar($param)
    {
        $builder = $this->db->table('event');
        $builder->set('start', $param['start']);
        $builder->set('end', $param['end']);
        $builder->where('eventId', $param['id']);
        $builder->update();
    }

    public function deleteCalendar($param)
    {
        if (isset($param['events'])) {
           $localEvents = $this->select();

           if(count($param['events']) > 0) {
                for ($i = 0; $i < count($param['events']); $i++) {
                    for ($j = 0; $j < count($localEvents); $j++) {
                        if($param['events'][$i]['id'] != $localEvents[$j]->eventId) {
                            $builder = $this->db->table('event');
                            $queryDelete = $builder->where('eventId', $localEvents[$j]->eventId);
                            $queryDelete->delete();
                        }
                    }
                }
           }   
        } else if(isset($param['id'])) {
           
            $builder = $this->db->table('event');
            $queryDelete = $builder->where('eventId', $param["id"]);
            $queryDelete->delete();

        } else {
            $builder = $this->db->table('event');
            $builder->emptyTable(); 
        }
        
    }
}