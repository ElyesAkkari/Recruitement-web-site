<?php

namespace App\EventSubscriber;

use App\Repository\OffreRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CalendarSubscriber implements EventSubscriberInterface
{

    private $session;

    private $lv ;
    function __construct(OffreRepository $lv,SessionInterface $session){
        $this->lv=$lv;
        $this->session = $session;
    }



    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $offres = $this->session->get('offres', []);

        foreach ($offres as $offre){

            $off=$this->lv->find($offre);
        if ($off)
        {
            // You may want to make a custom query from your database to fill the calendar
            $calendar->addEvent(new Event(

            $off->getNoms(),
            new \DateTime($off->getDatedeb()->format('d-m-Y')),
            new \DateTime($off->getDatefin()->format('d-m-Y'))
        ));
        }
        }



    }

    }
