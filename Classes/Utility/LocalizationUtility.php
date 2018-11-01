<?php
namespace Dagou\DagouExtbase\Utility;

use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LocalizationUtility extends \TYPO3\CMS\Extbase\Utility\LocalizationUtility {
    /**
     * @param string $key
     * @param string|NULL $extensionName
     * @param array|NULL $arguments
     * @param string|NULL $languageKey
     *
     * @return null|string
     */
    public static function translate($key, $extensionName = NULL, $arguments = NULL, $languageKey = NULL) {
        $value = NULL;

        if (GeneralUtility::isFirstPartOfStr($key, 'LLL:')) {
            $value = self::translateFileReference($key);
        } else {
            if (empty($extensionName)) {
                throw new \InvalidArgumentException(
                    'Parameter $extensionName cannot be empty if a fully-qualified key is not specified.', 1498144052
                );
            }

            self::initializeLocalization($extensionName, $languageKey);

            if (!empty(self::$LOCAL_LANG[$extensionName][self::$languageKey][$key][0]['target'])
                || isset(self::$LOCAL_LANG_UNSET[$extensionName][self::$languageKey][$key])) {
                $value = self::$LOCAL_LANG[$extensionName][self::$languageKey][$key][0]['target'];
            } elseif (!empty(self::$alternativeLanguageKeys)) {
                $languages = array_reverse(self::$alternativeLanguageKeys);

                foreach ($languages as $language) {
                    if (!empty(self::$LOCAL_LANG[$extensionName][$language][$key][0]['target'])
                        || isset(self::$LOCAL_LANG_UNSET[$extensionName][$language][$key])) {
                        $value = self::$LOCAL_LANG[$extensionName][$language][$key][0]['target'];

                        break;
                    }
                }
            }

            if ($value === NULL
                && (!empty(self::$LOCAL_LANG[$extensionName]['default'][$key][0]['target'])
                    || isset(self::$LOCAL_LANG_UNSET[$extensionName]['default'][$key]))) {
                $value = self::$LOCAL_LANG[$extensionName]['default'][$key][0]['target'];
            }
        }

        if (is_array($arguments) && $value !== NULL) {
            return vsprintf($value, $arguments);
        }

        return $value;
    }

    /**
     * @param string $extensionName
     * @param string|NULL $languageKey
     */
    protected static function initializeLocalization($extensionName, $languageKey = NULL) {
        if (isset(self::$LOCAL_LANG[$extensionName])) {
            return;
        }

        $locallangPathAndFilename = 'EXT:'
            .GeneralUtility::camelCaseToLowerCaseUnderscored($extensionName)
            .'/'
            .self::$locallangPath
            .'locallang.xlf';

        self::setLanguageKeys($languageKey);

        $languageFactory = GeneralUtility::makeInstance(LocalizationFactory::class);

        self::$LOCAL_LANG[$extensionName] =
            $languageFactory->getParsedData($locallangPathAndFilename, self::$languageKey, 'utf-8');

        foreach (self::$alternativeLanguageKeys as $language) {
            $tempLL = $languageFactory->getParsedData($locallangPathAndFilename, $language, 'utf-8');

            if (self::$languageKey !== 'default' && isset($tempLL[$language])) {
                self::$LOCAL_LANG[$extensionName][$language] = $tempLL[$language];
            }
        }

        self::loadTypoScriptLabels($extensionName);
    }

    /**
     * @param string|NULL $languageKey
     */
    protected static function setLanguageKeys($languageKey = NULL) {
        self::$languageKey = 'default';
        self::$alternativeLanguageKeys = [];

        if ($languageKey) {
            self::$languageKey = $languageKey;

            /** @var $locales \TYPO3\CMS\Core\Localization\Locales */
            $locales = GeneralUtility::makeInstance(Locales::class);

            if (in_array(self::$languageKey, $locales->getLocales())) {
                foreach ($locales->getLocaleDependencies(self::$languageKey) as $language) {
                    self::$alternativeLanguageKeys[] = $language;
                }
            }
        }
    }
}