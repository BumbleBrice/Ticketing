<?php

namespace App\Service;

use App\Entity\Spectacle;
use App\Service\Weezevent;
use App\Repository\SpectacleRepository;

class RefreshEvents
{
    public function refresh($spectacleRepository, $em)
    {
        $weezevent = new Weezevent('collilieux.brice@gmail.com', 'bumbleDev&2018', 'accbf05c0bc82872681e3c63eb9d0d4d');

        $events = $weezevent->getEvents([
            'include_not_published' => true,    
            'include_closed'        => true,
            'include_without_sales' => true]);

        dump($events);
        dump($weezevent->getEventDetails('374379'));

        $eventsIds = [];

        foreach($events->events as $event)
        {
            $eventsIds[] = $event->id;
            
            $eventDetail = $weezevent->getEventDetails($event->id);

            $spectacle = $spectacleRepository->findOneBy(['weezevent_id' => $event->id]);

            if($spectacle === null || ($spectacle !== null && $spectacle->getLastUpdate() !== $eventDetail->last_update))
            {
                if($spectacle === null)
                {
                    $spectacle = new Spectacle();
                    $new_spectacle = true;
                }
                
                $spectacle->setNom($event->name);
                
                $date = new \DateTime($event->date->start);

                $spectacle->setDate($date);
                $spectacle->setPlaces($event->participants);
                $spectacle->setDescription(strip_tags($eventDetail->events->description));
                $spectacle->setWeezeventId($event->id);
                $spectacle->setLastUpdate($eventDetail->last_update);
                $spectacle->setPicture($eventDetail->events->image);
                $spectacle->setSiteURL($eventDetail->events->site_url);

                if(isset($new_spectacle))
                {
                    $em->persist($spectacle);
                }
                
                $em->flush();
            }
        }

        foreach($spectacleRepository->findAll() as $spectacle)
        {
            if(!in_array($spectacle->getWeezeventId(), $eventsIds))
            $em->remove($spectacle);
            $em->flush();
        }
    }
}