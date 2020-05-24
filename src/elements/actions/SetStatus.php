<?php

namespace barrelstrength\sproutbaseemail\elements\actions;

use Craft;
use craft\base\Element;
use craft\base\ElementAction;
use craft\base\ElementInterface;
use craft\elements\db\ElementQueryInterface;

class SetStatus extends ElementAction
{
    const ENABLED = 'enabled';

    const PENDING = 'pending';

    const DISABLED = 'disabled';

    /**
     * @var string|null The status elements should be set to
     */
    public $status;

    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('app', 'Set Status');
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['status'], 'required'];
        $rules[] = [['status'], 'in', 'range' => [self::ENABLED, self::PENDING, self::DISABLED]];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getTriggerHtml()
    {
        return Craft::$app->getView()->renderTemplate('_components/elementactions/SetStatus/trigger');
    }

    /**
     * @inheritdoc
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        /** @var ElementInterface $elementType */
        $elementType = $this->elementType;
        $isLocalized = $elementType::isLocalized() && Craft::$app->getIsMultiSite();
        $elementsService = Craft::$app->getElements();

        /** @var Element[] $elements */
        $elements = $query->all();
        $failCount = 0;

        foreach ($elements as $element) {
            switch ($this->status) {
                case self::ENABLED:
                    // Skip if there's nothing to change
                    if ($element->enabled && $element->enabledForSite) {
                        continue 2;
                    }

                    $element->enabled = $element->enabledForSite = true;
                    $element->setScenario(Element::SCENARIO_LIVE);
                    break;

                case self::DISABLED:
                    // Is this a multi-site element?
                    if ($isLocalized && count($element->getSupportedSites()) !== 1) {
                        // Skip if there's nothing to change
                        if (!$element->enabledForSite) {
                            continue 2;
                        }
                        $element->enabledForSite = false;
                    } else {
                        // Skip if there's nothing to change
                        if (!$element->enabled) {
                            continue 2;
                        }
                        $element->enabled = false;
                    }
                    break;
            }

            if ($elementsService->saveElement($element) === false) {
                // Validation error
                $failCount++;
            }
        }

        // Did all of them fail?
        if ($failCount === count($elements)) {
            if (count($elements) === 1) {
                $this->setMessage(Craft::t('app', 'Could not update status due to a validation error.'));
            } else {
                $this->setMessage(Craft::t('app', 'Could not update statuses due to validation errors.'));
            }

            return false;
        }

        if ($failCount !== 0) {
            $this->setMessage(Craft::t('app', 'Status updated, with some failures due to validation errors.'));
        } else {
            if (count($elements) === 1) {
                $this->setMessage(Craft::t('app', 'Status updated.'));
            } else {
                $this->setMessage(Craft::t('app', 'Statuses updated.'));
            }
        }

        return true;
    }
}
