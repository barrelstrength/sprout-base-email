<?php

namespace barrelstrength\sproutbaseemail\base;

use craft;

abstract class ScheduledJobEvent extends NotificationEvent {
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
	public function getEventClassName() {
		return NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function getEventName() {
		return NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function getEventHandlerClassName() {
		return NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function settingsAttributes(): array {
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
	 * @param array $context
	 * @return string
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function getSettingsHtml(array $context = []): string {
		return Craft::$app->getView()->renderTemplate('sprout-base-email/_components/events/scheduled-job', [
			'event' => $this
		]);
	}
}
