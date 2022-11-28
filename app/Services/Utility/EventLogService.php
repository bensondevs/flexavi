<?php

namespace App\Services\Utility;

use App\Models\User;
use App\Repositories\EventLogRepository;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest
 *      To the class unit tester class.
 */
class EventLogService
{
    /**
     * Class key name in session.
     *
     * This class can be called through session by using
     * Session::get(EventLogService::CLASS_SESSION_KEY_NAME)
     *
     * @const
     */
    const CLASS_SESSION_KEY_NAME = 'event_log';

    /**
     * Event log repository class instance container property.
     *
     * @var EventLogRepository
     */
    private EventLogRepository $eventLogRepository;

    /**
     * Set doer of current action specified in service.
     *
     * @var User|null
     */
    private ?User $doer = null;

    /**
     * Event key name of current action.
     *
     * @var string
     */
    private string $eventKey = 'unknown.event';

    /**
     * Description of the current event log.
     *
     * @var string
     */
    private string $description = '';

    /**
     * Event loggables container property.
     *
     * @var array
     */
    private array $eventLoggables = [];

	/**
	 * Create New Service Instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->eventLogRepository = new EventLogRepository();
	}

    /**
     * Set the doer instance as the doer of action.
     *
     * @param User|Authenticatable $user
     * @return $this
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsUserToPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function setDoer(User|Authenticatable $user): self
    {
        $this->doer = $user;

        return $this;
    }

    /**
     * Get the doer instance that has been set into the class
     * property.
     *
     * @return User|Authenticatable|null
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsUserToPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function getDoer(): User|Authenticatable|null
    {
        $doer = $this->doer ?: auth()->user();

        return $doer ?: null;
    }

    /**
     * Set event key as the identifier of the action.
     *
     * @param string $key
     * @return $this
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsEventKeyToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function setEventKey(string $key): static
    {
        $this->eventKey = $key;

        return $this;
    }

    /**
     * Get event key of the current execution.
     *
     * @return string
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsEventKeyToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function getEventKey(): string
    {
        return $this->eventKey;
    }

    /**
     * Set description of the recorded log.
     *
     * @param string $description
     * @param bool $setToChildren
     * @return $this
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsDescriptionToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function setDescription(
        string $description,
        bool $setToChildren = false,
    ): static {
        $this->description = $description;

        if ($setToChildren) {
            $this->eventLoggables = array_map(function ($eventLoggable) use ($description) {
                $eventLoggable['description'] = $description;
            }, $this->eventLoggables);
        }

        return $this;
    }

    /**
     * Get description of current recording.
     *
     * @return string
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsDescriptionToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Add event loggable item into the record.
     *
     * @param mixed $instance
     * @param array $changes
     * @param string $description
     * @return $this
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsEventLoggablesToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function addEventLoggable(
        mixed  $instance,
        array  $changes = [],
        string $description = ''
    ): self {
        $this->eventLoggables[] = [
            'event_loggable_type' => get_class($instance),
            'event_loggable_id' => $instance->id ?? null,
            'changes' => json_encode($changes),
            'description' => $description,
        ];

        return $this;
    }

    /**
     * Get event loggables as the children of the record.
     *
     * @return array
     * @see \Tests\Unit\Services\EventLogService\EventLogServiceTest::itSetsEventLoggablesToClassPropertyCorrectly()
     *      To the method unit tester method.
     */
    public function getEventLoggables(): array
    {
        return $this->eventLoggables;
    }

    /**
     * Record the set properties to the repository.
     *
     * @return void
     */
    public function record(): void
    {
        $eventLog = $this->eventLogRepository->createEventLog([
            'doer_id' => $this->doer?->id ?? authUserId(),
            'event_key' => $this->getEventKey(),
            'description' => $this->getDescription(),
        ]);

        $loggables = $this->getEventLoggables();
        if (count($loggables)) {
            $this->eventLogRepository->createEventLoggables(
                $eventLog,
                $loggables,
            );
        }
    }
}
