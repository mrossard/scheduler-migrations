<?php

namespace App\Scheduler;

use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule]
class SomeScheduleProvider implements ScheduleProviderInterface
{
    use ClockAwareTrait;

    public function __construct(private readonly ParameterRepository $parameterRepository)
    {

    }

    public function getSchedule(): Schedule
    {
        $frequency = $this->parameterRepository->findOneBy([
            'key' => Parameter::REMINDER_FREQUENCY,
        ]);

        return (new Schedule())->add(
            RecurringMessage::every(frequency: $frequency->getValue(),
                                    message  : new SomeMessage(),
                                    from     : $this->now())
        );
    }
}
