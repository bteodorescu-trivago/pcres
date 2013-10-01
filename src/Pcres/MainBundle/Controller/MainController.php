<?php

namespace Pcres\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pcres\MainBundle\Form\Search;

use Pcres\MainBundle\Entity\PcArea as PcArea;
use Pcres\MainBundle\Entity\Pc;
use Pcres\MainBundle\Entity\PcReservation as PcReservation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new Search());

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();

            // get data from the form
            $form_data = $form->getData();


            $name = '';
            if (isset($form_data['pc_name']))
            {
                $name = $form_data['pc_name'];
            }

            $area = '';
            if (isset($form_data['pc_area']))
            {
                $area = $form_data['pc_area'];
            }


            $qb->select('p')
               ->from('Pcres\MainBundle\Entity\Pc', 'p')
               ->join('p.area', 'a')
               ->Where($qb->expr()->andX(
                  $qb->expr()->like('p.name', '?1'),
                  $qb->expr()->like('a.name', '?2')
             ))
              ->orderBy('p.id', 'ASC')

               ->setParameter(1, '%' . $name . '%')
               ->setParameter(2, '%' . $area . '%');

            $query = $qb->getQuery();

            // find all pcs that matches pc name and pc area from inputs
            $pcs = $query->getResult();


            $valid_pcs = array();
            $available_hours =array();
            $alternative = true;

            foreach ($pcs as $pc)
            {
                // calculate data
                if ($form_data['day'] == "tomorrow" ){
                    $start_time = new \DateTime("tomorrow");
                }
                else{
                    $start_time = new \DateTime("today");
                }
                $start_time->setTime($form_data['start_hour'], 0);

                if ($form_data['day'] == "tomorrow" ){
                    $end_time = new \DateTime("tomorrow");
                }
                else{
                    $end_time = new \DateTime("today");
                }
                $end_time->setTime($form_data['end_hour'], 0);


                // create query to check if there are any reservation for current pc
                $qb2 = $em->createQueryBuilder();
                $qb2->select('r')
                ->from('Pcres\MainBundle\Entity\PcReservation', 'r')
                ->join('r.pc', 'p')
                ->Where($qb2->expr()->andX(
                    $qb2->expr()->eq('p.id', '?1'),
                    $qb2->expr()->gt('r.endTime', '?2'),
                    $qb2->expr()->lt('r.startTime', '?3')
                ))
                ->orderBy('p.id', 'ASC')

                ->setParameter(1, $pc->getId())
                ->setParameter(2, $start_time)
                ->setParameter(3, $end_time);

                $query2 = $qb2->getQuery();
                $reservations = $query2->getResult();

                if (count($reservations) == 0)
                {
                    $valid_pcs[] = $pc;
                    $alternative = false;
                }
            }


            // Provide alternative timestamps
            if (count($valid_pcs) == 0)
            {
                foreach ($pcs as $pc)
                {
                    // for current computer check if there is an reservation available on timestamp [input_start_hour minus one hour,   input_end_hour minus one hour]
                    if ($form_data['start_hour'] > 8 )
                    {
                        $start_time->setTime($form_data['start_hour']-1, 0);
                        $end_time->setTime($form_data['end_hour']-1, 0);

                        // create query to check if there are any reservation for current pc
                        $qb2 = $em->createQueryBuilder();
                        $qb2->select('r')
                            ->from('Pcres\MainBundle\Entity\PcReservation', 'r')
                            ->join('r.pc', 'p')
                            ->Where($qb2->expr()->andX(
                                $qb2->expr()->eq('p.id', '?1'),
                                $qb2->expr()->gt('r.endTime', '?2'),
                                $qb2->expr()->lt('r.startTime', '?3')
                            ))
                            ->orderBy('p.id', 'ASC')

                            ->setParameter(1, $pc->getId())
                            ->setParameter(2, $start_time)
                            ->setParameter(3, $end_time);

                        $query2 = $qb2->getQuery();
                        $reservations = $query2->getResult();
                        if (count($reservations) == 0)
                        {
                            $valid_pcs[] = $pc;

                            $available_hours[] = array( 'start_hour' => $form_data['start_hour']-1, 'end_hour' => $form_data['end_hour']-1 );
                            $alternative = true;
                        }
                    }

                    // for current computer check if there is an reservation available on timestamp [input_start_hour plus one hour,   input_end_hour plus one hour]
                    if ($form_data['end_hour'] < 18 )
                    {
                        $start_time->setTime($form_data['start_hour']+1, 0);
                        $end_time->setTime($form_data['end_hour']+1, 0);

                        // create query to check if there are any reservation for current pc
                        $qb2 = $em->createQueryBuilder();
                        $qb2->select('r')
                            ->from('Pcres\MainBundle\Entity\PcReservation', 'r')
                            ->join('r.pc', 'p')
                            ->Where($qb2->expr()->andX(
                                $qb2->expr()->eq('p.id', '?1'),
                                $qb2->expr()->gt('r.endTime', '?2'),
                                $qb2->expr()->lt('r.startTime', '?3')
                            ))
                            ->orderBy('p.id', 'ASC')

                            ->setParameter(1, $pc->getId())
                            ->setParameter(2, $start_time)
                            ->setParameter(3, $end_time);

                        $query2 = $qb2->getQuery();
                        $reservations = $query2->getResult();
                        if (count($reservations) == 0)
                        {
                            $valid_pcs[] = $pc;

                            $available_hours[] = array( 'start_hour' => $form_data['start_hour']+1, 'end_hour' => $form_data['end_hour']+1 );
                            $alternative = true;
                        }
                    }

                }

            }

            $result = array();
            $result['pcs'] = $valid_pcs;

            $result['start_hour'] =  $form_data['start_hour'];
            $result['end_hour'] =  $form_data['end_hour'];
            $result['day'] =  $form_data['day'];
            $result['email'] =  $form_data['email'];

            $result['alternative'] =  $alternative;
            $result['available_hours'] =  $available_hours;


            return $this->render(
                'PcresMainBundle:Main:search_result.html.twig',
                array('search_result' => $result)
            );
        }

        return $this->render('PcresMainBundle:Main:search.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function reserveAction()
    {
        $em = $this->getDoctrine()->getManager();

        $request = $this->getRequest();
        $id = $request->query->get('id');

        $email = $request->query->get('email');

        $day = $request->query->get('day');

        $start_hour = $request->query->get('start_hour');

        $end_hour = $request->query->get('end_hour');

        $pc = $em->find('Pcres\MainBundle\Entity\Pc', $id);

        if ( !empty($pc) && $start_hour < $end_hour && $start_hour>=8 && $end_hour<=18 )
        {
            // calculate data
            if ($day == "tomorrow" ){
                $start_time = new \DateTime("tomorrow");
            }
            else{
                $start_time = new \DateTime("today");
            }
            $start_time->setTime($start_hour, 0);


            if ($day == "tomorrow" ){
                $end_time = new \DateTime("tomorrow");
            }
            else{
                $end_time = new \DateTime("today");
            }
            $end_time->setTime($end_hour, 0);


            // create query to check if there are any reservation for current pc
            $qb2 = $em->createQueryBuilder();
            $qb2->select('r')
                ->from('Pcres\MainBundle\Entity\PcReservation', 'r')
                ->join('r.pc', 'p')
                ->Where($qb2->expr()->andX(
                    $qb2->expr()->eq('p.id', '?1'),
                    $qb2->expr()->gt('r.endTime', '?2'),
                    $qb2->expr()->lt('r.startTime', '?3')
                ))
                ->orderBy('p.id', 'ASC')

                ->setParameter(1, $id)
                ->setParameter(2, $start_time)
                ->setParameter(3, $end_time);

            $query2 = $qb2->getQuery();
            $reservations = $query2->getResult();


            // 1 means it was saved
            // 0 means it was not saved
            // e means there was an error
            $saved = '0';
            if (count($reservations) == 0)
            {
                // save the data into database
                $reservation = new PcReservation();
                $reservation->setPc($pc);
                $reservation->setStartTime($start_time);
                $reservation->setEndTime($end_time);
                $reservation->setEmail($email);
                $em->persist($reservation);
                $em->flush();
                $saved = '1';

                // send email
                $mailBody = array();
                $mailBody['pc_name'] = $pc->getName();
                $mailBody['pc_area'] = $pc->getArea()->getName();
                $mailBody['start_time'] = $start_time;
                $mailBody['end_time'] = $end_time;

                $message = \Swift_Message::newInstance()
                    ->setSubject('PC Reservation')
                    ->setFrom('teobogdan87@yahoo.com')
                    ->setTo($email)
                    ->setBody($this->renderView('PcresMainBundle:Main:email.txt.twig', array('mailBody' => $mailBody)));
                $this->get('mailer')->send($message);


            }
        }
        else  // check what kind of error to display
        {
            if (empty($pc)) $saved = 'e1';
            if ( $start_hour >= $end_hour ) $saved = 'e2';
            if ( $start_hour < 8 ) $saved = 'e3';
            if ( $end_hour > 18 ) $saved = 'e4';
        }



        return $this->render(
            'PcresMainBundle:Main:reserve_result.html.twig',
            array('reserve_result' => $saved)
        );

    }

    public function testAction()
    {

        $pc_area = new PcArea();
        $pc_area->setName('Special Projects ');

        $em = $this->getDoctrine()->getManager();
        $em->persist($pc_area);



        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 101');
        $pc->setIp('200.200.0.101');

        $em->persist($pc);
        $em->flush();

        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 102');
        $pc->setIp('200.200.0.102');

        $em->persist($pc);
        $em->flush();

        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 103');
        $pc->setIp('200.200.0.103');

        $em->persist($pc);
        $em->flush();


        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 104');
        $pc->setIp('200.200.0.104');

        $em->persist($pc);
        $em->flush();


        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 105');
        $pc->setIp('200.200.0.105');

        $em->persist($pc);
        $em->flush();


        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 100');
        $pc->setIp('200.200.0.101');

        $em->persist($pc);
        $em->flush();



        $pc_area = new PcArea();
        $pc_area->setName('Partner');
        $em->persist($pc_area);

        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 201');
        $pc->setIp('200.200.20.1');

        $em->persist($pc);
        $em->flush();

        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 202');
        $pc->setIp('200.202.20.101');

        $em->persist($pc);
        $em->flush();

        $pc = new Pc();
        $pc->setArea($pc_area);
        $pc->setName('PC 203');
        $pc->setIp('200.20.0.101');

        $em->persist($pc);
        $em->flush();

        return new Response('Created pc id '.$pc->getId() . ' ' . $pc_area->getName());

    }

}