<?php

namespace barrelstrength\sproutbaseemail\base;

use Craft;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @property string $jobType
 */
abstract class ScheduledJobEvent extends NotificationEvent
{
    /**
     * @var string
     */
    public $frequencyUnit;
    /**
     * @var float
     */
    public $frequencyQuantity;

    /**
     * @return string Namespaced name of job class.
     */
    abstract public function getJobType(): string;

    /**
     * @inheritdoc
     */
    public function getEventClassName()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getEventName()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getEventHandlerClassName()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function settingsAttributes(): array
    {
        $attributes = array_merge(
            parent::settingsAttributes(),
            [
                'frequencyUnit',
                'frequencyQuantity',
            ]
        );

        return $attributes;
    }

    /**
     * @inheritdoc
     *
     * @param array $context
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getSettingsHtml(array $context = []): string
    {
        return Craft::$app->getView()->renderTemplate('sprout-base-email/_components/events/scheduled-job', [
            'event' => $this
        ]);
    }
}
